<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materiel;
use App\Models\Panne;
use App\Models\Preventive;

class DashboardController extends Controller
{
   public function index()
   {
    // Récupération des statistiques pour le DSI

    $totalMateriels = Materiel::count();
    $pannesEnAttente = Panne::where('statut', 'en attente')->count();
    $preventivesCount = Preventive::count();

    // Dernières pannes signalées
    $dernieresPannes = Panne::latest()->take(5)->get();

    // Prochaines maintenances préventives
    $prochainesPreventives = Preventive::orderBy('prochaine_rep', 'asc')->take(5)->get();

    return view('dashboard', compact(
        'totalMateriels',
        'pannesEnAttente',
        'preventivesCount',
        'dernieresPannes',
        'prochainesPreventives'
    ));
   }
}
