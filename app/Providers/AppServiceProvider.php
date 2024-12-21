<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $user = Auth::user();
            if ($user) {
                $pendingRequests = $user->receivedRelations()
                    ->where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->get();

                $pendingRequestsCount = $pendingRequests->count();

                $view->with([
                    'user' => $user,
                    'pendingRequests' => $pendingRequests,
                    'pendingRequestsCount' => $pendingRequestsCount
                ]);
            }
        });
    }
}
