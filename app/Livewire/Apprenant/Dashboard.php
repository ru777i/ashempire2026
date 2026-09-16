<?php

namespace App\Livewire\Apprenant;

use App\Models\Inscription;
use App\Models\Note;
use App\Models\Presences;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $apprenant;
    public $inscription;

    // Cartes
    public $formation;
    public $moyenne = 0;
    public $progression = 0;
    public $totalModules = 0;
    public $modulesTermines = 0;
    public $tauxPresence = 0;

    // Graphiques
    public $chartNotes = [];
    public $chartPresence = [];

    // Données
    public $modules = [];
    public $dernieresNotes = [];
    public $prochainesEvaluations = [];
    public $emploiTemps = [];

    public function mount()
    {
        $this->chargerInscription();

        if (!$this->inscription) {
            return;
        }

        $this->chargerStatistiques();
        $this->chargerModules();
        $this->chargerNotes();
        $this->chargerPresences();
        $this->chargerEvaluations();
    }
    protected function chargerInscription()
{
    $this->apprenant = Auth::user()->apprenant;

    $this->inscription = Inscription::with([
        'sessionn.formation',
        'sessionn.sessionnModule.module',
        'notes.evaluation.sessionModule.module',
        'presences'
    ])
    ->where('apprenant_id', $this->apprenant->id)
    ->first();

    if ($this->inscription) {
        $this->formation = $this->inscription->sessionn->formation->nom;
    }
}

protected function chargerStatistiques()
{
    $notes = Inscription::with([
        'sessionn.formation',
        'sessionn.sessionnModule.module',
        'notes.evaluation.sessionModule.module',
        'presences'
    ])
    ->where('apprenant_id', $this->apprenant->id)
    ->first()->notes;

    $this->moyenne = round(
        $notes->whereNotNull('note')->avg('note') ?? 0,
        2
    );

    $modules = Inscription::with([
        'sessionn.formation',
        'sessionn.sessionnModule.module',
        'notes.evaluation.sessionModule.module',
        'presences'
    ])
    ->where('apprenant_id', $this->apprenant->id)
    ->first()
        ->sessionn
        ->sessionnModule;

    $this->totalModules = $modules->count();

    $this->modulesTermines = $modules
        ->where('progress', '>=', 100)
        ->count();

    $this->progression = $this->totalModules > 0
        ? round(($this->modulesTermines / $this->totalModules) * 100)
        : 0;
}

protected function chargerModules()
{
    $this->modules = $this->inscription
        ->sessionn
        ->sessionnModule
        ->map(function ($module) {

            return [

                'nom' => $module->module->nom,

                'progression' => $module->progress,

                'volume' => $module->module->volumeHoraire,

                'statut' => $module->statut,

                'formateur' => optional(
                    optional($module->formateur)->user
                )->name,

            ];

        })->toArray();
}

protected function chargerNotes()
{
    $notes = $this->inscription
        ->notes
        ->sortByDesc('created_at');

    $this->dernieresNotes = $notes
        ->take(5)
        ->map(function ($note) {

            return [

                'module' => $note->evaluation
                    ->sessionModule
                    ->module
                    ->nom,

                'note' => $note->note,

                'date' => $note->evaluation->dateEvaluation,

            ];

        })->values()->toArray();

    $this->chartNotes = [

        'labels' => $notes->pluck(
            'evaluation.sessionModule.module.nom'
        ),

        'values' => $notes->pluck('note'),

    ];
}

protected function chargerPresences()
{
    $presences = $this->inscription->presences;

    $total = $presences->count();

    $present = $presences
        ->where('statut', Presences::PRESENT)
        ->count();

    $retard = $presences
        ->where('statut', Presences::RETARD)
        ->count();

    $absent = $presences
        ->where('statut', Presences::ABSENT)
        ->count();

    $this->tauxPresence = $total > 0
        ? round(($present / $total) * 100)
        : 0;

    $this->chartPresence = [

        'present' => $present,

        'retard' => $retard,

        'absent' => $absent,

    ];
}

protected function chargerEvaluations()
{
    $this->prochainesEvaluations = $this->inscription
        ->sessionn
        ->sessionnModule
        ->flatMap(function ($module) {

            return $module->evaluations;

        })
        ->where('dateEvaluation', '>=', now())
        ->sortBy('dateEvaluation')
        ->take(5)
        ->map(function ($evaluation) {

            return [

                'module' => $evaluation
                    ->sessionModule
                    ->module
                    ->nom,

                'date' => $evaluation->dateEvaluation,

                'heure' => $evaluation->heureDebut,

            ];

        })
        ->values()
        ->toArray();
}

    public function render()
    {
        return view('livewire.apprenant.dashboard');
    }
}
