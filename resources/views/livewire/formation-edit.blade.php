<div>

    <div class="">

        <div class="">
            <flux:heading size="xl">Formations</flux:heading>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#" icon="home" class=" my-3" />

                <flux:breadcrumbs.item href="{{ route('formations') }}">Formations</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('AjouterFormation') }}">Ajouter</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>

        <form wire:submit.prevent ="enregistrer()" class=" grid gap-4 grid-cols-1  p-4  ">
            <div class="grid gap-4 grid-cols-1 lg:grid-cols-2">
                <flux:input wire:model="volume_horaire" icon="circle-stack" label="volume_horaire" type="number"
                    placeholder=" Entrer le volume horaire de la formation" />
                <flux:input wire:model="code" icon="swatch" type="text" label="Code Formation" />
                <flux:input wire:model="nom" icon="underline" type="text" label=" Nom de la formation" />
            </div>

            <flux:textarea wire:model="description" label="Description"
                description="ecriver un petit resume de la formations" placeholder="Entrer la capacite de la session" />
            <flux:radio.group wire:model="statut" label="Selectionner le statut de la formation">
                <flux:radio value="ouvert" label="Ouverte" />
                <flux:radio value="fermer" label="Fermer" />
            </flux:radio.group>
            <flux:button type="submit" icon="arrow-down-tray" class=" outline outline-green-800">
                Enregister</flux:button>

        </form>


        {{-- Happiness is not something readymade. It comes from your own actions. - Dalai Lama --}}
    </div>
