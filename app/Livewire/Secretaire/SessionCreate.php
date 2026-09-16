<?php

namespace App\Livewire\Secretaire;

use App\Models\Sessionn;
use App\Models\Formation as FormationModel;
use App\Models\Mprogression;
use App\Models\SessionModule;
use Flux\Flux;
use Livewire\Component;

class SessionCreate extends Component
{
    public $moduless = [];
    public $type;
    public $modules = [];
    public $dateDebut;
    public $dateFin;
    public $formation_id = '';
    public $capacite;
    public $statut;
    public $formateur_id = '';
    public $code;
    public $id;
    public $anne_academique_id = '';
    public function codeGenrer()
    {
        $lastSession = Sessionn::orderBy('id', 'desc')->first();
        if ($lastSession) {
            $lastCode = $lastSession->code;
            $lastNumber = (int) substr($lastCode, 1);
            $newNumber = $lastNumber + 1;
            return 'S' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        } else {
            return 'S001';
        }
    }
    public function mount($id = null)
    {
        if ($id != null) {

            $sessio = Sessionn::findOrFail($id);
            $this->id = $sessio->id;
            $this->statut = $sessio->statut;
            $this->type = $sessio->type;
            $this->moduless= $sessio->modules()->pluck('modules.id');
            $this->dateDebut = $sessio->dateDebut;
            $this->dateFin = $sessio->dateFin;
            $this->capacite = $sessio->capacite;
            $this->anne_academique_id = $sessio->anne_academique_id;
            $this->formation_id = $sessio->formation_id;
            # code...
        }
    }


    public function enregistrer()
    {
        $validated =  $this->validate([
            'type' => isset($this->id) ? ['nullable'] : ['required', 'string'],
            'dateDebut' => isset($this->id) ? ['nullable', 'required'] : ['date', 'required'],
            'dateFin' => isset($this->id) ? ['nullable', 'required'] : ['date', 'required', 'after:dateDebut'],
            'capacite' => isset($this->id) ? ['nullable', 'required'] : ['required', 'integer', 'min:0'],
            'formation_id' => isset($this->id) ? ['nullable', 'required', 'exists:formations,id'] : ['required', 'exists:formations,id'],
            'statut' => isset($this->id) ? ['nullable'] : ['required', 'string', 'in:en cours,terminee,annulee,en attente'],
            'anne_academique_id' => isset($this->id) ? ['nullable', 'exists:anne_academiques,id'] : ['required', 'exists:anne_academiques,id'],

        ]);
        if ($this->id) {
            try {
                //code...
                $sess = Sessionn::findOrFail($this->id);
                $sess->update($validated);
                foreach ($this->moduless as $key => $value) {
                    SessionModule::updateOrCreate(
                        [
                            'module_id' => $value,
                            'sessionn_id' => $sess->id,
                        ]
                    );
                }
           $mo  =$sess->modules()->whereNotIn('module_id',$this->moduless)->pluck('module_id');

                foreach ($sess->sessionnModule as $key => $value) {
                    Mprogression::updateOrCreate([
                        'sessionn_module_id' => $value->id,
                        'sessionn_id' => $sess->id,
                    ]);
                }
                Flux::toast(variant: 'success', text: __('Session mise a jour success.'));
            } catch (\Throwable $th) {
                Flux::toast(variant: 'error', text: __('Unable to save the sessionnn.' . $th->getMessage()));
                //throw $th;
            }
        } else {
            try {
                //code...
                $validated['code'] = $this->codeGenrer();
                $sessionn = Sessionn::create($validated);
                 foreach ($this->moduless as $key => $value) {
                    SessionModule::updateOrCreate(
                        [
                            'module_id' => $value,
                            'sessionn_id' => $sessionn->id,
                        ]
                    );
                    $sessionn->modules()->whereNotIn('module_id',$this->moduless)->delete();
                }

                foreach ($sessionn->sessionnModule as $key => $value) {
                    Mprogression::updateOrCreate([
                        'sessionn_module_id' => $value->id,
                        'sessionn_id' => $sessionn->id,
                    ]);
                }

                Flux::toast(variant: 'success', text: __('Session cree avec success.'));
            } catch (\Throwable $th) {
                Flux::toast(variant: 'error', text: __('Unjjjable to save the user.' . $th->getMessage()));
                //throw $th;
            }
        }
    }
    public function render()
    {

        $anneAcademiques = \App\Models\AnneAcademique::all();
        $formations = \App\Models\Formation::all();
        if ($this->formation_id) {
            # code...
            $this->modules = FormationModel::findOrFail($this->formation_id)->modules;
        }

        return view('livewire.secretaire.session-create', compact('formations', 'anneAcademiques'));
    }
}
