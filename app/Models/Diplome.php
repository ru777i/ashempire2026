<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diplome extends Model
{
    protected $fillable = [
        'apprenant_id',
        'path'
    ];
    public function apprenant(){
        return $this->belongsTo(Apprenant::class);
    }
}
