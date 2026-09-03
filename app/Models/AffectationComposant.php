<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class AffectationComposant extends Model
{
    use HasFactory, Auditable;

    protected $table = 'affectations_composant';
    public $timestamps = false;

    protected $fillable = [
        'id_unite',
        'id_materiel',
        'date_installation',
        'date_retrait',
        'etat_au_retrait',
        'id_technicien',
    ];

    public function unite()
    {
        return $this->belongsTo(UniteComposant::class, 'id_unite', 'id');
    }

    public function materiel()
    {
        return $this->belongsTo(Materiel::class, 'id_materiel', 'id');
    }

    public function technicien()
    {
        return $this->belongsTo(Utilisateur::class, 'id_technicien', 'id_utilisateur');
    }
}