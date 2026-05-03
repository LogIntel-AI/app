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
        $apiKey = config('services.openai.key');
        if (!$apiKey) {
            Log::warning('OPENAI_API_KEY is not set. Skipping AI analysis.');
            return;
        }

        try {
            $response = Http::withToken($apiKey)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an AI server log analyzer. Return ONLY a JSON object with: "category", "severity" (low/medium/high/critical), "summary", "suggestion". Do not wrap in markdown or anything else.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $this->logEntry->message . "\n" . $this->logEntry->raw_log
                        ]
                    ],
                ]);

            if ($response->successful()) {
                $data = json_decode($response->json('choices.0.message.content'), true);

                if ($data) {
                    $this->logEntry->aiAnalysis()->create([
                        'category' => $data['category'] ?? 'Unknown',
                        'severity' => $data['severity'] ?? 'Unknown',
                        'summary' => $data['summary'] ?? null,
                        'suggestion' => $data['suggestion'] ?? null,
                        'model_used' => 'gpt-3.5-turbo',
                    ]);

                    $this->logEntry->update([
                        'category' => $data['category'] ?? $this->logEntry->category,
                        'severity' => $data['severity'] ?? $this->logEntry->severity,
                    ]);
                }
            } else {
                Log::error('OpenAI API failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('AI Analysis failed: ' . $e->getMessage());
        }
    }
}
