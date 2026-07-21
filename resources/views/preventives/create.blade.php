@extends('layouts.app')

@section('title', 'Planifier une Maintenance Préventive')
@section('role-badge', 'Espace DSI')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <!-- En-tête -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Planifier une Maintenance Préventive</h1>
        <p class="text-gray-400 text-sm mt-1">Anticiper l'usure du matériel suite aux alertes d'analyse système.</p>
    </div>

    <!-- Formulaire -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 shadow-lg">
        <form action="{{ route('preventives.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- ID Préventif -->
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-1">ID de Maintenance Préventive (ex: PREV-001)</label>
                <input type="text" name="id_preventive" required 
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-lime-400 font-mono"
                    placeholder="PREV-001">
            </div>

            <!-- Choix du materiel / élément cible -->
            <div>
                 <label class="block text-gray-300 text-sm font-medium mb-1">Matériel concerné</label>
                 <select name="id_materiel" required 
                  class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-lime-400">
                    <option value="">Sélectionner un équipement du parc</option>
                    @foreach($materiels as $mat)
                    <option value="{{ $mat->id }}">
                        {{ $mat->id }} - {{ $mat->type ?? 'Équipement' }} ({{ $mat->modele ?? 'Modèle standard' }})
                     </option>
                    @endforeach
                 </select>
            </div>

            <!-- Fréquence en jours -->
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-1">Fréquence (en jours)</label>
                <input type="number" name="frequence_jour" required min="1"
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-lime-400"
                    placeholder="Ex: 30 (pour une révision mensuelle)">
            </div>

            <!-- Prochaine intervention -->
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-1">Date de la prochaine intervention</label>
                <input type="date" name="prochaine_rep" required 
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-lime-400">
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('preventives.index') }}" class="px-4 py-2 bg-gray-800 text-gray-300 rounded-lg hover:bg-gray-700 transition">
                    Annuler
                </a>
                <button type="submit" class="px-5 py-2 bg-lime-500 hover:bg-lime-600 text-black font-semibold rounded-lg transition">
                    Enregistrer la planification
                </button>
            </div>
        </form>
    </div>
</div>
@endsection