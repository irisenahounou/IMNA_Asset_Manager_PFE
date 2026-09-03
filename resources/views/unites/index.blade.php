@extends('layouts.app')

@section('title', 'Traçabilité — ' . $composant->nom_composant)
@section('role-badge', 'Responsable DSI')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <a href="{{ route('composants.index') }}" class="text-sm text-gray-400 hover:text-white">← Retour au stock</a>

    <div>
        <h1 class="text-2xl font-bold text-white tracking-wide">🔎 Traçabilité — {{ $composant->nom_composant }}</h1>
        <p class="text-sm text-gray-400 mt-1">
            Suivi individuel de chaque pièce physique par numéro de série (état, localisation, historique).
        </p>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-lg bg-green-950 border border-green-800 text-green-300 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-lg bg-red-950 border border-red-800 text-red-300 text-sm">{{ session('error') }}</div>
    @endif

    <!-- Formulaire d'ajout d'une nouvelle unité -->
    <div class="bg-darkcard border border-darkborder rounded-xl p-6 shadow-xl">
        <h2 class="font-semibold text-white mb-4">+ Enregistrer une nouvelle unité</h2>
        <form method="POST" action="{{ route('unites.store', $composant->id_composant) }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            @csrf
            <input type="text" name="numero_serie" placeholder="Numéro de série" required
                   class="bg-darkbg text-gray-200 border border-darkborder rounded px-3 py-2 text-sm focus:outline-none focus:border-limeacc">
            <select name="etat" class="bg-darkbg text-gray-200 border border-darkborder rounded px-3 py-2 text-sm focus:outline-none focus:border-limeacc">
                <option value="Neuf">Neuf</option>
                <option value="Opérationnel">Opérationnel</option>
            </select>
            <input type="date" name="date_achat"
                   class="bg-darkbg text-gray-200 border border-darkborder rounded px-3 py-2 text-sm focus:outline-none focus:border-limeacc">
            <button type="submit" class="px-4 py-2 bg-limeacc text-black font-semibold rounded-lg text-sm hover:opacity-90 transition">
                Ajouter au stock
            </button>
        </form>
        @error('numero_serie')
            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Liste des unités -->
    <div class="bg-darkcard border border-darkborder rounded-xl overflow-hidden shadow-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-darkborder text-xs text-gray-400 uppercase tracking-wider bg-darkbg/50">
                    <th class="px-6 py-4">N° Série</th>
                    <th class="px-6 py-4">État</th>
                    <th class="px-6 py-4">Date d'achat</th>
                    <th class="px-6 py-4">Localisation</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-darkborder text-sm">
                @forelse($unites as $unite)
                    <tr class="hover:bg-darkborder/30 transition">
                        <td class="px-6 py-4 text-white font-medium">{{ $unite->numero_serie }}</td>
                        <td class="px-6 py-4">
                            @php
                                $couleurEtat = match($unite->etat) {
                                    'Neuf' => 'bg-blue-950 text-blue-300 border-blue-800',
                                    'Opérationnel' => 'bg-green-950 text-green-300 border-green-800',
                                    'Usé' => 'bg-yellow-950 text-yellow-300 border-yellow-800',
                                    'Défectueux' => 'bg-red-950 text-red-300 border-red-800',
                                    default => 'bg-darkborder text-gray-300 border-darkborder',
                                };
                            @endphp
                            <span class="px-2.5 py-1 text-xs rounded-full border {{ $couleurEtat }}">{{ $unite->etat }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-400">{{ $unite->date_achat ? \Carbon\Carbon::parse($unite->date_achat)->format('d/m/Y') : '—' }}</td>
                        <td class="px-6 py-4 text-gray-400">
                            @if($unite->materielActuel)
                                📍 {{ $unite->materielActuel->nom_equipement }}
                            @else
                                <span class="text-gray-500">En stock</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($unite->estEnStock())
                                <form method="POST" action="{{ route('unites.affecter', $unite->id) }}" class="inline-flex gap-2 justify-end items-center">
                                    @csrf
                                    <select name="id_materiel" required class="bg-darkbg text-gray-200 border border-darkborder rounded px-2 py-1 text-xs focus:outline-none focus:border-limeacc">
                                        <option value="">Choisir une machine...</option>
                                        @foreach($materiels as $m)
                                            <option value="{{ $m->id }}">{{ $m->nom_equipement }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="px-3 py-1 bg-limeacc text-black font-semibold text-xs rounded hover:opacity-90 transition">
                                        Affecter
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('unites.retirer', $unite->id) }}" class="inline-flex gap-2 justify-end items-center"
                                      onsubmit="return confirm('Retirer cette unité de la machine ?');">
                                    @csrf
                                    <select name="nouvel_etat" required class="bg-darkbg text-gray-200 border border-darkborder rounded px-2 py-1 text-xs focus:outline-none focus:border-limeacc">
                                        <option value="Usé">Retirer — état Usé</option>
                                        <option value="Défectueux">Retirer — état Défectueux</option>
                                    </select>
                                    <button type="submit" class="px-3 py-1 bg-orange-900/60 border border-orange-700 text-orange-200 font-semibold text-xs rounded hover:bg-orange-900 transition">
                                        Retirer
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">
                            Aucune unité tracée pour ce composant. Ajoute-en une ci-dessus.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection