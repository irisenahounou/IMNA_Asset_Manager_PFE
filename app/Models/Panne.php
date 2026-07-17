<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Panne extends Model
{
    use HasFactory;
    protected $table ='Panne';
    protected $primarykey ='id';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'titre',
        'description',
        'date_declaration',
        'statut',
        'id_materiel',
        'id_employe'
    ];
    public function materiel()
    {
        return $this->belongsTO (Materiel::class, 'id_materiel', 'id');
    }
    public function declarant()
    {
        return $this->belongsTo (Utilisateur::class, 'id_employe', 'id_utilisateur');
    }
    public function reparations()
    {
        return $this->hasMany(Reparation::class,'id_panne', 'id');
    }
}
