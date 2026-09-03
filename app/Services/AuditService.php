<?php

namespace App\Services; // <--- Vérifie bien ceci

use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    public static function enregistrer(string $action, ?string $details = null, $utilisateur = null)
    {
        $utilisateurId = $utilisateur->id_utilisateur ?? (Auth::check() ? Auth::user()->id_utilisateur : null ); 

        Audit::create([
            'utilisateur_id' => $utilisateurId,
            'action' => $action,
            'ip_address' => request()->ip(),
            'details' => $details,
        ]);
    }
}