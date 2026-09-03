<?php

namespace App\Http\Controllers;

use App\Models\AffectationComposant;
use App\Models\Composant;
use App\Models\Materiel;
use App\Models\UniteComposant;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * RM-07 : "Tout composant unitaire retiré d'une machine doit obligatoirement
 * voir son état mis à jour (Usé ou Défectueux) et sa date de retrait
 * consignée dans l'historique d'affectation."
 *
 * Module de Traçabilité Unitaire (§2, Module de Gestion des Stocks) :
 * suivi individualisé de chaque pièce physique par numéro de série.
 */
class UniteComposantController extends Controller
{
    // Liste des unités d'un type de composant donné (ex: toutes les
    // barrettes de RAM DDR4 16Go individuellement, avec leur état et
    // leur localisation actuelle).
    public function index($idComposant)
    {
        $composant = Composant::findOrFail($idComposant);
        $unites = UniteComposant::where('id_composant', $idComposant)
            ->with('materielActuel')
            ->get();
        $materiels = Materiel::orderBy('nom_equipement')->get();

        return view('unites.index', compact('composant', 'unites', 'materiels'));
    }

    // Enregistre une nouvelle unité physique (nouvelle pièce achetée).
    public function store(Request $request, $idComposant)
    {
        $composant = Composant::findOrFail($idComposant);

        $validated = $request->validate([
            'numero_serie' => 'required|string|max:255|unique:unites_composant,numero_serie',
            'etat' => 'required|in:Neuf,Opérationnel,Usé,Défectueux',
            'date_achat' => 'nullable|date',
        ]);

        $dernierId = UniteComposant::count() + 1;
        $unite = UniteComposant::create([
            'id' => 'UNT-' . now()->format('Y') . '-' . str_pad((string) $dernierId, 4, '0', STR_PAD_LEFT),
            'id_composant' => $composant->id_composant,
            'numero_serie' => $validated['numero_serie'],
            'etat' => $validated['etat'],
            'date_achat' => $validated['date_achat'] ?? null,
            'id_materiel_actuel' => null, // arrive en stock, pas encore installée
        ]);

        AuditService::enregistrer(
            'Création Unité Composant',
            "Nouvelle unité '{$unite->numero_serie}' enregistrée pour le composant '{$composant->nom_composant}'"
        );

        return redirect()->route('unites.index', $composant->id_composant)
            ->with('success', "Unité {$unite->numero_serie} enregistrée en stock.");
    }

    // Affecte une unité en stock à une machine (installation).
    public function affecter(Request $request, $id)
    {
        $unite = UniteComposant::findOrFail($id);

        $validated = $request->validate([
            'id_materiel' => 'required|exists:Materiel,id',
        ]);

        if (!$unite->estEnStock()) {
            return redirect()->back()->with('error', 'Cette unité est déjà installée sur une machine. Retire-la d\'abord.');
        }

        $unite->id_materiel_actuel = $validated['id_materiel'];
        $unite->save();

        AffectationComposant::create([
            'id_unite' => $unite->id,
            'id_materiel' => $validated['id_materiel'],
            'date_installation' => now(),
            'id_technicien' => Auth::id(),
        ]);

        $materiel = Materiel::find($validated['id_materiel']);
        AuditService::enregistrer(
            'Affectation Unité Composant',
            "Unité '{$unite->numero_serie}' installée sur le matériel '{$materiel->nom_equipement}'"
        );

        return redirect()->back()->with('success', "Unité {$unite->numero_serie} affectée avec succès.");
    }

    // Retire une unité de sa machine actuelle (RM-07 : état + date obligatoires).
    public function retirer(Request $request, $id)
    {
        $unite = UniteComposant::findOrFail($id);

        $validated = $request->validate([
            'nouvel_etat' => 'required|in:Usé,Défectueux',
        ]);

        if ($unite->estEnStock()) {
            return redirect()->back()->with('error', 'Cette unité n\'est installée sur aucune machine.');
        }

        $ancienMateriel = $unite->materielActuel;

        // Clôture l'affectation active (celle sans date_retrait) avec l'état
        // constaté au moment du retrait, conformément à RM-07.
        AffectationComposant::where('id_unite', $unite->id)
            ->where('id_materiel', $unite->id_materiel_actuel)
            ->whereNull('date_retrait')
            ->latest('date_installation')
            ->first()
            ?->update([
                'date_retrait' => now(),
                'etat_au_retrait' => $validated['nouvel_etat'],
            ]);

        $unite->etat = $validated['nouvel_etat'];
        $unite->id_materiel_actuel = null;
        $unite->save();

        AuditService::enregistrer(
            'Retrait Unité Composant',
            "Unité '{$unite->numero_serie}' retirée du matériel '{$ancienMateriel->nom_equipement}' (nouvel état : {$validated['nouvel_etat']})"
        );

        return redirect()->back()->with('success', "Unité {$unite->numero_serie} retirée et marquée '{$validated['nouvel_etat']}'.");
    }
}