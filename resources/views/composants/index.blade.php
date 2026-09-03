@extends('layouts.app')

@section('title', 'Gestion des Stocks')
@section('role-badge', 'Responsable DSI')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- En-tête de page -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-wide">📦 Gestion des Stocks de Composants</h1>
            <p class="text-sm text-gray-400 mt-1">Suivi des pièces de rechange, seuils d'alerte et validation des mouvements.</p>
        </div>
        <a href="{{ route('composants.create') }}" class="px-4 py-2.5 bg-limeacc text-black font-semibold rounded-lg text-sm hover:opacity-90 transition shadow-lg">
            + Ajouter un Composant
        </a>
    </div>

    <!-- Messages Flash -->
    @if(session('success'))
        <div class="p-4 rounded-lg bg-green-950 border border-green-800 text-green-300 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-lg bg-red-950 border border-red-800 text-red-300 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Alertes de stock critique -->
    @if(isset($alertesStock) && $alertesStock->count() > 0)
        <div class="p-4 rounded-lg bg-red-950/60 border border-red-800/80 text-red-200">
            <h3 class="font-bold text-sm mb-2 flex items-center gap-2">
                <span>⚠️</span> Alertes de stock critique :
            </h3>
            <ul class="list-disc list-inside text-xs space-y-1">
                @foreach($alertesStock as $alerte)
                    <li>Le composant <span class="font-semibold text-white">{{ $alerte->nom_composant }}</span> a un stock critique de <span class="text-red-400 font-bold">{{ $alerte->quantite_stock }}</span> (Seuil : {{ $alerte->seuil_alerte }}).</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- SECTION 1 : Demandes de mouvements en attente de validation DSI -->
    <div class="bg-darkcard border border-darkborder rounded-xl overflow-hidden shadow-xl">
        <div class="px-6 py-4 border-b border-darkborder font-semibold text-white flex items-center justify-between">
            <span>⏳ Demandes de mouvements en attente de validation (DSI)</span>
        </div>
        
        <div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-darkborder text-xs text-gray-400 uppercase tracking-wider bg-darkbg/50">
                        <th class="px-6 py-4">ID Mvt</th>
                        <th class="px-6 py-4">Composant</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Quantité</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4 text-right">Actions DSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-darkborder text-sm">
                    @php
                        $mouvementsEnAttente = \App\Models\MouvementStock::where('statut_validation', 'En attente')->with('composant')->get();
                    @endphp

                    @forelse($mouvementsEnAttente as $mouvement)
                        <tr class="hover:bg-darkborder/30 transition">
                            <td class="px-6 py-4 text-gray-400">{{ $mouvement->id_mouvement }}</td>
                            <td class="px-6 py-4 font-semibold text-white">{{ $mouvement->composant->nom_composant ?? 'Inconnu' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs rounded-full {{ $mouvement->type_mouvement === 'Entrée' ? 'bg-blue-950 text-blue-400 border border-blue-800' : 'bg-orange-950 text-orange-400 border border-orange-800' }}">
                                    {{ $mouvement->type_mouvement }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-white">{{ $mouvement->quantite }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs rounded-full bg-yellow-950 text-yellow-400 border border-yellow-800">
                                    {{ $mouvement->statut_validation }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('mouvements.valider', $mouvement->id_mouvement) }}" method="POST" class="inline-flex gap-2 justify-end">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" name="statut_validation" value="Validé" class="px-3 py-1 bg-green-600 text-white font-semibold text-xs rounded hover:bg-green-500 transition">
                                        Valider
                                    </button>
                                    <button type="submit" name="statut_validation" value="Rejeté" class="px-3 py-1 bg-red-600 text-white font-semibold text-xs rounded hover:bg-red-500 transition">
                                        Rejeter
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-xs">
                                Aucune demande de mouvement en attente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECTION 2 : Inventaire général des composants -->
    <div class="bg-darkcard border border-darkborder rounded-xl overflow-hidden shadow-xl">
        <div class="px-6 py-4 border-b border-darkborder font-semibold text-white flex items-center justify-between">
            <span>Inventaire des Composants</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-darkborder text-xs text-gray-400 uppercase tracking-wider bg-darkbg/50">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Nom du Composant</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Quantité Stock</th>
                        <th class="px-6 py-4">Seuil Alerte</th>
                        <th class="px-6 py-4">Traçabilité</th>
                        <th class="px-6 py-4 text-right">Initier un Mouvement</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-darkborder text-sm">
                    @forelse($composants as $composant)
                        <tr class="hover:bg-darkborder/30 transition">
                            <td class="px-6 py-4 text-gray-400">{{ $composant->id_composant }}</td>
                            <td class="px-6 py-4 font-semibold text-white">{{ $composant->nom_composant }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs rounded-full bg-darkborder text-gray-300">
                                    {{ $composant->type_composant }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $composant->quantite_stock <= $composant->seuil_alerte ? 'bg-red-950 text-red-400 border border-red-800' : 'bg-green-950 text-green-400 border border-green-800' }}">
                                    {{ $composant->quantite_stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400">{{ $composant->seuil_alerte }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('unites.index', $composant->id_composant) }}" class="text-limeacc text-xs hover:underline">Voir les unités → </a>
                            </td>   

                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('mouvements.store') }}" method="POST" class="inline-flex items-center gap-2 justify-end">
                                    @csrf
                                    <input type="hidden" name="id_composant" value="{{ $composant->id_composant }}">
                                    <select name="type_mouvement" class="bg-darkbg text-gray-200 border border-darkborder rounded px-2 py-1 text-xs focus:outline-none focus:border-limeacc">
                                        <option value="Sortie">Sortie</option>
                                        <option value="Entrée">Entrée</option>
                                    </select>
                                    <input type="number" name="quantite" value="1" min="1" class="w-16 bg-darkbg text-gray-200 border border-darkborder rounded px-2 py-1 text-xs focus:outline-none focus:border-limeacc" placeholder="Qté">
                                    <button type="submit" class="px-3 py-1 bg-limeacc text-black font-semibold text-xs rounded hover:opacity-95 transition">
                                        Demander
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                Aucun composant enregistré pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection