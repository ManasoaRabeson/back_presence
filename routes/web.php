<?php

use App\Http\Controllers\AbonnementController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CategoriesReponsesController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\LoginUserController;
use App\Http\Controllers\QcmController;
use App\Http\Controllers\RepportingClientController;
use App\Http\Controllers\CreditsPaymentController;
use App\Http\Controllers\CreditsWalletController;
use App\Http\Controllers\QcmBaremeController;
use App\Http\Controllers\CommissionSettingsController;
use App\Http\Controllers\CommissionsReceivedController;
use App\Http\Controllers\CreditsPacksController;
use App\Http\Controllers\DevisesController;
use App\Http\Controllers\MarketPlaceController;
use App\Http\Controllers\QcmInvitationController;
use App\Http\Controllers\QcmInvitCampController;
use App\Http\Controllers\TransactionHistoryController;

// Account
Auth::routes();
Route::post('login', [LoginUserController::class, 'login']);

Route::controller(AccountController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('user', [AccountController::class, 'userLogin'])->name('user.login');
    Route::get('/login', [AccountController::class, 'userLogin'])->name('login');
    Route::get('user/login', [AccountController::class, 'userLoginWithPassword']);
    Route::get('user/register', [AccountController::class, 'userRegister']);
    Route::post('checkUser', [AccountController::class, 'checkUser'])->name('user.check');
    Route::get('succes/forgot', [AccountController::class, 'redirect'])->name('succes.forgot');
    Route::get('resend_password', 'forgot')->name('forgot');
    Route::get('register', [AccountController::class, 'register'])->name('user.register');
    Route::post('register', [AccountController::class, 'store'])->name('register.customer.store');
});

Route::get('/export/{id}', [FactureController::class, 'exportInvoicePdf'])->name('exportInvoicePublic');

// Testing Center routes
Route::get('/qcm/index', [QcmController::class, 'index_qcm'])->name('index.qcm'); # Index des QCM
Route::get('/qcm/public', [QcmController::class, 'index_qcm_not_auth'])->name('index.qcm.public'); # Index des QCM pour le public
Route::get('/show/{id}', [QcmController::class, 'show_qcm_details'])->name('show.qcm.publics'); # Route menant au détails du qcm en général pour le public
Route::get('/qcm/invitations-index', [QcmInvitationController::class, 'index_invitation'])->name('qcm.invitations.index'); # Route pour afficher la liste des invitations envoyés avec filtre
Route::delete('/qcm/invitations/{id}', [QcmInvitationController::class, 'destroyInvitationQcm'])->name('qcm.invitation.destroy'); # Route pour supprimer une invitation qcm

# Routes pour les Formateurs, Centre de formation (Cfp) et les employés de Cfp (EmpCfp)
Route::middleware(['isFormtOrCfpOrEmpCfp'])->group(function () {
    Route::prefix('qcm')->group(function () {
        // Routes pour CRUD QCM
        Route::get('/create', [QcmController::class, 'create_qcm'])->name('create.qcm.form'); # Route menant au formulaire de création d'un QCM
        Route::post('/store', [QcmController::class, 'storeQcm'])->name('store.qcm'); # Route mettre la création du qcm effective
        Route::get('/show/{id}', [QcmController::class, 'show_qcm_details'])->name('show.qcm.responsables'); # Route menant au détails du qcm en général pour les formateurs, les centres de formations et les centres de formations
        Route::get('/{id}/edit', [QcmController::class, 'edit_qcm'])->name('qcm.edit'); # Route vers le formulaire pour mettre à jour un QCM
        Route::post('/{id}/update', [QcmController::class, 'update_qcm'])->name('qcm.update'); # Route pour mettre la mise à jour effective
        Route::delete('/{id}/delete', [QcmController::class, 'destroy_qcm'])->name('qcm.destroy'); # Route pour supprimer un qcm avec ses questions et réponses
        Route::post('/{id}/update-status', [QcmController::class, 'updateStatus'])->name('qcm.update.status'); # Route pour le toggle button des qcm pour les mettrent actif ou non actif
        // Routes pour CRUD QCM
        // Routes pour les barèmes
        Route::get('/bareme/create/{id}', [QcmBaremeController::class, 'create_qcm_bareme'])->name('qcm.bareme.create'); # Route vers la vue pour créer / modifier le barème d'un qcm
        Route::get('/get/{id}', [QcmBaremeController::class, 'getBaremes']); # Route pour avoir les barèmes d'un qcm 
        Route::get('/qcm_bareme/{id}', [QcmBaremeController::class, 'getBareme']); # Appelée directement dans la vue "create_qcm_bareme"
        Route::post('/qcm-bareme/store/{id}', [QcmBaremeController::class, 'storeQcmBareme']); # Appelée directement dans la vue "create_qcm_bareme"
        Route::post('/qcm-bareme/update/{id}', [QcmBaremeController::class, 'updateQcmBareme']); # Appelée directement dans la vue "create_qcm_bareme"
        Route::delete('/qcm-bareme/delete/{id}', [QcmBaremeController::class, 'deleteQcmBareme']); # Appelée directement dans la vue "create_qcm_bareme"
        // Routes pour les barèmes
        // Routes Resultats
        Route::get('/{id}/list-apprenants/results', [QcmBaremeController::class, 'list_apprenants_test'])->name('qcm.results.students.list'); # Route pour avoir la liste des apprenants avec leur résultat après un test
        Route::get('/{id}/appr/{idAppr}/session/{idSession}/result', [QcmBaremeController::class, 'result_appr_qcm_one_session'])->name('qcm.result.one.student'); # Route pour avoir les résulats détaillés d'un apprenant après un QCM (les réponses qu'il a choisi, son total de points, son niveau)
        Route::get('/{id}/appr/{idAppr}/section/{idSection}/session/{idSession}/details-results', [QcmBaremeController::class, 'result_appr_sectiondetails_one_session'])->name('qcm.result.details.one.student'); # Route pour voir les détails des choix d'un utilisateur dans une section précises
        Route::get('/spider-chart/{id}/{idQCM}/{idSession}', [QcmBaremeController::class, 'showSpiderChart'])->name('qcm.spider-chart'); # Route menant vers le diagramme en araigné d'un apprenant après un test (avec vue)
        Route::get('/spider-chart-data/{id}/{idQCM}/{idSession}', [QcmBaremeController::class, 'getSpiderChartData']); # Route menant vers le diagramme en araigné d'un apprenant après un test pour les formateurs (avec modal)
        Route::get('/spider-chart-data-global/{id}/{idCtf}', [QcmBaremeController::class, 'getGlobalSpiderChartData'])->name('qcm.spider-chart.data.global'); # Route pour obtenir les données du graphique (avec modal)
        // Routes Resultats
    });

    // Routes pour les catégories de réponses ou les sections
    Route::prefix('ct-reponses')->group(function () {
        Route::get('/', [CategoriesReponsesController::class, 'index_categories_reponses'])->name('ct_reponses.index'); # Route vers la vue des catégories de réponses ou sections 
        Route::post('/store', [CategoriesReponsesController::class, 'store_categories_reponses'])->name('ct_reponses.store'); # Appelée directement dans la vue "index_ct_reponses"
        Route::put('/update/{id}', [CategoriesReponsesController::class, 'update_categories_reponses'])->name('ct_reponses.update'); # Appelée directement dans la vue "index_ct_reponses"
        Route::delete('/delete/{id}', [CategoriesReponsesController::class, 'delete_categories_reponses'])->name('ct_reponses.delete'); # Appelée directement dans la vue "index_ct_reponses"
        Route::get('/search', [CategoriesReponsesController::class, 'search']); # Route pour rechercher les categories de réponses
        Route::post('/create', [CategoriesReponsesController::class, 'create']); # Route pour sauvegarder les categories de réponses si il n'existe pas
    });
    // Routes pour les catégories de réponses ou les sections

    // Route pour résultat global de tous les Qcm d'un CTF
    Route::prefix('global-results/qcm')->group(function () {
        Route::get('/', [QcmBaremeController::class, 'index_global_results_allQcmOfUser'])->name('ctf.qcm.globalresults.index'); # Route vers la vue des résultats globals des qcm d'un centre de formation
        Route::get('/chart-data-global/{id}', [QcmBaremeController::class, 'getGlobalChartAllQcmOfCfp'])->name('qcm.chart.data.global'); # Route pour le diagramme en baton des résultats globals
    });
    // Route pour résultat global de tous les Qcm d'un CTF
});
# Routes pour les Formateurs, Centre de formation (Cfp) et les employés de Cfp (EmpCfp)

# Routes pour les employés et les particuliers
Route::middleware(['isEmpOrParticulier'])->group(function () {
    Route::prefix('qcm/solve')->group(function () {
        Route::get('/show/{id}/qcm_details', [QcmController::class, 'show_qcm_details'])->name('show.qcm.apprenant'); # Route menant au détails du qcm en général pour les employés et les partticuliers
        // Routes pour résoudre un QCM
        // Route::get('/{id}/respond/{questionIndex}', [QcmController::class, 'show_qcm_to_respond'])->name('qcm.show_respond'); # Route menant au formulaire pour la résolution d'un QCM (v5 de la fonction)
        Route::get('/{id}/respond/{questionIndex?}', [QcmController::class, 'show_qcm_to_respond'])->name('qcm.show_respond'); # Route menant au formulaire pour la résolution d'un QCM (v6 de la fonction)
        Route::get('/{id}/respond/{questionIndex?}/invitation/{invitationId}', [QcmController::class, 'show_qcm_to_respond'])->name('qcm.show_respond_with_invitation'); # Route menant au formulaire pour la résolution d'un QCM pour les invitations (v6 de la fonction)
        Route::post('/{id}/respond/save', [QcmController::class, 'save_qcm_response'])->name('qcm.save_response'); # Route pour sauvegarder les réponses et naviguer Suivant/Précédente question
        Route::get('/{id}/review', [QcmController::class, 'review_qcm'])->name('qcm.review'); # Route pour revoir les réponses avant de soumettre les réponses choisies et ainsi donner la possibilité de modifier ces choix à l'utilisateur
        Route::post('/{id}/submit', [QcmController::class, 'submit_qcm'])->name('qcm.submit'); # Route pour soumettre et calculer les résultats de l'utilisateur ayant effectuer le test
        Route::get('/{id}/results', [QcmController::class, 'show_qcm_results_after_test'])->name('qcm.results'); # Route pour afficher les résultats de l'utilisateur au QCM juste après le test
        // Routes pour résoudre un QCM
    });
    // Routes pour les résultats d'un apprenant pour un qcm
    Route::prefix('/qcm')->group(function () {
        Route::get('/{id}/appr/{idAppr}/results', [QcmBaremeController::class, 'results_one_appr_test'])->name('results.one.appr.one.qcm'); # Route pour avoir les résultats d'un apprenant dans un seul qcm
        Route::get('/{id}/appr/{idAppr}/session/{idSession}', [QcmBaremeController::class, 'result_appr_qcm_one_session'])->name('qcm.result.one.appr'); # Route pour avoir les résulats détaillés d'un apprenant après un QCM (les réponses qu'il a choisi, son total de points, son niveau)
        // Route::get('/{id}/appr/{idAppr}/section/{idSection}/session/{idSession}/results-details', [QcmBaremeController::class, 'result_appr_sectiondetails_one_session'])->name('qcm.result.details.one.appr'); # Route pour voir les détails des choix d'un utilisateur dans une section précises (tsy ampiasaina tampoka)
        // Route pour les Resultats
        Route::get('/spider-chart-data/appr/{id}/{idQCM}/{idSession}', [QcmBaremeController::class, 'getSpiderChartData']); # Route menant vers le diagramme en araigné d'un apprenant après un test pour les apprenants (avec modal)
        // Route pour les Resultats
    });
    // Routes pour les résultats d'un apprenant pour un qcm
});
# Routes pour les employés et les particuliers

// Routes test pour les crédits (test efa mety)
Route::get('/credits-wallet/{id}', [CreditsWalletController::class, 'show_user_credit_wallet'])->name('user.credits.wallet'); # Route pour avoir les crédits d'un utilisateur
Route::get('/credits-wallet/operation/{id}', [CreditsWalletController::class, 'showWalletOperationForm'])->name('wallet.operation.form'); # Route vers le formulaire de créditation du compte d'un utilisateur
Route::post('/credits-wallet/crediter/{id}', [CreditsWalletController::class, 'crediterCompte'])->name('credit.account'); # Route pour créditer un compte d'utilisateur
Route::post('/credits-wallet/debiter/{id}', [CreditsWalletController::class, 'debiterCompte'])->name('debit.account'); # Route pour débiter un compte d'utilisateur
Route::get('/credits-wallet/operation/{id}/multi', [CreditsWalletController::class, 'showWalletOperationFormMultiEmp'])->name('wallet.operation.form.multi'); # Route vers le formulaire de créditation du compte des employés d'une entreprise
Route::post('/approvisionner/emp', [CreditsWalletController::class, 'crediterCompteEmp'])->name('multi.operation.entreprise'); # Route pour effectuer l'approvisionnement des employés d'une entreprise
// Routes test pour les crédits (test efa mety)

// Routes test pour les paiement (cb, chèque, virement bancaire) (mety)
Route::get('/credits-pack/buy', [CreditsPacksController::class, 'index_buy_credits_pack'])->name('credits.index'); # Route pour afficher les packs de crédits disponible à l'achat
Route::get('/credits/{id}/recap', [CreditsPacksController::class, 'recapPurchase'])->name('credits.recap'); # Route pour afficher le recap d'un pack de crédits disponible à l'achat, avant de l'acheter
Route::post('/credits/{id}/process', [CreditsPacksController::class, 'processPurchase'])->name('credits.process'); # Route pour procéder à l'achat de crédits
Route::get('/credits/history', [CreditsPacksController::class, 'history'])->name('credits.history'); # Route pour voir l'historique d'achat de crédits
Route::get('/credits-payment/invoice/{id}', [CreditsPaymentController::class, 'oneCreditsPaymentInvoice'])->name('credits-payment.invoice'); # Route pour afficher la facture de paiement d'achat de crédits (mety)
// Routes test pour les paiement (cb, chèque, virement bancaire) (mety)

// Routes test pour les chiffres d'affaires
Route::get('/sales-revenue', [CreditsPaymentController::class, 'getSalesRevenus'])->name('sales.revenue'); # Route pour les chiffres d'affaires dans la vente de crédits (return json value)
// Routes test pour les chiffres d'affaires

// Routes test pour les invitations par mail
Route::get('/qcm/invitations/{id}', [QcmInvitationController::class, 'getInvitation'])->name('qcm.invitations.details'); # Route test pour afficher le détails d'une invitation
// Routes test pour les invitations par mail

// Routes pour index historique de transactions (efa mandeha)
Route::get('/credits-payments', [CreditsPaymentController::class, 'index_credits_payments'])->name('credits-payments.index'); # Route menant à la vue pour l'index des historiques de transactions d'achat de crédits (new)
Route::get('/credits-payments/filter', [CreditsPaymentController::class, 'filterTransactions'])->name('credits-payments.filter'); # Route pour le filtre des transactions
// Routes pour index historique de transactions (efa mandeha)

// Routes test pour les commissions
Route::get('/commissions/dashboard', [CommissionsReceivedController::class, 'dashboard_commissions'])->name('commissions.dashboard'); # Route menant au dashboard des commissions
Route::get('/commissions', [CommissionsReceivedController::class, 'index_commissions'])->name('commissions.index'); # Route menant à l'index des commissions
Route::get('/commissions/details/{id}', [CommissionsReceivedController::class, 'getCommissionDetails'])->name('commissions.details'); # Route pour les détails d'une commission
// Routes test pour les commissions

// Commission Settings Routes crud test (mandeha)
Route::get('/commission-settings', [CommissionSettingsController::class, 'index'])->name('commission-settings.index'); # Route pour les index des paramètres de commissions
Route::post('/commission-settings', [CommissionSettingsController::class, 'store'])->name('commission-settings.store'); # Route pour stocker un nouveau paramètre de commission
Route::get('/commission-settings/{id}/edit', [CommissionSettingsController::class, 'edit'])->name('commission-settings.edit'); # Route vers la vue pour éditer un paramètre de commission
Route::post('/commission-settings/{id}', [CommissionSettingsController::class, 'update'])->name('commission-settings.update'); # Route pour màj un paramètre de commission
Route::delete('/commission-settings/{id}/delete', [CommissionSettingsController::class, 'destroy'])->name('commission-settings.destroy'); # Route pour supprimer un paramètre de commission
// Commission Settings Routes crud test (mandeha)

// Routes test pour campagne d'invitation QCM (improvement to do)
Route::get('/qcm/invitation/campaign', [QcmInvitCampController::class, 'index_campaign'])->name('qcm.invitation.campaign.index'); # Route menant à l'index des campagnes d'invitation
Route::get('/qcm/invitation/{id}', [QcmInvitCampController::class, 'getInvitationDetails'])->name('qcm.invitation.details'); # Route pour avoir une invitation
Route::delete('/qcm/invit-camp/{id}', [QcmInvitCampController::class, 'destroy'])->name('qcm.invit-camp.destroy'); # Route pour supprimer une campagne

Route::prefix('qcm/invitation/campaign')->name('qcm.invitation.campaign.')->group(function () {
    // Step 1: Campaign Name
    Route::get('/step-one', [QcmInvitCampController::class, 'stepOne'])->name('step-one'); # Route menant vers la vue de l'étape 1
    Route::post('/step-one', [QcmInvitCampController::class, 'storeStepOne'])->name('store-step-one'); # Route pour stocker les choix de l'étape 1 en session
    Route::post('/qcm/invitation/campaign/save-draft-name', [QcmInvitCampController::class, 'saveDraftName'])->name('save-draft-name'); # Route pour sauvegarder le nom de la campagne en session

    // Step 2: QCM Selection
    Route::get('/step-two', [QcmInvitCampController::class, 'stepTwo'])->name('step-two'); # Route menant vers la vue de l'étape 2
    Route::post('/step-two', [QcmInvitCampController::class, 'storeStepTwo'])->name('store-step-two'); # Route pour stocker les choix de l'étape 2 en session

    // Step 3: Employee Selection
    Route::get('/step-three', [QcmInvitCampController::class, 'stepThree'])->name('step-three'); # Route menant vers la vue de l'étape 3
    Route::post('/step-three', [QcmInvitCampController::class, 'storeStepThree'])->name('store-step-three'); # Route pour stocker les choix de l'étape 3 en session (fonction non disponible)
    Route::post('/ajax-update-employees', [QcmInvitCampController::class, 'ajaxUpdateEmployees'])->name('ajax-update-employees'); # Rpute pour l ajax de l employe

    // Step 4 : Dates (Valid From and Valid To) with optionnal messages
    Route::post('/invitation-campaign/save-step-four-data', [QcmInvitCampController::class, 'saveStepFourData'])->name('save-step-four-data'); # Route pour sauvegarder les données de l'étape 4
    Route::get('/step-four', [QcmInvitCampController::class, 'stepFour'])->name('step-four'); # Route menant vers la vue de l'étape 4
    
    Route::post('/create', [QcmInvitCampController::class, 'createCampaign'])->name('create'); # Route pour créer la campagne d invitation

    Route::get('/back-to-step-one', [QcmInvitCampController::class, 'backToStepOne'])->name('back-to-step-one'); # Go back routes to step one
    Route::get('/back-to-step-two', [QcmInvitCampController::class, 'backToStepTwo'])->name('back-to-step-two'); # Go back routes to step two
    Route::get('/back-to-step-three', [QcmInvitCampController::class, 'backToStepThree'])->name('back-to-step-three'); # Go back routes to step three
});

Route::get('/credits-payments/invoice/{id}/pdf', [CreditsPaymentController::class, 'generateCreditsInvoicePDF'])->name('credits.payments.invoice.pdf'); # Route pour générer la facture d'achat de crédits en pdf

// Routes test pour campagne d'invitation QCM (improvement to do)

Route::post('/devises/store', [DevisesController::class, 'store'])->name('devises.store'); # Route pour stocker une devise

Route::get('/qcm/{id}/dashboard', [App\Http\Controllers\QcmController::class, 'dashboardQcmResults'])->name('qcm.dashboard.results'); # Route pour le dashboard des résultats d'un QCM (test)

Route::get('/testing-center/invitations/dashboard', [QcmInvitationController::class, 'dashboardInvitations'])->name('testing-center.invitations.dashboard'); # Route pour le dashboard des invitations (test) [Vue pas encore implémentée pour les SuperAdmin]
Route::get('/qcm/{id}/apprenant/{idApprenant}/session/{idSession}/rapport', [QcmController::class, 'getAbilitiesReport'])->name('qcm.abilities.report'); # Route pour le rapport des compétences d'un apprenant après un test qcm
Route::get('/qcm/{id}/apprenant/{idApprenant}/session/{idSession}/rapport/pdf', [QcmController::class, 'exportAbilitiesReportPDF'])->name('qcm.abilities.report.pdf'); # Route pour exporter le rapport des compétences d'un apprenant après un test qcm en pdf

Route::get('/qcm/invitation/{qcmId}/{invitationId}', [QcmController::class, 'handleInvitationStart'])->name('qcm.invitation.start'); # Route pour gérer le début d'une invitation si l'utilisateur n'est pas connecté ou oui il l'est

// Routes pour les transactions de crédits (crédit ou débit) improvement 27-2-2025
Route::get('/user-transactions', [TransactionHistoryController::class, 'userTransactions'])->name('transactions.user'); # Route pour les transactions d'un utilisateur (vues pas encore implémentées)
Route::post('/transactions/details', [TransactionHistoryController::class, 'getTransactionDetails'])->name('transactions.details'); # Route pour les détails d'une transaction (vues pas encore implémentées))
// Routes pour les transactions de crédits (crédit ou débit) improvement 27-2-2025

// Testing Center routes

Route::prefix('formation')->group(function () {
    Route::get('/', [ClientController::class, 'indexFormation']);
    Route::get('/search', [MarketPlaceController::class, 'search'])->name('search.formation');
    Route::get('/search/json', [MarketPlaceController::class, 'searchJson'])->name('searchJson.formation');
    Route::get('/session_guaranteed', [MarketPlaceController::class, 'searchSessionGuaranteed'])->name('sessionGuaranteed.formation');
    Route::get('/filterCourse', [MarketPlaceController::class, 'filterCourse'])->name('filter.course.formation');
    Route::get('/filterCourseGuaranteed', [MarketPlaceController::class, 'filterCourseGuaranteed'])->name('filter.courseGuaranteed');
});

Route::get('/formation/category/{id}', [ClientController::class, 'getFormationByCategory']);
Route::get('/formation_by_numerika/{cours}', [ClientController::class, 'formationByNumerika']);
Route::get('/landing', [ClientController::class, 'landing']);
Route::get('/formation/detail/{id}', [ClientController::class, 'getDetailFormation'])->name('formation.detail');
Route::get('/formation/exportPdf/{id}', [ClientController::class, 'exportPdf'])->name('formation.exportPdf');
Route::get('/formation_inter/detail/{id}/{idProjet}', [ClientController::class, 'getDetailFormationInter'])->name('formationInter.detail');
Route::get('/user/reset_password', [ClientController::class, 'resetPassword'])->name('resetPassword');
Route::get('/demande_devis/{id}/{idModule}', [ClientController::class, 'indexQuote']);
Route::get('/demande_devis/company/{id}/{idModule}', [ClientController::class, 'quoteCompany']);
Route::post('/demande_devis/company', [ClientController::class, 'sendDemandCompany']);
Route::get('/demande_devis/individual/{id}/{idModule}', [ClientController::class, 'quoteIndividual']);
Route::post('/demande_devis/individual', [ClientController::class, 'sendDemandIndividual']);
Route::get('/organisme', [ClientController::class, 'organisme']);
Route::get('/liste_organisme', [ClientController::class, 'listeOrganisme']);
Route::get('/organisme_formation/{id}', [ClientController::class, 'formationInfo'])->name('formationInfo');
Route::get('/formation/reservation/{project_id}', [ClientController::class, 'reservation'])->name('reservation');
Route::post('/client/register', [ClientController::class, 'register'])->name('register.client');
Route::post('/client/login', [ClientController::class, 'login'])->name('login.client');
Route::post('/reservation/store', [ClientController::class, 'reservationStore'])->name('reservation.store');
Route::get('/evaluation/{idModule}', [ClientController::class, 'getEval']);
Route::get('formation/reservation_confirmed/{reservation_id}', [ClientController::class, 'reservationConfirmed'])->name('reservation.confirmed');
Route::get('/detail_formation', [ClientController::class, 'detailFormation'])->name('detail.formation');
Route::get('/vous_etes', [ClientController::class, 'vousEtes'])->name('detail.vousetes');
Route::get('/vous_etes_formateur', [ClientController::class, 'vousEtesFormateur'])->name('detail.formateur');
Route::get('/vous_etes_etp', [ClientController::class, 'vousEtesEtp'])->name('detail.etp');
Route::get('/vous_etes_cfp', [ClientController::class, 'vousEtesCfp'])->name('detail.cfp');
Route::get('/vous_etes_apprenant', [ClientController::class, 'vousEtesApprenant'])->name('detail.apprenant');
Route::get('/vous_etes_particulier', [ClientController::class, 'vousEtesParticulier'])->name('detail.particulier');
Route::get('/vous_etes_cfp2', [ClientController::class, 'vousEtesCfp2'])->name('detail.cfp2');

Route::get('/contact', [ClientController::class, 'contacterFormaFUsion'])->name('contact.formafusion');
Route::post('/send-email', [ClientController::class, 'sendEmail'])->name('send.email');

Route::get('/formation/searchJsonOnlyKey', [ClientController::class, 'searchJsonOnlyKey'])->name('searchJsonOnlyKey.formation');

Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('test_reporting/{year}/{id_customer}', [RepportingClientController::class, 'getLearnerByYear']);

Route::get('markAsRead/{idNotif}', [AbonnementController::class, 'makeReadNotification'])->name('notifications.markAsRead');
