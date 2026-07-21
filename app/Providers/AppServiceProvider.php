<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Utilisateur;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('acces-employe', function (Utilisateur $utilisateur)
        {
            return $utilisateur->estEmploye();
        });
        Gate::define('acces-technicien', function (Utilisateur $utilisateur)
        {
            return $utilisateur->estTechnicien();
        });
        Gate::define('acces-responsable', function (Utilisateur $utilisateur)
        {
            return $utilisateur->estResponsable();
        });
    }
}
