<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\ApprenantController;
use App\Http\Controllers\EmargementController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\ShowDrawerController;
use Illuminate\Support\Facades\Route;

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::post('/auth/check-email', [AccountController::class, 'checkEmail']);
Route::post('/auth/check-entity-name', [AccountController::class, 'checkCustomerName']);
Route::post('/register/customer', [AccountController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', [LoginController::class, 'logout']);
});

// -------------CFP
Route::middleware(['auth:sanctum', 'isEmployeCfp'])->group(function () {
    Route::get('/countProject', [ProjetController::class, 'getCountProject']);
    Route::prefix('cfp/projets')->group(function () {
        Route::get('/list', [ProjetController::class, 'getProjectList'])->name('cfp.projets.list');
        Route::get('/{status}', [ProjetController::class, 'index']);
        // Presences
        Route::get('/{idProjet}/getDataPresence', [ProjetController::class, 'getDataPresence']);
    });

    // Apprenant_project
    Route::prefix('cfp/projet/apprenants')->group(function () {
        Route::get('/getApprenantAdded/{idProjet}', [ApprenantController::class, 'getApprenantAdded']);
        Route::get('/getApprAddedInter/{idProjet}', [ApprenantController::class, 'getApprAddedInter']);
    });

    // Emargements
    Route::prefix('cfp/emargement')->group(function () {
        Route::post('/', [EmargementController::class, 'store'])->name('emargements.cfp.store');
        Route::patch('/update/{idProjet}/{isPresent}', [EmargementController::class, 'update'])->name('emargements.update');
        Route::get('/{idProjet}', [EmargementController::class, 'edit'])->name('emargements.edit');
        Route::delete('/{idProjet}/seances/{idSeance}/employes/{id}', [EmargementController::class, 'destroy'])->name('emargements.destroy');
    });

    Route::prefix('/cfp')->group(function () {
        Route::get('/etp-drawer/{idEtp}', [ShowDrawerController::class, 'showEtpDrawer'])->name('cfp.etp-drawer.index');
        Route::get('/form-drawer/{idFormateur}', [ShowDrawerController::class, 'showFormDrawer'])->name('cfp.form-drawer.index');
        Route::get('/session-drawer/{idProjet}', [ShowDrawerController::class, 'showSessionDrawer'])->name('cfp.session-drawer.index');
        Route::get('/document-drawer/{idProjet}', [ShowDrawerController::class, 'showDocumentDrawer'])->name('cfp.document-drawer.index');
        Route::get('/dossier-drawer/{idProjet}', [ShowDrawerController::class, 'showDossierDrawer'])->name('cfp.dossier-drawer.index');
        Route::get('/apprenant-drawer/{idProjet}', [ShowDrawerController::class, 'showApprenantDrawer'])->name('cfp.apprenant-drawer.index');
        Route::get('/etp-drawers/apprenant/{id}', [ShowDrawerController::class, 'showApprenantWithProjectCfp']);
        Route::get('/planreperage-drawer/{idProjet}', [ShowDrawerController::class, 'showPLanDeReperageDrawer'])->name('cfp.dossier-drawer.plan');
    });

    Route::get('employes/projets/{idFormateur}/mini-cv', [ApprenantController::class, 'getMiniCv']);
});


// --------------FORMATEURS
Route::middleware(['auth:sanctum', 'isFormateur'])->group(function () {
    // Emargements
    Route::prefix('projetsForm/emargement')->group(function () {
        Route::post('/', [EmargementController::class, 'store']);
        Route::patch('/update/{idProjet}/{idPresent}', [EmargementController::class, 'update']);
        Route::get('/{idProjet}', [EmargementController::class, 'edit']);
    });
});
