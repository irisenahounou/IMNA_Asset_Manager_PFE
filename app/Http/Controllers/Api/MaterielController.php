<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Materiel;

/**
 * Consultation terrain du parc (US du module Assets) :
 * "Possibilité pour le technicien de consulter la fiche technique
 * d'une machine [...] directement depuis son mobile."
 */
class MaterielController extends Controller
{
    public function index()
    {
        return response()->json(
            Materiel::orderBy('nom_equipement')->get()
        );
    }

    public function show($id)
    {
        $materiel = Materiel::with(['pannes' => function ($query) {
            $query->orderBy('date_declaration', 'desc');
        }])->findOrFail($id);

        return response()->json($materiel);
    }
}