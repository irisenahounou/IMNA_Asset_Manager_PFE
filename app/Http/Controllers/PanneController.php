<?php

namespace App\Http\Controllers;
use App\Models\Panne;
use App\Models\Materiel;
use Illuminate\Http\Request;

class PanneController extends Controller
{
    public function index(Request $request)
    {
        $query = Panne::with(['materiel', 'employe']);
        if ($request->has('statut') && !empty($request->statut))
            {
                $query->where('statut', $request->statut);
            }
            $pannes = $query->orderBy('date_declaration', 'desc')->get();
            return view('pannes.index', compact('pannes'));
    }
    public function create()
    {
        $materiels = Materiel::all();
        return view('pannes.create', compact('materiels'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate ([
            'titre'       => 'required|string|max:255',
            'description' => 'required|string',
            'id_materiel' => 'required|string|exists:Materiel,id',
            'id_employe'  => 'required|integer|exists:Employe,id_employe',
        ]);
        $validated['statut'] = 'Ouvert';
        Panne::create($validated);
        return redirect()->route('pannes.index')
        ->with('success', 'La panne a été déclarée avec succès.');
    }
    public function show($id)
    {
        $panne = Panne::with(['materiel', 'employe', 'reparations'])->findOrFail($id);
        return view('pannes.show', compact('panne'));
    }
    public function updateStatut(Request $request, $id)
    {
        $request->validate ([
            'statut' => 'required|in:Ouvert,En cours,Résolu',
        ]);
        $panne = Panne::findOrFail($id);
        $panne->statut = $request->statut;
        $panne->save();
        return redirect()->back()
        ->with('success', 'Le statut de la panne a été mis à jour.');
    }

}
