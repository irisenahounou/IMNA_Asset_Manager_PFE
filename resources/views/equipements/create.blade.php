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
                <label class="block text-xs font-semibold text-gray-300 mb-2">ID de l'équipement </label>
                <input type="text" name="id" required placeholder="Ex: MAT-305" class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">Numéro de série</label>
                <input type="text" name="numero_serie" maxlength="100" required placeholder="Ex: SN-DJI-T40-001" value="{{ old('numero_serie') }}" class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">Nom de l'équipement</label>
                <input type="text" name="nom_equipement" required placeholder="Ex: Serveur Rack 05, Drone..." class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>
            <div>
           <label class="block text-xs font-semibold text-gray-300 mb-2">Emplacement / Localisation (Optionnel)</label>
          <input type="text" name="localisation" placeholder="Ex: Salle Serveur - DSI, Atelier R&D..." class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">Type d'équipement</label>
                <input type="text" name="type" required placeholder="Ex: Serveur, Drone, PC..." class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">Date d'achat</label>
                <input type="date" name="date_achat" required class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">ID du Service</label>
                <input type="number" name="id_service" required placeholder="Ex: 1" class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">ID du Responsable</label>
                <input type="number" name="id_responsable" required placeholder="Ex: 1" class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>

            <button type="submit" class="w-full bg-limeacc hover:bg-lime-400 text-black font-bold py-3 rounded-lg text-sm transition">
                Enregistrer l'équipement
            </button>
        </form>
    </div>

</div>
@endsection