<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Cache;
use App\Models\Utilisateur;

class TwoFactorController extends Controller
{
    public function index()
    {
        if (!session()->has('2fa_user_id')){
            return redirect()->route('login');
        }
        return view('auth.2fa');
    }
    public static function genererEtStockerCode($userId): string
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $cacheKey = "2fa_code_{$userId}";
        Cache::put($cacheKey, $code, now()->addMinutes(10));
        logger("Code 2FA généré pour l'utilisateur {$userId} : {$code}");
        return $code;

    }
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);
        $userId = session('2fa_user_id');
        if (!$userId){
            return redirect()->route('login')->withErrors(['email' => 'Session expirée, veuillez vous re-connecter.']);
        }
        $user = Utilisateur::find($userId);
        if (!$user){
            return redirect()->route('login')->withErrors(['email' => 'Utilisateur introuvable.']);
        }
        $cacheKey = "2fa_code_{$userId}";
        $codeEnCache = Cache::get($cacheKey);
        if (!$codeEnCache || $codeEnCache !== $request->input('code')){
            return back()->withErrors(['code' => 'Le code de vérification est incorrect ou a expiré.']);
        }
        Cache::forget($cacheKey);
        session()->forget('2fa_user_id');
        Auth::login($user);
        $request->session()->regenerate();
        if ($user->estResponsable()){
           return redirect()->route('responsable.dashboard'); 
        }
        return redirect()->route('technicien.dashboard');
    }
    public function resend()
    {
        $userId = session('2fa_user_id');
        if (!$userId){
            return redirect()->route('login');
        }
        self::genererEtStockerCode($userId);
        return back()->with('success', 'Un nouveau code de vérification a été généré.');
    }
}
