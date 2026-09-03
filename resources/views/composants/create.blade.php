@extends('layouts.app')

@section('title', 'Ajouter un Composant')
@section('role-badge', 'Responsable DSI')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white tracking-wide">📦 Ajouter un nouveau composant</h1>
        <p class="text-sm text-gray-400 mt-1">Enregistrez une nouvelle pièce ou un composant dans l'inventaire du stock.</p>
    </div>

    <!-- Formulaire -->
    <div class="bg-darkcard border border-darkborder rounded-xl p-6 shadow-xl">
        <form action="{{ route('composants.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nom du composant -->
            <div>
                <label for="nom_composant" class="block text-sm font-medium text-gray-300 mb-2">Nom du composant</label>
                <input type="text" class="w-full bg-darkbg text-gray-200 border border-darkborder rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-limeacc transition" id="nom_composant" name="nom_composant" required placeholder="Ex: RAM DDR4 16GB">
            </div>

            <!-- Type / Catégorie -->
            <div>
                <label for="type_composant" class="block text-sm font-medium text-gray-300 mb-2">Type / Catégorie</label>
                <input type="text" class="w-full bg-darkbg text-gray-200 border border-darkborder rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-limeacc transition" id="type_composant" name="type_composant" required placeholder="Ex: RAM, SSD, Disque Dur">
            </div>

            <!-- Quantité initiale en stock -->
            <div>
                <label for="quantite_stock" class="block text-sm font-medium text-gray-300 mb-2">Quantité initiale en stock</label>
                <input type="number" class="w-full bg-darkbg text-gray-200 border border-darkborder rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-limeacc transition" id="quantite_stock" name="quantite_stock" min="0" value="0" required>
            </div>

            <!-- Seuil d'alerte critique -->
            <div>
                <label for="seuil_alerte" class="block text-sm font-medium text-gray-300 mb-2">Seuil d'alerte critique</label>
                <input type="number" class="w-full bg-darkbg text-gray-200 border border-darkborder rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-limeacc transition" id="seuil_alerte" name="seuil_alerte" min="0" value="5" required>
                <p class="text-xs text-gray-500 mt-1">Une alerte s'affichera lorsque la quantité descendra en dessous de ce seuil.</p>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-between pt-4 border-t border-darkborder">
                <a href="{{ route('composants.index') }}" class="px-4 py-2 bg-darkborder text-gray-300 font-semibold rounded-lg text-sm hover:text-white transition">
                    Retour
                </a>
                <button type="submit" class="px-6 py-2.5 bg-limeacc text-black font-semibold rounded-lg text-sm hover:opacity-95 transition shadow-lg">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection