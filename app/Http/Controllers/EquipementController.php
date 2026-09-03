<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materiel;

class EquipementController extends Controller
{
    
    public function index()
    {
        $equipements = Materiel::orderBy('date_achat', 'desc')->get();
        return view('equipements.index', compact('equipements'));
    }
    public function create()
    {
        return view('equipements.create');
    }
    public function store(Request $request)
    {
        $request->validate ([
            'id'             => 'required|string|max:50|unique:Materiel,id',
            'numero_serie'   => 'required|string|max:100|unique:Materiel,numero_serie',
            'nom_equipement' => 'required|string|max:50',
            'type'           => 'required|string|max:50',
            'date_achat'     => 'required|date',
            'localisation'   => 'nullable|string|max:100',
            'id_service'     => 'required|integer',
            'id_responsable' => 'required|integer',
        ]);
       Materiel::create([
            'id'                => $request->id,
            'numero_serie'      => $request->numero_serie,
            'nom_equipement'    => $request->nom_equipement,
            'type'              => $request->type,
            'date_achat'        => $request->date_achat,
            'etat_operationnel' => 'fonctionnel', // Valeur par défaut logique
            'localisation'      => $request->localisation,
            'id_service'        => $request->id_service,
            'id_responsable'    => $request->id_responsable,
        ]);
        return redirect()->route('equipements.index')->with('success', 'Équipement ajouté au parc avec succès !');
    }
    public function edit($id)
    {
        $equipement = Materiel::findOrFail($id);
        return view('equipements.edit', compact('equipement'));
    }
    public function update(Request $request, $id)
    {
        $equipement = Materiel::findOrFail($id);
        $request->validate ([
            'numero_serie'   => 'required|string|max:100|unique:Materiel,numero_serie,' . $id . ',id',
            'nom_equipement' => 'required|string|max:50',
            'type'           => 'required|string|max:50',
            'date_achat'     => 'required|date',
            'localisation'   => 'nullable|string|max:100',
            'id_service'     => 'required|integer',
            'id_responsable' => 'required|integer',
        ]);
        $equipement->update([
            'numero_serie'   => $request->numero_serie,
            'nom_equipement' => $request->nom_equipement,
            'type'           => $request->type,
            'date_achat'     => $request->date_achat,
            'localisation'   => $request->localisation,
            'id_service'     => $request->id_service,
            'id_responsable' => $request->id_responsable,
        ]);
        return redirect()->route('equipements.index')->with('success', "L'équipement a été mis à jour avec succès !");
    }
    public function destroy($id)
    {
        $equipement = Materiel::findOrFail($id);
        $equipement->delete();
        return redirect()->route('equipements.index')->with('success', "L'équipement a été retiré du parc avec succès !");
    
    }
    public function usureIndex() {
        $materiels = Materiel::all();
        return view('dashboards.usure', compact('materiels'));
    }
}
