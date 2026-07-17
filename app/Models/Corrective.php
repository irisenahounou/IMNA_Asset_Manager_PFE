<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Corrective extends Model
{
    use HasFactory;
    protected $table = 'Corrective';
    protected $primarykey = 'id_corrective';
    protected $keytype = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'id_corrective',
        'id_panne'
    ];
}
