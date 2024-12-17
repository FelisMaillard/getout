<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    protected function getPasswordRules(): array
    {
        return [
            'required',
            Password::min(10)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),
        ];
    }

    protected function getMessages(): array
    {
        return [
            'current_password.required' => 'Le mot de passe actuel est requis.',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.required' => 'Le nouveau mot de passe est requis.',
            'password.confirmed' => 'La confirmation du nouveau mot de passe ne correspond pas.',
            'password.min' => 'Le nouveau mot de passe doit contenir au moins 10 caractères.',
            'password.letters' => 'Le nouveau mot de passe doit contenir au moins une lettre.',
            'password.mixed' => 'Le nouveau mot de passe doit contenir au moins une majuscule et une minuscule.',
            'password.numbers' => 'Le nouveau mot de passe doit contenir au moins un chiffre.',
            'password.symbols' => 'Le nouveau mot de passe doit contenir au moins un caractère spécial.',
            'password.uncompromised' => 'Ce mot de passe a été compromis. Veuillez en choisir un autre.',
        ];
    }

    public function update(Request $request): RedirectResponse
    {
        try {
            $validator = validator($request->all(), [
                'current_password' => ['required', 'current_password'],
                'password' => [...$this->getPasswordRules(), 'confirmed'],
            ], $this->getMessages());

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $request->user()->update([
                'password' => Hash::make($validator->validated()['password']),
            ]);

            return back()->with('status', 'password-updated');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors de la mise à jour du mot de passe. Veuillez réessayer.'
            ]);
        }
    }
}
