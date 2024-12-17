<?php

use App\Http\Controllers\Page\HomeController;
use App\Http\Controllers\Page\SearchController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route principale (home)
Route::get('/', function () {
    return redirect()->route('home');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Home route
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Search routes
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/search/query', [SearchController::class, 'search'])->name('search.query');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
