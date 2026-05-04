<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\ProviderController;

Route::get('/auth/google', [ProviderController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [ProviderController::class, 'callback']);

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('servers', \App\Http\Controllers\ServerController::class);
    Route::post('servers/{server}/pull', [\App\Http\Controllers\ServerController::class, 'pullLogs'])->name('servers.pull');
    Route::post('logs/{log}/reanalyze', [\App\Http\Controllers\DashboardController::class, 'reanalyze'])->name('logs.reanalyze');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Secure endpoint to view Laravel logs directly in the browser
    Route::get('/logs', function () {
        $logFile = storage_path('logs/laravel.log');
        if (!file_exists($logFile)) {
            return response('Log file does not exist.', 404);
        }
        return response()->file($logFile, [
            'Content-Type' => 'text/plain',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    })->name('logs.view');
});

require __DIR__.'/auth.php';
