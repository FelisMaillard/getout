<section>
    <header>
        <h2 class="text-xl font-bold text-white">
            {{ __('Supprimer le compte') }}
        </h2>
        <p class="mt-1 text-sm text-gray-400">
            {{ __('Une fois votre compte supprimé, toutes vos données seront définitivement effacées.') }}
        </p>
    </header>

    <div class="mt-6">
        <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            {{ __('Supprimer le compte') }}
        </x-danger-button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-white">
                {{ __('Êtes-vous sûr de vouloir supprimer votre compte ?') }}
            </h2>

            <p class="mt-2 text-sm text-gray-400">
                {{ __('Cette action est irréversible. Pour confirmer, veuillez saisir votre mot de passe.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Mot de passe') }}" class="sr-only" />
                <x-text-input id="password" name="password" type="password" class="mt-1" placeholder="{{ __('Mot de passe') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-4">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Annuler') }}
                </x-secondary-button>

                <x-danger-button>
                    {{ __('Supprimer définitivement') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section class="mb-6">
