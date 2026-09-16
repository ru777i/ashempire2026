<?php

namespace App\Livewire;

use App\Models\Formation;
use Flux\Flux;
use Livewire\Component;

class FormationEdit extends Component
{

  public $open=true;
    public $volume_horaire;
    public $description;
    public $code;
    public $nom;
    public $search='';
    public $nomModule;
    public $id;
    public $formateur_id='';
    public $statut;
    public $descriptionModule;
    public $volumeHoraire;

  public function mount($id = null)
    {
    //     if ($id== null) {
    //         $id=$this->enterId;
    //         # code...
    //     }
        if ($id != null) {
            $formation = Formation::findOrFail($id);
            $this->volume_horaire = $formation->volume_horaire;
            $this->description = $formation->description;
            $this->code = $formation->code;
            $this->nom = $formation->nom;

           $this->statut = $formation->statut;
        }
    }
    public function suprimer($id){
        Formation::destroy($id);
    }

    public function enregistrer()
    {
        $validated = $this->validate([
            'description' =>  isset($this->id) ? ['nullable', 'required', 'string'] : ['nullable', 'string'],
            'nom' =>  isset($this->id) ? ['nullable', 'required', 'string'] : ['required', 'string'],
            'code' =>  isset($this->id) ? ['nullable', 'required', 'string'] : ['string', 'required'],
            'volume_horaire' => isset($this->id) ? ['nullable', 'required', 'integer'] : ['required', 'integer'],
            'statut' =>['required']
        ]);
        if ($this->id && $this->id != null) {
            try {
                //code...
                $form = Formation::findOrFail($this->id);
                $form->update($validated);
                Flux::toast(variant: 'success', text: __('Session mise a jour success.'));
                $this->reset();
            } catch (\Throwable $th) {
                Flux::toast(variant: 'error', text: __('une erreur s est produit.' . $th->getMessage()));

                //throw $th;
            }


            # code...
        } else {
            try {
                Formation::create($validated);
                Flux::toast(variant: 'success', text: __('Formation cree avec success.'));
             $this->reset();
            } catch (\Throwable $th) {
                Flux::toast(variant: 'error', text: __('erreur survenu lors de la creation' . $th->getMessage()));
            }
        }

    }
    public function render()
    {
        return view('livewire.formation-edit');
    }
}
