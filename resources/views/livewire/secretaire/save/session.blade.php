<div>
    <div class="">
        <div class="">
            <flux:heading size="xl">Sessions</flux:heading>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#" icon="home" class=" my-3" />
                <flux:breadcrumbs.item href="{{ route('sessions') }}">Sessions</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="#">Ajouter </flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
        <div class=" flex justify-between gap-4">
            <flux:input kbd="⌘K" icon="magnifying-glass" placeholder="Search..." wire:model.live='search' />

            <flux:button variant="primary" href="{{ route('sessionCreate') }}" type="submit" icon="plus"
                color="green" class=" ">Ajouter</flux:button>

        </div>

        <div>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Code</flux:table.column>
                    <flux:table.column>Formation</flux:table.column>
                    @if (Auth::check() && Auth::user()->role == 'secretaire')
                        <flux:table.column>Module</flux:table.column>
                    @endif
                    <flux:table.column>Annee Academique</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column>Periode</flux:table.column>
                    <flux:table.column>capacite</flux:table.column>
                    @if (Auth::check() && Auth::user()->role == 'secretaire')
                        <flux:table.column>Action</flux:table.column>
                    @endif

                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($sessions as $sessionp)
                        <flux:table.row>
                            <flux:table.cell>
                                {{ $sessionp->code }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $sessionp->formation->nom ?? '' }}
                            </flux:table.cell>

                            @if (Auth::check() && Auth::user()->role == 'secretaire')
                                <flux:table.cell>
                                    <flux:modal.trigger name="edit-profile-{{ $sessionp->id }}">
                                        <flux:button icon="eye"></flux:button>
                                    </flux:modal.trigger>
                                    <flux:modal name="edit-profile-{{ $sessionp->id }}" class="lg:max-w-7xl">
                                        <flux:heading>Liste des modules affecter a la session formation</flux:heading>
                                        <flux:spacer />
                                        <flux:table>
                                            <flux:table.columns>
                                                <flux:table.column>Nom</flux:table.column>
                                                <flux:table.column> Volume Horaire</flux:table.column>
                                                <flux:table.column> Formateur</flux:table.column>
                                                <flux:table.column>Staut</flux:table.column>
                                                <flux:table.column>progression</flux:table.column>
                                                <flux:table.column>Action</flux:table.column>
                                            </flux:table.columns>
                                            <flux:table.rows>
                                                @forelse ($sessionp->sessionnModule as $sM)
                                                    <flux:table.row>
                                                        <flux:table.cell>
                                                            {{ $sM->module->nom }}
                                                        </flux:table.cell>
                                                        <flux:table.cell>
                                                            {{ $sM->module->volumeHoraire }}
                                                        </flux:table.cell>
                                                        <flux:table.cell>
                                                            @if (!$sM->formateur)
                                                                <flux:modal.trigger
                                                                    name="assign-formateur-{{ $sM->id }}">
                                                                    <flux:button>Assigner un formateur</flux:button>
                                                                </flux:modal.trigger>
                                                            @else
                                                                <flux:text>{{ $sM->formateur->user->name }}</flux:text>
                                                            @endif

                                                            <flux:modal name="assign-formateur-{{ $sM->id }}"
                                                                flyout>
                                                                <form
                                                                    wire:submit.prevent="AssignerFormateur({{ $sM->id }})"
                                                                    class="space-y-6">
                                                                    <div>
                                                                        <flux:heading size="lg">Assigner un
                                                                            proffesseur
                                                                            a ce module
                                                                        </flux:heading>
                                                                    </div>

                                                                    <flux:select wire:model="selctFormateur"
                                                                        placeholder=" Selectionner un formateur"
                                                                        label=" Formateur">
                                                                        @foreach (\App\Models\Formateur::all() as $formateur)
                                                                            <flux:select.option
                                                                                value="{{ $formateur->id }}">
                                                                                {{ $formateur->user->name }}
                                                                            </flux:select.option>
                                                                        @endforeach

                                                                    </flux:select>



                                                                    <div class="flex">
                                                                        <flux:spacer />

                                                                        <flux:button type="submit" variant="primary">
                                                                            Save
                                                                            changes</flux:button>
                                                                    </div>
                                                                </form>
                                                            </flux:modal>
                                                        </flux:table.cell>
                                                        <flux:table.cell>
                                                            {{ $sM->statut }}
                                                        </flux:table.cell>
                                                        <flux:table.cell>
                                                            <span
                                                                class=" font-extrabold font-serif text-green-500">{{ round($sM->progress/$sM->module->volumeHoraire*100,2) }}%</span>
                                                        </flux:table.cell>
                                                        <flux:table.cell>
                                                            <flux:button color="red" icon="trash">suprimer
                                                            </flux:button>
                                                        </flux:table.cell>
                                                    </flux:table.row>
                                                @empty
                                                    <flux:text>Aucun module trouve</flux:text>
                                                @endforelse
                                            </flux:table.rows>
                                        </flux:table>
                                        <flux:separator />
                                        <flux:spacer />


                                        <div class="mt-4">
                                            <flux:modal.close>
                                                <flux:button icon="x-mark">fermer</flux:button>
                                            </flux:modal.close>
                                        </div>
                                    </flux:modal>
                            @endif



                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $sessionp->annee->dateDebut->format('Y') }} -
                                {{ $sessionp->annee->dateFin->format('Y') }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $sessionp->statut }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $sessionp->dateDebut->format('Y/m/d') }} -
                                {{ $sessionp->dateFin->format('Y/m/d') }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $sessionp->capacite }}
                            </flux:table.cell>
                            @if (Auth::check() && Auth::user()->role == 'secretaire')
                                <flux:table.cell>
                                    <flux:button wire:click="suprimer({{ $sessionp->id }})" class="hover:bg-red-300!"
                                        :loading="true">
                                        <x-heroicon-o-trash class="w-5 h-5 text-red-700" />
                                    </flux:button>
                                    <flux:button wire:click="modifier({{ $sessionp->id }})" class="hover:bg-gray-300!"
                                        :loading="true">
                                        <x-heroicon-o-pencil class="w-5 h-5 text-green-700" />
                                    </flux:button>
                                    {{-- <flux:button class="hover:bg-gray-300!" :loading="true">
                                        <x-heroicon-o-eye class="w-5 h-5 text-gray-700" />
                                    </flux:button> --}}
                                </flux:table.cell>
                            @endif

                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>
    </div>
