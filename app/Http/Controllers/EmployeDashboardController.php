<?php

namespace App\Http\Controllers;

use App\Models\Materiel;
use App\Models\Panne;
use App\Services\AuditService;
use Illuminate\Http\Request;

/**
 * Équivalent Web du contrôleur API mobile (App\Http\Controllers\Api\PanneController)
 * pour l'Employé. Couvre US-01 (déclarer un incident) et US-02 (suivre ses tickets).
 */
class EmployeDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $materiels = Materiel::orderBy('nom_equipement')->get();

        // US-02 : suivi de ses propres tickets déclarés
        $mesPannes = Panne::where('id_employe', $user->id_utilisateur)
            ->orderBy('date_declaration', 'desc')
            ->get();

        return view('dashboards.employe', compact('materiels', 'mesPannes'));
    }

    public function declarer(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'id_materiel' => 'required|string|exists:Materiel,id',
            'photo' => 'nullable|image|max:5120',
        ]);

        $validated['id_employe'] = $request->user()->id_utilisateur;
        $validated['statut'] = 'Ouvert';
        $validated['date_declaration'] = now();

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('pannes', 'public');
        }

        $panne = Panne::create($validated);

        AuditService::enregistrer(
            'Déclaration Panne (Web)',
            "Panne '{$panne->titre}' déclarée sur le matériel {$panne->id_materiel}"
        );

        return redirect()->route('employe.dashboard')->with('success', 'Incident déclaré avec succès.');
    }
}