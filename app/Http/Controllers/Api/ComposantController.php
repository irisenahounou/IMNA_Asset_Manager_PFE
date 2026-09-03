<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Composant;
use App\Models\MouvementStock;
use App\Services\AuditService;
use Illuminate\Http\Request;

/**
 * RM-06 : "Aucune pièce ne peut sortir du stock sans être rattachée à une
 * demande de composant explicitement 'Accordée' par le Responsable."
 * -> Le mobile ne fait QUE la demande (US-04). La validation reste une
 * action exclusivement Web/DSI (déjà gérée par ComposantController::validerMouvement).
 */
class ComposantController extends Controller
{
    public function index()
    {
        return response()->json(Composant::orderBy('nom_composant')->get());
    }

    public function demander(Request $request)
    {
        $validated = $request->validate([
            'id_composant' => 'required|exists:composants,id_composant',
            'quantite' => 'required|integer|min:1',
            'type_mouvement' => 'required|in:Entrée,Sortie',
        ]);

        $mouvement = MouvementStock::create([
            'id_composant' => $validated['id_composant'],
            'quantite' => $validated['quantite'],
            'type_mouvement' => $validated['type_mouvement'],
            'statut_validation' => 'En attente',
            'id_technicien' => $request->user()->id_utilisateur,
        ]);

        AuditService::enregistrer(
            'Demande Mouvement Stock (Mobile)',
            "Demande de {$mouvement->type_mouvement} de {$mouvement->quantite} unité(s) pour le composant #{$mouvement->id_composant}",
            $request->user()
        );

        return response()->json($mouvement, 201);
    }
}