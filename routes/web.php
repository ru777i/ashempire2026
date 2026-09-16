<?php

use App\Http\Middleware\EnsureTeamMembership;
use App\Livewire\AnnonceList;
use App\Livewire\AnnonceManager;
use App\Livewire\Apprenant\Dashboard as ApprenantDashboard;
use App\Livewire\Apprenant\MesNotes;
use App\Livewire\Apprenant\Presences;
use App\Livewire\Apprenant\Recour;
use App\Livewire\Dashboard;
use App\Livewire\EmploiTemp\EdtCreate;
use App\Livewire\Evaluation\CreateNote;
use App\Livewire\Formateur\Avancement;
use App\Livewire\Formateur\Dashboard as FormateurDashboard;
use App\Livewire\Formateur\EdtFormateur;
use App\Livewire\Formateur\FormateurCreate;
use App\Livewire\Formateur\FormateurShow;
use App\Livewire\Formateur\GestionRecour;
use App\Livewire\Formateur\MesApprenant;
use App\Livewire\Formateur\Module;
use App\Livewire\Formation;
use App\Livewire\FormationEdit;
use App\Livewire\Presence\Create as PresnceCreate;
use App\Livewire\Presence\Index as PresenceIndex;
use App\Livewire\Secretaire\CertificatManager;
use App\Livewire\Apprenant\MesCertificats;
use App\Livewire\Secretaire\CreateInscription;
use App\Livewire\Secretaire\Inscription;
use App\Livewire\Secretaire\Session;
use App\Livewire\Secretaire\SessionCreate;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// Route::prefix('{current_team}')
//     ->middleware(['auth', 'verified', EnsureTeamMembership::class])
//     ->group(function () {
//         Route::get('dashboard', Dashboard::class)->name('dashboard');
//     });
 Route::get('dashboard', Dashboard::class)->name('dashboard');
Route::middleware(['auth'])->group(function () {
    Route::livewire('invitations/{invitation}/accept', 'pages::teams.accept-invitation')->name('invitations.accept');
    Route::livewire('dtel', 'pages::teams.index')->name('teams');
});

Route::get('inscriptions/add/{id?}', CreateInscription::class)->name('ajouterInscription');
Route::get('formation/{id?}',FormationEdit::class)->name('AjouterFormation');
Route::get('sessions/create/{id?}', SessionCreate::class)->name('sessionCreate');
Route::get('formateur/{id?}', FormateurCreate::class)->name('formateur');
Route::get('formateurs/{id?}', FormateurShow::class)->name('formateurs');
Route::get('formations/{id?}', Formation::class)->name('formations');
Route::get('inscriptions',Inscription::class)->name('inscriptions');
Route::get('sessions', Session::class)->name('sessions');
Route::get('edt/create/{id?}', EdtCreate::class)->name('edtCreate');
Route::get('presences', PresnceCreate::class)->name('addPresence');
Route::get('evaluations',CreateNote::class)->name('createNote');
Route::get('presences/historique', PresenceIndex::class)->name('presencesIndex');
Route::get('module', Module::class)->name('formateurModule');
Route::get('edt/formateur/{id?}', EdtFormateur::class)->name('edtFormateur');

Route::get('avancement', Avancement::class)->name('avancement');
Route::get('apprenants',MesApprenant::class)->name('mesApprenants');
Route::get('notes',MesNotes::class)->name('mesNotes');
Route::get('apprenant/presences',Presences::class)->name('mesPresences');
Route::get('recours',Recour::class)->name('mesRecours');
Route::get('format/recour',GestionRecour::class)->name('gestionRecours');
Route::get('anonn/manager',AnnonceManager::class)->name('anonceManager');
Route::get('annonce/list',AnnonceList::class)->name('annonceListe');
Route::get('dashboard/apprenant',ApprenantDashboard::class)->name('dashboardAprenant');
Route::get('dashboard/formateur',FormateurDashboard::class)->name('dashboardFormateur');
Route::get('certificats',CertificatManager::class)->name('certificats');
Route::get('mes/certificats',MesCertificats::class)->name('mesCertificats');



require __DIR__ . '/settings.php';
