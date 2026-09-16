<div>
    <div class="">

        <div class="">
            <flux:heading size="xl">Formations</flux:heading>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#" icon="home" class=" my-3" />
                <flux:breadcrumbs.item href="{{ route('formations') }}">Formations</flux:breadcrumbs.item>
                {{-- <flux:breadcrumbs.item href="{{ route('AjouterFormation') }}">Ajouter</flux:breadcrumbs.item> --}}
            </flux:breadcrumbs>
        </div>
        <div x-data="{ open: false }" class=" shadow-2xl rounded-2xl p-2 mb-3">
            <div class=" flex justify-between gap-4 mb-3">
                <flux:input kbd="⌘K" icon="magnifying-glass" placeholder="Search..." wire:model.live='search' />

                <flux:button href="{{ route('AjouterFormation') }}" icon="plus"> ajouter</flux:button>

            </div>

        </div>
        <div class="">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Code</flux:table.column>
                    <flux:table.column>Nom</flux:table.column>
                    <flux:table.column>Modules</flux:table.column>
                    <flux:table.column>Volume horaire</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column>Action</flux:table.column>
                </flux:table.columns>


                <flux:table.rows>
                    @foreach ($formations as $formation)
                        <flux:table.row>
                            <flux:table.cell>
                                {{ $formation->code }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $formation->nom }}
                            </flux:table.cell>
                            <flux:table.cell>

                                <flux:modal.trigger name="add-{{ $formation->id }}">
                                    <flux:button icon="plus"></flux:button>
                                </flux:modal.trigger>
                                <flux:modal.trigger name="voir-{{ $formation->id }}">
                                    <flux:button icon="eye"></flux:button>
                                </flux:modal.trigger>

                                <flux:modal name="voir-{{ $formation->id }}" class=" w-full">
                                    <div class="space-y-6">
                                        <div>
                                            <flux:heading size="lg">Module</flux:heading>

                                        </div>

                                        <div class="">
                                            <flux:table>
                                                <flux:table.columns>
                                                    <flux:table.column>Nom</flux:table.column>
                                                    <flux:table.column>Volume horaire</flux:table.column>
                                                    <flux:table.column>Action</flux:table.column>
                                                </flux:table.columns>

                                                <flux:table.rows>
                                                    @foreach ($formation->modules as $module)
                                                        <flux:table.row>
                                                            <flux:table.cell>
                                                                {{ $module->nom }}
                                                            </flux:table.cell>

                                                            <flux:table.cell>
                                                                {{ $module->volumeHoraire }}
                                                            </flux:table.cell>

                                                            <flux:table.cell>
                                                                <flux:button
                                                                    wire:click="suprimerModule({{ $module->id }})"
                                                                    class="hover:bg-red-300!" :loading="true">
                                                                    <x-heroicon-o-trash class="w-5 h-5 text-red-700" />
                                                                </flux:button>
                                                            </flux:table.cell>

                                                        </flux:table.row>
                                                    @endforeach
                                                </flux:table.rows>
                                            </flux:table>
                                        </div>
                                        <div class="flex">
                                            <flux:spacer />

                                            <flux:button type="submit" variant="primary">Fermer</flux:button>
                                        </div>
                                    </div>
                                </flux:modal>

                                <flux:modal name="add-{{ $formation->id }}" class="md:w-96">
                                    <form wire:submit.prevent="saveModule({{ $formation->id }})" class="space-y-6">
                                        <div>
                                            <flux:heading size="lg">Ajouter un module</flux:heading>
                                            <flux:text class="mt-2">Remplissez les informations concernant le module
                                                ----{{ $formation->id }}.
                                            </flux:text>
                                        </div>
                                        <flux:input wire:model="nomModule" label="Name" placeholder="Nom du module" />
                                        <flux:input wire:model="volumeHoraire" label="Volume horaire"
                                            placeholder="Volume horaire du module" />
                                        <flux:textarea wire:model="descriptionModule" label="Description"
                                            placeholder="Description du module" />
                                        <flux:select wire:model="formateur_id" placeholder="Choisir un formateur..."
                                            label="Formateur">
                                            @foreach ($formateurs as $formateur)
                                                <flux:select.option value="{{ $formateur->id }}">
                                                    {{ $formateur->user->name }}---{{ $formateur->specialite }}
                                                </flux:select.option>
                                            @endforeach
                                        </flux:select>
                                        <div class="flex">
                                            <flux:spacer />

                                            <flux:button icon="arrow-down-tray" type="submit" variant="primary">
                                                Enregistrer</flux:button>
                                        </div>
                                    </form>
                                </flux:modal>
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $formation->volume_horaire }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $formation->statut }}
                            </flux:table.cell>

                            <flux:table.cell variant="strong">

                                <flux:button wire:click="suprimer({{ $formation->id }})" class="hover:bg-red-300!"
                                    :loading="true">
                                    <x-heroicon-o-trash class="w-5 h-5 text-red-700" />
                                </flux:button>
                                <flux:modal.trigger class="ajouterFormation">
                                    <flux:button icon="pencil" class="hover:bg-gray-300!" href="{{ route('AjouterFormation',$formation->id) }}">
                                        {{-- <x-heroicon-o-pencil class="w-5 h-5 text-green-700" /> --}}
                                    </flux:button>
                                </flux:modal.trigger>
                                <flux:button icon="eye" class="hover:bg-gray-300!" :loading="true">

                                </flux:button>
                            </flux:table.cell>


                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>

    </div>
</div>
