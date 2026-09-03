<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit;

class AuditController extends Controller
{
    public function index() {
        // Récupérer les audits du plus récent au plus ancien avec l'utilisateur associé
        $audits = Audit::with('utilisateur')->orderBy('created_at', 'desc')->paginate(15);
        return view('audits.index', compact('audits'));
    }
}
