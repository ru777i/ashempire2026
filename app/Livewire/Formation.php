<?php

namespace App\Livewire;

use Flux\Flux;
use App\Models\Formation as FormationModel;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Formation extends Component
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
public $enterId='';

    public function mount($id = null)
    {
        if ($id== null) {
            $id=$this->enterId;
            # code...
        }
        if ($id != null) {
            $formation = FormationModel::findOrFail($id);
            $this->volume_horaire = $formation->volume_horaire;
            $this->description = $formation->description;
            $this->code = $formation->code;
            $this->nom = $formation->nom;

           $this->statut = $formation->statut;
        }
    }
    public function suprimer($id){
        FormationModel::destroy($id);
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
                $form = FormationModel::findOrFail($this->id);
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
                FormationModel::create($validated);
                Flux::toast(variant: 'success', text: __('Formation cree avec success.'));
             $this->reset();
            } catch (\Throwable $th) {
                Flux::toast(variant: 'error', text: __('erreur survenu lors de la creation' . $th->getMessage()));
            }
        }

    }

public function suprimerModule($id){
        $module = \App\Models\Module::findOrFail($id);
        $module->delete();
        Flux::toast(variant: 'success', text: __('Module suprime avec success.'));
    }

    // public function assignerFormateur($moduleId)
    // {
    //     $validated = $this->validate([
    //         'formateur_id' => ['required', 'exists:formateurs,id'],
    //     ]);

    //     try {
    //         $module = \App\Models\Module::findOrFail($moduleId);
    //         $module->formateur_id = $this->formateur_id;
    //         $module->save();

    //         Flux::toast(variant: 'success', text: __('Formateur assigné avec succès.'));
    //     } catch (\Throwable $th) {
    //         Flux::toast(variant: 'error', text: __('Erreur lors de l\'assignation du formateur : ' . $th->getMessage()));
    //     }
    // }
    public function saveModule($id){
      $validated= $this->validate([
            'nomModule'=>['required','string'],
            'descriptionModule'=>['nullable','string'],
            // 'formateur_id'=>['required','integer','exists:formateurs,id'],
            'volumeHoraire'=>['required','integer']
        ]);
        try{
        $formation= FormationModel::findOrFail($id);
        $formation->modules()->create([
            'nom'=>$this->nomModule,
            'description'=>$this->descriptionModule,
              // 'formateur_id'=>$this->formateur_id,
            'volumeHoraire'=>$this->volumeHoraire
        ]);
        $this->reset(['nomModule','description','volumeHoraire']);
        Flux::toast(variant: 'success', text: __('Module cree avec success.'));
        }catch(\Throwable $th){
            Flux::toast(variant: 'error', text: __('erreur survenu lors de la creation' . $th->getMessage()));
        }
    }
    public function render()
    {
        $query = FormationModel::query();
        if ($this->search) {
            $query->where('nom','like','%'.$this->search.'%');
        # code...
        }
        $formations = $query->paginate(10);
        $formateurs = \App\Models\Formateur::all();
        return view('livewire.formation' , compact('formations','formateurs'));
    }
}
