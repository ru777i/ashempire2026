<div>
    <div class="">
        <flux:heading size="xl">Ajouter une session</flux:heading>
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="#" icon="home" class=" my-3" />
            <flux:breadcrumbs.item href="{{ route('sessions') }}">Sessions</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#">Ajouter </flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>
    <div class=" shadow-2xl rounded-2xl p-2 my-3">

        <form wire:submit.prevent ="enregistrer()" @click.outside = " open = false" class="   p-4  ">
            <div class="grid gap-4 grid-cols-1 lg:grid-cols-2 mb-4">
                <flux:select wire:model.live="formation_id" label="Choisir une formation"
                    placeholder="Choisir une formation">
                    @foreach ($formations as $formation)
                        <flux:select.option value="{{ $formation->id }}">{{ $formation->nom }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:input wire:model="dateDebut" type="date" label=" Date debut session" />

                <flux:input wire:model="dateFin" type="date" label=" Date fin session" />

                <flux:select wire:model="type" label="type session" placeholder="Choisir le type de session">
                    <flux:select.option value="">Choisir le type de session</flux:select.option>
                    <flux:select.option value="journee">JOURNEE</flux:select.option>
                    <flux:select.option value="soiree">SOIREE</flux:select.option>
                </flux:select>


                <flux:input wire:model="capacite" icon="server" label="Capacite" type="number"
                    placeholder="Entrer la capacite de la session" />
            </div>
            <div class=" grid grid-cols-1 lg:grid-cols-2 gap-4">
                <flux:radio.group wire:model="statut" label="Statut" description="Selectionner le statut de la session">
                    <flux:radio value="en attente" label="EN ATTENTE" />
                    <flux:radio value="en cours" label="EN COURS" />
                    <flux:radio value="terminee" label="TERMINEE" />
                    <flux:radio value="annulee" label="ANNULEE" />
                </flux:radio.group>
                <flux:checkbox.group wire:model="moduless" label="Selectionner les modules de la session de formation">
                    @forelse ($modules as $module)
                        <flux:checkbox label="{{ $module->nom }}" value="{{ $module->id }}"  />
                    @empty
                    <flux:text>Aucun module disponible</flux:text>
                    @endforelse


                </flux:checkbox.group>
                <flux:radio.group wire:model="anne_academique_id" label="Anne Acadenique"
                    description="Selectionner l'anne academique de la session">
                    @foreach ($anneAcademiques as $annee)
                        <flux:radio value="{{ $annee->id }}"
                            label="{{ $annee->dateDebut }}    A  {{ $annee->dateFin }}" />
                    @endforeach
                </flux:radio.group>
            </div>

            <flux:button type="submit" variant="primary" color="green" icon="arrow-down-tray"
                class=" outline outline-green-800" class=" mt-4">Enregister
            </flux:button>
        </form>

    </div>


</div>
