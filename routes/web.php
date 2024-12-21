<?php

use App\Http\Controllers\Pages\HomeController;
use App\Http\Controllers\Pages\SearchController;
use App\Http\Controllers\Pages\UserRelationController;
use App\Http\Controllers\NotificationController;
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

    // Routes pour les amitiés
    Route::post('/relations/{userTag}', [UserRelationController::class, 'sendRequest'])->name('relations.send');
    Route::post('/relations/{relation}/accept', [UserRelationController::class, 'acceptRequest'])->name('relations.accept');
    Route::post('/relations/{user}/block', [UserRelationController::class, 'blockUser'])->name('relations.block');
    Route::delete('/relations/{relation}', [UserRelationController::class, 'removeRelation'])->name('relations.remove');

    // Routes des notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    // Profile routes
    Route::get('/@{tag}', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
