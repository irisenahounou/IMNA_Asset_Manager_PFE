<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class Composant extends Model
{
    use HasFactory,Auditable;
    // Définition de la table et de la clé primaire personnalisée
    protected $table = 'composants';
    protected $primaryKey = 'id_composant';

    protected $fillable = [
        'nom_composant',
        'type_composant',
        'quantite_stock',
        'seuil_alerte',
    ];
    // Relation : Un composant possède plusieurs mouvements de stock
    public function mouvements()
    {
        return $this->hasMany(MouvementStock::class, 'id_composant', 'id_composant');
    }
    public function unites()
    {
         return $this->hasMany(UniteComposant::class, 'id_composant', 'id_composant');
    }
}
