<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
       
        'dateEvaluation',
        'noteMax',
        'heureDebut',
        'heureFin',
        'sessionn_module_id'
    ];

    public  function sessionModule(){
        return $this->belongsTo(SessionModule::class,'sessionn_module_id');
    }
 


    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
