<div>
    <div class="mb-6">
        <flux:heading size="xl" class="!text-emerald-800 font-bold">Paiements & Facturation</flux:heading>
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="#" icon="home" class="my-3" />
            <flux:breadcrumbs.item href="#">Paiements & Facturation</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="bg-white rounded-2xl shadow-lg shadow-emerald-900/5 ring-1 ring-emerald-950/5 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <flux:input kbd="⌘K" icon="magnifying-glass" placeholder="Rechercher un apprenant..."
                wire:model.live="search" class="sm:max-w-sm" />

            <flux:select wire:model.live="filtreSolde" class="sm:max-w-xs">
                <flux:select.option value="tous">Toutes les inscriptions</flux:select.option>
                <flux:select.option value="soldes">Soldées</flux:select.option>
                <flux:select.option value="restants">Solde restant</flux:select.option>
            </flux:select>
        </div>

        <div class="overflow-x-auto rounded-xl ring-1 ring-emerald-950/5">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Apprenant</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Formation</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Montant total</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Payé</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Solde</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold text-right">Action</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse ($inscriptions as $inscription)
                        <flux:table.row class="hover:bg-emerald-50/60 transition-colors">
                            <flux:table.cell class="font-medium text-zinc-800">
                                {{ $inscription->aprenant->user->name ?? '' }}
                                {{ $inscription->aprenant->prenom ?? '' }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $inscription->sessionn->formation->nom ?? '' }}
                            </flux:table.cell>
                            <flux:table.cell>{{ number_format($inscription->montantTotal, 0) }}</flux:table.cell>
                            <flux:table.cell class="text-emerald-700 font-semibold">
                                {{ number_format($inscription->totalPaye(), 0) }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <span @class([
                                    'font-semibold text-xs tracking-wide uppercase rounded-full px-3 py-1 ring-1',
                                    'bg-emerald-50 text-emerald-700 ring-emerald-200' => $inscription->estSolde(),
                                    'bg-amber-50 text-amber-700 ring-amber-200' => !$inscription->estSolde(),
                                ])>
                                    {{ $inscription->estSolde() ? 'Soldé' : number_format($inscription->soldeRestant(), 0) }}
                                </span>
                            </flux:table.cell>
                            <flux:table.cell class="text-right">
                                @unless ($inscription->estSolde())
                                    <flux:button wire:click="ouvrirModal({{ $inscription->id }})" icon="plus"
                                        variant="primary" color="green" size="sm">
                                        Encaisser</flux:button>
                                @else
                                    <span class="text-zinc-400 text-sm">—</span>
                                @endunless
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="6" class="text-center py-10 text-zinc-400">
                                Aucune inscription trouvée.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <div class="mt-4">
            {{ $inscriptions->links() }}
        </div>
    </div>

    <flux:modal wire:model="modalOuverte" class="md:max-w-md">
        <form wire:submit.prevent="enregistrerPaiement" class="space-y-6">
            <div>
                <flux:heading size="lg" class="!text-emerald-800">Enregistrer un paiement</flux:heading>
                @if ($inscriptionSelectionnee)
                    <flux:text class="mt-1">
                        Pour {{ $inscriptionSelectionnee->aprenant->user->name ?? '' }}
                        {{ $inscriptionSelectionnee->aprenant->prenom ?? '' }} — Solde restant :
                        <span class="font-semibold text-emerald-700">
                            {{ number_format($inscriptionSelectionnee->soldeRestant(), 0) }}
                        </span>
                    </flux:text>
                @endif
            </div>

            <flux:input wire:model="montant" type="number" step="0.01" label="Montant" icon="banknotes" />

            <flux:select wire:model="methode" label="Méthode de paiement">
                @foreach ($methodes as $valeur => $libelle)
                    <flux:select.option value="{{ $valeur }}">{{ $libelle }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:input wire:model="reference" label="Référence (optionnel)"
                placeholder="N° reçu, transaction..." />

            <flux:input wire:model="datePaiement" type="date" label="Date du paiement" />

            <flux:textarea wire:model="note" label="Note (optionnel)" rows="2" />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button>Annuler</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" color="green" icon="check">
                    Valider le paiement</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
