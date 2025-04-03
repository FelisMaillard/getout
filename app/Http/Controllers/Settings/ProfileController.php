<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $canChangeTag = !$user->last_tag_change ||
            Carbon::parse($user->last_tag_change)->addMonth()->isPast();

        return view('profile.edit', [
            'user' => $user,
            'canChangeTag' => $canChangeTag,
            'nextTagChange' => $user->last_tag_change ?
                Carbon::parse($user->last_tag_change)->addMonth() :
                null
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // On gère private séparément
        $user->private = (bool)$request->input('private', false);

        // Si le tag est changé, mettre à jour la date de dernier changement
        if (isset($validated['tag']) && $validated['tag'] !== $user->tag) {
            $user->last_tag_change = now();
        }

        // On enlève private des données validées car on l'a déjà géré
        unset($validated['private']);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}
