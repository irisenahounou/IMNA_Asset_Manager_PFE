@extends('layouts.app')

@section('title', 'Gestion du Parc Matériel')
@section('role-badge', 'Responsable')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    
    <!-- En-tête -->
    <div class="flex justify-between items-center border-b border-darkborder pb-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Gestion du parc matériel</h1>
            <p class="text-xs text-gray-400 mt-1">Liste complète des équipements, serveurs et drones enregistrés.</p>
        </div>
        <a href="{{ route('equipements.create') }}" class="bg-limeacc hover:bg-lime-400 text-black px-4 py-2 rounded-lg font-bold text-sm transition flex items-center gap-2">
            + Ajouter un équipement
        </a>
    </div>

    <!-- Message de succès -->
    @if(session('success'))
        <div class="bg-lime-950/60 border border-limeacc text-limeacc px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tableau du Parc Matériel -->
    <div class="bg-darkcard rounded-xl border border-darkborder p-6 shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-300">
                <thead class="text-xs uppercase bg-darkbg text-gray-400 border-b border-darkborder">
                    <tr>
                        <th class="p-3">ID</th>
                        <th class="p-3">Nom de l'équipement</th>
                        <th class="p-3">Localisation / Salle</th>
                        <th class="p-3">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-darkborder">
                    @forelse($equipements as $equipement)
                        <tr class="hover:bg-darkbg/50 transition">
                            <td class="p-3 font-mono text-gray-400">#MAT-{{ $equipement->id_equipement }}</td>
                            <td class="p-3 font-medium text-white">{{ $equipement->nom_equipement }}</td>
                            <td class="p-3 text-gray-400">{{ $equipement->localisation ?? 'Non spécifiée' }}</td>
                            <td class="p-3">
                                @if($equipement->statut === 'Opérationnel')
                                    <span class="px-2.5 py-1 bg-lime-950 text-limeacc border border-limeacc/30 rounded text-xs font-semibold">
                                        Opérationnel
                                    </span>
                                @elseif($equipement->statut === 'En panne')
                                    <span class="px-2.5 py-1 bg-red-950 text-red-400 border border-red-500/30 rounded text-xs font-semibold">
                                        En panne
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-amber-950 text-amber-400 border border-amber-500/30 rounded text-xs font-semibold">
                                        {{ $equipement->statut }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500 italic">
                                Aucun équipement enregistré dans le parc pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection