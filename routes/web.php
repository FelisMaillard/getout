<?php

use App\Http\Controllers\Pages\HomeController;
use App\Http\Controllers\Pages\SearchController;
use App\Http\Controllers\Pages\UserRelationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Settings\ProfileController as SettingsProfileController;
use App\Http\Controllers\Settings\ProfilePhotoController;
use App\Http\Controllers\Servers\ServerController;
use App\Http\Controllers\Servers\ServerMemberController;
use App\Http\Controllers\Servers\ChannelController;
use App\Http\Controllers\Servers\MessageController;
use App\Http\Controllers\Servers\ServerInviteController;
use App\Http\Controllers\UserReportController;

use Illuminate\Support\Facades\Route;


// Route principale (home)
Route::get('/', function () {
    return redirect()->route('home');
});

Route::middleware(['auth', 'verified', 'ban'])->group(function () {
    // Home route
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Search routes
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    // Routes de paramètres
    Route::get('/settings/profile', [SettingsProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/settings/profile', [SettingsProfileController::class, 'update'])->name('profile.update');
    Route::delete('/settings/profile', [SettingsProfileController::class, 'destroy'])->name('profile.destroy');

    // Nouvelle route pour la confirmation de suppression
    Route::get('/settings/profile/confirm-delete', function () {
        return view('profile.confirm-delete');
    })->name('profile.confirm-delete');

    // Route::put('/profile/photo', [ProfilePhotoController::class, 'update'])->name('profile.photo.update');
    // Route::delete('/profile/photo', [ProfilePhotoController::class, 'destroy'])->name('profile.photo.delete');

    // Routes pour les amitiés
    Route::post('/relations/{userTag}', [UserRelationController::class, 'sendRequest'])->name('relations.send');
    Route::post('/relations/{relation}/accept', [UserRelationController::class, 'acceptRequest'])->name('relations.accept');
    Route::post('/relations/{userTag}/block', [UserRelationController::class, 'blockUser'])->name('relations.block');
    Route::delete('/relations/{relation}', [UserRelationController::class, 'removeRelation'])->name('relations.remove');

    // Routes pour les reports
    Route::get('/report/user/{user}', [UserReportController::class, 'show'])->name('reports.user.show');
    Route::post('/report/user/{user}', [UserReportController::class, 'store'])->name('reports.user.store');

    // Routes des notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/followers/read-all', [NotificationController::class, 'markAllFollowersAsRead'])->name('notifications.followers.read');

    // Profile public routes
    Route::get('/@{tag}', [ProfileController::class, 'show'])->name('profile.show');

    // Routes des serveurs
    Route::prefix('servers')->name('servers.')->group(function () {
        // Listing et création
        Route::get('/', [ServerController::class, 'index'])->name('index');
        Route::get('/create', [ServerController::class, 'create'])->name('create');
        Route::post('/', [ServerController::class, 'store'])->name('store');

        // Gestion d'un serveur spécifique
        Route::prefix('{server}')->group(function () {
            // Routes du serveur
            Route::get('/', [ServerController::class, 'show'])->name('show');
            Route::get('/edit', [ServerController::class, 'edit'])->name('edit');
            Route::put('/', [ServerController::class, 'update'])->name('update');
            Route::delete('/', [ServerController::class, 'destroy'])->name('destroy');

            // Route de recherche d'amis
            Route::get('/search-friends', [ServerInviteController::class, 'searchFriends'])
                ->name('search-friends');

            // Routes des membres
            Route::prefix('members')->name('members.')->group(function () {
                Route::post('/', [ServerMemberController::class, 'store'])->name('store');
                Route::put('{member}', [ServerMemberController::class, 'update'])->name('update');
                Route::delete('{member}', [ServerMemberController::class, 'destroy'])->name('destroy');
            });

            // Routes des invitations
            Route::prefix('invites')->name('invites.')->group(function () {
                Route::post('/', [ServerInviteController::class, 'store'])->name('store');
                Route::post('{invite}/accept', [ServerInviteController::class, 'accept'])->name('accept');
                Route::delete('{invite}', [ServerInviteController::class, 'cancel'])->name('cancel');
                Route::post('{invite}/reject', [ServerInviteController::class, 'reject'])
                    ->name('reject')
                    ->whereNumber('invite');
            });


            // Routes des channels
            Route::prefix('channels')->name('channels.')->group(function () {
                Route::post('/', [ChannelController::class, 'store'])->name('store');
                Route::get('{channel}', [ChannelController::class, 'show'])->name('show');
                Route::put('{channel}', [ChannelController::class, 'update'])->name('update');
                Route::delete('{channel}', [ChannelController::class, 'destroy'])->name('destroy');

                // Messages
                Route::prefix('{channel}/messages')->name('messages.')->group(function () {
                    Route::post('/', [MessageController::class, 'store'])->name('store');
                    Route::get('/{message}/edit', [MessageController::class, 'edit'])->name('edit'); // Ajoutez cette ligne
                    Route::put('/{message}', [MessageController::class, 'update'])->name('update');
                    Route::delete('/{message}', [MessageController::class, 'destroy'])->name('delete');
                });
            });
        });
    });
});

// Route pour afficher la page de bannissement
Route::get('/banned', function () {
    // Si aucune sanction en session, rediriger vers l'accueil
    if (!session()->has('user_sanction')) {
        return redirect()->route('home');
    }

    return view('banned');
})->name('banned');

require __DIR__ . '/auth.php';
