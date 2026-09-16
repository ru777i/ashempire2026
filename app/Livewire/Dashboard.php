<?php

namespace App\Livewire;

use App\Models\Formation;
use App\Models\Inscription;
use App\Models\Paiement;
use App\Models\Presences;
use App\Models\ResultatFinal;
use App\Models\Sessionn;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public $formationsEncous;
    public $sesionEncours;
    public $inscriptionsEncours;
    public $derniersInsription;

    public $labels = [];
    public $data = [];

    public function mount()
    {
        $this->chargerStatistiques();
    }
    public function getNombreSessionEnCours()
    {
        return Sessionn::where('statut', 'En cours')->count();
    }
    public function getNombreApprenantsFormation()
    {
        return Inscription::whereHas('sessionn', function ($query) {
            $query->whereDate('dateDebut', '<=', Carbon::today())
                ->whereDate('dateFin', '>=', Carbon::today());
        })->count();
    }
    // public function getNombreSessionEnCours()
    // {
    //     return Sessionn::whereDate('dateDebut', '<=', Carbon::today())
    //         ->whereDate('dateFin', '>=', Carbon::today())
    //         ->count();
    // }

    public function chargerStatistiques()
    {
        $formations = Formation::withCount('inscriptions')->get();

        $this->labels = $formations->pluck('nom')->toArray();
        $this->data = $formations->pluck('inscriptions_count')->toArray();
    }
    public function donneesChartFormation()
    {
        $formations = Formation::withCount('inscriptions')
            ->select('id', 'nom')
            ->get();

        return [
            'labels' => $formations->pluck('nom')->toArray(),
            'data'   => $formations->pluck('inscriptions_count')->toArray(),
        ];
    }
    public function getRevenusTotal(): float
    {
        return (float) Paiement::where('statut', 'valide')->sum('montant');
    }

    public function getRevenusEnAttente(): float
    {
        $totalAttendu = (float) Inscription::sum('montantTotal');

        return max(0, $totalAttendu - $this->getRevenusTotal());
    }

    public function getTauxPresence(): float
    {
        $total = Presences::count();

        if ($total === 0) {
            return 0;
        }

        $presents = Presences::whereIn('statut', ['present', 'retard'])->count();

        return round(($presents / $total) * 100, 1);
    }

    public function getTauxReussite(): float
    {
        $total = ResultatFinal::count();

        if ($total === 0) {
            return 0;
        }

        $admis = ResultatFinal::where('descision', 'Admis')->count();

        return round(($admis / $total) * 100, 1);
    }

    public function render()
    {
        $this->sesionEncours = Sessionn::where('statut', 'en cours')->get();
        $this->inscriptionsEncours = Inscription::where('statut', 'En formation')->get();
        $inscriptions = Inscription::orderBy('created_at', 'asc')->paginate(4);
        $nbA=$this->getNombreApprenantsFormation();
        $nbS=$this->getNombreSessionEnCours();
        $revenusTotal = $this->getRevenusTotal();
        $revenusEnAttente = $this->getRevenusEnAttente();
        $tauxPresence = $this->getTauxPresence();
        $tauxReussite = $this->getTauxReussite();

        return view('dashboard', compact(
            'inscriptions',
            'nbA',
            'nbS',
            'revenusTotal',
            'revenusEnAttente',
            'tauxPresence',
            'tauxReussite'
        ));
    }
}
