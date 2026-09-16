<?php

namespace App\Livewire\Apprenant;

use App\Models\Formateur;
use App\Models\Recour as ModelRecour;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Recour extends Component
{

    use WithPagination;


    public $objet;
    public $type;
    public $description;
    public $formateur_id;


    public $recour_id;


    public $showModal = false;

    public $editMode = false;



    protected $rules = [

        'objet'=>'required|min:5',

        'type'=>'required',

        'description'=>'required|min:10',

        'formateur_id'=>'required|exists:formateurs,id',

    ];



    public function render()
    {

        $apprenant = Auth::user()->apprenant;


        return view('livewire.apprenant.recour',[


            'recours'=>ModelRecour::with([
                'formateur.user',
                'traitePar.user'
            ])
            ->where('apprenant_id',$apprenant->id)
            ->latest()
            ->paginate(10),



            'formateurs'=>Formateur::with('user')->get()


        ]);

    }



    /**
     * Ouvrir le formulaire
     */
    public function create()
    {

        $this->resetForm();

        $this->editMode=false;

        $this->showModal=true;

    }




    /**
     * Enregistrer un recours
     */
    public function save()
    {

        $this->validate();



        ModelRecour::create([


            'apprenant_id'=>Auth::user()->apprenant->id,


            'formateur_id'=>$this->formateur_id,


            'objet'=>$this->objet,


            'type'=>$this->type,


            'description'=>$this->description,


            'statut'=>'en_attente'


        ]);



        session()->flash(
            'message',
            'Votre recours a été envoyé avec succès.'
        );


        $this->showModal=false;


        $this->resetForm();

    }





    /**
     * Modifier un recours
     */
    public function edit($id)
    {


        $recour=ModelRecour::findOrFail($id);



        // Sécurité : seul un recours non traité est modifiable

        if($recour->statut !== 'en_attente')
        {
            session()->flash(
                'error',
                'Ce recours ne peut plus être modifié.'
            );

            return;
        }



        $this->recour_id=$recour->id;


        $this->objet=$recour->objet;

        $this->type=$recour->type;

        $this->description=$recour->description;

        $this->formateur_id=$recour->formateur_id;



        $this->editMode=true;

        $this->showModal=true;


    }





    /**
     * Mise à jour
     */
    public function update()
    {

        $this->validate();



        $recour=ModelRecour::findOrFail($this->recour_id);



        if($recour->statut !== 'en_attente')
        {
            return;
        }



        $recour->update([


            'objet'=>$this->objet,


            'type'=>$this->type,


            'description'=>$this->description,


            'formateur_id'=>$this->formateur_id,


        ]);



        session()->flash(
            'message',
            'Recours modifié avec succès.'
        );


        $this->showModal=false;


        $this->resetForm();

    }





    /**
     * Supprimer
     */
    public function delete($id)
    {

        $recour=ModelRecour::findOrFail($id);



        if($recour->statut === 'en_attente')
        {

            $recour->delete();


            session()->flash(
                'message',
                'Recours supprimé.'
            );

        }

    }





    private function resetForm()
    {

        $this->objet=null;

        $this->type=null;

        $this->description=null;

        $this->formateur_id=null;

        $this->recour_id=null;

    }



}
