@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 text-gray-200">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Tableau de Bord - Analyse Prédictive d'Usure</h1>
        <form action="{{ route('analyser.usure.trigger') }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg shadow font-semibold transition">
                Relancer l'analyse Python
            </button>
        </form>
    </div>

    <!-- Section 1 : Graphique en Araignée (Radar) -->
    <div class="bg-gray-800 border border-gray-700 p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-lg font-semibold text-white mb-4">Profil Multicritère des Équipements (Radar)</h2>
        <div class="relative w-full max-w-xl mx-auto h-80">
            <canvas id="radarChart"></canvas>
        </div>
    </div>

    <!-- Section 2 : Tableau de Synthèse -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-900 border-b border-gray-700">
            <h2 class="text-lg font-semibold text-white">Détail du Parc Matériel</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">ID Matériel</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Nom de l'équipement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date d'achat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Score d'Usure</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Statut Recommandé</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($materiels as $materiel)
                        <tr class="hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $materiel->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $materiel->nom_equipement }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $materiel->date_achat ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $materiel->score_usure >= 75 ? 'bg-red-900/50 text-red-300 border border-red-700' : ($materiel->score_usure >= 50 ? 'bg-yellow-900/50 text-yellow-300 border border-yellow-700' : 'bg-green-900/50 text-green-300 border border-green-700') }}">
                                    {{ $materiel->score_usure }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-200">{{ $materiel->statut_usure }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-400">Aucun matériel trouvé dans la base de données.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Inclusion de Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('radarChart').getContext('2d');
        const materielsData = @json($materiels);

        const labels = ['Score Usure (%)', 'Indice Santé (100 - Usure)', 'Facteur Risque Pannes', 'Niveau Criticité', 'Indice Vieillissement'];
        
        const datasets = materielsData.slice(0, 5).map((mat, index) => {
            const colors = [
                { bg: 'rgba(54, 162, 235, 0.3)', border: 'rgb(54, 162, 235)' },
                { bg: 'rgba(255, 99, 132, 0.3)', border: 'rgb(255, 99, 132)' },
                { bg: 'rgba(75, 192, 192, 0.3)', border: 'rgb(75, 192, 192)' },
                { bg: 'rgba(255, 206, 86, 0.3)', border: 'rgb(255, 206, 86)' },
                { bg: 'rgba(153, 102, 255, 0.3)', border: 'rgb(153, 102, 255)' }
            ];
            const color = colors[index % colors.length];
            let score = mat.score_usure || 0;

            return {
                label: mat.nom_equipement,
                data: [score, Math.max(0, 100 - score), score * 0.8, score >= 75 ? 90 : (score >= 50 ? 50 : 20), score * 0.9],
                fill: true,
                backgroundColor: color.bg,
                borderColor: color.border,
                pointBackgroundColor: color.border,
                pointBorderColor: '#1f2937',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: color.border
            };
        });

        new Chart(ctx, {
            type: 'radar',
            data: { labels: labels, datasets: datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                elements: { line: { borderWidth: 2 } },
                scales: {
                    r: {
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        angleLines: { color: 'rgba(255, 255, 255, 0.1)' },
                        pointLabels: { color: '#9ca3af', font: { size: 11 } },
                        ticks: { color: '#9ca3af', backdropColor: 'transparent' },
                        suggestedMin: 0,
                        suggestedMax: 100
                    }
                },
                plugins: {
                    legend: { labels: { color: '#d1d5db' } }
                }
            }
        });
    });
</script>
@endsection