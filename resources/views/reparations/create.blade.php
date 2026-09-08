@extends('layouts.app')

@section('title', 'Planifier une réparation')
@section('role-badge', 'Responsable')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Planifier une Réparation</h1>
        <p class="text-gray-400 text-sm mt-1">
            Panne concernée : <span class="text-limeacc font-semibold">#{{ $panne->id }} - {{ $panne->titre }}</span>
        </p>
    </div>

    <div class="bg-darkcard border border-darkborder rounded-xl p-6 shadow-lg">
        <form action="{{ route('reparations.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-gray-300 text-sm font-medium mb-1">ID de Réparation (ex: REP-001)</label>
                <input type="text" name="id" required
                    class="w-full bg-darkbg border border-darkborder rounded-lg px-4 py-2 text-white focus:outline-none focus:border-limeacc"
                    placeholder="REP-001">
            </div>

            <input type="hidden" name="id_panne" value="{{ $panne->id }}">

            <div>
                <label class="block text-gray-300 text-sm font-medium mb-1">Date et heure de début</label>
                <input type="datetime-local" name="date_debut" required
                    class="w-full bg-darkbg border border-darkborder rounded-lg px-4 py-2 text-white focus:outline-none focus:border-limeacc">
            </div>

            <div>
                <label class="block text-gray-300 text-sm font-medium mb-1">Technicien assigné</label>
                <select name="id_technicien" required
                    class="w-full bg-darkbg border border-darkborder rounded-lg px-4 py-2 text-white focus:outline-none focus:border-limeacc">
                    <option value="">Sélectionner un technicien</option>
                    @foreach($techniciens as $tech)
                        <option value="{{ $tech->id_technicien }}">
                            {{ $tech->utilisateur->prenom ?? '' }} {{ $tech->utilisateur->nom ?? 'Technicien #' . $tech->id_technicien }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-300 text-sm font-medium mb-1">Rapport technique / Description</label>
                <textarea name="rapport_technique" rows="4"
                    class="w-full bg-darkbg border border-darkborder rounded-lg px-4 py-2 text-white focus:outline-none focus:border-limeacc"
                    placeholder="Détails de l'intervention à effectuer..."></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('pannes.index') }}" class="px-4 py-2 bg-darkborder text-gray-300 rounded-lg hover:bg-darkbg transition">
                    Annuler
                </a>
                <button type="submit" class="px-5 py-2 bg-limeacc hover:bg-lime-400 text-black font-semibold rounded-lg transition">
                    Enregistrer la réparation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection