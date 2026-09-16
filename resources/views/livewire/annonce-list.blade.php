<div class="max-w-7xl mx-auto p-6" x-data="{ open: false }">
    <!-- Barre de recherche et filtres -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 space-y-4 md:space-y-0">
        <input type="text" wire:model="search" placeholder="🔍 Rechercher..."
               class="w-full md:w-1/2 border rounded px-3 py-2 focus:ring focus:ring-blue-300">

        <div class="flex space-x-2">
            <select wire:model="categorie" class="border rounded px-3 py-2 focus:ring focus:ring-blue-300">
                <option value="">Toutes catégories</option>
                <option value="Immobilier">Immobilier</option>
                <option value="Véhicules">Véhicules</option>
                <option value="Électronique">Électronique</option>
            </select>
            <input type="number" wire:model="minPrix" placeholder="Prix min"
                   class="w-24 border rounded px-2 py-1">
            <input type="number" wire:model="maxPrix" placeholder="Prix max"
                   class="w-24 border rounded px-2 py-1">
        </div>
    </div>

    <!-- Grille des annonces -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($annonces as $annonce)
        <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-xl transition transform hover:scale-105">
            <div class="p-4">
                <h2 class="text-lg font-semibold text-gray-800">{{ $annonce->titre }}</h2>
                <p class="text-gray-600 mt-2">{{ Str::limit($annonce->description, 100) }}</p>
                <p class="text-blue-600 font-bold mt-3">{{ number_format($annonce->prix, 0, ',', ' ') }} FCFA</p>
                <span class="inline-block bg-gray-200 text-gray-700 text-sm px-2 py-1 rounded mt-2">
                    {{ $annonce->categorie }}
                </span>
            </div>
            <div class="p-4 border-t flex justify-between items-center">
                <button @click="open = true" 
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Voir détails
                </button>
                <button class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                    ❤️ Favoris
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $annonces->links() }}
    </div>

    <!-- Modal dynamique -->
    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-xl font-bold mb-4">Détails de l’annonce</h2>
            <p class="text-gray-700">Ici tu peux afficher les infos complètes de l’annonce sélectionnée.</p>
            <button @click="open = false" class="mt-4 bg-red-600 text-white px-4 py-2 rounded">
                Fermer
            </button>
        </div>
    </div>
</div>
