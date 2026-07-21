<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reparation;
use App\Models\Panne;
use App\Models\Technicien;
use App\Models\Corrective;

class ReparationController extends Controller
{
    public function index()
    {
        $reparations = Reparation::with(['panne', 'technicien'])->get();

        return view('reparations.index', compact('reparations'));
    }
    public function create($id_panne)
    {
        $panne = Panne::findOrFail($id_panne);
        $techniciens = Technicien::all();

        return view('reparations.create', compact('panne', 'techniciens'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|string|unique:Reparation,id',
            'date_debut' => 'required|date',
            'rapport_technique' => 'nullable|string',
            'id_panne' => 'required|exists:Panne,id',
            'id_technicien' => 'required|exists:Technicien,id_technicien',
        ]);
        // 1. Création de la réparation


        $reparation = Reparation::create([
            'id' => $request->id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'rapport_technique' => $request->rapport_technique,
            'id_panne' => $request->id_panne,
            'id_technicien' => $request->id_technicien,
        ]);
        // 2. Si c'est une panne corrective, on l'ajoute dans la table corrective


        Corrective::create([
            'id_corrective' => $request->id,
            'id_panne' => $request->id_panne,

        ]);

        // 3. Optionnel : Mettre à jour le statut de la panne à "En cours" ou "Traité"

        $panne = Panne::findOrFail($request->id_panne);
        $panne->statut = 'En cours';
        $panne->save();

        return redirect()->route('pannes.index')->with('success', 'Réparation planifiée avec succès.');
    }
}
