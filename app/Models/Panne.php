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
}
