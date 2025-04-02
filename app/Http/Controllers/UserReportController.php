<?php

namespace App\Http\Controllers;

use App\Models\ReportUser;
use App\Models\TypeReportUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserReportController extends Controller
{
    public function show(User $user)
    {
        // Vérifier que l'utilisateur ne s'auto-signale pas
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas vous signaler vous-même.');
        }

        // Récupérer les types de signalement actifs
        $reportTypes = TypeReportUser::where('active', true)->get();

        return view('reports.user-report', [
            'user' => $user,
            'reportTypes' => $reportTypes
        ]);
    }

    public function store(Request $request, User $user)
    {
        // Validation
        $validated = $request->validate([
            'type_report_id' => ['required', 'exists:type_report_users,id'],
            'description' => ['required', 'string', 'min:10', 'max:1000'],
            'evidence' => ['nullable', 'array'],
            'evidence.*' => ['nullable', 'string', 'max:255']
        ]);

        // Vérifier que l'utilisateur ne s'auto-signale pas
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas vous signaler vous-même.');
        }

        // Vérifier si un rapport récent existe déjà (pour éviter le spam)
        $existingReport = ReportUser::where('reporter_id', Auth::id())
            ->where('reported_user_id', $user->id)
            ->where('type_report_id', $validated['type_report_id'])
            ->where('created_at', '>', now()->subDays(7))
            ->exists();

        if ($existingReport) {
            return back()->with('error', 'Vous avez déjà signalé cet utilisateur pour cette raison récemment.');
        }

        // Créer le rapport
        $report = ReportUser::create([
            'reporter_id' => Auth::id(),
            'reported_user_id' => $user->id,
            'type_report_id' => $validated['type_report_id'],
            'description' => $validated['description'],
            'evidence' => $validated['evidence'] ?? null,
            'status' => 'pending'
        ]);

        return redirect()->route('profile.show', $user->tag)
            ->with('success', 'Merci pour votre signalement. Notre équipe va examiner ce cas dans les plus brefs délais.');
    }
}
