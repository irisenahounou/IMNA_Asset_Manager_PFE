<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Preventive extends Model
{
    use HasFactory;
    protected $table = 'Preventive';
    protected $primaryKey ='id_preventive';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'id_preventive',
        'id_materiel',
        'frequence_jour',
        'prochaine_rep'
    ];
    public function panne()
    {
        return $this->belongsTo(Panne::class, 'id_materiel', 'id');
    }
}
