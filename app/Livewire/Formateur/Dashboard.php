<?php

namespace App\Livewire\Formateur;

use App\Models\EmploiTemp;
use App\Models\Formateur;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $formateur;

    // Statistiques
    public $nombreModules = 0;
    public $nombreCoursAujourdhui = 0;
    public $nombreApprenants = 0;
    public $evaluationsACorriger = 0;

    // Données
    public $emploiTemps = [];
    public $modules = [];
    public $prochainesEvaluations = [];

    // Graphiques
    public $chartModules = [];
    public $chartApprenants = [];

    public function mount()
    {
        $this->chargerFormateur();

        if (!$this->formateur) {
            return;
        }

        $this->chargerStatistiques();
        $this->chargerEmploiTemps();
        $this->chargerModules();
        $this->chargerEvaluations();
    }

    protected function chargerFormateur()
{
    $this->formateur = Formateur::with([
        'user',
        'sessionModule.module',
        'emploisTemps.salle',
        'emploisTemps.module',
    ])
    ->where('user_id', Auth::id())
    ->first();
}

protected function chargerStatistiques()
{
    $this->nombreModules = $this->formateur
        ->sessionModule
        ->pluck('module_id')
        ->unique()
        ->count();

    $this->nombreCoursAujourdhui = EmploiTemp::where('formateur_id', $this->formateur->id)
        ->whereDate('date', today())
        ->count();

    $sessionIds = $this->formateur
        ->sessionModule
        ->pluck('sessionn_id')
        ->unique();

    $this->nombreApprenants = \App\Models\Inscription::whereIn(
        'sessionn_id',
        $sessionIds
    )->count();

    $this->evaluationsACorriger = 0;
}
protected function chargerModules()
{
    $modules = $this->formateur
        ->sessionModule;

    $this->modules = $modules
        ->map(function ($module) {

            return [

                'nom' => $module->module->nom,

                'progression' => $module->progress,

                'statut' => $module->statut,

                'formation' => optional(
                    $module->sessionn->formation
                )->nom,

            ];

        })
        ->toArray();

    $this->chartModules = [

        'labels' => collect($this->modules)
            ->pluck('nom'),

        'values' => collect($this->modules)
            ->pluck('progression'),

    ];
}
protected function chargerEvaluations()
{
    $evaluations = collect();

    foreach ($this->formateur->sessionModule as $module) {

        foreach ($module->evaluations as $evaluation) {

            $evaluations->push([

                'module' => $module->module->nom,

                'date' => $evaluation->dateEvaluation,

                'heure' => $evaluation->heureDebut,

                'id' => $evaluation->id,

            ]);

        }

    }

    $this->prochainesEvaluations = $evaluations
        ->sortBy('date')
        ->take(5)
        ->values()
        ->toArray();
}
protected function chargerEmploiTemps()
{
    $this->emploiTemps = EmploiTemp::with([
        'module',
        'salle'
    ])
    ->where('formateur_id', $this->formateur->id)
    ->whereDate('date', Carbon::today())
    ->orderBy('heureDebut')
    ->get()
    ->map(function ($cours) {

        return [

            'module' => $cours->module->nom,

            'salle' => $cours->salle->nom,

            'debut' => $cours->heureDebut,

            'fin' => $cours->heureFin,

            'type' => $cours->type,

        ];

    })
    ->toArray();
}

    public function render()
    {
        return view('livewire.formateur.dashboard');
    }

}
