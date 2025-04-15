<section class="mb-24">
    <header>
        <h2 class="text-xl font-bold text-white">
            {{ __('Supprimer le compte') }}
        </h2>
        <p class="mt-1 text-sm text-gray-400">
            {{ __('Une fois votre compte supprimé, toutes vos données seront définitivement effacées.') }}
        </p>
    </header>

    <div class="mt-6">
        <a href="{{ route('profile.confirm-delete') }}" class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors duration-200">
            {{ __('Supprimer le compte') }}
        </a>
    </div>
</section>
