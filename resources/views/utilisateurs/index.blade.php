@extends('layouts.app')

@section('title', 'Gestion des Accès Mobiles')
@section('role-badge', 'Responsable DSI')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-white tracking-wide">🔐 Gestion des Accès Mobiles</h1>
        <p class="text-sm text-gray-400 mt-1">
            Liste des utilisateurs pouvant se connecter à l'application mobile. En cas de perte d'un
            appareil, révoque son accès depuis la fiche de l'utilisateur (RM-04).
        </p>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-lg bg-green-950 border border-green-800 text-green-300 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-darkcard border border-darkborder rounded-xl overflow-hidden shadow-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-darkborder text-xs text-gray-400 uppercase tracking-wider bg-darkbg/50">
                    <th class="px-6 py-4">Nom</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Rôle</th>
                    <th class="px-6 py-4">Appareils connectés</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($utilisateurs as $u)
                    <tr class="border-b border-darkborder hover:bg-darkborder/40 transition">
                        <td class="px-6 py-4 text-white font-medium">{{ $u->prenom }} {{ $u->nom }}</td>
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $u->email }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if($u->estResponsable())
                                <span class="px-2 py-1 rounded-full bg-limeacc/20 text-limeacc text-xs font-semibold">DSI / Responsable</span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-blue-900/50 text-blue-300 text-xs font-semibold">Technicien</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($u->tokens_count > 0)
                                <span class="text-white font-semibold">{{ $u->tokens_count }}</span> actif(s)
                            @else
                                <span class="text-gray-500">Aucun</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('utilisateurs.show', $u->id_utilisateur) }}" class="text-limeacc text-sm hover:underline">
                                Voir les appareils →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">Aucun utilisateur mobile trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection