<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class Employe extends Model
{
    use HasFactory,Auditable;
    protected $table = 'Employe';
   protected $primaryKey = 'id_employe';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = 
    [
        'id_employe'

    ];
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class,'id_employe','id_utilisateur');
    }
}
