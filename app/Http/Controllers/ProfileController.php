<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show(Request $request, $tag)
    {
        // Récupérer l'utilisateur par son tag
        $user = User::where('tag', $tag)->firstOrFail();

        // Vérifier si c'est le profil de l'utilisateur connecté
        $isOwnProfile = Auth::id() === $user->id;

        // Vérifier si l'utilisateur courant suit déjà cet utilisateur
        $isFollowing = !$isOwnProfile && Auth::user()->sentRelations()
            ->where('friend_id', $user->id)
            ->where('status', 'accepted')
            ->exists();

        // Vérifier s'il y a une demande en attente
        $hasPendingRequest = !$isOwnProfile && Auth::user()->sentRelations()
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        return view('profile.show', [
            'user' => $user,
            'isOwnProfile' => $isOwnProfile,
            'isFollowing' => $isFollowing,
            'hasPendingRequest' => $hasPendingRequest,
        ]);
    }
}
