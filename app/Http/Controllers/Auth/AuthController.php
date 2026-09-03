<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TwoFactorController;
use App\Models\Utilisateur;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Authentification pour l'application mobile (React Native).
 * Contrairement au LoginController Web (session/cookie), ce contrôleur
 * délivre un Token Sanctum (RM-04 : "Jeton d'accès stocké localement,
 * révocable à distance par le DSI en cas de perte de l'appareil").
 */
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $throttleKey = strtolower($request->input('email')) . '|' . $request->ip();

        // RM-01 : blocage 15 minutes après 3 tentatives infructueuses consécutives
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'message' => "Compte temporairement bloqué. Réessayez dans {$seconds} secondes.",
            ], 429);
        }

        $user = Utilisateur::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->mot_passe)) {
            RateLimiter::hit($throttleKey, 900); // 900s = 15 minutes
            return response()->json([
                'message' => 'Identifiants incorrects.',
            ], 401);
        }

        RateLimiter::clear($throttleKey);

        // RM-03 : 2FA obligatoire pour les rôles administratifs (DSI, Techniciens)
        if ($user->doitUtiliserDeuxFacteurs()) {
            TwoFactorController::genererEtStockerCode($user->id_utilisateur);

            return response()->json([
                'requires_2fa' => true,
                'id_utilisateur' => $user->id_utilisateur,
                'message' => 'Un code de vérification a été envoyé.',
            ]);
        }

        return $this->issueToken($user);
    }

    public function verify2fa(Request $request)
    {
        $request->validate([
            'id_utilisateur' => 'required|integer',
            'code' => 'required|numeric|digits:6',
        ]);

        $user = Utilisateur::find($request->input('id_utilisateur'));

        if (!$user) {
            return response()->json(['message' => 'Utilisateur introuvable.'], 404);
        }

        $cacheKey = "2fa_code_{$user->id_utilisateur}";
        $codeEnCache = Cache::get($cacheKey);

        if (!$codeEnCache || $codeEnCache !== $request->input('code')) {
            return response()->json(['message' => 'Code invalide ou expiré.'], 422);
        }

        Cache::forget($cacheKey);

        AuditService::enregistrer(
            'Connexion Système (Mobile)',
            "Connexion réussie de l'utilisateur {$user->prenom} {$user->nom} ({$user->email})",
            $user
        );

        return $this->issueToken($user);
    }

    public function resend2fa(Request $request)
    {
        $request->validate(['id_utilisateur' => 'required|integer']);
        TwoFactorController::genererEtStockerCode($request->input('id_utilisateur'));

        return response()->json(['message' => 'Nouveau code envoyé.']);
    }

    public function logout(Request $request)
    {
        // Révoque uniquement le token utilisé pour cette requête
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnecté.']);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $user,
            'role' => $this->roleDe($user),
        ]);
    }

    private function issueToken(Utilisateur $user)
    {
        // Un nom de token par device permettrait, à terme, une révocation
        // individuelle par appareil depuis le dashboard DSI.
        $token = $user->createToken('mobile-' . $user->id_utilisateur)->plainTextToken;

        return response()->json([
            'requires_2fa' => false,
            'token' => $token,
            'role' => $this->roleDe($user),
            'user' => $user,
        ]);
    }

    private function roleDe(Utilisateur $user): string
    {
        if ($user->estResponsable()) {
            return 'responsable';
        }
        if ($user->estTechnicien()) {
            return 'technicien';
        }
        return 'employe';
    }
}