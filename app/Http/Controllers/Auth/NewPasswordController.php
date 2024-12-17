<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    protected function getPasswordRules(): array
    {
        return [
            'required',
            PasswordRule::min(10)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),
            'confirmed'
        ];
    }

    protected function getMessages(): array
    {
        return [
            'token.required' => 'Le token est requis.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.',
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

    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validator = validator($request->all(), [
                'token' => ['required'],
                'email' => ['required', 'email'],
                'password' => $this->getPasswordRules(),
            ], $this->getMessages());

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput($request->only('email'));
            }

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                }
            );

            return $status == Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', __($status))
                : back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors de la réinitialisation du mot de passe. Veuillez réessayer.'
            ])->withInput($request->only('email'));
        }
    }
}
