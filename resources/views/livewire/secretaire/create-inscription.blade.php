<div>
    <div class="mb-6">
        <flux:heading size="xl" class="!text-emerald-800 font-bold">Inscription</flux:heading>
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="#" icon="home" class="my-3" />
            <flux:breadcrumbs.item href="#">Inscriptions</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#">Ajouter</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="bg-white shadow-lg shadow-emerald-900/5 ring-1 ring-emerald-950/5 rounded-2xl">

        <form wire:submit.prevent="inscrire()" class="p-4 sm:p-6 space-y-8">

            <div>
                <h3 class="text-sm font-semibold text-emerald-700 uppercase tracking-wide mb-4">Informations
                    personnelles</h3>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <x-form.input label="Nom" name="nom" icon="user" />
                    <x-form.input label="Prenom" name="prenom" icon="user" />
                    <x-form.input label="E-mail" name="email" icon="envelope" />
                    <x-form.input label="Telephone" name="phone" icon="phone" />
                    <x-form.input label="Date de Naissance" name="dateNaissance" icon="calendar" type="date" />
                </div>
            </div>

            <flux:separator />

            <div>
                <h3 class="text-sm font-semibold text-emerald-700 uppercase tracking-wide mb-4">Formation choisie
                </h3>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <flux:select wire:model.live="formation_id" placeholder="Choisir une formation..."
                        label="Formation">
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
                </div>
            </div>

            <flux:separator />

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div>
                    <flux:radio.group wire:model="sexe" label="Selectionner le sexe">
                        <flux:radio value="M" label="Masculin" />
                        <flux:radio value="F" label="Feminin" />
                    </flux:radio.group>
                </div>
                <div>
                    <flux:input type="file" wire:model="diplomes" multiple accept=".jpg,.pdf,.png"
                        label="Diplômes / justificatifs" />
                </div>
            </div>

            <flux:separator />

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-emerald-50/50 rounded-xl p-4 ring-1 ring-emerald-100">
                    <flux:radio.group wire:model="statut" label="Statut">
                        <flux:radio value="En attente" label="En attente" />
                        <flux:radio value="Validée" label="Validée" />
                        <flux:radio value="Refusée" label="Refusée" />
                        <flux:radio value="En formation" label="En formation" />
                        <flux:radio value="Terminée" label="Terminée" />
                        <flux:radio value="Abandonnée" label="Abandonnée" />
                    </flux:radio.group>
                </div>
                <div class="bg-emerald-50/50 rounded-xl p-4 ring-1 ring-emerald-100">
                    <flux:checkbox.group wire:model="selectedModules" label="Choisissez les modules">
                        @forelse ($modules as $module)
                            <flux:checkbox value="{{ $module->id }}" label="{{ $module->nom }}" />
                        @empty
                            <span class="text-zinc-400 text-sm">Aucun module disponible {{ $sessionn_id }}</span>
                        @endforelse
                    </flux:checkbox.group>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <flux:button variant="primary" type="submit" color="green" icon="arrow-down-tray"
                    class="shadow-md shadow-emerald-600/20">
                    Enregistrer</flux:button>
            </div>

        </form>
    </div>
</div>
