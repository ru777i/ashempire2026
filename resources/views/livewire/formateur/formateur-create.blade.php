<div>
      <div class=" my-4">
            <flux:heading size="xl">Formateurs</flux:heading>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#" icon="home" class=" my-3" />
                <flux:breadcrumbs.item href="{{ route('formateurs') }}">Formateurs</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="#">Ajouter</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
    <form wire:submit.prevent="saveFormateur()" class="space-y-6 shadow-2xl p-4 m-auto rounded-lg bg-white">
                    <div>
                        <flux:heading size="lg">Ajouter un formateur</flux:heading>
                        <flux:text class="mt-2">Remplissez les détails du nouveau formateur.</flux:text>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 w-full">
                        <flux:input icon="user" label="Nom" placeholder="Nom" wire:model="nomFormateur" />
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
</div>
