<?php

namespace App\Http\Middleware;

use App\Models\SanctionUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserBan
{
    /**
     * Vérifie si l'utilisateur est banni avant de traiter la requête.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si l'utilisateur n'est pas connecté, continuer normalement
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Rechercher une sanction active pour l'utilisateur
        $activeSanction = SanctionUser::where('user_id', $user->id)
            ->where('status', 'active')
            ->where(function ($query) {
                $now = now();
                $query->where('is_permanent', true)
                    ->orWhere(function ($q) use ($now) {
                        $q->where('start_at', '<=', $now)
                          ->where(function ($inner) use ($now) {
                              $inner->whereNull('end_at')
                                    ->orWhere('end_at', '>=', $now);
                          });
                    });
            })
            ->with('typeReport')
            ->first();

        // Si une sanction active est trouvée, rediriger vers la page de bannissement
        if ($activeSanction) {
            // Stocker les infos de sanction dans la session
            session(['user_sanction' => $activeSanction]);

            // Rediriger vers la page de bannissement
            return redirect()->route('banned');
        }

        // Si aucune sanction active, continuer normalement
        return $next($request);
    }
}
