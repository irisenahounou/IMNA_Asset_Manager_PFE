<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Utilisateur extends Authenticatable
{
    use HasFactory;
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
            'mot_passe' =>'hashed',
        ];

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
