<div>
    <div class="mb-6">
        <flux:heading size="xl" class="!text-emerald-800 font-bold">Sessions</flux:heading>
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="#" icon="home" class="my-3" />
            <flux:breadcrumbs.item href="{{ route('sessions') }}">Sessions</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#">Ajouter</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="bg-white rounded-2xl shadow-lg shadow-emerald-900/5 ring-1 ring-emerald-950/5 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <flux:input kbd="⌘K" icon="magnifying-glass" placeholder="Rechercher une session..."
                wire:model.live='search' class="sm:max-w-sm" />

            <flux:button variant="primary" href="{{ route('sessionCreate') }}" type="submit" icon="plus"
                color="green" class="shadow-md shadow-emerald-600/20">Ajouter</flux:button>
        </div>

        <div class="overflow-x-auto rounded-xl ring-1 ring-emerald-950/5">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Code</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Formation</flux:table.column>
                    @if (Auth::check() && Auth::user()->role == 'secretaire')
                        <flux:table.column class="!text-emerald-700 !font-semibold">Module</flux:table.column>
                    @endif
                    <flux:table.column class="!text-emerald-700 !font-semibold">Annee Academique</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Status</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Periode</flux:table.column>
                    <flux:table.column class="!text-emerald-700 !font-semibold">Capacite</flux:table.column>
                    @if (Auth::check() && Auth::user()->role == 'secretaire')
                        <flux:table.column class="!text-emerald-700 !font-semibold text-right">Action</flux:table.column>
                    @endif
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($sessions as $sessionp)
                        <flux:table.row class="hover:bg-emerald-50/60 transition-colors">
                            <flux:table.cell class="font-mono text-sm text-zinc-500">
                                {{ $sessionp->code }}
                            </flux:table.cell>
                            <flux:table.cell class="font-medium text-zinc-800">
                                {{ $sessionp->formation->nom ?? '' }}
                            </flux:table.cell>

                            @if (Auth::check() && Auth::user()->role == 'secretaire')
                                <flux:table.cell>
                                    <flux:modal.trigger name="edit-profile-{{ $sessionp->id }}">
                                        <flux:button icon="eye" size="sm"></flux:button>
                                    </flux:modal.trigger>
                                    <flux:modal name="edit-profile-{{ $sessionp->id }}" class="lg:max-w-7xl">
                                        <flux:heading class="!text-emerald-800">Liste des modules affectés à la
                                            session de formation</flux:heading>
                                        <flux:spacer />
                                        <div class="overflow-x-auto rounded-xl ring-1 ring-emerald-950/5 mt-4">
                                            <flux:table>
                                                <flux:table.columns>
                                                    <flux:table.column>Nom</flux:table.column>
                                                    <flux:table.column>Volume Horaire</flux:table.column>
                                                    <flux:table.column>Formateur</flux:table.column>
                                                    <flux:table.column>Statut</flux:table.column>
                                                    <flux:table.column>Progression</flux:table.column>
                                                    <flux:table.column class="text-right">Action</flux:table.column>
                                                </flux:table.columns>
                                                <flux:table.rows>
                                                    @forelse ($sessionp->sessionnModule as $sM)
                                                        <flux:table.row class="hover:bg-emerald-50/60">
                                                            <flux:table.cell class="font-medium">
                                                                {{ $sM->module->nom }}
                                                            </flux:table.cell>
                                                            <flux:table.cell>
                                                                {{ $sM->module->volumeHoraire }}
                                                            </flux:table.cell>
                                                            <flux:table.cell>
                                                                @if (!$sM->formateur)
                                                                    <flux:modal.trigger
                                                                        name="assign-formateur-{{ $sM->id }}">
                                                                        <flux:button size="sm" variant="primary">
                                                                            Assigner un formateur</flux:button>
                                                                    </flux:modal.trigger>
                                                                @else
                                                                    <flux:text>{{ $sM->formateur->user->name }}
                                                                    </flux:text>
                                                                @endif

                                                                <flux:modal name="assign-formateur-{{ $sM->id }}"
                                                                    flyout>
                                                                    <form
                                                                        wire:submit.prevent="AssignerFormateur({{ $sM->id }})"
                                                                        class="space-y-6">
                                                                        <div>
                                                                            <flux:heading size="lg"
                                                                                class="!text-emerald-800">Assigner un
                                                                                professeur à ce module
                                                                            </flux:heading>
                                                                        </div>

                                                                        <flux:select wire:model="selctFormateur"
                                                                            placeholder="Selectionner un formateur"
                                                                            label="Formateur">
                                                                            @foreach (\App\Models\Formateur::all() as $formateur)
                                                                                <flux:select.option
                                                                                    value="{{ $formateur->id }}">
                                                                                    {{ $formateur->user->name }}
                                                                                </flux:select.option>
                                                                            @endforeach
                                                                        </flux:select>

                                                                        <div class="flex">
                                                                            <flux:spacer />
                                                                            <flux:button type="submit"
                                                                                variant="primary" color="green">
                                                                                Enregistrer
                                                                            </flux:button>
                                                                        </div>
                                                                    </form>
                                                                </flux:modal>
                                                            </flux:table.cell>
                                                            <flux:table.cell>
                                                                {{ $sM->statut }}
                                                            </flux:table.cell>
                                                            <flux:table.cell>
                                                                @php
                                                                    $pct = round(
                                                                        ($sM->progress / $sM->module->volumeHoraire) *
                                                                            100,
                                                                        2,
                                                                    );
                                                                @endphp
                                                                <div class="flex items-center gap-2">
                                                                    <div
                                                                        class="w-20 h-2 rounded-full bg-emerald-100 overflow-hidden">
                                                                        <div class="h-full bg-emerald-500 transition-all duration-500"
                                                                            style="width: {{ min($pct, 100) }}%">
                                                                        </div>
                                                                    </div>
                                                                    <span
                                                                        class="font-semibold text-emerald-600 text-sm">{{ $pct }}%</span>
                                                                </div>
                                                            </flux:table.cell>
                                                            <flux:table.cell class="text-right">
                                                                <flux:button color="red" icon="trash" size="sm">
                                                                    Supprimer
                                                                </flux:button>
                                                            </flux:table.cell>
                                                        </flux:table.row>
                                                    @empty
                                                        <flux:table.row>
                                                            <flux:table.cell colspan="6"
                                                                class="text-center py-6 text-zinc-400">Aucun module
                                                                trouvé</flux:table.cell>
                                                        </flux:table.row>
                                                    @endforelse
                                                </flux:table.rows>
                                            </flux:table>
                                        </div>
                                        <flux:separator class="mt-4" />

                                        <div class="mt-4 flex justify-end">
                                            <flux:modal.close>
                                                <flux:button icon="x-mark">Fermer</flux:button>
                                            </flux:modal.close>
                                        </div>
                                    </flux:modal>
                                </flux:table.cell>
                            @endif

                            <flux:table.cell class="text-zinc-500 text-sm">
                                {{ $sessionp->annee->dateDebut->format('Y') }} -
                                {{ $sessionp->annee->dateFin->format('Y') }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <span
                                    class="font-semibold text-xs tracking-wide uppercase rounded-full px-3 py-1 ring-1 bg-emerald-50 text-emerald-700 ring-emerald-200">
                                    {{ $sessionp->statut }}
                                </span>
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-500 text-sm">
                                {{ $sessionp->dateDebut->format('Y/m/d') }} -
                                {{ $sessionp->dateFin->format('Y/m/d') }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $sessionp->capacite }}
                            </flux:table.cell>
                            @if (Auth::check() && Auth::user()->role == 'secretaire')
                                <flux:table.cell class="text-right whitespace-nowrap">
                                    <div class="inline-flex gap-1.5">
                                        <flux:button wire:click="suprimer({{ $sessionp->id }})"
                                            class="hover:bg-red-100!" size="sm" :loading="true">
                                            <x-heroicon-o-trash class="w-4 h-4 text-red-700" />
                                        </flux:button>
                                        <flux:button wire:click="modifier({{ $sessionp->id }})"
                                            class="hover:bg-emerald-100!" size="sm" :loading="true">
                                            <x-heroicon-o-pencil class="w-4 h-4 text-emerald-700" />
                                        </flux:button>
                                    </div>
                                </flux:table.cell>
                            @endif
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>
    </div>
</div>
