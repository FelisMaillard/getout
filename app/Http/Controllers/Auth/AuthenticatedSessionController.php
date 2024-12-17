<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    protected function getValidationRules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'password' => ['required', 'string'],
        ];
    }

    protected function getMessages(): array
    {
        return [
            'email.required' => 'L\'adresse email est requise.',
            'email.string' => 'L\'adresse email doit être une chaîne de caractères.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.regex' => 'L\'adresse email n\'a pas un format valide. Exemple: nom@domaine.com',
            'password.required' => 'Le mot de passe est requis.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
        ];
    }

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            // Validation des données
            $validator = validator($request->all(), $this->getValidationRules(), $this->getMessages());

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput($request->except('password'));
            }

            $credentials = $validator->validated();

            // Vérification du rate limiting
            $key = 'login.' . $request->ip();
            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);
                throw ValidationException::withMessages([
                    'email' => ['Trop de tentatives de connexion. Veuillez réessayer dans ' . $seconds . ' secondes.'],
                ]);
            }

            // Tentative de connexion
            if (!Auth::attempt($credentials, $request->boolean('remember'))) {
                RateLimiter::hit($key);

                return back()
                    ->withErrors([
                        'password' => 'Identifiants incorrects. Veuillez vérifier votre email et mot de passe.'
                    ])
                    ->withInput($request->except('password'));
            }

            // Si la connexion réussit, on réinitialise le rate limiter
            RateLimiter::clear($key);

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard', absolute: false));

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput($request->except('password'));
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la connexion. Veuillez réessayer.'])
                ->withInput($request->except('password'));
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
