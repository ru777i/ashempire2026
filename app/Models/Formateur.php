<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Formateur extends Model
{
    protected $fillable = [
        'id',
        'specialite',
        'user_id',

    ];
    public  function user(){
        return $this->belongsTo(User::class);
    }
   public function enseigner(){
    return $this->hasMany(Module::class);
   }
   public function sessionModule(){
    return $this-> hasMany(SessionModule::class);
   }

   public function formateurModule(){
    return $this->belongsToMany(FormateurModule::class);
   }
   public function modules(){
    return $this->belongsTo(Module::class,'fomateur_modules');
   }
    public function emploisTemps()
    {
        return $this->hasMany(EmploiTemp::class);
    }

    public function moduless(){
        return $this->hasMany(Module::class);
    }
    public function notesSaisies()
    {
        return $this->hasMany(Note::class);
    }
    public function evaluation(){
        return $this->hasMany(Evaluation::class);
    }

    public function formateurs(){
        return $this->hasMany(Module::class);
    }
    public function sessionns(){
        return $this->belongsToMany(Sessionn::class,'formateur_sessions') ->withPivot([
            'module_id',
        ]);;
    }
}
