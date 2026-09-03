@extends('layouts.app')

@section('title', 'Accès Mobile de ' . $utilisateur->prenom)
@section('role-badge', 'Responsable DSI')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <a href="{{ route('utilisateurs.index') }}" class="text-sm text-gray-400 hover:text-white">← Retour à la liste</a>

    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-wide">{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ $utilisateur->email }}</p>
        </div>
        @if($utilisateur->tokens->count() > 0)
            <form method="POST" action="{{ route('utilisateurs.revoquerTousLesTokens', $utilisateur->id_utilisateur) }}"
                  onsubmit="return confirm('Révoquer TOUS les accès mobiles de cet utilisateur ? Il devra se reconnecter.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2.5 bg-red-900/60 border border-red-700 text-red-200 font-semibold rounded-lg text-sm hover:bg-red-900 transition">
                    🚫 Révoquer tous les accès
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="p-4 rounded-lg bg-green-950 border border-green-800 text-green-300 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-darkcard border border-darkborder rounded-xl overflow-hidden shadow-xl">
        <div class="px-6 py-4 border-b border-darkborder font-semibold text-white">
            📱 Appareils / sessions mobiles actives
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-darkborder text-xs text-gray-400 uppercase tracking-wider bg-darkbg/50">
                    <th class="px-6 py-4">Appareil</th>
                    <th class="px-6 py-4">Connecté le</th>
                    <th class="px-6 py-4">Dernière utilisation</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($utilisateur->tokens as $token)
                    <tr class="border-b border-darkborder hover:bg-darkborder/40 transition">
                        <td class="px-6 py-4 text-white text-sm">{{ $token->name }}</td>
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $token->created_at?->format('d/m/Y H:i') ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-400 text-sm">
                            {{ $token->last_used_at?->format('d/m/Y H:i') ?? 'Jamais utilisé' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form method="POST" action="{{ route('utilisateurs.revoquerToken', [$utilisateur->id_utilisateur, $token->id]) }}"
                                  onsubmit="return confirm('Révoquer cet accès mobile ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 text-sm hover:underline">Révoquer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">
                            Aucun appareil mobile connecté pour cet utilisateur.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection