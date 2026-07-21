@extends('layouts.app')

@section('title', 'Dashboard Technicien')
@section('role-badge', 'Technicien')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex justify-between items-center border-b border-darkborder pb-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Mes Interventions</h1>
            <p class="text-xs text-limeacc font-semibold mt-1">● Statut : Disponible</p>
        </div>
    </div>

    <!-- Onglets À Faire / Historique (Comme sur Figma) -->
    <div class="flex space-x-3">
        <button class="bg-limeacc text-black px-6 py-2 rounded-lg font-bold text-sm">A Faire</button>
        <button class="bg-darkcard text-gray-400 hover:text-white px-6 py-2 rounded-lg text-sm border border-darkborder">Historique</button>
    </div>

    <!-- Layout 2 colonnes (Liste tickets à gauche, Détails à droite) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Colonne Gauche : Liste des cartes -->
        <div class="space-y-4">
            <!-- Carte 1 -->
            <div class="bg-darkcard p-5 rounded-xl border border-darkborder space-y-2 hover:border-limeacc transition cursor-pointer">
                <span class="px-2.5 py-1 bg-red-900/50 text-red-400 rounded-full text-xs font-bold">Critique</span>
                <h3 class="text-lg font-bold text-white mt-1">Serveur Rack 04</h3>
                <p class="text-xs text-gray-400">Salle Serveur - DSI</p>
                <p class="text-xs text-gray-500 font-mono">ID : #MAT-402</p>
            </div>

            <!-- Carte 2 -->
            <div class="bg-darkcard p-5 rounded-xl border border-darkborder space-y-2 hover:border-limeacc transition cursor-pointer">
                <span class="px-2.5 py-1 bg-amber-900/50 text-amber-400 rounded-full text-xs font-bold">Haute</span>
                <h3 class="text-lg font-bold text-white mt-1">Imprimante 3D Ultimaker</h3>
                <p class="text-xs text-gray-400">Atelier Prototype - R&D</p>
                <p class="text-xs text-gray-500 font-mono">ID : #MAT-305</p>
            </div>
        </div>

        <!-- Colonne Droite : Détail Intervention #PN-402 (Figma 05_Détails_Intervention) -->
        <div class="bg-darkcard p-6 rounded-xl border border-darkborder space-y-5">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-white">Intervention #PN-402</h2>
                <span class="px-3 py-1 bg-amber-900/40 text-amber-400 rounded-full text-xs font-semibold">En cours</span>
            </div>

            <div class="space-y-2 text-sm text-gray-300 bg-darkbg p-4 rounded-lg border border-darkborder">
                <p><strong class="text-white">Équipement :</strong> Serveur Rack 04</p>
                <p><strong class="text-white">Lieu :</strong> Salle Serveur - DSI</p>
                <p class="text-xs text-gray-400 pt-2 border-t border-darkborder">
                    <strong class="text-gray-200">Description :</strong> Le serveur a disjoncté suite à une coupure de courant. Les voyants restent rouges.
                </p>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-400 mb-2">Compte-rendu d'intervention</label>
                <textarea rows="4" placeholder="Saisir le compte-rendu de l'intervention..." class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc"></textarea>
            </div>

            <button class="w-full bg-limeacc hover:bg-lime-400 text-black font-bold py-3 rounded-lg text-sm transition">
                Clôturer le ticket
            </button>
        </div>

    </div>

</div>
@endsection