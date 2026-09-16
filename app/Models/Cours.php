<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    //
    protected $fillable = [
        'module_id','formateur_id','titre','contenu','type_cours'
    ];

    public function module() {
        return $this->belongsTo(Module::class);
    }

    public function formateur() {
        return $this->belongsTo(Formateur::class);
    }
}
