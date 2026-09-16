<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultatModule extends Model
{
    public function inscription(){
        return $this->belongsTo(Module::class);
    }
}
