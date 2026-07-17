<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Utilisateur extends Model
{
    use HasFactory;
    protected $table = 'Utilisateur';
    protected $primarykey ='id_utilisateur';
    protected $keytype = 'int';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'statut_compte',
    ];
}
