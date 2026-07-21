<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
       $throttleKey = strtolower($request->input('email')) . '|' . $request->ip();
       if (RateLimiter::tooManyAttempts($throttleKey, 5)){
        $seconds = RateLimiter::availableIn($throttleKey);
        throw ValidationException::withMessages([
            'email' => ["Trop de tentatives de connexion. Veuillez réessayer dans {$seconds} secondes."],
        ]);
       } 
       $user = Utilisateur::where('email', $request->input('email'))->first();
       if (!$user || !Hash::check($request->input('password'), $user->mot_passe)){
        RateLimiter::hit($throttleKey, 60);
        throw ValidationException::withMessages([
            'email' => ['Ces identifiants ne correspondent pas à nos enregistrements.'],
        ]);
       }
       RateLimiter::clear($throttleKey);
       if ($user->estResponsable()) {
            $roleTexte = 'responsable';
        } elseif ($user->estTechnicien()) {
            $roleTexte = 'technicien';
        } else {
            $roleTexte = 'employé';
        }
       if ($user->doitUtiliserDeuxFacteurs()){
        session(['2fa_user_id' => $user->id_utilisateur]);
        \App\Http\Controllers\TwoFactorController::genererEtStockerCode($user->id_utilisateur);
        return redirect()->route('2fa.index');
       }
       Auth::login($user);
       $request->session()->regenerate();
       return redirect()->intended('/dashboard');
    
    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
