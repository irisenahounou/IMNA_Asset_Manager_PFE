<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Panne;
use App\Models\Reparation;
use App\Services\AuditService;
use Illuminate\Http\Request;

/**
 * Implémente le cycle des tickets décrit au §2(3) du cahier des charges :
 * 1. L'Employé déclare (Ouvert)
 * 2. Le Technicien prend en charge (En cours) -> crée une Reparation
 * 3. Le Technicien clôture (Résolu) -> renseigne le rapport technique
 */
class PanneController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Panne::with(['materiel', 'declarant', 'reparations']);

        if ($user->estTechnicien()) {
            // Le technicien voit les tickets ouverts à prendre en charge,
            // ainsi que ceux qu'il a lui-même pris en charge.
            $query->where(function ($q) use ($user) {
                $q->where('statut', 'Ouvert')
                    ->orWhereHas('reparations', function ($r) use ($user) {
                        $r->where('id_technicien', $user->id_utilisateur);
                    });
            });
        } else {
            // L'employé ne voit que ses propres déclarations (US-02)
            $query->where('id_employe', $user->id_utilisateur);
        }

        return response()->json(
            $query->orderBy('date_declaration', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'id_materiel' => 'required|string|exists:Materiel,id',
            'photo' => 'nullable|image|max:5120', // 5 Mo max
        ]);

        // Sécurité : on ignore toute valeur "id_employe" envoyée par le client,
        // c'est toujours l'utilisateur authentifié par le token qui déclare.
        $validated['id_employe'] = $request->user()->id_utilisateur;
        $validated['statut'] = 'Ouvert';
        $validated['date_declaration'] = now();

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('pannes', 'public');
        }

        $panne = Panne::create($validated);

        AuditService::enregistrer(
            'Déclaration Panne (Mobile)',
            "Panne '{$panne->titre}' déclarée sur le matériel {$panne->id_materiel}",
            $request->user()
        );

        return response()->json($panne->load('materiel'), 201);
    }

    public function show($id)
    {
        $panne = Panne::with(['materiel', 'declarant', 'reparations'])->findOrFail($id);

        return response()->json($panne);
    }

    public function prendreEnCharge(Request $request, $id)
    {
        $user = $request->user();
        $panne = Panne::findOrFail($id);

        if ($panne->statut !== 'Ouvert') {
            return response()->json(['message' => 'Ce ticket est déjà pris en charge.'], 409);
        }

        $reparation = Reparation::create([
            'id' => 'REP-' . now()->format('Y') . '-' . str_pad((string) (Reparation::count() + 1), 3, '0', STR_PAD_LEFT),
            'date_debut' => now(),
            'id_panne' => $panne->id,
            'id_technicien' => $user->id_utilisateur,
        ]);

        $panne->statut = 'En cours';
        $panne->save();

        AuditService::enregistrer(
            'Prise en charge Panne (Mobile)',
            "Le technicien {$user->prenom} {$user->nom} a pris en charge la panne #{$panne->id}",
            $user
        );

        return response()->json($panne->load('reparations'));
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
            return response()->json(['message' => 'Aucune prise en charge trouvée pour ce technicien sur ce ticket.'], 404);
        }

        $reparation->date_fin = now();
        $reparation->rapport_technique = $request->input('rapport_technique');
        $reparation->save();

        $panne->statut = 'Résolu';
        $panne->save();

        AuditService::enregistrer(
            'Clôture Ticket (Mobile)',
            "Le technicien {$user->prenom} {$user->nom} a clôturé la panne #{$panne->id}",
            $user
        );

        return response()->json($panne->load('reparations'));
    }
}