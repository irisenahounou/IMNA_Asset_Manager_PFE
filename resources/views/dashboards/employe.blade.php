@extends('layouts.app')

@section('title', 'Déclarer un incident')
@section('role-badge', 'Employé')

@section('content')
<div class="max-w-xl mx-auto pt-10 space-y-6">

    <div class="text-center space-y-2">
        <h1 class="text-3xl font-extrabold text-white">Signalez un Incident</h1>
        <p class="text-xs text-gray-400">Transmettez directement votre problème au service maintenance.</p>
    </div>

    <form class="bg-darkcard p-8 rounded-xl border border-darkborder space-y-5 shadow-2xl">
        <div>
            <label class="block text-xs font-semibold text-gray-300 mb-2">Sélectionnez l'équipement</label>
            <select class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-gray-200 focus:outline-none focus:border-limeacc">
                <option value="">Sélectionnez l'équipement (ex: Imprimante, PC...)</option>
                <option value="1">Imprimante 3D Ultimaker - Atelier R&D</option>
                <option value="2">PC Dell XPS 15 - Bureau 102</option>
                <option value="3">Drone Agricole Alpha 01</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-300 mb-2">Description du problème</label>
            <textarea rows="5" placeholder="Décrivez le problème rencontré en quelques mots..." class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-gray-200 focus:outline-none focus:border-limeacc"></textarea>
        </div>

        <button type="submit" class="w-full bg-limeacc hover:bg-lime-400 text-black font-bold py-3 rounded-lg text-sm transition">
            Soumettre la déclaration
        </button>
    </form>

</div>
@endsection