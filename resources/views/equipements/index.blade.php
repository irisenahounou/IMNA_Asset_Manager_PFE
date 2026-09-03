@extends('layouts.app')

@section('title', 'Gestion du Parc Matériel')
@section('role-badge', 'Responsable')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    
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
                        <th class="p-3">N° Série</th>
                        <th class="p-3">Nom</th>
                        <th class="p-3">Type</th>
                        <th class="p-3">Localisation</th>
                        <th class="p-3">Statut</th>
                        <th class="p-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-darkborder">
                    @forelse($equipements as $equipement)
                        <tr class="hover:bg-darkbg/50 transition">
                            <td class="p-3 font-mono text-gray-400">{{ $equipement->id }}</td>
                            <td class="p-3 text-gray-400">{{ $equipement->numero_serie }}</td>
                            <td class="p-3 font-medium text-white">{{ $equipement->nom_equipement }}</td>
                            <td class="p-3 text-gray-400">{{ $equipement->type }}</td>
                            <td class="p-3 text-gray-400">{{ $equipement->localisation ?? 'Non spécifiée' }}</td>
                            <td class="p-3">
                                <span class="px-2.5 py-1 bg-lime-950 text-limeacc border border-limeacc/30 rounded text-xs font-semibold">
                                    Opérationnel
                                </span>
                            </td>
                            <td class="p-3 text-right flex items-center justify-end gap-2">
                                <!-- Bouton Modifier -->
                                <a href="{{ route('equipements.edit', $equipement->id) }}" class="text-sky-400 hover:text-sky-300 text-xs bg-sky-950/40 border border-sky-500/20 px-3 py-1.5 rounded-lg transition">
                                    Modifier
                                </a>

                                <!-- Bouton Supprimer / Retirer -->
                                <form action="{{ route('equipements.destroy', $equipement->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment retirer cet équipement du parc ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs bg-red-950/40 border border-red-500/20 px-3 py-1.5 rounded-lg transition">
                                        Retirer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-gray-500 italic">
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

