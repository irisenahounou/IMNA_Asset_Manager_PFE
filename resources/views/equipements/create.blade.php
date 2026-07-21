@extends('layouts.app')

@section('title', 'Enregistrer un nouvel équipement')
@section('role-badge', 'Responsable')

@section('content')
<div class="max-w-xl mx-auto space-y-6 pt-4">

    <a href="{{ route('equipements.index') }}" class="text-xs text-gray-400 hover:text-white transition flex items-center gap-1">
        ← Retour au parc
    </a>

    <div class="bg-darkcard p-8 rounded-xl border border-darkborder shadow-2xl space-y-6">
        <div>
            <h1 class="text-xl font-bold text-white">Enregistrer un nouvel équipement</h1>
            <p class="text-xs text-gray-400 mt-1">Renseignez les détails pour l'ajouter au suivi du parc.</p>
        </div>

        <form method="POST" action="{{ route('equipements.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">Nom de l'équipement</label>
                <input type="text" name="nom_equipement" required placeholder="Ex: Serveur Rack 05, Imprimante 3D..." class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">Domaine / Type d'utilisation</label>
                <input type="text" name="type_equipement" required placeholder="Ex: Serveur, Drone, PC..." class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">Statut de l'équipement</label>
                <input type="text" value="Opérationnel (automatique)" disabled class="w-full bg-darkbg/50 border border-darkborder/50 rounded-lg p-3 text-sm text-gray-500 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">Emplacement / Localisation (Optionnel)</label>
                <input type="text" name="localisation" placeholder="Ex: Salle Serveur - DSI, Atelier R&D..." class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>

            <button type="submit" class="w-full bg-limeacc hover:bg-lime-400 text-black font-bold py-3 rounded-lg text-sm transition">
                Enregistrer l'équipement
            </button>
        </form>
    </div>

</div>
@endsection