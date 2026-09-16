<div>
    <div class="mb-6">
        <flux:heading size="xl" class="!text-emerald-800 font-bold">Attestations & Certificats</flux:heading>
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="#" icon="home" class="my-3" />
            <flux:breadcrumbs.item href="#">Attestations & Certificats</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="bg-white rounded-2xl shadow-lg shadow-emerald-900/5 ring-1 ring-emerald-950/5 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <flux:input kbd="⌘K" icon="magnifying-glass" placeholder="Rechercher un apprenant..."
                wire:model.live="search" class="sm:max-w-sm" />

            <flux:select wire:model.live="filtreStatut" class="sm:max-w-xs">
                <flux:select.option value="eligibles">Éligibles (non générés)</flux:select.option>
                <flux:select.option value="generes">Déjà générés</flux:select.option>
                <flux:select.option value="tous">Toutes les inscriptions évaluées</flux:select.option>
            </flux:select>
        </div>

        <div class="overflow-x-auto rounded-xl ring-1 ring-emerald-950/5">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Apprenant</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Formation</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Moyenne</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Mention</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Décision</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Document</flux:table.column>
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
                            <flux:table.cell>
                                {{ $inscription->resultatFormation?->moyenneGenerale ?? '—' }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $inscription->resultatFormation?->mention ?? '—' }}
                            </flux:table.cell>
                            <flux:table.cell>
                                @if ($inscription->resultatFormation)
                                    <span @class([
                                        'font-semibold text-xs tracking-wide uppercase rounded-full px-3 py-1 ring-1',
                                        'bg-emerald-50 text-emerald-700 ring-emerald-200' =>
                                            $inscription->resultatFormation->descision === 'Admis',
                                        'bg-red-50 text-red-700 ring-red-200' =>
                                            $inscription->resultatFormation->descision !== 'Admis',
                                    ])>
                                        {{ $inscription->resultatFormation->descision }}
                                    </span>
                                @else
                                    <span class="text-zinc-400 text-sm">En attente</span>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>
                                @if ($inscription->certificat)
                                    <span class="font-mono text-xs text-emerald-700">
                                        {{ $inscription->certificat->numero }}
                                    </span>
                                @else
                                    <span class="text-zinc-400 text-sm">Non généré</span>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell class="text-right whitespace-nowrap">
                                <div class="inline-flex gap-1.5">
                                    @if ($inscription->certificat)
                                        <flux:button wire:click="telecharger({{ $inscription->certificat->id }})"
                                            icon="arrow-down-tray" variant="primary" size="sm">
                                            Télécharger</flux:button>
                                        <flux:button wire:click="supprimer({{ $inscription->certificat->id }})"
                                            wire:confirm="Supprimer ce document ?" icon="trash" variant="danger"
                                            size="sm">
                                        </flux:button>
                                    @elseif ($inscription->resultatFormation)
                                        <flux:button
                                            wire:click="generer({{ $inscription->id }}, 'attestation')"
                                            icon="document-text" variant="primary" color="green" size="sm">
                                            Attestation</flux:button>
                                        <flux:button wire:click="generer({{ $inscription->id }}, 'certificat')"
                                            icon="academic-cap" size="sm">
                                            Certificat</flux:button>
                                    @else
                                        <span class="text-zinc-400 text-sm">—</span>
                                    @endif
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="7" class="text-center py-10 text-zinc-400">
                                Aucune inscription trouvée pour ce filtre.
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
</div>
