@extends('layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Gestion des Interventions & Réparations</h1>
            <p class="text-gray-400 text-sm mt-1">Suivi de l'historique et des interventions en cours.</p>
        </div>
    </div>

    <!-- Message de succès éventuel -->
    @if(session('success'))
        <div class="mb-6 bg-lime-500/10 border border-lime-500/20 text-lime-400 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tableau des réparations -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-800/50 border-b border-gray-800 text-gray-400 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4">ID Réparation</th>
                        <th class="px-6 py-4">Panne / Matériel</th>
                        <th class="px-6 py-4">Technicien Assigné</th>
                        <th class="px-6 py-4">Date Début</th>
                        <th class="px-6 py-4">Date Fin</th>
                        <th class="px-6 py-4">Rapport Technique</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-sm">
                    @forelse($reparations as $rep)
                        <tr class="hover:bg-gray-800/40 transition">
                            <td class="px-6 py-4 font-mono text-lime-400 font-medium">#{{ $rep->id }}</td>
                            <td class="px-6 py-4">
                                <div class="text-white font-medium">
                                    {{ $rep->panne->titre ?? 'Panne #' . $rep->id_panne }}
                                </div>
                                <div class="text-xs text-gray-400 font-mono mt-0.5">
                                    Matériel : {{ $rep->panne->id_materiel ?? 'N/C' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                @if($rep->technicien && $rep->technicien->utilisateur)
                                    {{ $rep->technicien->utilisateur->prenom }} {{ $rep->technicien->utilisateur->nom }}
                                @else
                                    <span class="text-gray-500 italic">Non assigné</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-400 whitespace-nowrap">
                                {{ $rep->date_debut ? date('d/m/Y H:i', strtotime($rep->date_debut)) : '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-400 whitespace-nowrap">
                                {{ $rep->date_fin ? date('d/m/Y H:i', strtotime($rep->date_fin)) : 'En cours' }}
                            </td>
                            <td class="px-6 py-4 text-gray-300 max-w-xs truncate" title="{{ $rep->rapport_technique }}">
                                {{ $rep->rapport_technique ?: 'Aucun rapport pour l\'instant' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Aucune réparation enregistrée pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection