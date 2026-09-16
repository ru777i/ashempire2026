<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormateurModule extends Model
{
    public function formateur(){
        return $this-> belongsTo(Formateur::class);

    }
    public function module(){
        return $this->belongsTo(Module::class);
    }
}
