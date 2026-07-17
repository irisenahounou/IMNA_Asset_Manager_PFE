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

}
