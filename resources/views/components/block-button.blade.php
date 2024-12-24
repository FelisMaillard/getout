@php
    // Récupérer la relation existante pour l'utilisateur cible
    $existingRelation = Auth::user()->sentRelations()
        ->withTrashed()
        ->where('friend_id', $targetUser->id)
        ->first();
@endphp

<div>
    @if($targetUser->id !== Auth::id())
        @if($existingRelation && $existingRelation->status === 'blocked')
            <!-- Bouton pour débloquer -->
            <form action="{{ route('relations.block', ['userTag' => $targetUser->tag]) }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                        class="md:px-4 md:py-2 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl transition-colors duration-200">
                    Débloquer
                </button>
            </form>
        @else
            <!-- Bouton pour bloquer -->
            <form action="{{ route('relations.block', ['userTag' => $targetUser->tag]) }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                        class="md:px-4 md:py-2 bg-red-700 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-xl transition-colors duration-200"
                        onclick="return confirm('Êtes-vous sûr de vouloir bloquer cet utilisateur ?')">
                    Bloquer
                </button>
            </form>
        @endif
    @endif
</div>
