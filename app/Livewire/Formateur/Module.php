<?php

namespace App\Livewire\Formateur;

use App\Models\Inscription;
use App\Models\Module as ModuleModel;
use App\Models\SessionModule;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Module extends Component
{
   
    public $search='';
    public function render()
    {
        $query = SessionModule::query();
        if (Auth::user()->role == 'apprenant') {
            $ids = Inscription::where('apprenant_id', Auth::user()->apprenant->id)->pluck('sessionn_id')->unique('sessionn_id');
            $query->whereIn('sessionn_id', $ids)->get();
        } else {
           $query->where('formateur_id', Auth::user()->formateur->id)->get();
        }
        if ($this->search) {
            $query->whereRelation('module','nom','like','%'.$this->search.'%');

            // ->orwhereHas('formateur',function ($q) {
            //     $q->whereRelation('user','name','like','%'.$this->search.'%');
            // })

            // ->orWhereHas('sessionn', function ($q){
            //     $q->whereRelation('formation', 'nom','like','%'.$this->search.'%');
            // });
        }
        $sessionModules=$query->paginate(18);

        // if (Auth::user()->role=='formateur') {
        // }
        return view('livewire.formateur.module', compact('sessionModules'));
    }
}
