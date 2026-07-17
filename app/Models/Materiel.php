<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Materiel extends Model
{
    use HasFactory;
    protected $table = 'Materiel';
    protected $primarykey = 'id';
    protected $keytype = 'string';
    public $incrementing = 'false';
    public $timestamps ='false';
    protected $fillable = [
        'id',
        'numero_serie',
        'nom_equipement',
        'type',
        'date_achat',
        'etat_operationnel',
        'id_service',
        'id_responsable',
    ];
}
