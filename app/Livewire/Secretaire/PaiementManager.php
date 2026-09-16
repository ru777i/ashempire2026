<?php

namespace App\Livewire\Secretaire;

use App\Models\Inscription;
use App\Models\Paiement;
use App\Notifications\PaiementEnregistreNotification;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PaiementManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filtreSolde = 'tous'; // tous | soldes | restants

    public ?int $inscriptionSelectionneeId = null;

    public $montant = '';
    public $methode = 'espece';
    public $reference = '';
    public $datePaiement = '';
    public $note = '';

    public bool $modalOuverte = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function ouvrirModal(int $inscriptionId)
    {
        $this->inscriptionSelectionneeId = $inscriptionId;
        $this->reset(['montant', 'methode', 'reference', 'note']);
        $this->methode = 'espece';
        $this->datePaiement = now()->format('Y-m-d');
        $this->modalOuverte = true;
    }

    public function enregistrerPaiement()
    {
        $validated = $this->validate([
            'montant' => ['required', 'numeric', 'min:1'],
            'methode' => ['required', 'in:espece,mobile_money,virement,cheque'],
            'reference' => ['nullable', 'string', 'max:100'],
            'datePaiement' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $inscription = Inscription::with('aprenant.user')->findOrFail($this->inscriptionSelectionneeId);

        $solde = $inscription->soldeRestant();

        if ($validated['montant'] > $solde && $solde > 0) {
            Flux::toast(
                variant: 'error',
                text: "Le montant dépasse le solde restant ({$solde})."
            );

            return;
        }

        $paiement = Paiement::create([
            'inscription_id' => $inscription->id,
            'montant' => $validated['montant'],
            'methode' => $validated['methode'],
            'reference' => $validated['reference'],
            'datePaiement' => $validated['datePaiement'],
            'statut' => 'valide',
            'note' => $validated['note'],
            'enregistre_par' => Auth::id(),
        ]);

        if ($inscription->aprenant?->user) {
            $inscription->aprenant->user->notify(new PaiementEnregistreNotification($paiement));
        }

        Flux::toast(
            variant: 'success',
            text: 'Paiement enregistré avec succès.'
        );

        $this->modalOuverte = false;
        $this->reset(['montant', 'methode', 'reference', 'note', 'inscriptionSelectionneeId']);
    }

    public function annulerPaiement(int $paiementId)
    {
        $paiement = Paiement::findOrFail($paiementId);
        $paiement->update(['statut' => 'annule']);

        Flux::toast(
            variant: 'success',
            text: 'Paiement annulé.'
        );
    }

    public function render()
    {
        $query = Inscription::with(['aprenant.user', 'sessionn.formation', 'paiements']);

        if ($this->search !== '') {
            $query->whereHas('aprenant.user', function ($q) {
                $q->where('name', 'like', "%{$this->search}%");
            })->orWhereHas('aprenant', function ($q) {
                $q->where('matricule', 'like', "%{$this->search}%");
            });
        }

        $inscriptions = $query->latest()->paginate(10);

        if ($this->filtreSolde !== 'tous') {
            $inscriptions->setCollection(
                $inscriptions->getCollection()->filter(function ($inscription) {
                    return $this->filtreSolde === 'soldes'
                        ? $inscription->estSolde()
                        : !$inscription->estSolde();
                })
            );
        }

        $inscriptionSelectionnee = $this->inscriptionSelectionneeId
            ? Inscription::with('aprenant.user')->find($this->inscriptionSelectionneeId)
            : null;

        return view('livewire.secretaire.paiement-manager', [
            'inscriptions' => $inscriptions,
            'methodes' => Paiement::getMethodes(),
            'inscriptionSelectionnee' => $inscriptionSelectionnee,
        ]);
    }
}
