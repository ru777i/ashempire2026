<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionModule extends Model
{
    protected $fillable = [
        'module_id',
        'sessionn_id',
        'statut',
        'formateur_id',
        'progress'
    ];


    public function module(){
        return $this->belongsTo(Module::class);
    }
    public function evaluations(){
        return $this->hasMany(Evaluation::class,'sessionn_module_id');
    }
    public  function sessionn(){
        return $this->belongsTo(Sessionn::class);
    }
    public function formateur(){
        return $this->belongsTo(Formateur::class);
    }
    public function progrressionModule(){
        return $this->hasMany(Mprogression::class);
    }
}
