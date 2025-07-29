<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CfpInviteEtp;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\SeanceController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SalleCfpController;
use App\Http\Controllers\AgendaCfpController;
use App\Http\Controllers\ApprenantController;
use App\Http\Controllers\FormateurController;
use App\Http\Controllers\EmargementController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ProjetInterController;
use App\Http\Controllers\CfpDashboardController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\ShowDrawerController;
use App\Http\Controllers\RestaurationController;
use App\Http\Controllers\DossierController;
use App\Http\Controllers\LocalisationController;
use App\Http\Controllers\SubContractorController;
use App\Http\Controllers\SearchController;

// Sous Référent Cfp
Route::middleware(['auth', 'isEmployeCfp'])->group(function () {
    Route::get('search_by_key', [SearchController::class, 'keySuggestion'])->name('keySuggestion');

    Route::prefix('/home')->group(function () {
        Route::get('/api/config', [CfpDashboardController::class, 'getConfigApi']);
    });

    // Referents

    // Module
    Route::prefix('cfp/modules')->group(function () {
        Route::get('/detail/{idModule}/drawer', [ModuleController::class, 'detailModule']);
        Route::get('/get/firstModule', [ModuleController::class, 'getFirstModules']);
    });

    //Reporting

    // Programme

    //ModuleRessource

    // Projets
    Route::prefix('/cfp/projets')->group(function () {
        Route::delete('/deletephoto/{idProjet}/{url}', [ProjetController::class, 'destroyPhoto'])->name('deletephoto.destroy');
        Route::get('/listephoto/{idProjet}/{idTypeImage}', [ProjetController::class, 'listePhotoMomentum'])->name('listephoto.momentum');
        Route::post('/uploadphoto/{idProjet}', [ProjetController::class, 'uploadPhotoMomentum'])->name('uploadphoto.momentum');
        Route::post('/', [ProjetController::class, 'store'])->name('cfp.projets.store');
        Route::get('/', [ProjetController::class, 'index'])->name('cfp.projets.index');
        Route::get('/{idProjet}/detail', [ProjetController::class, 'show'])->name('cfp.projets.show');
        Route::get('/{idProjet}/detail/momentum', [ProjetController::class, 'showmomentum'])->name('cfp.projets.showmomentum');
        Route::get('/formateur/{id}/mini-cv', [ProjetController::class, 'getMiniCV']);
        Route::post('/{idProjet}/update-taxe', [ProjetController::class, 'updateTaxe']);
        Route::get('/{idProjet}/{isEtp}/frais', [ProjetController::class, 'fraisdetails'])->name('cfp.projets.fraisdetails');
        Route::post('/{idProjet}/{idFrais}/{isEtp}/fraisprojet/assign', [ProjetController::class, 'fraisAssign'])->name('cfp.projets.fraisAssign');
        Route::post('/update-frais', [ProjetController::class, 'updateFrais'])->name('cfp.projets.updateFrais');
        Route::post('/{idProjet}/{idFraisProjet}/delete-frais', [ProjetController::class, 'fraisRemove'])->name('cfp.projets.deleteFrais');
        Route::post('/{idProjet}/total-frais', [ProjetController::class, 'fraisTotal'])->name('cfp.projets.fraisTotal');
        Route::get('/{idFraisProjet}/idProjet', [ProjetController::class, 'getIdProjetByIdFraisProjet'])->name('cfp.projets.getIdProjet');
        Route::delete('/{idProjet}/{idEtp}/removeEtpFraisProjet', [ProjetController::class, 'removeEtpFraisProjet'])->name('cfp.projets.removeEtpFraisProjet');
        Route::get('/fermeturefrais', [ProjetController::class, 'fermeturefrais'])->name('cfp.projets.fermeturefrais');
        Route::get('/{idProjet}/form/assign', [ProjetController::class, 'getFormAssign'])->name('cfp.projets.form.assign.index');
        Route::post('/{idProjet}/{idFormateur}/form/assign', [ProjetController::class, 'formAssign']);
        Route::get('/{idProjet}/getFormAdded', [ProjetController::class, 'getFormAdded']);
        Route::delete('/{idProjet}/{idFormateur}/form/assign', [ProjetController::class, 'formRemove']);
        Route::get('/{idProjet}/etp/assign', [ProjetController::class, 'getEtpAssign']);
        Route::patch('/{idProjet}/{idEtp}/etp/assign', [ProjetController::class, 'etpAssign']);
        Route::get('/{idProjet}/mainGetIdEtp', [ProjetController::class, 'mainGetIdEtp']);
        Route::get('/{idProjet}/mainGetIdModule', [ProjetController::class, 'mainGetIdModule']);
        Route::patch('/{idProjet}/{idModule}/module/assign', [ProjetController::class, 'moduleAssign']);
        Route::patch('/{idProjet}/date/assign', [ProjetController::class, 'dateAssign']);
        Route::get('/{idProjet}/details', [ProjetController::class, 'detailsJson']);
        Route::get('/{idModule}/getProgrammeProject', [ProjetController::class, 'getProgramme']);
        Route::get('/{idModule}/getModuleRessourceProject', [ProjetController::class, 'getModuleRessourceProject']);
        Route::delete('/{idProjet}/destroy', [ProjetController::class, 'destroy'])->name('cfp.projets.destroy');
        Route::post('/{idProjet}/duplicate', [ProjetController::class, 'duplicate'])->name('cfp.projets.duplicate');
        Route::patch('/{idProjet}/update/date', [ProjetController::class, 'updateDate'])->name('cfp.projets.updateDate');
        Route::patch('/{idProjet}/update/module', [ProjetController::class, 'updateModule'])->name('cfp.projets.updateModule');
        Route::patch('/update/financement/{idProjet}/{idCfp_inter}', [ProjetController::class, 'updateFinancement'])->name('cfp.projets.updateFinancement');
        Route::patch('/{idProjet}/update/price', [ProjetController::class, 'updatePrice'])->name('cfp.projets.updatePrice');
        Route::patch('/{idProjet}/{idSalle}/salle/assign', [ProjetController::class, 'salleAssign']);
        Route::get('/{idProjet}/getSalleAdded', [ProjetController::class, 'getSalleAdded']);
        Route::patch('/{idProjet}/cancel', [ProjetController::class, 'cancel']);
        Route::patch('/{idProjet}/repport', [ProjetController::class, 'repport']);
        Route::patch('/{idProjet}/close', [ProjetController::class, 'close']);
        Route::patch('/{idProjet}/updateProjet', [ProjetController::class, 'updateProjet']);
        Route::patch('/{idProjet}/updateNbPlace', [ProjetController::class, 'updateNbPlace']);
        Route::patch('/{idProjet}/updateProjetInter', [ProjetController::class, 'updateProjetInter']);
        Route::get('/filter/getDropdownItem', [ProjetController::class, 'getDropdownItem']);
        Route::get('/filter/items', [ProjetController::class, 'filterItems']);
        Route::get('/filter/item', [ProjetController::class, 'filterItem']);
        Route::patch('/{idProjet}/confirm', [ProjetController::class, 'confirm']);
        Route::get('/getVille', [ProjetController::class, 'getVille']);
        Route::patch('/{idProjet}', [ProjetController::class, 'updateVille']);
        Route::post('/{idProjet}/{idEtp}', [ProjetController::class, 'etpAssignInter']);
        Route::patch('/{idProjet}/update/modalite', [ProjetController::class, 'updateModalite'])->name('cfp.projets.updateModalite');
        Route::get('/getModalite', [ProjetController::class, 'getModalite']);
        Route::get('/parts/getAllParts', [ProjetController::class, 'getAllParts'])->name('parts.getAllParts');
        Route::post('/{idProjet}/{idParticulier}/part/assign', [ProjetController::class, 'assignPart']);
        Route::get('/{idProjet}/getPartAdded', [ProjetController::class, 'getPartAdded']);
        Route::delete('/{idProjet}/{idParticulier}/part/assign', [ProjetController::class, 'unassignPart'])->name('parts.unassign');
        Route::patch('/{idProjet}/updatePrivacy', [ProjetController::class, 'updatePrivacy']);
        Route::patch('/{idProjet}/trash', [ProjetController::class, 'trash'])->name('projets.cfp.trash');
        Route::patch('/{idProjet}/restore', [ProjetController::class, 'restore'])->name('projets.cfp.restore');

        Route::get('/{idProjet}/getDataPresence', [ProjetController::class, 'getDataPresence']);

        Route::get('/list', [ProjetController::class, 'getProjectList'])->name('cfp.projets.list');

        Route::get('/detailProjetCfpPdf/{id}', [ProjetController::class, 'detailProjetCfpPdf'])->name('cfp.projets.detailProjetCfpPdf');
        Route::patch('/{id}/archive', [ProjetController::class, 'makeArchive']);
        Route::patch('/{id}/restoreArchive', [ProjetController::class, 'restoreArchive']);

        Route::patch('/{id}/linkInvitation', [ProjetController::class, 'linkInvitation']);

        Route::get('/{id}/getApprListProjet', [ProjetController::class, 'getApprListProjet']);
        Route::get('/{id}/getFormProject', [ProjetController::class, 'getFormProject']);
        Route::get('/{id}/getSessionProject', [ProjetController::class, 'getSessionProject']);

        Route::get('/getEtpClient/{id}/{idCfp_inter}', [ProjetController::class, 'getEtpProjectInter']);
    });

    //Mes clients
    Route::prefix('cfp/invites/etp')->group(function () {
        Route::get('/getAllEtps', [CfpInviteEtp::class, 'getAllEtps']);
        Route::get('/getAllFrais', [CfpInviteEtp::class, 'getAllFrais']);
    });

    // Reservations

    // Apprenants
    Route::prefix('cfp/apprenants')->group(function () {
        Route::post('/', [ApprenantController::class, 'addEmp'])->name('cfp.apprenants.addEmp');
    });

    // Formateurs
    Route::prefix('cfp/forms')->group(function () {
        Route::get('/getAllForms', [FormateurController::class, 'getAllForms']);
    });

    // Gestion de dossier pour les projets
    Route::prefix('cfp/dossier')->group(function () {
        Route::get('/showAllDossier', [DossierController::class, 'getAllDossier'])->name('dossier.showAll');
        Route::post('/ajouter', [DossierController::class, 'store'])->name('dossier.store');

        Route::post('/document/ajouter/{idDossier}/{idProjet}', [DossierController::class, 'ajoutProjetInFolder'])->name('dossier.ajouter.fichier.dossier');
        Route::post('/document/upload/{idDossier}', [DossierController::class, 'uploadFichier'])->name('dossier.uploadFichier');
        Route::get('/document/projets/{idProjet}', [DossierController::class, 'getDocumentProjet'])->name('dossier.getDocumentProjet');
        Route::get('/document/section/', [DossierController::class, 'getSectionDocument'])->name('dossier.getDocumentSectionDocument');
        Route::get('/document/type/{idSectionDocument}', [DossierController::class, 'getTypeDocument'])->name('dossier.getTypeDocument');

        Route::get('/nombreDossier/{idDossier}', [DossierController::class, 'getNombreDocument'])->name('dossier.nombreDossier');
        Route::get('/getNombreProjet/{idDossier}', [DossierController::class, 'getNombreProjet'])->name('dossier.getNombreProjet');

        Route::get('/showSelected/{id}', [DossierController::class, 'getSelectedDossier'])->name('dossier.showSelected');
    });

    Route::prefix('cfp/projet/etpInter')->group(function () {
        Route::get('/getEtpAdded/{idProjet}', [ProjetInterController::class, 'getEtpAdded']);
        Route::get('/getApprenantProjetInter/{idProjet}', [ProjetInterController::class, 'getApprenantProjetInter']);
        Route::get('/getApprenantAddedInter/{idProjet}', [ProjetInterController::class, 'getApprenantAddedInter']);
        Route::delete('/{idProjet}/{idEtp}', [ProjetInterController::class, 'removeEtpInter']);
        Route::post('/{idProjet}/{idApprenant}/{idEtp}', [ProjetInterController::class, 'addApprenantInter']);
        Route::delete('/{idProjet}/{idApprenant}/{idEtp}', [ProjetInterController::class, 'removeApprsEtp']);
    });

    // Apprenant_project
    Route::prefix('cfp/projet/apprenants')->group(function () {
        Route::get('/getApprenantProjets/{idEtp}', [ApprenantController::class, 'getApprenantProjets']);
        Route::get('/getApprenantAdded/{idProjet}', [ApprenantController::class, 'getApprenantAdded']);
        Route::post('/{idProjet}/{idApprenant}', [ApprenantController::class, 'addApprenant']);
        Route::delete('/{idProjet}/{idApprenant}', [ApprenantController::class, 'removeApprenant']);
        Route::get('/getApprAddedInter/{idProjet}', [ApprenantController::class, 'getApprAddedInter']);
        Route::get('/checkPresences/{idProjet}', [ApprenantController::class, 'getPresencesBatch']);
        // Route::get('/checkPresence/{idProjet}/{idEmploye}', [ApprenantController::class, 'getPresenceUnique']);
    });

    Route::prefix('cfp/projet/evaluation')->group(function () {
        Route::post('/chaud', [EvaluationController::class, 'store'])->name('cfp.evaluation');
        Route::patch('/editEval', [EvaluationController::class, 'editEval'])->name('cfp.editEvaluation');
        Route::get('/checkEval/{idProjet}/{idEmploye}', [EvaluationController::class, 'checkEval']);
    });

    // cfp.salles
    Route::prefix('cfp/salles')->group(function () {
        Route::get('/getAllSalle/{idEtp}', [SalleCfpController::class, 'getAllSalle'])->name('cfp.salles.getAllSalle');
    });

    // Salles

    // cfp.seances
    Route::prefix('cfp/seances')->group(function () {
        Route::post('/', [SeanceController::class, 'store']);
        Route::get('/{idProjet}/getAllSeances', [SeanceController::class, 'getAllSeances']);
        Route::get('/{idProjet}/getInfoSeances', [SeanceController::class, 'getInfoSeances']);
        Route::get('/{idProjet}/getSeanceAndTotalTime', [SeanceController::class, 'getSeanceAndTotalTime']); // <===== Récupère le nombre de séance et sa durée en heure(TOTAL)
        Route::patch('/{idSeance}/update', [SeanceController::class, 'update']);
        Route::patch('/idCalendarLastSession/updateId', [SeanceController::class, 'updateIdCalendarLastSession']);
        Route::patch('/idListCalendarSession/updateIDs', [SeanceController::class, 'updateIdListCalendarSession']);

        Route::delete('/{idSeance}/delete', [SeanceController::class, 'destroy']);
        Route::get('/getLastFieldSeances', [SeanceController::class, 'getLastFieldSeances']);         // <===== Récupère le dernier élément de la table seances...
        Route::get('/getLastFieldVueSeances', [SeanceController::class, 'getLastFieldVueSeances']);   // <===== Récupère le dernier élément de la vue seances...
        Route::get('{idSeance}/getFieldVueSeanceOfId', [SeanceController::class, 'getFieldVueSeanceOfId']); // <===== Récupère le dernier élément de la vue seances en fonction de l'idSeance
        Route::post('/sendInvitationCalendar', [SeanceController::class, 'sendInvitationCalendar'])->name('send.invitation.calendar');
    });

    //cfp.profil

    //cfp.security
    Route::prefix('/cfp/security')->group(function () {
        Route::get('/', [SecurityController::class, 'index'])->name('cfp.security.index');
        Route::post('change-password', [SecurityController::class, 'changePassword'])->name('passwordUpdateCfp');
    });

    Route::prefix('/cfp/gallery')->group(function () {
        Route::post('/{idProjet}/addImage', [GalleryController::class, 'addImageGallery'])->name('cfp.gallery.addImage');
        Route::get('/', [GalleryController::class, 'getAllGallery'])->name('cfp.gallery.folder');
        Route::get('/folder', [GalleryController::class, 'getAllFolder']);
        Route::get('/folderFilter', [GalleryController::class, 'getAllFolderOrder']);
        Route::get('/getImage', [GalleryController::class, 'getGalleryByFolder']);
        Route::get('/image', [GalleryController::class, 'allImage']);
    });

    // Calendrier CFP

    // Particulier

    // EmployeCfps

    // AbnCfp

    // AgendaCfps

    Route::prefix('cfp/agendas')->group(function () {
        Route::get('/getEventsGroupBy', [AgendaCfpController::class, 'getEventsGroupBy']);
    });

    // ProjetInter

    // ApprenantExcel

    // EvaluationChaud

    // EvaluationFroid

    //Evaluation apprenant
    Route::post('/evaluation/aprrenant', [EvaluationController::class, 'save'])->name('evaluation.apprenant');
    Route::get('/evaluation/aprrenant/{idEmploye}/{idProjet}', [EvaluationController::class, 'get']);

    // Ressources materiels

    // Facture

    // facture payments


    //Analytic

    Route::prefix('cfp/emargement')->group(function () {
        Route::post('/', [EmargementController::class, 'store'])->name('emargements.cfp.store');
        Route::patch('/update/{idProjet}/{isPresent}', [EmargementController::class, 'update'])->name('emargements.update');
        Route::get('/{idProjet}', [EmargementController::class, 'edit'])->name('emargements.edit');
    });

    //customer drawer
    Route::prefix('/cfp')->group(function () {
        Route::get('/etp-drawer/{idEtp}', [ShowDrawerController::class, 'showEtpDrawer'])->name('cfp.etp-drawer.index');
    //     Route::get('/form-drawer/{idFormateur}', [ShowDrawerController::class, 'showFormDrawer'])->name('cfp.form-drawer.index');
        Route::get('/session-drawer/{idProjet}', [ShowDrawerController::class, 'showSessionDrawer'])->name('cfp.session-drawer.index');
        Route::get('/document-drawer/{idProjet}', [ShowDrawerController::class, 'showDocumentDrawer'])->name('cfp.document-drawer.index');
        Route::get('/dossier-drawer/{idProjet}', [ShowDrawerController::class, 'showDossierDrawer'])->name('cfp.dossier-drawer.index');
        Route::get('/apprenant-drawer/{idProjet}', [ShowDrawerController::class, 'showApprenantDrawer'])->name('cfp.apprenant-drawer.index');
        // Route::get('/etp-drawers/apprenant/{id}', [ShowDrawerController::class, 'showApprenantWithProjectCfp']);
        Route::get('/planreperage-drawer/{idProjet}', [ShowDrawerController::class, 'showPLanDeReperageDrawer'])->name('cfp.dossier-drawer.plan');
    });

    //restauration
    Route::post('/cfp/projets/addRestauration', [RestaurationController::class, 'store'])->name('restauration.store');
    Route::post('/cfp/projets/deleteRestauration/{idProjet}/{idRestauration}', [RestaurationController::class, 'deleteRestauration'])->name('restauration.delete');
    Route::get('/cfp/projets/getRestauration/{idProjet}', [RestaurationController::class, 'getRestauration'])->name('restauration.get');

    // FACTURE ACOMPTE

    // FACTURE PROFORMA

    // Sous-traitant project
    Route::prefix('/cfp/projects/subContractor')->group(function () {
        Route::get('/getAll', [SubContractorController::class, 'getAll']);
        Route::post('/{idProjet}/{idSubContractor}/assign', [SubContractorController::class, 'assign']);
        Route::get('/{idProjet}/getAssign', [SubContractorController::class, 'getAssign']);
        Route::delete('/{idSubContractor}/removeAssign', [SubContractorController::class, 'removeAssign']);
        Route::get('/', [SubContractorController::class, 'getSubContractorList'])->name('cfp.subContractor');
    });

    // Changement de langue
    Route::get('/locale/{lang}', [LocalisationController::class, 'setLang']);
});
