@extends('layouts.app')

@section('title', 'Liste des Pannes')
@section('role-badge', 'Responsable')

@section('content')
<div class="p-6">
    <!-- En-tête de page -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Liste des Pannes</h1>
        <a href="{{ route('pannes.create') }}" class="bg-limeacc hover:bg-lime-400 text-black font-semibold px-4 py-2 rounded-lg transition">
            + Déclarer une panne
        </a>
    </div>

    <!-- Tableau aéré et aligné -->
    <div class="bg-darkcard border border-darkborder rounded-xl overflow-hidden shadow-lg">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-darkbg/60 border-b border-darkborder text-gray-400 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Titre</th>
                    <th class="px-6 py-4">Matériel</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Statut</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-darkborder text-sm">
                @foreach($pannes as $panne)
                    @php
                        $couleurStatut = match($panne->statut) {
                            'Ouvert' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                            'En cours' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                            'Résolu' => 'bg-lime-500/10 text-limeacc border-limeacc/20',
                            default => 'bg-darkborder text-gray-300 border-darkborder',
                        };
                    @endphp
                    <tr class="hover:bg-darkbg/40 transition">
                        <td class="px-6 py-4 font-mono text-gray-400">#{{ $panne->id }}</td>
                        <td class="px-6 py-4 font-medium text-white">{{ $panne->titre }}</td>
                        <td class="px-6 py-4 text-gray-300">
                            <span class="bg-darkbg px-2 py-1 rounded text-xs font-mono border border-darkborder">
                                {{ $panne->id_materiel }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-400 whitespace-nowrap">{{ $panne->date_declaration }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $couleurStatut }} border">
                                {{ $panne->statut }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('reparations.create', $panne->id) }}" class="bg-lime-500/10 text-limeacc border border-limeacc/20 px-3 py-1 rounded-lg text-xs font-medium hover:bg-lime-500/20 transition">
                                Intervenir
                            </a>
                            <a href="{{ route('pannes.show', $panne->id) }}" class="text-limeacc hover:text-lime-300 font-medium hover:underline text-sm">
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