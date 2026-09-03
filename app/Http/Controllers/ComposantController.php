<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Composant;
use App\Models\MouvementStock;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditService;

class ComposantController extends Controller
{
    // Afficher la liste des composants et les alertes de stock
    public function index()
    {
        $composants = Composant::all();
        // Récupérer les composants dont le stock est inférieur ou égal au seuil d'alerte
        $alertesStock = Composant::whereColumn('quantite_stock', '<=', 'seuil_alerte')->get();

        return view('composants.index', compact('composants', 'alertesStock'));
    }
    // Formulaire d'ajout d'un composant
    public function create()
    {
        return view('composants.create');
    }
    // Enregistrer un nouveau composant
    public function store(Request $request)
    {
        $request->validate ([
            'nom_composant' => 'required|string|max:255',
            'type_composant' => 'required|string|max:255',
            'quantite_stock' => 'required|integer|min:0',
            'seuil_alerte' => 'required|integer|min:0',
        ]);

        $composant = Composant::create($request->all());
        // TRAÇABILITÉ AUDIT : Enregistrement de l'ajout d'un composant
        AuditService::enregistrer (
            'Création Composant',
            "Ajout du composant '{$composant->nom_composant}' (Stock initial: {$composant->quantite_stock})"
        );
       return redirect()->route('composants.index')->with('success', 'Composant ajouté avec succès.');
    }
    // Enregistrer une demande de mouvement (Sortie/Entrée) par un technicien
    public function stockMouvement(Request $request)
    {
        $request->validate ([
            'id_composant' => 'required|exists:composants,id_composant',
            'quantite' => 'required|integer|min:1',
            'type_mouvement' => 'required|in:Entrée,Sortie',
        ]);
        MouvementStock::create ([
             'id_composant' => $request->id_composant,
             'quantite' => $request->quantite,
             'type_mouvement' => $request->type_mouvement,
             'statut_validation' => 'En attente',
             'id_technicien' => Auth::id(), // Récupère l'utilisateur connecté

        ]);
        // TRAÇABILITÉ AUDIT : Demande de mouvement de stock
        AuditService::enregistrer (
            'Demande Mouvement Stock',
            "Demande de {$request->type_mouvement} de {$request->quantite} unité(s) pour le composant ID {$request->id_composant}"
        );
        return redirect()->back()->with('success', 'Demande de mouvement de stock enregistrée, en attente de validation par le DSI.');
         
    }
    // Validation ou rejet par le DSI
    public function validerMouvement(Request $request, $id)
    {
        $mouvement = MouvementStock::findOrFail($id);
        $request->validate ([
            'statut_validation' => 'required|in:Validé,Rejeté',
        ]);
        // On sauvegarde l'ancien statut avant de le modifier
        $ancienStatut = $mouvement->statut_validation;
        
        $composant = Composant::findOrFail($mouvement->id_composant);
        if ($request->statut_validation === 'Validé' && $mouvement->statut_validation !== 'Validé') {
            if ($mouvement->type_mouvement === 'Sortie') {
                if ($composant->quantite_stock < $mouvement->quantite) {
                    return redirect()->back()->with('error', 'Stock insuffisant pour valider cette sortie.');
                }
                // On retient si le stock était encore AU-DESSUS du seuil avant
        // cette sortie, pour ne notifier qu'au moment du franchissement
        // (US-06) et non à chaque validation suivante une fois déjà bas.
                $etaitAuDessusDuSeuil = $composant->quantite_stock > $composant->seuil_alerte;
               $composant->quantite_stock -= $mouvement->quantite;
               $composant->save();
               if ($etaitAuDessusDuSeuil && $composant->quantite_stock <= $composant->seuil_alerte) {
            $responsables = \App\Models\Utilisateur::whereHas('responsable')->get();
            \Illuminate\Support\Facades\Notification::send(
                $responsables,
                new \App\Notifications\SeuilStockAtteint($composant)
            );
        }
            } else {
                $composant->quantite_stock += $mouvement->quantite;
                $composant->save();
            }
            
        }
        $mouvement->statut_validation = $request->statut_validation;
        $mouvement->save();

        // TRAÇABILITÉ AUDIT : Action critique de validation/rejet par la Direction/DSI
        AuditService::enregistrer(
            'Validation Stock DSI',
            "Le DSI a mis à jour le mouvement #{$mouvement->id_mouvement} ({$mouvement->type_mouvement} de {$mouvement->quantite}): de '{$ancienStatut}' à '{$request->statut_validation}'"
        );

        return redirect()->back()->with('success', 'Le statut du mouvement a été mis à jour.');
    }
}
