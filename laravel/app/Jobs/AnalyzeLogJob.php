<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Models\LogEntry;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnalyzeLogJob implements ShouldQueue
{
    use Queueable;

    public $logEntry;

    /**
     * Create a new job instance.
     */
    public function __construct(LogEntry $logEntry)
    {
        $this->logEntry = $logEntry;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $geminiKey = config('services.gemini.key');
        $openAiKey = config('services.openai.key');

        if (!$geminiKey && !$openAiKey) {
            Log::warning('No AI API key set. Skipping AI analysis.');
            return;
        }

        try {
            $data = null;
            $modelUsed = 'unknown';

            $prompt = 'You are an AI server log analyzer. Return ONLY a valid JSON object with EXACTLY these keys: "category", "severity" (low/medium/high/critical), "summary", "suggestion". Do NOT wrap the response in markdown blocks (like ```json), just raw JSON. Here is the log: ' . $this->logEntry->message . "\n" . $this->logEntry->raw_log;

            if ($geminiKey) {
                // Use Google Gemini (Free Tier)
                $modelUsed = 'gemini-2.5-flash-lite';
                $response = Http::post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent?key=' . $geminiKey, [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ]
                ]);

                if ($response->successful()) {
                    $jsonText = $response->json('candidates.0.content.parts.0.text');
                    // Clean up markdown if Gemini accidentally included it
                    $jsonText = preg_replace('/```json\s*|\s*```/', '', $jsonText);
                    $data = json_decode(trim($jsonText), true);
                } else {
                    Log::error('Gemini API failed: ' . $response->body());
                }
            } else if ($openAiKey) {
                // Use OpenAI
                $modelUsed = 'gpt-3.5-turbo';
                $response = Http::withToken($openAiKey)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-3.5-turbo',
                        'messages' => [
                            ['role' => 'system', 'content' => 'Return ONLY raw JSON with: category, severity, summary, suggestion.'],
                            ['role' => 'user', 'content' => $prompt]
                        ],
                    ]);

                if ($response->successful()) {
                    $jsonText = $response->json('choices.0.message.content');
                    $jsonText = preg_replace('/```json\s*|\s*```/', '', $jsonText);
                    $data = json_decode(trim($jsonText), true);
                } else {
                    Log::error('OpenAI API failed: ' . $response->body());
                }
            }

            if ($data) {
                $this->logEntry->aiAnalysis()->create([
                    'category' => $data['category'] ?? 'Unknown',
                    'severity' => $data['severity'] ?? 'Unknown',
                    'summary' => $data['summary'] ?? null,
                    'suggestion' => $data['suggestion'] ?? null,
                    'model_used' => $modelUsed,
                ]);

                $this->logEntry->update([
                    'category' => $data['category'] ?? $this->logEntry->category,
                    'severity' => $data['severity'] ?? $this->logEntry->severity,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('AI Analysis failed: ' . $e->getMessage());
        }
    }
}
