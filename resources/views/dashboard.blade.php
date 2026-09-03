@extends('layouts.app')

@section('title', 'Tableau de bord DSI')
@section('role-badge', 'Espace DSI')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">
    <!-- En-tête -->
    <div>
        <h1 class="text-2xl font-bold text-white">Tableau de bord - Supervision DSI</h1>
        <p class="text-gray-400 text-sm mt-1">Vue d'ensemble et pilotage de la maintenance du parc industriel IMNA.</p>
    </div>

    <!-- Cartes de KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Carte Matériel -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 shadow-lg flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider font-semibold">Parc Matériel</p>
                <h3 class="text-3xl font-bold text-white mt-2">{{ $totalMateriels }}</h3>
                <p class="text-xs text-lime-400 mt-1">Équipements enregistrés</p>
            </div>
            <div class="p-3 bg-gray-800 rounded-lg text-lime-400 text-xl">📦</div>
        </div>

        <!-- Carte Pannes en attente -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 shadow-lg flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider font-semibold">Pannes / Tickets</p>
                <h3 class="text-3xl font-bold text-white mt-2">{{ $pannesEnAttente }}</h3>
                <p class="text-xs text-yellow-400 mt-1">En attente de prise en charge</p>
            </div>
            <div class="p-3 bg-gray-800 rounded-lg text-yellow-400 text-xl">⚠️</div>
        </div>

        <!-- Carte Préventives -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 shadow-lg flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider font-semibold">Maintenances Préventives</p>
                <h3 class="text-3xl font-bold text-white mt-2">{{ $preventivesCount }}</h3>
                <p class="text-xs text-lime-400 mt-1">Planifications actives</p>
            </div>
            <div class="p-3 bg-gray-800 rounded-lg text-lime-400 text-xl">🛡️</div>
        </div>
    </div>

    <!-- Section Tableaux récapitulatifs -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Dernières pannes -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden shadow-lg p-5">
            <h2 class="text-lg font-bold text-white mb-4 flex items-center space-x-2">
                <span>⚡ Dernières pannes signalées</span>
            </h2>
            <div class="space-y-3">
                @forelse($dernieresPannes as $panne)
                    <div class="bg-gray-800/40 p-3 rounded-lg border border-gray-800 flex justify-between items-center text-sm">
                        <div>
                            <span class="text-white font-medium">{{ $panne->titre ?? 'Panne #' . $panne->id }}</span>
                            <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($panne->description, 40) }}</p>
                        </div>
                        <a href="{{ route('preventives.create') }}" class="px-3 py-1 bg-gray-800 hover:bg-gray-700 text-gray-300 text-xs rounded transition">Traiter</a>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-6">Aucune panne signalée pour le moment.</p>
                @endforelse
            </div>
        </div>

        <!-- Prochaines maintenances préventives -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden shadow-lg p-5">
            <h2 class="text-lg font-bold text-white mb-4 flex items-center space-x-2">
                <span>📅 Prochaines interventions préventives</span>
            </h2>
            <div class="space-y-3">
                @forelse($prochainesPreventives as $prev)
                    <div class="bg-gray-800/40 p-3 rounded-lg border border-gray-800 flex justify-between items-center text-sm">
                        <div>
                            <span class="text-lime-400 font-mono font-medium">#{{ $prev->id_preventive }}</span>
                            <p class="text-xs text-gray-300 mt-0.5">Prévue le : {{ date('d/m/Y', strtotime($prev->prochaine_rep)) }}</p>
                        </div>
                        <span class="text-xs px-2.5 py-1 bg-gray-800 text-gray-300 rounded-md">Tous les {{ $prev->frequence_jour }} j</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-6">Aucune préventive planifiée.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection