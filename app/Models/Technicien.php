<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Technicien extends Model
{
    use HasFactory;
    protected $table = 'Technicien';
    protected $primaryKey = 'id_technicien';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = 
    [
        'id_technicien',
        'disponibilite'

    ];
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class,'id_technicien','id_utilisateur');
    }
}
