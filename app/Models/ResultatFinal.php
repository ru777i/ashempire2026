<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultatFinal extends Model
{
    protected $fillable = [
        'inscription_id',
        'moyenneGenerale',
        'descision',
        'mention',
    ];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }
}
