<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Utilisateur extends Authenticatable
{
    use HasFactory, HasApiTokens,Notifiable;
    protected $table = 'Utilisateur';
    protected $primaryKey ='id_utilisateur';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_passe',
        'two_factor_secret',
        'statut_compte',
    ];
    protected $hidden = [
        'mot_passe',
         'two_factor_secret',
    ];
    public function getAuthPassword()
    {
        return $this->mot_passe;
    }
    public function pannesDeclarees()
    {
        return $this->hasMany(Panne::class,'id_employe','id_utilisateur');
    }
    public function casts() : array
    {
        return [
            
        ];

    }
    // Le mutateur s'assure que le mot de passe est toujours haché, 
    // mais ne le re-hache PAS s'il l'est déjà (évite le double hachage)
    public function setMotPasseAttribute($value)
    {
        if (password_get_info($value)['algo'] !== 0) {
            // Le mot de passe est déjà un hash, on le prend tel quel
            $this->attributes['mot_passe'] = $value;
        } else {
            // C'est du texte en clair (futur utilisateur), on le hache
            $this->attributes['mot_passe'] = Hash::make($value);
        }
    }
    public function employe()
    {
        return $this->hasOne(Employe ::class, 'id_employe', 'id_utilisateur');
    }
    public function technicien()
    {
        return $this->hasOne(Technicien::class, 'id_technicien', 'id_utilisateur');
    }
    public function responsable()
    {
        return $this->hasOne(Responsable::class, 'id_responsable', 'id_utilisateur');
    }
    public function estEmploye() : bool
    {
        return $this->employe()->exists();
    }
    public function estTechnicien() : bool
    {
        return $this->technicien()->exists();
    }
    public function estResponsable() : bool
    {
        return $this->responsable() ->exists();
    }
    public function doitUtiliserDeuxFacteurs() : bool
    {
        return $this->estResponsable() || $this->estTechnicien();
    }
}
