@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- En-tête de page -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Liste des Pannes</h1>
        <a href="{{ route('pannes.create') }}" class="bg-lime-500 hover:bg-lime-600 text-black font-semibold px-4 py-2 rounded-lg transition">
            + Déclarer une panne
        </a>
    </div>

    <!-- Tableau aéré et aligné -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden shadow-lg">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800/60 border-b border-gray-700 text-gray-400 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Titre</th>
                    <th class="px-6 py-4">Matériel</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Statut</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800 text-sm">
                @foreach($pannes as $panne)
    <tr class="hover:bg-gray-800/40 transition">
        <td class="px-6 py-4 font-mono text-gray-400">#{{ $panne->id }}</td>
        <td class="px-6 py-4 font-medium text-white">{{ $panne->titre }}</td>
        <td class="px-6 py-4 text-gray-300">
            <span class="bg-gray-800 px-2 py-1 rounded text-xs font-mono border border-gray-700">
                {{ $panne->id_materiel }}
            </span>
        </td>
        <td class="px-6 py-4 text-gray-400 whitespace-nowrap">{{ $panne->date_declaration }}</td>
        <td class="px-6 py-4">
            <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                {{ $panne->statut }}
            </span>
        </td>
        <td class="px-6 py-4 text-right space-x-2">
            <!-- Bouton Intervenir qui redirige vers le formulaire de réparation -->
            <a href="{{ route('reparations.create', $panne->id) }}" class="bg-lime-500/10 text-lime-400 border border-lime-500/20 px-3 py-1 rounded-lg text-xs font-medium hover:bg-lime-500/20 transition">
                Intervenir
            </a>
            <a href="{{ route('pannes.show', $panne->id) }}" class="text-lime-400 hover:text-lime-300 font-medium hover:underline text-sm">
                Voir
            </a>
        </td>
    </tr>
     @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection