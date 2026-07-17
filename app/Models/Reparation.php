<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reparation extends Model
{
    use HasFactory;
    protected $table = 'Reparation';
    protected $primarykey = 'id';
    protected $keytype = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'date_debut',
        'date_fin',
        'rapport_technique',
        'id_panne',
        'id_technicien'
    ];
    public function panne()
    {
        return $this->belongsTo(Panne::class, 'id_panne', 'id');
    }
    public function technicien()
    {
        return $this->belongsTo(Utilisateur::class, 'id_technicien', 'id_utilisateur');
    }
    public function detailsCorrective()
    {
        return $this->hasOne(Corrective::class, 'id_corrective', 'id');
    }

}
