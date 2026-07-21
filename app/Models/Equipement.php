<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipement extends Model
{
    use HasFactory;
    protected $table = 'equipements';
    protected $primaryKey = 'id_equipement';

    protected $fillable = [
        'nom_equipement',
        'type_equipement',
        'localisation',
        'statut',
    ];
}
