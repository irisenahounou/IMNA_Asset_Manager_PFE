<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class Materiel extends Model
{
    use HasFactory,Auditable;
    protected $table = 'Materiel';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'numero_serie',
        'nom_equipement',
        'type',
        'date_achat',
        'etat_operationnel',
        'localisation',
        'id_service',
        'id_responsable',
    ];
    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service', 'id_service');
    }
    public function pannes()
    {
        return $this->hasMany(Panne::class,'id_materiel', 'id');
    }
    public function unitesInstallees()
    {
        return $this->hasMany(UniteComposant::class, 'id_materiel_actuel', 'id');
    }
}
