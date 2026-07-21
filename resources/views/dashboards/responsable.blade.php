@extends('layouts.app')

@section('title', 'Dashboard Responsable')
@section('role-badge', 'Responsable')

@section('content')
<div class="space-y-8">
    
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-white">Bonjour, Responsable DSI</h1>
            <p class="text-sm text-gray-400 mt-1">Aperçu global de l'état du parc et des pannes.</p>
        </div>
        <button class="bg-limeacc hover:bg-lime-400 text-black px-4 py-2 rounded-lg font-semibold text-sm transition">
            + Ajouter un équipement
        </button>
    </div>

    <!-- 3 Cartes KPI Sombre / Lime (Exact Figma) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-darkcard p-6 rounded-xl border border-darkborder">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Matériels opérationnels</p>

            <p class="text-3xl font-bold text-limeacc mt-2">124</p>
        </div>

        <div class="bg-darkcard p-6 rounded-xl border border-darkborder">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Pannes en attente</p>
            <p class="text-3xl font-bold text-amber-400 mt-2">5</p>
        </div>

        <div class="bg-darkcard p-6 rounded-xl border border-darkborder">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Techniciens disponibles</p>
            <p class="text-3xl font-bold text-white mt-2">3</p>
        </div>
    </div>

    <!-- Tableau du parc / pannes récentes -->
    <div class="bg-darkcard rounded-xl border border-darkborder p-6 space-y-4">
        <h2 class="text-lg font-bold text-white">Suivi Récent des Équipements</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-300">
                <thead class="text-xs uppercase bg-darkbg text-gray-400 border-b border-darkborder">
                    <tr>
                        <th class="p-3">ID</th>
                        <th class="p-3">Matériel</th>
                        <th class="p-3">Priorité</th>
                        <th class="p-3">Date</th>
                        <th class="p-3">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-darkborder">
                    <tr>
                        <td class="p-3 font-mono text-gray-400">PN-101</td>
                        <td class="p-3 font-medium text-white">Serveur Rack 04</td>
                        <td class="p-3"><span class="px-2 py-1 bg-red-950 text-red-400 rounded text-xs font-bold">Critique</span></td>
                        <td class="p-3 text-gray-400">19 Juil</td>
                        <td class="p-3"><span class="px-2 py-1 bg-amber-950 text-amber-400 rounded text-xs">En attente</span></td>
                    </tr>
                    <tr>
                        <td class="p-3 font-mono text-gray-400">PN-102</td>
                        <td class="p-3 font-medium text-white">PC Dell XPS 15</td>
                        <td class="p-3"><span class="px-2 py-1 bg-amber-950 text-amber-400 rounded text-xs font-bold">Moyenne</span></td>
                        <td class="p-3 text-gray-400">18 Juil</td>
                        <td class="p-3"><span class="px-2 py-1 bg-blue-950 text-blue-400 rounded text-xs">En cours</span></td>
                    </tr>
                    <tr>
                        <td class="p-3 font-mono text-gray-400">PN-103</td>
                        <td class="p-3 font-medium text-white">Imprimante 3D Ultimaker</td>
                        <td class="p-3"><span class="px-2 py-1 bg-gray-800 text-gray-300 rounded text-xs font-bold">Basse</span></td>
                        <td class="p-3 text-gray-400">15 Juil</td>
                        <td class="p-3"><span class="px-2 py-1 bg-lime-950 text-limeacc rounded text-xs">Clôturé</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection