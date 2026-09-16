<div>

    <div class="  ">

        <div class="">
            <flux:heading size="xl">Formateurs</flux:heading>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#" icon="home" class=" my-3" />
                <flux:breadcrumbs.item href="#">Formateurs</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="#"> </flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
        <div class=" flex justify-between gap-4">
            <flux:input kbd="⌘K" icon="magnifying-glass" placeholder="Search..." wire:model.live='search' />
            <flux:modal.trigger name="edit-profile">
                <flux:button icon="plus">Ajouter</flux:button>
            </flux:modal.trigger>

            <flux:modal name="edit-profile" class="md:w-96">
                <form wire:submit.prevent="saveFormateur()" class="space-y-6">
                    <div>
                        <flux:heading size="lg">Ajouter un formateur</flux:heading>
                        <flux:text class="mt-2">Remplissez les détails du nouveau formateur.</flux:text>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 w-full">
                        <flux:input icon="user" label="Nom" placeholder="Nom" wire:model="name" />
                        <flux:input icon="envelope" label="Email" placeholder="Email" wire:model="email" />
                        <flux:input icon="phone" label="Telephone" placeholder="Telephone" wire:model="telephone" />
                        <flux:input icon="star" label="Specialité" placeholder="Specialité"
                            wire:model="specialite" />

                    </div>
                    <div class="flex">
                        <flux:spacer />

                        <flux:button type="submit" icon="arrow-down-tray" variant="primary">Enregistrer</flux:button>
                    </div>
                </form>
            </flux:modal>
        </div>
        <div class="">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Nom</flux:table.column>
                    <flux:table.column>Email</flux:table.column>
                    <flux:table.column>Telephone</flux:table.column>
                    <flux:table.column>Specialité</flux:table.column>
                    <flux:table.column>Modules</flux:table.column>
                    <flux:table.column>Action</flux:table.column>
                </flux:table.columns>


                <flux:table.rows>
                    @foreach ($formateurs as $formateur)
                        <flux:table.row>
                            <flux:table.cell>
                                {{ $formateur->user->name }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $formateur->user->email }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $formateur->user->telephone }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $formateur->specialite }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:modal.trigger name="modulef-{{ $formateur->id }}">
                                    <flux:button variant="filled" icon="eye"></flux:button>
                                </flux:modal.trigger>

                                <flux:modal name="modulef-{{ $formateur->id }}" flyout variant="floating"
                                    class="md:w-xg">
                                    <div class="space-y-6">
                                        <flux:heading size="lg">Modules</flux:heading>

                                        <flux:subheading>Liste de cours enseigner par le formateur.</flux:subheading>

                                        <flux:table>
                                            <flux:table.columns>
                                                <flux:table.column>Nom</flux:table.column>
                                                <flux:table.column>Volme Horaire</flux:table.column>
                                                <flux:table.column>Progresion</flux:table.column>
                                                <flux:table.column>Statut</flux:table.column>
                                            </flux:table.columns>

                                            <flux:table.rows>
                                                @forelse ($formateur->sessionModule as $sessM)
                                                    <flux:table.row>
                                                        <flux:table.cell>{{ $sessM->module->nom }} </flux:table.cell>
                                                        <flux:table.cell>{{ $sessM->module->volumeHoraire }}
                                                        </flux:table.cell>
                                                        <flux:table.cell class=" text-green-600!">
                                                            {{ $sessM->progress }}%
                                                        </flux:table.cell>
                                                        <flux:table.cell><span
                                                                class=" rounded-md bg-gray-100 text-green-600 p-2">{{ $sessM->statut }}</span>
                                                        </flux:table.cell>
                                                    </flux:table.row>
                                                @empty
                                                    <flux:text>Aucun module Assigner</flux:text>
                                                @endforelse
                                            </flux:table.rows>


                                        </flux:table>
                                    </div>

                                    <x-slot name="footer" class="flex items-center justify-end gap-2">
                                        <flux:modal.close>
                                            <flux:button variant="filled">Cancel</flux:button>
                                        </flux:modal.close>

                                        <flux:button type="submit" variant="primary">Save changes</flux:button>
                                    </x-slot>
                                </flux:modal>
                            </flux:table.cell>
                            <flux:table.cell>
                                {{-- <flux:button href="{{ route('formateurs', $formateur->id) }}" icon="eye"
                                    variant="primary"></flux:button> --}}

                                <flux:button href="{{ route('formateur', $formateur->id) }}" icon="pencil">
                                </flux:button>

                                <flux:button wire:click="delete({{ $formateur->id }})" icon="trash" variant="danger">
                                </flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>

    </div>

</div>
