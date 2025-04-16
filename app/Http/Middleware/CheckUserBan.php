<?php

namespace App\Http\Middleware;

use App\Models\SanctionUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckUserBan
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('CheckUserBan middleware is running', [
            'user_id' => Auth::id(),
            'path' => $request->path()
        ]);

        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Requête simplifiée - juste chercher une sanction active pour cet utilisateur
        $activeSanction = SanctionUser::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        Log::info('Recherche de sanctions', [
            'user_id' => $user->id,
            'found' => $activeSanction ? 'oui' : 'non',
            'sanction_id' => $activeSanction ? $activeSanction->id : null
        ]);

        if ($activeSanction) {
            // Simple vérification si la sanction est active (permanent ou dans les dates)
            $isActive = $activeSanction->is_permanent ||
                        ($activeSanction->start_at <= now() &&
                         ($activeSanction->end_at === null || $activeSanction->end_at >= now()));

            Log::info('Vérification si sanction active', [
                'is_active' => $isActive,
                'is_permanent' => $activeSanction->is_permanent,
                'start_at' => $activeSanction->start_at,
                'end_at' => $activeSanction->end_at
            ]);

            if ($isActive) {
                session(['user_sanction' => $activeSanction]);
                return redirect()->route('banned');
            }
        }

        return $next($request);
    }
}
