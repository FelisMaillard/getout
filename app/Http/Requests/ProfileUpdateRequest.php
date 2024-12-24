<?php

namespace App\Http\Requests;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'private' => (bool) $this->private
        ]);
    }

    public function rules(): array
    {
        $rules = [
            'prenom' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'bio' => ['nullable', 'string', 'max:500'],
            'private' => ['nullable'],
        ];

        // Ajouter la validation du tag seulement si l'utilisateur essaie de le changer
        if ($this->tag !== $this->user()->tag) {
            // Vérifier si l'utilisateur peut changer son tag
            $lastChange = $this->user()->last_tag_change;
            $canChangeTag = !$lastChange || Carbon::parse($lastChange)->addMonth()->isPast();

            if (!$canChangeTag) {
                $rules['tag'] = ['prohibited'];
            } else {
                $rules['tag'] = [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-Z0-9_]+$/',
                    Rule::unique(User::class)->ignore($this->user()->id)
                ];
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'tag.prohibited' => 'Vous ne pouvez changer votre tag qu\'une fois par mois.',
            'tag.regex' => 'Le tag ne peut contenir que des lettres, des chiffres et des underscores.',
        ];
    }
}
