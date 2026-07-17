<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Preventive extends Model
{
    use HasFactory;
    protected $table = 'Preventive';
    protected $primarykey ='id_preventive';
    protected $keytype = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'id_preventive',
        'id_panne',
        'frequence_jour',
        'prochaine_rep'
    ];
}
