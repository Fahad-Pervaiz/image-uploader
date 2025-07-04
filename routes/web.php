<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::get('/', [ImageController::class, 'index'])->name('images.index');
    Route::get('/images', [ImageController::class, 'show'])->name('images.show');
    Route::post('/upload', [ImageController::class, 'store'])->name('images.store');
    Route::delete('/images/{filename}', [ImageController::class, 'destroy'])->name('images.destroy');
});

Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});


require __DIR__.'/auth.php';
