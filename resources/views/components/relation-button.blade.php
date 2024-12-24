@php
    // Récupérer la relation existante pour l'utilisateur cible
    $existingRelation = Auth::user()->sentRelations()
        ->withTrashed()
        ->where('friend_id', $targetUser->id)
        ->first();
@endphp

<div>
    @if($targetUser->id === Auth::id())
        <a href="{{ "@" . $targetUser->tag }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-xl transition-colors duration-200">Votre profil</a>
    @else
        @if($existingRelation)
            @if($existingRelation->status === 'blocked')
                <!-- Si l'utilisateur est bloqué, on affiche le bouton de déblocage -->
                <form action="{{ route('relations.block', ['userTag' => $targetUser->tag]) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl transition-colors duration-200">
                        Débloquer
                    </button>
                </form>
            @elseif($existingRelation->trashed())
                <!-- Relation supprimée -->
                <form action="{{ route('relations.send', ['userTag' => $targetUser->tag]) }}" method="POST" class="inline">
                    @csrf
                    @if(session('query'))
                        <input type="hidden" name="query" value="{{ session('query') }}">
                    @endif
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-xl transition-colors duration-200">
                        @if($targetUser->private)
                            Demande de Follow
                        @else
                            Follow
                        @endif
                    </button>
                </form>
            @elseif($existingRelation->status === 'pending')
                <form action="{{ route('relations.remove', ['relation' => $existingRelation->id]) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-xl transition-colors duration-200">
                        Annuler la demande
                    </button>
                </form>
            @else
                <form action="{{ route('relations.remove', ['relation' => $existingRelation->id]) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-xl transition-colors duration-200">
                        Unfollow
                    </button>
                </form>
            @endif
        @else
            <!-- Si l'utilisateur n'est pas bloqué, on peut soit le suivre soit le bloquer -->
            <div class="flex gap-2">
                <form action="{{ route('relations.send', ['userTag' => $targetUser->tag]) }}" method="POST" class="inline">
                    @csrf
                    @if(session('query'))
                        <input type="hidden" name="query" value="{{ session('query') }}">
                    @endif
                    <button type="submit"
                            class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-xl transition-colors duration-200">
                        {{ $targetUser->private ? 'Demande de Follow' : 'Follow' }}
                    </button>
                </form>
            </div>
        @endif
    @endif
</div>
