<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Responsable extends Model
{
    use HasFactory;
    protected $table = 'Responsable';
    protected $primarykey = 'id_responsable';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable =
    [
        'id_responsable',
        'disponibilite'

    ];
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_responsable','id_utilisateur');
    }

}
