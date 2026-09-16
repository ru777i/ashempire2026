<div class="max-w-4xl mx-auto p-6">
    <!-- Formulaire -->
    <form wire:submit.prevent="{{ $annonceId ? 'updateAnnonce' : 'createAnnonce' }}" 
          class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">
            {{ $annonceId ? 'Modifier une annonce' : 'Créer une annonce' }}
        </h2>

        <div class="space-y-4">
            <input type="text" wire:model="titre" placeholder="Titre"
                   class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
            <textarea wire:model="description" placeholder="Description"
                      class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"></textarea>
            <input type="number" wire:model="prix" placeholder="Prix"
                   class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
            <input type="text" wire:model="categorie" placeholder="Catégorie"
                   class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
        </div>

        <button type="submit" 
                class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            {{ $annonceId ? 'Mettre à jour' : 'Créer' }}
        </button>
    </form>

    <!-- Liste des annonces -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Liste des annonces</h2>
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2 text-left">Titre</th>
                    <th class="border px-4 py-2 text-left">Prix</th>
                    <th class="border px-4 py-2 text-left">Catégorie</th>
                    <th class="border px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($annonces as $annonce)
                <tr>
                    <td class="border px-4 py-2">{{ $annonce->titre }}</td>
                    <td class="border px-4 py-2">{{ $annonce->prix }}</td>
                    <td class="border px-4 py-2">{{ $annonce->categorie }}</td>
                    <td class="border px-4 py-2 space-x-2">
                        <button wire:click="editAnnonce({{ $annonce->id }})"
                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                            Modifier
                        </button>
                        <button wire:click="deleteAnnonce({{ $annonce->id }})"
                                class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                            Supprimer
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $annonces->links() }}
        </div>
    </div>
</div>
