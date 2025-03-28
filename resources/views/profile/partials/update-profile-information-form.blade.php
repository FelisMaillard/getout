<section>
    <header>
        <h2 class="text-xl font-bold text-white">
            {{ __('Informations du profil') }}
        </h2>
        <p class="mt-1 text-sm text-gray-400">
            {{ __("Mettez à jour vos informations personnelles.") }}
        </p>
    </header>

    <!-- Photo de profil -->
    <div class="mb-6 border-b border-gray-800 pb-6">
        <h3 class="text-lg font-medium text-white mb-4">Photo de profil</h3>

        <!-- Affichage de la photo actuelle et input sur la même ligne -->
        <div class="flex items-start gap-4">
            @if($user->profile_photo_url)
                <div class="relative">
                    <img src="{{ Storage::url($user->profile_photo_url) }}"
                        alt="Photo de profil actuelle"
                        class="w-20 h-20 rounded-full object-cover">

                    <form action="{{ route('profile.photo.delete') }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="absolute -top-2 -right-2 bg-red-600 rounded-full p-1 hover:bg-red-700">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </form>
                </div>
            @else
                <div class="w-20 h-20 rounded-full bg-purple-600 flex items-center justify-center">
                    <span class="text-2xl text-white">{{ substr($user->prenom, 0, 1) . substr($user->nom, 0, 1) }}</span>
                </div>
            @endif

            <!-- Formulaire d'upload de photo -->
            <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col">
                @csrf
                @method('PUT')

                <div>
                    <input type="file"
                        id="photo"
                        name="photo"
                        accept="image/jpeg,image/png,image/webp"
                        class="block w-full text-sm text-gray-400
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-purple-600 file:text-white
                                hover:file:bg-purple-700">
                    <p class="mt-1 text-sm text-gray-500">
                        Formats acceptés : JPG, PNG, WebP. Taille maximale : 2MB.
                    </p>
                </div>

                <button type="submit" class="mt-auto text-sm text-white bg-purple-600 rounded-md px-4 py-2 hover:bg-purple-700">
                    Enregistrer
                </button>
                <x-input-error class="mt-2" :messages="$errors->get('photo')" />
            </form>
        </div>
    </div>


    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Prénom -->
            <div>
                <x-input-label for="prenom" :value="__('Prénom')" />
                <x-text-input id="prenom" name="prenom" type="text" class="mt-1" :value="old('prenom', $user->prenom)" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('prenom')" />
            </div>

            <!-- Nom -->
            <div>
                <x-input-label for="nom" :value="__('Nom')" />
                <x-text-input id="nom" name="nom" type="text" class="mt-1" :value="old('nom', $user->nom)" required />
                <x-input-error class="mt-2" :messages="$errors->get('nom')" />
            </div>
        </div>

        <!-- Tag -->
        <div>
            <x-input-label for="tag" :value="__('Tag')" />
            <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">@</span>
                <x-text-input
                    id="tag"
                    name="tag"
                    type="text"
                    class="pl-7"
                    :value="old('tag', $user->tag)"
                    :disabled="!$canChangeTag"
                    required
                />
            </div>
            @if(!$canChangeTag && $nextTagChange)
                <p class="mt-1 text-sm text-yellow-500">
                    Vous pourrez changer votre tag à partir du {{ $nextTagChange->format('d/m/Y') }}
                </p>
            @else
                <p class="mt-1 text-sm text-gray-500">
                    Choisissez votre tag avec précaution, vous ne pourrez le changer qu'une fois par mois.
                </p>
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('tag')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1" :value="old('email', $user->email)" required />
            <p class="mt-1 text-sm text-gray-500">Votre email ne sera jamais partagé publiquement.</p>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <!-- Bio -->
        <div>
            <x-input-label for="bio" :value="__('Bio')" />
            <textarea id="bio" name="bio"
                class="mt-1 w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                rows="4">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <!-- Compte Privé -->
        <div class="mt-4">
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="private" name="private" type="checkbox"
                        class="w-4 h-4 rounded bg-gray-900 border-gray-700 text-purple-600 focus:ring-purple-500 focus:ring-offset-gray-900"
                        {{ old('private', $user->private) ? 'checked' : '' }}>
                </div>
                <div class="ml-3">
                    <label for="private" class="text-sm font-medium text-white">Compte privé</label>
                    <p class="text-sm text-gray-500">Les nouveaux abonnés devront être approuvés manuellement.</p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('private')" />
        </div>

        <div class="flex items-center gap-4 mt-6">
            <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-400">{{ __('Enregistré.') }}</p>
            @endif
        </div>
    </form>
</section>
