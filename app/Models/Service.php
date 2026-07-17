<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;
    protected $table ='Service';
    protected $primarykey ='id_service';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'id_service'
    ];
    public function materiels()
    {
        return $this->hasMany(Materiel::class, 'id_service', 'id_service');
    }
}
