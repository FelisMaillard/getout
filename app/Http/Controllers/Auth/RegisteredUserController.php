<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    protected function getPasswordRules(): array
    {
        return [
            'required',
            'confirmed',
            Password::min(10)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),
        ];
    }

    protected function getNameRules(): array
    {
        return [
            'required',
            'string',
            'max:255',
            'min:2',
            'regex:/^[a-zA-ZÀ-ÿ\s\-]+$/'
        ];
    }

    protected function getTagRules(): array
    {
        return [
            'required',
            'string',
            'min:3',
            'max:30',
            'unique:users,tag',
            'regex:/^[a-z0-9]+$/',
            'not_in:admin,root,system,moderator,modo,support,help'
        ];
    }

    protected function getEmailRules(): array
    {
        return [
            'required',
            'string',
            'lowercase',
            'email',
            'max:255',
            'unique:'.User::class,
            'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
        ];
    }

    protected function getMessages(): array
    {
        return [
            // Nom
            'nom.required' => 'Le nom est requis.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'nom.min' => 'Le nom doit contenir au moins 2 caractères.',
            'nom.regex' => 'Le nom ne peut contenir que des lettres, des espaces et des tirets.',

            // Prénom
            'prenom.required' => 'Le prénom est requis.',
            'prenom.string' => 'Le prénom doit être une chaîne de caractères.',
            'prenom.max' => 'Le prénom ne peut pas dépasser 255 caractères.',
            'prenom.min' => 'Le prénom doit contenir au moins 2 caractères.',
            'prenom.regex' => 'Le prénom ne peut contenir que des lettres, des espaces et des tirets.',

            // Tag
            'tag.required' => 'Le tag est requis.',
            'tag.string' => 'Le tag doit être une chaîne de caractères.',
            'tag.min' => 'Le tag doit contenir au moins 3 caractères.',
            'tag.max' => 'Le tag ne peut pas dépasser 30 caractères.',
            'tag.unique' => 'Ce tag est déjà utilisé.',
            'tag.regex' => 'Le tag ne peut contenir que des lettres minuscules et des chiffres.',
            'tag.not_in' => 'Ce tag est réservé et ne peut pas être utilisé.',

            // Email
            'email.required' => 'L\'adresse email est requise.',
            'email.string' => 'L\'adresse email doit être une chaîne de caractères.',
            'email.lowercase' => 'L\'adresse email doit être en minuscules.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.max' => 'L\'adresse email ne peut pas dépasser 255 caractères.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'email.regex' => 'L\'adresse email n\'a pas un format valide. Exemple: nom@domaine.com',

            // Password
            'password.required' => 'Le mot de passe est requis.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 10 caractères.',
            'password.letters' => 'Le mot de passe doit contenir au moins une lettre.',
            'password.mixed' => 'Le mot de passe doit contenir au moins une majuscule et une minuscule.',
            'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
            'password.symbols' => 'Le mot de passe doit contenir au moins un caractère spécial.',
            'password.uncompromised' => 'Ce mot de passe a été compromis. Veuillez en choisir un autre.',
        ];
    }

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validator = validator($request->all(), [
                'nom' => $this->getNameRules(),
                'prenom' => $this->getNameRules(),
                'tag' => $this->getTagRules(),
                'email' => $this->getEmailRules(),
                'password' => $this->getPasswordRules(),
            ], $this->getMessages());

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput($request->except('password'));
            }

            $tag = strtolower(preg_replace('/[^a-z0-9]/', '', $request->tag));

            $user = User::create([
                'nom' => ucwords(strtolower($request->nom)),
                'prenom' => ucwords(strtolower($request->prenom)),
                'tag' => $tag,
                'email' => strtolower($request->email),
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            Auth::login($user);

            return redirect(route('dashboard', absolute: false));

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.'
            ])->withInput($request->except('password'));
        }
    }
}
