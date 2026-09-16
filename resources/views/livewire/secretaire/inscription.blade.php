<div>

    <!-- En-tête -->
    <div class="mb-6">
        <flux:heading size="xl" class="!text-emerald-800 font-bold">Inscriptions</flux:heading>
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="/" icon="home" class="my-3" />
            <flux:breadcrumbs.item href="#">Inscriptions</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="bg-white rounded-2xl shadow-lg shadow-emerald-900/5 ring-1 ring-emerald-950/5 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <flux:input kbd="⌘K" icon="magnifying-glass" placeholder="Rechercher un apprenant..."
                wire:model.live='search' class="sm:max-w-sm" />
            <flux:button href="{{ route('ajouterInscription') }}" variant="primary" color="green" icon="plus"
                class="shadow-md shadow-emerald-600/20">
                Inscrire</flux:button>
        </div>

        <div class="overflow-x-auto rounded-xl ring-1 ring-emerald-950/5">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Matricule</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Nom &amp; Prénom</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Sexe</flux:table.column>
                    {{-- <flux:table.column>Date de naissance</flux:table.column> --}}
                    <flux:table.column class="!text-emerald-700 !font-semibold">Formation</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Session</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Statut</flux:table.column>

                    <flux:table.column class="!text-emerald-700 !font-semibold text-right">Action</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse ($inscriptions as $inscription)
                        <flux:table.row class="hover:bg-emerald-50/60 transition-colors">
                            <flux:table.cell class="font-mono text-sm text-zinc-500">
                                {{ $inscription->aprenant->matricule ?? '' }}</flux:table.cell>
                            <flux:table.cell class="font-medium text-zinc-800">
                                {{ $inscription->aprenant->user->name ?? '' }}
                                {{ $inscription->aprenant->prenom }}</flux:table.cell>
                            <flux:table.cell> {{ $inscription->aprenant->sexe == 'M' ? 'Masculin' : 'Feminin' ?? '' }}
                            </flux:table.cell>
                            {{-- <flux:table.cell>{{ $inscription->aprenant->dateNaissance ?? '' }}</flux:table.cell> --}}
                            <flux:table.cell>{{ $inscription->sessionn->formation->nom }}</flux:table.cell>
                            <flux:table.cell class="text-zinc-500 text-sm">
                                {{ $inscription->sessionn->dateDebut->format('Y/m/d') ?? '' }} Au
                                {{ $inscription->sessionn->dateFin->format('Y/m/d') ?? '' }}</flux:table.cell>
                            <flux:table.cell> <span @class([
                                'font-semibold text-xs tracking-wide uppercase rounded-full px-3 py-1 ring-1',

                                'bg-yellow-50 text-yellow-700 ring-yellow-200' => $inscription->statut === 'En attente',

                                'bg-emerald-50 text-emerald-700 ring-emerald-200' => $inscription->statut === 'Validée',

                                'bg-red-50 text-red-700 ring-red-200' => $inscription->statut === 'Refusée',

                                'bg-blue-50 text-blue-700 ring-blue-200' => $inscription->statut === 'En formation',

                                'bg-purple-50 text-purple-700 ring-purple-200' => $inscription->statut === 'Terminée',

                                'bg-gray-100 text-gray-600 ring-gray-200' => $inscription->statut === 'Abandonnée',
                            ])>
                                    {{ $inscription->statut }}
                                </span>
                            </flux:table.cell>
                            <flux:table.cell class="text-right whitespace-nowrap">
                                <div class="inline-flex gap-1.5">
                                    <flux:button href="{{ route('formateurs', $inscription->id) }}" icon="eye"
                                        variant="primary" size="sm"></flux:button>

                                    <flux:button href="{{ route('ajouterInscription', $inscription->id) }}"
                                        icon="pencil" size="sm">
                                    </flux:button>

                                    <flux:button wire:click="delete({{ $inscription->id }})" icon="trash"
                                        variant="danger" size="sm">
                                    </flux:button>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="7" class="text-center py-10 text-zinc-400">
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
    {{-- He who is contented is rich. - Laozi --}}
</div>
