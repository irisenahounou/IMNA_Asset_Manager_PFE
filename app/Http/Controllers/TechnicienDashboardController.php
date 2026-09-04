<?php

namespace App\Http\Controllers;

use App\Models\Panne;
use App\Models\Reparation;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Équivalent Web du contrôleur API mobile (App\Http\Controllers\Api\PanneController)
 * pour le Technicien. Même logique métier, adaptée à l'authentification par
 * session (Web) plutôt que par token (Mobile).
 */
class TechnicienDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Tickets ouverts (à prendre en charge) + ceux déjà pris en charge par ce technicien
        $tickets = Panne::with(['materiel', 'declarant', 'reparations'])
            ->where(function ($q) use ($user) {
                $q->where('statut', 'Ouvert')
                    ->orWhereHas('reparations', function ($r) use ($user) {
                        $r->where('id_technicien', $user->id_utilisateur);
                    });
            })
            ->orderBy('date_declaration', 'desc')
            ->get();

        // Ticket actuellement sélectionné (affiché dans le panneau de droite)
        $ticketSelectionneId = $request->query('ticket');
        $ticketSelectionne = $ticketSelectionneId
            ? Panne::with(['materiel', 'declarant', 'reparations'])->find($ticketSelectionneId)
            : $tickets->first();

        return view('dashboards.technicien', compact('tickets', 'ticketSelectionne'));
    }

    public function prendreEnCharge(Request $request, $id)
    {
        $user = $request->user();
        $panne = Panne::findOrFail($id);

        if ($panne->statut !== 'Ouvert') {
            return redirect()->back()->with('error', 'Ce ticket est déjà pris en charge.');
        }

        Reparation::create([
            'id' => 'REP-' . now()->format('Y') . '-' . str_pad((string) (Reparation::count() + 1), 3, '0', STR_PAD_LEFT),
            'date_debut' => now(),
            'id_panne' => $panne->id,
            'id_technicien' => $user->id_utilisateur,
        ]);

        $panne->statut = 'En cours';
        $panne->save();

        AuditService::enregistrer(
            'Prise en charge Panne (Web)',
            "Le technicien {$user->prenom} {$user->nom} a pris en charge la panne #{$panne->id}"
        );

        return redirect()->route('technicien.dashboard', ['ticket' => $panne->id])->with('success', 'Ticket pris en charge.');
    }

    public function cloturer(Request $request, $id)
    {
        $request->validate(['rapport_technique' => 'required|string']);

        $user = $request->user();
        $panne = Panne::findOrFail($id);

        $reparation = Reparation::where('id_panne', $panne->id)
            ->where('id_technicien', $user->id_utilisateur)
            ->latest('date_debut')
            ->first();

        if (!$reparation) {
            return redirect()->back()->with('error', 'Aucune prise en charge trouvée pour ce technicien sur ce ticket.');
        }

        $reparation->date_fin = now();
        $reparation->rapport_technique = $request->input('rapport_technique');
        $reparation->save();

        $panne->statut = 'Résolu';
        $panne->save();

        AuditService::enregistrer(
            'Clôture Ticket (Web)',
            "Le technicien {$user->prenom} {$user->nom} a clôturé la panne #{$panne->id}"
        );

        return redirect()->route('technicien.dashboard')->with('success', 'Ticket clôturé avec succès.');
    }
}