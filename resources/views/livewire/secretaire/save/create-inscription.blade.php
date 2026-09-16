<div>
    <div class="">
        <flux:heading size="xl">Incription</flux:heading>
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="#" icon="home" class=" my-3" />
            <flux:breadcrumbs.item href="#">Inscriptions</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#">Ajouter </flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>
    <div class=" shadow-2xl rounded-2xl">

        <form wire:submit.prevent="inscrire()" class="p-3">
            <div class=" grid grid-cols-1 gap-4 lg:grid-cols-2">
                <x-form.input label="Nom" name="nom" icon="user" />
                <x-form.input label="Prenom" name="prenom" icon="user" />
                <x-form.input label="E-mail" name="email" icon="envelope" />
                <x-form.input label="Telephone" name="phone" icon="phone" />
                <x-form.input label="Date de Naissance" name="dateNaissance" icon="calendar" type="date" />
                <flux:select wire:model.live="formation_id" placeholder="Choisir une  formation..." label="Formation">
                    @forelse ($formations as $formation)
                        <flux:select.option value="{{ $formation->id }}">
                            {{ $formation->nom }}
                        </flux:select.option>
                    @empty
                        <flux:select.option value="">Aucune session disponible {{ $sessionn_id }}
                        </flux:select.option>
                    @endforelse

                </flux:select>
                <flux:select wire:model.live="sessionn_id" placeholder="Choisir une session..." label="Session">
                    <flux:select.option>---Session de formation---- </flux:select.option>
                    @forelse ($sessions as $sessio)
                        <flux:select.option value="{{ $sessio->id }}">
                            {{ $sessio->formation->nom }} Au {{ $sessio->dateDebut->format('Y/m/d') }} Annee
                            {{ $sessio->dateFin->format('Y/m/d') }}
                            ({{ $sessio->annee->dateDebut?->format('Y') }} -
                            {{ $sessio->annee->dateFin?->format('Y') }})
                        </flux:select.option>
                    @empty
                        <flux:select.option value="">Aucune session disponible {{ $sessionn_id }}
                        </flux:select.option>
                    @endforelse

                </flux:select>

                <div class="">
                    <flux:radio.group wire:model="sexe" label="Selectionner le sexe">
                        <flux:radio value="M" label="Masculin" />
                        <flux:radio value="F" label="Feminin" />
                    </flux:radio.group>
                </div>
                <flux:input type="file" wire:model="diplomes" multiple accept=".jpg,.pdf,.png" />
            </div>
            <div class=" grid grid-cols-1 lg:grid-cols-2 mt-3">
                <div class="">
                    <flux:radio.group wire:model="statut" label="Statut">
                        <flux:radio value="En attente" label="En attente" />
                        <flux:radio value="Validée" label="Validée" />
                        <flux:radio value="Refusée" label="Refusée" />
                        <flux:radio value="En formation" label="En formation" />
                        <flux:radio value="Terminée" label="Terminée" />
                        <flux:radio value="Abandonnée" label="Abandonnée" />
                    </flux:radio.group>
                </div>
                <div class="">
                    <flux:checkbox.group wire:model="selectedModules" label="Choisissez les modules">
                        @forelse ($modules as $module)
                            <flux:checkbox value="{{ $module->id }}" label="{{ $module->nom }}" />
                        @empty
                            <span>Aucun module disponible {{ $sessionn_id }}</span>
                        @endforelse
                    </flux:checkbox.group>
                </div>

            </div>
            <flux:button variant="primary" type="submit" color="green" icon="arrow-down-tray" class=" mt-4">
                Enregistrer</flux:button>

        </form>
    </div>
</div>
