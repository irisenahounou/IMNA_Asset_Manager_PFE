@extends('layouts.app')

@section('title', "Modifier l'équipement")
@section('role-badge', 'Responsable')

@section('content')
<div class="max-w-xl mx-auto space-y-6 pt-4">

    <a href="{{ route('equipements.index') }}" class="text-xs text-gray-400 hover:text-white transition flex items-center gap-1">
        ← Retour au parc
    </a>

    <div class="bg-darkcard p-8 rounded-xl border border-darkborder shadow-2xl space-y-6">
        <div>
            <h1 class="text-xl font-bold text-white">Modifier l'équipement : {{ $equipement->nom_equipement }}</h1>
            <p class="text-xs text-gray-400 mt-1">Mettez à jour les informations de cet équipement dans le parc.</p>
        </div>

        <form method="POST" action="{{ route('equipements.update', $equipement->id) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">ID de l'équipement (Non modifiable)</label>
                <input type="text" value="{{ $equipement->id }}" disabled class="w-full bg-darkbg/50 border border-darkborder/50 rounded-lg p-3 text-sm text-gray-500 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">Numéro de série</label>
                <input type="number" name="numero_serie" value="{{ old('numero_serie', $equipement->numero_serie) }}" required class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">Nom de l'équipement</label>
                <input type="text" name="nom_equipement" value="{{ old('nom_equipement', $equipement->nom_equipement) }}" required class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">Emplacement / Localisation (Optionnel)</label>
                <input type="text" name="localisation" value="{{ old('localisation', $equipement->localisation) }}" placeholder="Ex: Salle Serveur - DSI..." class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">Type d'équipement</label>
                <input type="text" name="type" value="{{ old('type', $equipement->type) }}" required class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">Date d'achat</label>
                <input type="date" name="date_achat" value="{{ old('date_achat', $equipement->date_achat) }}" required class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">ID du Service</label>
                <input type="number" name="id_service" value="{{ old('id_service', $equipement->id_service) }}" required class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">ID du Responsable</label>
                <input type="number" name="id_responsable" value="{{ old('id_responsable', $equipement->id_responsable) }}" required class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc">
            </div>

            <button type="submit" class="w-full bg-limeacc hover:bg-lime-400 text-black font-bold py-3 rounded-lg text-sm transition">
                Mettre à jour l'équipement
            </button>
        </form>
    </div>

</div>
@endsection