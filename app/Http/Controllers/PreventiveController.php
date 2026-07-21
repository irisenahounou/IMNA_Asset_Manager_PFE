<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Preventive;
use App\Models\Materiel;

class PreventiveController extends Controller
{
    public function index()
    {
        $preventives = Preventive::with('panne')->get();
        return view('preventives.index', compact('preventives'));
    }

    public function create()
    {
        $materiels = Materiel::all(); // Ou filtrer selon les équipements ciblés par le script Python
        return view('preventives.create', compact('materiels'));
    }

   // Enregistrement de la planification préventive
   public function store(Request $request)
   {
    $request->validate ([
        'id_preventive' => 'required|string|unique:Preventive,id_preventive',
        'id_materiel' => 'required|exists:materiel,id',
        'frequence_jour' => 'required|integer',
        'prochaine_rep' => 'required|date',
    ]);
    Preventive::create ([
        'id_preventive' => $request->id_preventive,
        'id_materiel' => $request->id_materiel,
        'frequence_jour' => $request->frequence_jour,
        'prochaine_rep' => $request->prochaine_rep,

    ]);
    return redirect()->route('preventives.index')->with('success', 'Maintenance préventive planifiée avec succès.');
    }
    
}
