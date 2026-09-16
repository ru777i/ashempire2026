<div>

    <div class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <flux:card>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Total</p>
                        <h2 class="text-3xl font-bold">12</h2>
                    </div>

                    <flux:icon.clipboard-document-list class="size-10 text-blue-500" />
                </div>
            </flux:card>

            <flux:card>
                <div>
                    <p class="text-sm text-gray-500">En attente</p>
                    <h2 class="text-3xl font-bold text-yellow-500">
                      4
                    </h2>
                </div>
            </flux:card>

            <flux:card>
                <div>
                    <p class="text-sm text-gray-500">Traités</p>
                    <h2 class="text-3xl font-bold text-green-600">
                  4
                    </h2>
                </div>
            </flux:card>

            <flux:card>
                <div>
                    <p class="text-sm text-gray-500">Rejetés</p>
                    <h2 class="text-3xl font-bold text-red-600">
                       4
                    </h2>
                </div>
            </flux:card>

        </div>
        <flux:card>

            <div class="grid lg:grid-cols-3 gap-4">

                <flux:input icon="magnifying-glass" placeholder="Rechercher..." wire:model.live="search" />

                <flux:select wire:model.live="filtre">

                    <option value="">Tous les statuts</option>

                    <option value="en_attente">
                        En attente
                    </option>

                    <option value="traite">
                        Traité
                    </option>

                    <option value="rejete">
                        Rejeté
                    </option>

                </flux:select>

            </div>

        </flux:card>
        <flux:card>

            <flux:table>

                <flux:table.columns>

                    <flux:table.column>
                        Apprenant
                    </flux:table.column>

                    <flux:table.column>
                        Objet
                    </flux:table.column>

                    <flux:table.column>
                        Type
                    </flux:table.column>

                    <flux:table.column>
                        Date
                    </flux:table.column>

                    <flux:table.column>
                        Statut
                    </flux:table.column>

                    <flux:table.column align="end">
                        Actions
                    </flux:table.column>

                </flux:table.columns>

                <flux:table.rows>

                    @foreach ($recours as $recour)
                        <flux:table.row>

                            <flux:table.cell>

                                {{ $recour->apprenant->nom }}

                            </flux:table.cell>

                            <flux:table.cell>

                                {{ $recour->objet }}

                            </flux:table.cell>

                            <flux:table.cell>

                                <flux:badge color="blue">

                                    {{ ucfirst($recour->type) }}

                                </flux:badge>

                            </flux:table.cell>

                            <flux:table.cell>

                                {{ $recour->created_at->format('d/m/Y') }}

                            </flux:table.cell>

                            <flux:table.cell>

                                @if ($recour->statut == 'en_attente')
                                    <flux:badge color="yellow">

                                        En attente

                                    </flux:badge>
                                @elseif($recour->statut == 'traite')
                                    <flux:badge color="green">

                                        Traité

                                    </flux:badge>
                                @else
                                    <flux:badge color="red">

                                        Rejeté

                                    </flux:badge>
                                @endif

                            </flux:table.cell>

                            <flux:table.cell align="end">

                                <div class="flex gap-2 justify-end">

                                    <flux:button size="sm" wire:click="show({{ $recour->id }})">

                                        Voir

                                    </flux:button>

                                    @if ($recour->statut == 'en_attente')
                                        <flux:button variant="primary" size="sm"
                                            wire:click="show({{ $recour->id }})">

                                            Répondre

                                        </flux:button>
                                    @endif

                                </div>

                            </flux:table.cell>

                        </flux:table.row>
                    @endforeach

                </flux:table.rows>

            </flux:table>

            <div class="mt-5">

                {{ $recours->links() }}

            </div>

        </flux:card>
        <flux:modal wire:model="showModal" class="md:w-2xl">

            <div class="space-y-5">

                <h2 class="text-2xl font-bold">

                    Traitement du recours

                </h2>

                <flux:textarea label="Description" readonly rows="5">

                    {{ $selectedRecour?->description }}

                </flux:textarea>

                <flux:textarea wire:model="reponse" label="Votre réponse" rows="6" />

                <flux:radio.group wire:model="statut" label="Décision">

                    <flux:radio value="traite">

                        Accepter

                    </flux:radio>

                    <flux:radio value="rejete">

                        Rejeter

                    </flux:radio>

                </flux:radio.group>

                <div class="flex justify-end gap-3">

                    <flux:button variant="ghost" wire:click="closeModal">

                        Annuler

                    </flux:button>

                    <flux:button variant="primary" wire:click="saveResponse">

                        Enregistrer

                    </flux:button>

                </div>

            </div>

        </flux:modal>

        {{-- Simplicity is an acquired taste. - Katharine Gerould --}}
    </div>

</div>
