@extends('layouts.app')

@section('title', 'Maintenance Préventive')
@section('role-badge', 'Espace DSI')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Supervision - Maintenance Préventive</h1>
            <p class="text-gray-400 text-sm mt-1">Anticipation de l'usure et planification des interventions préventives (Analyse Python).</p>
        </div>
        <div>
            <a href="{{ route('preventives.create') }}" class="px-4 py-2.5 bg-limeacc hover:bg-lime-400 text-black font-semibold rounded-lg text-sm transition flex items-center space-x-2">
                <span>➕ Planifier une préventive</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-lime-500/10 border border-limeacc/20 text-limeacc px-4 py-3 rounded-lg text-sm flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-darkcard border border-darkborder rounded-xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-darkbg/50 border-b border-darkborder text-gray-400 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4">ID Préventif</th>
                        <th class="px-6 py-4">Matériel Cible</th>
                        <th class="px-6 py-4">Fréquence (Jours)</th>
                        <th class="px-6 py-4">Prochaine Intervention</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-darkborder text-sm">
                    @forelse($preventives as $prev)
                        <tr class="hover:bg-darkbg/40 transition">
                            <td class="px-6 py-4 font-mono text-limeacc font-medium">
                                #{{ $prev->id_preventive }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-white font-medium">
                                    {{ $prev->materiel->id ??   $prev->id_materiel }}
                                </div>
                                <div class="text-xs text-gray-400 font-mono mt-0.5">
                                    {{ $prev->materiel->type ?? 'Analyse prédictive système' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                <span class="px-2.5 py-1 bg-darkbg border border-darkborder rounded-md text-xs font-mono text-gray-300">
                                    Tous les {{ $prev->frequence_jour }} jours
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-300 whitespace-nowrap">
                                <span class="flex items-center space-x-1.5">
                                    <span class="w-2 h-2 rounded-full bg-limeacc"></span>
                                    <span>{{ date('d/m/Y', strtotime($prev->prochaine_rep)) }}</span>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="#" class="text-gray-400 hover:text-white text-xs bg-darkbg hover:bg-darkborder px-3 py-1.5 rounded transition">
                                    Détails
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <span class="text-2xl">🛡️</span>
                                    <p class="text-sm">Aucune maintenance préventive planifiée pour le moment.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection