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

    @if(session('success'))
        <div class="p-4 rounded-lg bg-green-950 border border-green-800 text-green-300 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-lg bg-red-950 border border-red-800 text-red-300 text-sm">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Colonne Gauche : Liste réelle des tickets -->
        <div class="space-y-4">
            @forelse($tickets as $ticket)
                @php
                    $estSelectionne = $ticketSelectionne && $ticketSelectionne->id === $ticket->id;
                    $couleurStatut = match($ticket->statut) {
                        'Ouvert' => 'bg-red-900/50 text-red-400',
                        'En cours' => 'bg-amber-900/50 text-amber-400',
                        'Résolu' => 'bg-green-900/50 text-green-400',
                        default => 'bg-darkborder text-gray-300',
                    };
                @endphp
                <a href="{{ route('technicien.dashboard', ['ticket' => $ticket->id]) }}"
                   class="block bg-darkcard p-5 rounded-xl border {{ $estSelectionne ? 'border-limeacc' : 'border-darkborder' }} space-y-2 hover:border-limeacc transition">
                    <span class="px-2.5 py-1 {{ $couleurStatut }} rounded-full text-xs font-bold">{{ $ticket->statut }}</span>
                    <h3 class="text-lg font-bold text-white mt-1">{{ $ticket->titre }}</h3>
                    <p class="text-xs text-gray-400">{{ $ticket->materiel->nom_equipement ?? '—' }}</p>
                    <p class="text-xs text-gray-500 font-mono">ID : #{{ $ticket->id }}</p>
                </a>
            @empty
                <p class="text-sm text-gray-500 text-center py-6">Aucun ticket ouvert pour le moment.</p>
            @endforelse
        </div>

        <!-- Colonne Droite : Détail + actions du ticket sélectionné -->
        <div class="bg-darkcard p-6 rounded-xl border border-darkborder space-y-5 h-fit">
            @if($ticketSelectionne)
                @php
                    $mesReparations = $ticketSelectionne->reparations->where('id_technicien', Auth::id());
                    $jePeuxPrendreEnCharge = $ticketSelectionne->statut === 'Ouvert';
                    $jePeuxCloturer = $ticketSelectionne->statut === 'En cours' && $mesReparations->whereNull('date_fin')->count() > 0;
                @endphp

                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white">Intervention #{{ $ticketSelectionne->id }}</h2>
                    <span class="px-3 py-1 bg-amber-900/40 text-amber-400 rounded-full text-xs font-semibold">{{ $ticketSelectionne->statut }}</span>
                </div>

                <div class="space-y-2 text-sm text-gray-300 bg-darkbg p-4 rounded-lg border border-darkborder">
                    <p><strong class="text-white">Équipement :</strong> {{ $ticketSelectionne->materiel->nom_equipement ?? '—' }}</p>
                    <p><strong class="text-white">Déclaré par :</strong> {{ $ticketSelectionne->declarant->prenom ?? '' }} {{ $ticketSelectionne->declarant->nom ?? '' }}</p>
                    <p class="text-xs text-gray-400 pt-2 border-t border-darkborder">
                        <strong class="text-gray-200">Description :</strong> {{ $ticketSelectionne->description }}
                    </p>
                </div>

                @if($jePeuxPrendreEnCharge)
                    <form method="POST" action="{{ route('technicien.prendreEnCharge', $ticketSelectionne->id) }}">
                        @csrf
                        <button type="submit" class="w-full bg-limeacc hover:bg-lime-400 text-black font-bold py-3 rounded-lg text-sm transition">
                            Prendre en charge
                        </button>
                    </form>
                @endif

                @if($jePeuxCloturer)
                    <form method="POST" action="{{ route('technicien.cloturer', $ticketSelectionne->id) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-2">Compte-rendu d'intervention</label>
                            <textarea name="rapport_technique" required rows="4" placeholder="Saisir le compte-rendu de l'intervention..."
                                class="w-full bg-darkbg border border-darkborder rounded-lg p-3 text-sm text-white focus:outline-none focus:border-limeacc"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-limeacc hover:bg-lime-400 text-black font-bold py-3 rounded-lg text-sm transition">
                            Clôturer le ticket
                        </button>
                    </form>
                @endif

                @if($ticketSelectionne->statut === 'Résolu')
                    <p class="text-sm text-green-400 text-center py-2">✅ Ticket déjà résolu.</p>
                @endif
            @else
                <p class="text-sm text-gray-500 text-center py-10">Sélectionne un ticket dans la liste à gauche.</p>
            @endif
        </div>

    </div>

</div>
@endsection