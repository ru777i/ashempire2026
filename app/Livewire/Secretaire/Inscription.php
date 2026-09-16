<?php

namespace App\Livewire\Secretaire;

use Livewire\Component;
use App\Models\Inscription as InscriptionModel;

class Inscription extends Component
{
    public $search ='';
    // public $inscriptions =[];
    public function delete(int $id)
    {
        InscriptionModel::destroy($id);
    }
    public function render()
    {
         $query = InscriptionModel::query();
        if ($this->search) {
            $query->whereRelation('aprenant','prenom','like','%'.$this->search.'%')->
            orWhereHas('aprenant', function ($q) {
                $q->whereRelation('user','name','like','%'.$this->search.'%');
            })
            ->orWhereRelation('sessionn.formation','nom','like','%'.$this->search.'%');
        }
        $inscriptions=$query->paginate(6);

        return view('livewire.secretaire.inscription', compact('inscriptions'));
    }
}
