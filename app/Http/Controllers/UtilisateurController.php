<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Services\AuditService;
use Illuminate\Http\Request;

/**
 * RM-04 : "La session [Mobile] est maintenue par un Jeton d'accès (Token)
 * stocké localement sur le smartphone, révocable à distance par le DSI
 * en cas de perte de l'appareil."
 */
class UtilisateurController extends Controller
{
    // Vérifie que seul le DSI/Responsable accède à ces actions sensibles.
    private function verifierAccesResponsable(Request $request)
    {
        if (!$request->user()->estResponsable()) {
            abort(403, 'Accès réservé au Responsable / DSI.');
        }
    }

    // Liste des utilisateurs pouvant se connecter au Mobile (Techniciens,
    // Responsables), avec le nombre de tokens actifs pour chacun.
    public function index(Request $request)
    {
        $this->verifierAccesResponsable($request);

        $utilisateurs = Utilisateur::where(function ($q) {
                $q->whereHas('technicien')->orWhereHas('responsable');
            })
            ->withCount('tokens')
            ->orderBy('nom')
            ->get();

        return view('utilisateurs.index', compact('utilisateurs'));
    }

    // Détail d'un utilisateur : liste de ses appareils/tokens connectés.
    public function show(Request $request, $id)
    {
        $this->verifierAccesResponsable($request);

        $utilisateur = Utilisateur::with('tokens')->findOrFail($id);

        return view('utilisateurs.show', compact('utilisateur'));
    }

    // Révoque un token précis (perte d'appareil).
    public function revoquerToken(Request $request, $id, $tokenId)
    {
        $this->verifierAccesResponsable($request);

        $utilisateur = Utilisateur::findOrFail($id);
        $token = $utilisateur->tokens()->findOrFail($tokenId);
        $nomToken = $token->name;
        $token->delete();

        AuditService::enregistrer(
            'Révocation Token Mobile',
            "Le DSI a révoqué le token '{$nomToken}' de l'utilisateur {$utilisateur->prenom} {$utilisateur->nom}"
        );

        return redirect()->back()->with('success', "Accès mobile '{$nomToken}' révoqué avec succès.");
    }

    // Révoque en une fois tous les tokens (cas d'urgence : perte d'appareil).
    public function revoquerTousLesTokens(Request $request, $id)
    {
        $this->verifierAccesResponsable($request);

        $utilisateur = Utilisateur::findOrFail($id);
        $nombre = $utilisateur->tokens()->count();
        $utilisateur->tokens()->delete();

        AuditService::enregistrer(
            'Révocation Totale Tokens Mobile',
            "Le DSI a révoqué {$nombre} token(s) mobile(s) de l'utilisateur {$utilisateur->prenom} {$utilisateur->nom}"
        );

        return redirect()->back()->with('success', 'Tous les accès mobiles ont été révoqués.');
    }
}