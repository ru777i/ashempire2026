<?php

namespace App\Livewire\Apprenant;

use App\Models\Apprenant;
use App\Models\Evaluation;
use App\Models\Inscription;
use App\Models\Note;
use App\Models\SessionModule;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MesNotes extends Component
{
    public $evaluations = [];
    public $notes = [];
    public $evaluationId;
    public function render()
    {

        if (Auth::check() && Auth::user()->role =='apprenant') {

            $idInscriptions = Inscription::where('apprenant_id', Auth::user()->apprenant->id)->pluck('id');
                $ids = Inscription::where('apprenant_id', Auth::user()->apprenant->id)->pluck('sessionn_id')->unique('sessionn_id');
                $idSm = SessionModule::whereIn('sessionn_id', $ids)->pluck('id');
          
            $this->evaluations = Evaluation::whereIn('sessionn_module_id', $idSm)->get();
            $idIn= Auth::user()->apprenant->inscriptions->pluck('id');

            $this->notes =  Note::whereIn('inscription_id', Auth::user()->apprenant->inscriptions()->pluck('id'))->get();

            // if ($this->evaluationId) {
            //     $notes = Note::where('evaluation_id', $this->evaluationId)->where('inscription_id',Inscription::where('apprenant_id',Auth::user()->aprenant->id)->first());
            // }
        }
        // $this->evaluations=;
        return view('livewire.apprenant.mes-notes');
    }
}
