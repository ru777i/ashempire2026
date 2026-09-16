<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
         'evaluation_id',
        'inscription_id',
        'note',
        'mention',

    ];
     public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    public function formateur()
    {
        return $this->belongsTo(Formateur::class);
    }
}
