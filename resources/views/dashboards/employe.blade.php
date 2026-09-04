@extends('layouts.app')

@section('title', 'Déclarer un incident')
@section('role-badge', 'Employé')

@section('content')
<div class="max-w-2xl mx-auto pt-10 space-y-10 pb-16">

    <div class="text-center space-y-2">
        <h1 class="text-3xl font-extrabold text-white">Signalez un Incident</h1>
        <p class="text-xs text-gray-400">Transmettez directement votre problème au service maintenance.</p>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-lg bg-green-950 border border-green-800 text-green-300 text-sm">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="p-4 rounded-lg bg-red-950 border border-red-800 text-red-300 text-sm">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('employe.declarer') }}" enctype="multipart/form-data" class="bg-darkcard p-8 rounded-xl border border-darkborder space-y-5 shadow-2xl">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-gray-300 mb-2">Sélectionnez l'équipement</label>
            <select name="id_materiel" required class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-gray-200 focus:outline-none focus:border-limeacc">
                <option value="">Sélectionnez l'équipement (ex: Imprimante, PC...)</option>
                @foreach($materiels as $materiel)
                    <option value="{{ $materiel->id }}">{{ $materiel->nom_equipement }} — {{ $materiel->type }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-300 mb-2">Titre</label>
            <input type="text" name="titre" required placeholder="Ex : L'écran ne s'allume plus"
                class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-gray-200 focus:outline-none focus:border-limeacc">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-300 mb-2">Description du problème</label>
            <textarea name="description" required rows="5" placeholder="Décrivez le problème rencontré en quelques mots..."
                class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-gray-200 focus:outline-none focus:border-limeacc"></textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-300 mb-2">Photo (optionnel)</label>
            <input type="file" name="photo" accept="image/*"
                class="w-full bg-darkbg border border-darkborder rounded-lg p-2.5 text-xs text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-darkborder file:text-gray-200 file:text-xs">
        </div>

        <button type="submit" class="w-full bg-limeacc hover:bg-lime-400 text-black font-bold py-3 rounded-lg text-sm transition">
            Soumettre la déclaration
        </button>
    </form>

    <!-- US-02 : suivi de ses propres tickets -->
    <div class="space-y-4">
        <h2 class="text-lg font-bold text-white">Mes déclarations</h2>

        @forelse($mesPannes as $panne)
            @php
                $couleurStatut = match($panne->statut) {
                    'Ouvert' => 'bg-amber-900/50 text-amber-400',
                    'En cours' => 'bg-blue-900/50 text-blue-400',
                    'Résolu' => 'bg-green-900/50 text-green-400',
                    default => 'bg-darkborder text-gray-300',
                };
            @endphp
            <div class="bg-darkcard p-5 rounded-xl border border-darkborder">
                <div class="flex justify-between items-start gap-3">
                    <h3 class="text-sm font-bold text-white">{{ $panne->titre }}</h3>
                    <span class="px-2.5 py-1 {{ $couleurStatut }} rounded-full text-xs font-bold whitespace-nowrap">{{ $panne->statut }}</span>
                </div>
                <p class="text-xs text-gray-400 mt-2">{{ Str::limit($panne->description, 80) }}</p>
                <p class="text-xs text-gray-600 mt-3">{{ \Carbon\Carbon::parse($panne->date_declaration)->format('d/m/Y H:i') }}</p>
            </div>
        @empty
            <p class="text-sm text-gray-500 text-center py-6">Tu n'as déclaré aucun incident pour le moment.</p>
        @endforelse
    </div>

</div>
@endsection