<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class UniteComposant extends Model
{
    use HasFactory, Auditable;

    protected $table = 'unites_composant';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_composant',
        'numero_serie',
        'etat',
        'date_achat',
        'id_materiel_actuel',
    ];

    public function composant()
    {
        return $this->belongsTo(Composant::class, 'id_composant', 'id_composant');
    }

    public function materielActuel()
    {
        return $this->belongsTo(Materiel::class, 'id_materiel_actuel', 'id');
    }

    public function affectations()
    {
        return $this->hasMany(AffectationComposant::class, 'id_unite', 'id');
    }

    public function estEnStock(): bool
    {
        return is_null($this->id_materiel_actuel);
    }
}