<?php

namespace App\Livewire\Secretaire;

use App\Models\SessionModule;
use App\Models\Sessionn;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Session extends Component
{
    public $search='';
    public $selctFormateur= '';

    public function suprimer($id){
        \App\Models\Sessionn::destroy($id);
    }
    public function modifier($id){
        return redirect( route('sessionCreate',$id));
    }
    public function AssignerFormateur( int $id){
        $this->validate([
            'selctFormateur'=>['required','exists:formateurs,id'],
        ]);
        try {
            $sessm= SessionModule::findOrFail($id);
            $sessm->update([
                'formateur_id'=>$this->selctFormateur,
            ]);
             Flux::toast(variant:'success',text:'Formateur assigner avec success');
        } catch (\Throwable $th) {
            Flux::toast(variant:'error',text:'erreur'.$th->getMessage());
        }

    }
    public function render()
    {  
        if (Auth::check() && Auth::user()->role=='formateur') {
       $query =Sessionn::whereRelation('sessionnModule','formateur_id',Auth::user()->formateur->id);
    }elseif(Auth::check() && Auth::user()->role=='apprenant'){
        $query=Sessionn::whereRelation('inscriptions','apprenant_id',Auth::user()->apprenant->id);
    }
    else{
       $query=Sessionn::query();
    }

        if ($this->search) {
            $query->whereRelation('formation','nom','like','%'.$this->search.'%');


        }
   $sessions=$query->paginate(10);
        return view('livewire.secretaire.session', compact('sessions'));

    }
}
