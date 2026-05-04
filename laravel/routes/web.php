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
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
