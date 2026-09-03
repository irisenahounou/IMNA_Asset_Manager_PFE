<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Audit extends Model
{
    use HasFactory;

    protected $table = 'audits';

    protected $fillable = [
        'utilisateur_id',
        'action',
        'ip_address',
        'details',
    ];

    // Relation vers l'utilisateur (table Utilisateur, clé primaire id_utilisateur)
    public function utilisateur() {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id', 'id_utilisateur');
    }
}
