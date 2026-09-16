<div class="">
    <div>

        <div class="">
            <div class="">
                <flux:heading size="xl">Mes notes</flux:heading>
                <flux:breadcrumbs>
                    <flux:breadcrumbs.item href="#" icon="home" class=" my-3" />
                    <flux:breadcrumbs.item href="#">Notes</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>

        </div>
        <div class="">
            <flux:select placeholder="Selectionner le module">
                <flux:select.option value="">--Tous les notes--</flux:select.option>
                @foreach ( $evaluations as $evaluation )
                       <flux:select.option value="{{ $evaluation->id }}"> Module: {{ $evaluation->sessionModule->module->nom }} Formateur: {{ $evaluation->sessionModule->formateur->user->name }}</flux:select.option>
                @endforeach
             
            </flux:select>
            <div class="">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Module</flux:table.column>
                        <flux:table.column>Note</flux:table.column>
                        <flux:table.column>Mention</flux:table.column>
                        <flux:table.column>Statut</flux:table.column>
                        <flux:table.column>Action</flux:table.column>

                    </flux:table.columns>
                    <flux:table.row>
                        @foreach ($notes as $note)
                            <flux:table.row>
                                    <flux:table.cell>{{ $note->evaluation->sessionModule->module->nom ?? ''}}</flux:table.cell>
                                    <flux:table.cell>{{ $note->note }}</flux:table.cell>
                                    <flux:table.cell>{{ $note->mention }}</flux:table.cell>
                                    <flux:table.cell class="{{ $note->note >=10 ?' text-green-500!' : 'text-red-600' }}">{{ $note->note >=10 ?' Reussi' : 'Echec'}}</flux:table.cell>
                                    <flux:table.cell> <flux:button icon="bell" >signaler</flux:button></flux:table.cell>



                            </flux:table.row>
                        @endforeach

                    </flux:table.row>
                </flux:table>
            </div>
        </div>

        {{-- Because you are alive, everything is possible. - Thich Nhat Hanh --}}
    </div>
