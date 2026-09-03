<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class MouvementStock extends Model
{
    use HasFactory,Auditable;
    // Définition de la table et de la clé primaire personnalisée
    protected $table = 'mouvements_stock';
    protected $primaryKey = 'id_mouvement';

    protected $fillable = [
        'id_composant',
        'quantite',
        'type_mouvement',
        'statut_validation',
        'id_technicien',
    ];

    // Relation : Un mouvement de stock appartient à un composant
    public function composant()
    {
        return $this->belongsTo(Composant::class, 'id_composant', 'id_composant');
    }
    // Relation optionnelle vers l'utilisateur/technicien si ton modèle s'appelle Utilisateur
    public function technicien()
    {
        return $this->belongsTo(Utilisateur::class, 'id_technicien', 'id_utilisateur'); // Ajuste 'id_utilisateur' si besoin selon ta table utilisateurs
    
    }
}
