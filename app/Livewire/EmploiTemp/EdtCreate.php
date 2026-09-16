<?php

namespace App\Livewire\EmploiTemp;

use App\Models\Apprenant;
use App\Models\EmploiTemp;
use App\Models\Formateur;
use App\Models\Inscription;
use App\Models\Module;
use App\Models\Salle;
use App\Models\SessionModule;
use App\Models\Sessionn;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EdtCreate extends Component
{
    public $salles = [];
    public $modules = [];
    public $formateurs = [];
    public $sessions = [];
    public $sessionn_id = '';
    public $module_id = '';
    public $date = '';
    public $jour = '';
    public $type = '';
    public $heureDebut = '';
    public $heureFin = '';
    public $salle_id  = '';
    public $formateur_id = '';
    public $id = '';
    public $isEditing = false;
    public $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
    public $timeSlots = ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
    public $seances = [];

    public function mount($id = null)
    {
        $this->refreshSeances();

        if ($id !== null) {
            $this->editSeance($id);
        }
    }

    public function resetForm()
    {

        $this->sessionn_id = '';
        $this->module_id = '';
        $this->date = '';
        $this->jour = '';
        $this->type = '';
        $this->heureDebut = '';
        $this->heureFin = '';
        $this->salle_id = '';
        $this->formateur_id = '';
        $this->id = '';
        $this->isEditing = false;
    }

    public function prepareCreate()
    {
        $this->resetForm();
    }

    public function refreshSeances()
    {
        if (Auth::user()->role == 'formateur') {
            # code...

            $this->seances = EmploiTemp::where('formateur_id', Auth::user()->formateur->id)->orderBy('jour')
                ->orderBy('heureDebut')
                ->get();
        } elseif (Auth::user()->role == 'secretaire') {
            # code...
            $this->seances = EmploiTemp::query()
                ->orderBy('jour')
                ->orderBy('heureDebut')
                ->get();
        } else {
            $sess = Inscription::where('apprenant_id', Auth::user()->apprenant->id)->pluck('sessionn_id');
            $this->seances = EmploiTemp::whereIn('sessionn_id', $sess)->orderBy('jour')
                ->orderBy('heureDebut')
                ->get();
        }
    }

    public function editSeance($id)
    {
        $emploiTemp = EmploiTemp::find($id);

        if ($emploiTemp) {
            $this->sessionn_id = $emploiTemp->sessionn_id;
            $this->module_id = $emploiTemp->module_id;
            $this->date = $emploiTemp->date;
            $this->jour = $emploiTemp->jour;
            $this->type = $emploiTemp->type;
            $this->heureDebut = $emploiTemp->heureDebut;
            $this->heureFin = $emploiTemp->heureFin;
            $this->salle_id = $emploiTemp->salle_id;
            $this->formateur_id = $emploiTemp->formateur_id;
            $this->id = $emploiTemp->id;
            $this->isEditing = true;
        }
    }

    public function slotForTime($heure)
    {
        $normalized = substr($heure, 0, 5);

        return collect($this->timeSlots)->first(function ($slot) use ($normalized) {
            return $slot === $normalized;
        }) ?? $normalized;
    }
    public function saveEdt()
    {
        $validatedData = $this->validate([
            'sessionn_id' => ['required', 'exists:sessionns,id'],
            'module_id' => ['required', 'exists:modules,id'],
            'date' => 'required|date',
            'jour' => 'required|in:Lundi,Mardi,Mercredi,Jeudi,Vendredi',
            'type' => 'required|in:CM,TD,TP',
            'heureDebut' => 'required|date_format:H:i',
            'heureFin' => 'required|date_format:H:i|after:heureDebut',
            'salle_id' => ['required', 'exists:salles,id'],
            // 'formateur_id' => ['required', 'exists:formateurs,id'],
        ]);

        if (!EmploiTemp::verifierLibre($this->salle_id, $this->heureDebut, $this->heureFin, $this->date, $this->jour, $this->id)) {
            Flux::toast(variant: 'error', text: 'La salle est déjà occupée à cette heure.');
            return;
        }

        try {
            if ($this->id) {
                $emploiTemp = EmploiTemp::find($this->id);

                if ($emploiTemp) {
                    $res = SessionModule::where('sessionn_id', $this->sessionn_id)->where('module_id', $this->module_id)->first();
                    $validatedData['formateur_id'] = $res->formateur_id;
                    $emploiTemp->update($validatedData);
                    Flux::toast(variant: 'success', text: 'Seance updated successfully.');
                }
            } else {
                $res = SessionModule::where('sessionn_id', $this->sessionn_id)->where('module_id', $this->module_id)->first();
                $validatedData['formateur_id'] = $res->formateur_id;
                EmploiTemp::create($validatedData);
                Flux::toast(variant: 'success', text: 'Seance created successfully.');
            }

            $this->resetForm();
            $this->refreshSeances();
            session()->flash('message', 'Seance saved successfully.');
        } catch (\Throwable $th) {
            Flux::toast(variant: 'error', text: 'An error occurred while saving the seance: ' . $th->getMessage());
        }
    }
    public function deleteSeance($id = null)
    {
        $emploiTempId = $id ?? $this->id;

        if ($emploiTempId) {
            $emploiTemp = EmploiTemp::find($emploiTempId);

            if ($emploiTemp) {
                $emploiTemp->delete();
                $this->resetForm();
                $this->refreshSeances();
                session()->flash(variant: 'message', text: 'Seance deleted successfully.');
            }
        }
    }
    public function modifier()
    {
        if ($this->id) {
            $emploiTemp = \App\Models\EmploiTemp::find($this->id);
            $emploiTemp->sessionn_id = $this->sessionn_id;
            $emploiTemp->module_id = $this->module_id;
            $emploiTemp->date = $this->date;
            $emploiTemp->jour = $this->jour;
            $emploiTemp->type = $this->type;
            $emploiTemp->heureDebut = $this->heureDebut;
            $emploiTemp->heureFin = $this->heureFin;
            $emploiTemp->salle_id = $this->salle_id;
            $emploiTemp->formateur_id = $this->formateur_id;

            $emploiTemp->save();

            session()->flash(variant: 'message', text: 'Seance updated successfully.');
        }
    }

    public function refre()
    {
        //      $salles = Salle::all();
        // $modules=Module::all();
        // $formateurs = Formateur::all();
        // $sessions = Sessionn::all();

        if ($this->module_id && $this->sessionn_id) {
            $res = SessionModule::where('sessionn_id', $this->sessionn_id)->where('module_id', $this->module_id)->first();
            if ($res) {
                # code...
                $this->formateur_id = $res->pluck('formateur_id');
            } else {
                // $this->formateur_id= '';
            }
        }
    }
    public function render()
    {

        $this->salles = Salle::all();
        // $this->modules = Module::all();
        $this->formateurs = Formateur::all();
        $this->sessions = Sessionn::all();
        if ($this->sessionn_id) {
            $this->modules = Sessionn::findOrFail($this->sessionn_id)->formation->modules; # code...
        }
        if ($this->module_id && $this->sessionn_id) {
            $res = SessionModule::where('sessionn_id', $this->sessionn_id)->where('module_id', $this->module_id)->first();
            if ($res) {
                # code...
                $this->formateur_id = $res->pluck('formateur_id');
            } else {
                $this->formateur_id = '';
            }
        }
        // if ($this->module_id && $this->sessionn_id) {
        //     $this->refre();
        // }


        return view('livewire.emploi-temp.edt-create');
    }
}
