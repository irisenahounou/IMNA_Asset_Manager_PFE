@extends('layouts.app')

@section('title', 'Journal d\'Audit Global')
@section('role-badge', 'Responsable DSI')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-wide">📋 Journal d'Audit Global</h1>
            <p class="text-sm text-gray-400 mt-1">Traçabilité complète des actions sensibles et des adresses IP (Réservé à la Direction).</p>
        </div>
    </div>

    <!-- Tableau des logs -->
    <div class="bg-darkcard border border-darkborder rounded-xl overflow-hidden shadow-xl">
        <div class="px-6 py-4 border-b border-darkborder font-semibold text-white">
            Historique des actions système
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-darkborder text-xs text-gray-400 uppercase tracking-wider bg-darkbg/50">
                        <th class="px-6 py-4">Date / Heure</th>
                        <th class="px-6 py-4">Utilisateur</th>
                        <th class="px-6 py-4">Action</th>
                        <th class="px-6 py-4">Adresse IP</th>
                        <th class="px-6 py-4">Détails</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-darkborder text-sm">
                    @forelse($audits as $audit)
                        <tr class="hover:bg-darkborder/30 transition">
                            <td class="px-6 py-4 text-gray-400 whitespace-nowrap">{{ $audit->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="px-6 py-4 font-semibold text-white">
                                {{ $audit->utilisateur ? $audit->utilisateur->prenom . ' ' . $audit->utilisateur->nom : 'Système / Anonyme' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs rounded-full bg-darkborder text-limeacc border border-darkborder">
                                    {{ $audit->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-gray-400">{{ $audit->ip_address }}</td>
                            <td class="px-6 py-4 text-gray-300 text-xs">{{ $audit->details }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                Aucun journal d'audit enregistré pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 border-t border-darkborder">
            {{ $audits->links() }}
        </div>
    </div>
</div>
@endsection