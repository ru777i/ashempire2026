<?php

namespace App\Livewire\Evaluation;

use App\Models\Evaluation;
use App\Models\Formateur;
use App\Models\Inscription;
use App\Models\Module;
use App\Models\Note;
use App\Models\SessionModule;
use App\Models\Sessionn;
use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateNote extends Component
{
   public $evaluations = [];
    public $sessionModules = [];

    public $selectedEvaluationId = '';

    public $apprenants = [];
    public $notes = [];

    #[Validate('required|date')]
    public $dateEvaluation;

    #[Validate('required')]
    public $heureDebut;

    #[Validate('required|after:heureDebut')]
    public $heureFin;

    #[Validate('required|numeric')]
    public $noteMax;

    public $sessionn_module_id = '';

    public function mount()
    {
        $formateur = Auth::user()->formateur;

        $this->sessionModules = SessionModule::where(
            'formateur_id',
            $formateur->id
        )->get();

        $ids = $this->sessionModules->pluck('id');

        $this->evaluations = Evaluation::with('sessionModule')
            ->whereIn('sessionn_module_id', $ids)
            ->get();
    }

    public function updatedSelectedEvaluationId()
    {
        $this->apprenants = [];
        $this->notes = [];

        if (!$this->selectedEvaluationId) {
            return;
        }

        $evaluation = Evaluation::with([
            'sessionModule.sessionn.inscriptions'
        ])->find($this->selectedEvaluationId);

        if (!$evaluation) {
            return;
        }

        $this->apprenants = $evaluation
            ->sessionModule
            ->sessionn
            ->inscriptions;

        // Initialiser toutes les notes
        foreach ($this->apprenants as $inscription) {
            $this->notes[$inscription->id] = [
                'note' => ''
            ];
        }

        // Charger les notes existantes
        $notes = Note::where(
            'evaluation_id',
            $this->selectedEvaluationId
        )->get();

        foreach ($notes as $note) {
            $this->notes[$note->inscription_id] = [
                'note' => $note->note
            ];
        }
    }

    public function saveEvaluation()
    {
        $this->validate([
            'dateEvaluation' => 'required|date',
            'noteMax' => 'required|numeric',
            'sessionn_module_id' => 'required',
            'heureDebut' => 'required',
            'heureFin' => 'required|after:heureDebut',
        ]);

        try {

            Evaluation::create([
                'dateEvaluation' => $this->dateEvaluation,
                'noteMax' => $this->noteMax,
                'sessionn_module_id' => $this->sessionn_module_id,
                'heureDebut' => $this->heureDebut,
                'heureFin' => $this->heureFin,
            ]);

            Flux::toast(
                variant: 'success',
                text: 'Évaluation créée avec succès.'
            );

            $this->reset([
                'dateEvaluation',
                'noteMax',
                'heureDebut',
                'heureFin',
                'sessionn_module_id',
            ]);

            // Rafraîchir la liste
            $this->mount();

        } catch (\Throwable $e) {

            Flux::toast(
                variant: 'error',
                text: $e->getMessage()
            );
        }
    }

    public function saveNotes()
    {
        if (!$this->selectedEvaluationId) {

            Flux::toast(
                variant: 'error',
                text: 'Veuillez sélectionner une évaluation.'
            );

            return;
        }

        try {

            foreach ($this->apprenants as $apprenant) {

                $noteValue = $this->notes[$apprenant->id]['note'] ?? null;

                if ($noteValue === null || $noteValue === '') {
                    continue;
                }

                $noteValue = (float) $noteValue;

                $mention = match (true) {

                    $noteValue >= 16 => 'Très bien',
                    $noteValue >= 14 => 'Bien',
                    $noteValue >= 12 => 'Assez bien',
                    $noteValue >= 10 => 'Passable',
                    default => 'Insuffisant',
                };

                Note::updateOrCreate(

                    [
                        'evaluation_id' => $this->selectedEvaluationId,
                        'inscription_id' => $apprenant->id,
                    ],

                    [
                        'note' => $noteValue,
                        'mention' => $mention,
                    ]

                );
            }

            Flux::toast(
                variant: 'success',
                text: 'Notes enregistrées avec succès.'
            );

        } catch (\Throwable $e) {

            Flux::toast(
                variant: 'error',
                text: $e->getMessage()
            );
        }
    }

    public function render()
    {
        return view('livewire.evaluation.create-note');
    }
}