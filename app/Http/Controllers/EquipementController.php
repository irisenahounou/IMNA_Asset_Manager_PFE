<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipement;

class EquipementController extends Controller
{
    public function index()
    {
        $equipements = Equipement::orderBy('created_at', 'desc')->get();
        return view('equipements.index', compact('equipements'));

    }
    public function create()
    {
        return view('equipements.create');
    }
    public function store(Request $request)
    {
        $request->validate ([
            'nom_equipement' => 'required|string|max:255',
            'type_equipement' => 'required|string|max:255',
            'localisation'    => 'nullable|string|max:255',
            'statut' => 'Opérationnel',
        ]);
        Equipement::create ([
            'nom_equipement'  => $request->nom_equipement,
            'type_equipement' => $request->type_equipement,
            'localisation'    => $request->localisation,
            'statut'          => 'Opérationnel',
        ]);
        return redirect()->route('equipements.index')->with('success', 'Équipement ajouté au parc avec succès !');
    }
}
