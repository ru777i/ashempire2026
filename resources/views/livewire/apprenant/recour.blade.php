<div class="p-6">

    {{-- Message succès --}}
    @if(session()->has('message'))
        <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-700">
            {{ session('message') }}
        </div>
    @endif


    @if(session()->has('error'))
        <div class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-red-700">
            {{ session('error') }}
        </div>
    @endif



    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Mes recours
            </h1>

            <p class="text-sm text-gray-500">
                Consultez et gérez vos demandes de recours.
            </p>
        </div>


        <button
            wire:click="create"
            class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">

            + Nouveau recours

        </button>

    </div>





    {{-- Tableau --}}
    <div class="overflow-hidden rounded-xl border bg-white shadow">


        <table class="w-full text-left">


            <thead class="bg-gray-100 text-sm text-gray-600">

                <tr>

                    <th class="px-5 py-3">
                        Objet
                    </th>


                    <th class="px-5 py-3">
                        Type
                    </th>


                    <th class="px-5 py-3">
                        Formateur
                    </th>


                    <th class="px-5 py-3">
                        Statut
                    </th>


                    <th class="px-5 py-3">
                        Réponse
                    </th>


                    <th class="px-5 py-3">
                        Actions
                    </th>

                </tr>

            </thead>



            <tbody class="divide-y">


            @forelse($recours as $recour)


                <tr>


                    {{-- Objet --}}
                    <td class="px-5 py-4">

                        <div class="font-semibold text-gray-800">
                            {{ $recour->objet }}
                        </div>


                        <div class="text-sm text-gray-500">

                            {{ Str::limit($recour->description,50) }}

                        </div>

                    </td>




                    {{-- Type --}}
                    <td class="px-5 py-4">

                        <span class="rounded-full bg-gray-100 px-3 py-1 text-sm">

                            {{ ucfirst($recour->type) }}

                        </span>

                    </td>





                    {{-- Formateur --}}
                    <td class="px-5 py-4">


                        {{ $recour->formateur?->user?->name ?? 'Non défini' }}


                    </td>





                    {{-- Statut --}}
                    <td class="px-5 py-4">


                        @switch($recour->statut)


                            @case('en_attente')

                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-sm text-yellow-700">

                                    En attente

                                </span>

                            @break



                            @case('en_cours')

                                <span class="rounded-full bg-blue-100 px-3 py-1 text-sm text-blue-700">

                                    En cours

                                </span>

                            @break



                            @case('accepte')

                                <span class="rounded-full bg-green-100 px-3 py-1 text-sm text-green-700">

                                    Accepté

                                </span>

                            @break



                            @case('rejete')

                                <span class="rounded-full bg-red-100 px-3 py-1 text-sm text-red-700">

                                    Rejeté

                                </span>

                            @break



                            @default

                                <span class="rounded-full bg-gray-100 px-3 py-1 text-sm">

                                    {{ $recour->statut }}

                                </span>


                        @endswitch


                    </td>






                    {{-- Réponse --}}
                    <td class="max-w-xs px-5 py-4">


                        @if($recour->reponse)


                            <p class="text-sm text-gray-700">

                                {{ Str::limit($recour->reponse,60) }}

                            </p>


                            @if($recour->date_traitement)

                                <span class="text-xs text-gray-400">

                                    Traité le :
                                    {{ $recour->date_traitement->format('d/m/Y') }}

                                </span>

                            @endif


                        @else


                            <span class="text-sm text-gray-400">

                                Pas encore traité

                            </span>


                        @endif


                    </td>







                    {{-- Actions --}}
                    <td class="px-5 py-4">


                        @if($recour->statut === 'en_attente')


                            <div class="flex gap-3">


                                <button
                                    wire:click="edit({{ $recour->id }})"
                                    class="text-blue-600 hover:underline">

                                    Modifier

                                </button>



                                <button
                                    wire:click="delete({{ $recour->id }})"
                                    onclick="return confirm('Supprimer ce recours ?')"
                                    class="text-red-600 hover:underline">

                                    Supprimer

                                </button>


                            </div>


                        @else


                            <span class="text-sm text-gray-400">

                                Verrouillé

                            </span>


                        @endif


                    </td>


                </tr>



            @empty


                <tr>

                    <td colspan="6" class="px-5 py-8 text-center text-gray-500">

                        Aucun recours enregistré.

                    </td>

                </tr>


            @endforelse



            </tbody>


        </table>


    </div>



    {{-- Pagination --}}
    <div class="mt-5">

        {{ $recours->links() }}

    </div>







    {{-- Modal --}}
    @if($showModal)


        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">


            <div class="w-full max-w-xl rounded-xl bg-white p-6 shadow-xl">


                <h2 class="mb-5 text-xl font-bold">


                    {{ $editMode ? 'Modifier le recours' : 'Nouveau recours' }}


                </h2>




                {{-- Formateur --}}
                <label class="mb-2 block text-sm font-medium">

                    Destinataire

                </label>


                <select
                    wire:model="formateur_id"
                    class="mb-4 w-full rounded-lg border-gray-300">


                    <option value="">

                        Choisir un formateur

                    </option>


                    @foreach($formateurs as $formateur)


                        <option value="{{ $formateur->id }}">

                            {{ $formateur->user->name }}

                        </option>


                    @endforeach


                </select>


                @error('formateur_id')
                    <span class="text-sm text-red-600">

                        {{ $message }}

                    </span>
                @enderror







                {{-- Objet --}}
                <input
                    wire:model="objet"
                    type="text"
                    placeholder="Objet du recours"
                    class="mb-4 w-full rounded-lg border-gray-300">


                @error('objet')
                    <span class="text-sm text-red-600">

                        {{ $message }}

                    </span>
                @enderror





                {{-- Type --}}
                <select
                    wire:model="type"
                    class="mb-4 w-full rounded-lg border-gray-300">


                    <option value="">
                        Choisir un type
                    </option>


                    <option value="note">
                        Erreur de note
                    </option>


                    <option value="absence">
                        Absence
                    </option>


                    <option value="autre">
                        Autre
                    </option>


                </select>





                {{-- Description --}}
                <textarea
                    wire:model="description"
                    rows="5"
                    placeholder="Expliquez votre problème..."
                    class="mb-5 w-full rounded-lg border-gray-300"></textarea>





                <div class="flex justify-end gap-3">


                    <button
                        wire:click="$set('showModal',false)"
                        class="rounded-lg border px-4 py-2">

                        Annuler

                    </button>




                    @if($editMode)


                        <button
                            wire:click="update"
                            class="rounded-lg bg-green-600 px-4 py-2 text-white">

                            Modifier

                        </button>


                    @else


                        <button
                            wire:click="save"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-white">

                            Envoyer

                        </button>


                    @endif


                </div>


            </div>


        </div>


    @endif


</div>
