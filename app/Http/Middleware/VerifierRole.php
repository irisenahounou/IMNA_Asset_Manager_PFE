<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restreint l'accès à une route selon le rôle de l'utilisateur.
 * Usage dans les routes : ->middleware('role:responsable')
 * ou plusieurs rôles autorisés : ->middleware('role:responsable,technicien')
 */
class VerifierRole
{
    public function handle(Request $request, Closure $next, ...$rolesAutorises): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $rolesUtilisateur = [];
        if ($user->estResponsable()) {
            $rolesUtilisateur[] = 'responsable';
        }
        if ($user->estTechnicien()) {
            $rolesUtilisateur[] = 'technicien';
        }
        if ($user->estEmploye()) {
            $rolesUtilisateur[] = 'employe';
        }

        $autorise = count(array_intersect($rolesAutorises, $rolesUtilisateur)) > 0;

        if (!$autorise) {
            abort(403, "Accès réservé. Cette page n'est pas disponible pour ton rôle.");
        }

        return $next($request);
    }
}