<?php

namespace App\Http\Controllers;

use App\Services\DossierService;
use App\Services\UtilService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DossierController extends Controller
{

    protected $dossierService;
    protected $utilService;

    public function __construct(DossierService $dossierService, UtilService $utilService)
    {
        $this->dossierService = $dossierService;
        $this->utilService = $utilService;
    }

    public function idCfp()
    {
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        return $customer[0]->idCustomer;
    }

    public function new(Request $request)
    {
        $dossierSearch = $request->dossierSearch;
        return view('CFP.dossiers.folder', compact('dossierSearch'));
        // return view('CFP.dossiers.dossier', compact('dossierSearch'));
    }

    // service
    public function getAllDossier(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $idCfp = $this->idCfp();

        $dossiers = $this->dossierService->getAllDossiersByCfpAndYear($idCfp, $year);

        $allFilesList = view('components.all-files-list', [
            'dossiers' => $dossiers,
            'idProjet' => $request->idProjet,
        ])->render();

        if ($dossiers->isEmpty()) {
            return response()->json(['message' => 'Aucun dossier trouvé pour cet utilisateur.']);
        }

        return response()->json([
            'message' => 'Dossiers récupérés avec succès.',
            'dossiers' => $dossiers,
            'allFilesList' => $allFilesList,
        ]);
    }

    // service
    public function store(Request $request)
    {
        try {
            $request->validate([
                'dossier' => 'required|min:2|max:200',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $dossier = $request->input('dossier');
        $idCfp = Auth::user()->id;

        $idDossier = $this->dossierService->createDossier($dossier, $idCfp);

        return response()->json(
            [
                'success' => "Dossier créé avec succès : $dossier",
                'idDossier' => $idDossier,
            ]
        );
    }

    // service
    public function showByIdCfp(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $cfpId = $this->idCfp();

        $dossiers = $this->dossierService->getDossiersByCfpAndYear($cfpId, $year);

        if ($dossiers->isEmpty()) {
            return response()->json(['message' => 'Aucun dossier trouvé pour cet utilisateur.']);
        }

        return response()->json([
            'message' => 'Dossiers récupérés avec succès.',
            'dossiers' => $dossiers,
        ]);
    }

    // service
    public function edit(Request $request, $idDossier)
    {
        $request->validate([
            'nomDossier' => 'required|min:2|max:200',
        ]);

        $nouveauNom = $request->input('nomDossier');

        if ($this->dossierService->dossierExists($nouveauNom)) {
            return response()->json([
                'success' => false,
                'message' => 'Un dossier avec ce nom existe déjà.',
            ]);
        }

        $updated = $this->dossierService->updateDossier($idDossier, $nouveauNom);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Dossier mis à jour avec succès.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Dossier non trouvé.',
            ], 404);
        }
    }

    // service
    public function destroy($idDossier)
    {
        try {
            DB::transaction(function () use ($idDossier) {
                $this->dossierService->deleteFiles($idDossier);
                $this->dossierService->deleteRelatedProjets($idDossier);
                $deleted = $this->dossierService->deleteDossier($idDossier);

                if (!$deleted) {
                    throw new \Exception('Dossier non trouvé.');
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Dossier supprimé avec succès.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du dossier.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // service
    public function getDossierDetail($idDossier)
    {
        $idCfp = $this->idCfp();
        $entreprises = $this->dossierService->getEntreprisesDossierDetail($idDossier, $idCfp);
        $montantTotal = $this->dossierService->getMontantTotalDossierDetail($idDossier);
        $project_types = $this->dossierService->getProjectTypesDossierDetail($idDossier, $idCfp);
        $module_names = $this->dossierService->getModuleNamesDossierDetail($idDossier, $idCfp);
        $villes = $this->dossierService->getVillesDossierDetail($idDossier, $idCfp);
        $dateMinProjet = $this->dossierService->getDateMinProjetDossierDetail($idDossier);
        $dateMaxProjet = $this->dossierService->getDateMaxProjetDossierDetail($idDossier);
        $nombreDocument = $this->dossierService->getNombreDocumentDossierDetail($idDossier);
        $nbProjet = $this->dossierService->getNbProjetDossierDetail($idDossier);
        $apprenants = $this->dossierService->getApprenantCountDossierDetail($idDossier);
        $status = $this->dossierService->getPaymentStatusDossierDetail($idDossier);

        return response()->json([
            'apprenants' => $apprenants,
            'entreprises' => $entreprises,
            'montantTotal' => $montantTotal->montantTotal,
            'project_types' => $project_types,
            'dateMinProjet' => $dateMinProjet,
            'dateMaxProjet' => $dateMaxProjet,
            'villes' => $villes,
            'module_names' => $module_names,
            'projet_count' => $nbProjet,
            'nombreDocument' => $nombreDocument,
            'status' => $status
        ]);
    }

    function getFichier($idDossier)
    {
        $projects = DB::table('v_projet_cfps')
            ->select('idProjet', 'dateDebut', 'ville', 'idEtp', 'dateFin', 'project_status', 'module_name', 'etp_name', DB::raw('COALESCE(total_ht, 0) AS total_ht'), 'project_type', 'project_reference', DB::raw('DATE_FORMAT(dateDebut, "%M, %Y") AS headDate'), 'idCfp_inter', 'modalite', 'idModule')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('idCfp', $this->idCfp())
                        ->orWhere('idCfp_inter', $this->idCfp())
                        ->orWhere('idSubContractor', $this->idCfp());
                });
            })
            ->where('idDossier', $idDossier)
            ->where('project_is_trashed', 0)
            ->orderBy('dateDebut', 'asc')
            ->get();

        $projets = [];
        $totalHtSum = 0;
        $totalNbApprenants = 0;

        foreach ($projects as $project) {
            $totalHtSum += $project->total_ht;

            $totalNbApprenants += $this->getNombreApprenant($project->idProjet);

            $projets[] = [
                'projectIsPaid' => $this->projectIsPaid($project->idProjet),
                'nbApprenant' => $this->getNombreApprenant($project->idProjet),
                'idProjet' => $project->idProjet,
                'ville' => $project->ville,
                'idCfp_inter' => $project->idCfp_inter,
                'dateDebut' => $project->dateDebut,
                'dateFin' => $project->dateFin,
                'total_ht' => $this->utilService->formatPrice($project->total_ht),
                'module_name' => $project->module_name,
                'etp_name' => $this->getEtpProjectInter($project->idProjet, $project->idCfp_inter),
                'idEtp' => $project->idEtp,
                'project_status' => $project->project_status,
                'project_type' => $project->project_type,
                'project_reference' => $project->project_reference,
                'modalite' => $project->modalite,
                'etp_name_in_situ' => $project->etp_name,
                'idModule' => $project->idModule,
            ];
        }

        $documents = DB::table('v_document_dossier')
            ->where('idDossier', $idDossier)
            ->get();

        $nomDossier = DB::table('dossiers')
            ->select('nomDossier', 'idDossier')
            ->where('idDossier', $idDossier)
            ->first();

        return response()->json([
            'documents' => $documents,
            'nomDossier' => $nomDossier,
            'projets' => $projets,
            'total_ht_sum' => $this->utilService->formatPrice($totalHtSum),
            'totalNbApprenants' => $totalNbApprenants
        ]);
    }

    public function ajoutProjetInFolder($idDossier, $idProjet)
    {
        try {
            DB::table('projets')
                ->where('idProjet', $idProjet)
                ->update(['idDossier' => $idDossier]);

            return response()->json([
                'success' => 'Projet ajouté à ce dossier avec succès.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Échec de l\'ajout du projet dans ce dossier.',
            ]);
        }
    }

    public function showByDossier($idDossier)
    {
        $dossier = DB::table('dossiers')
            ->where('idDossier', $idDossier)
            ->first();

        return view('CFP.dossiers.fichier', compact('dossier'));
    }

    public function editDocument($idDocument, Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'type_document' => 'required|integer',
        ]);

        $updated = DB::table('documents')
            ->where('idDocument', $idDocument)
            ->update([
                'titre' => $request->input('titre'),
                'idTypeDocument' => $request->input('type_document'),
                'updated_at' => now()
            ]);

        if ($updated) {
            return response()->json(['success' => true, 'message' => 'Document mis à jour avec succès']);
        } else {
            return response()->json(['success' => false, 'message' => 'Échec de la mise à jour du document'], 500);
        }
    }

    public function destroyDocument($idDocument)
    {
        $document = DB::table('documents')
            ->where('idDocument', $idDocument)
            ->first();

        if ($document) {
            $filePath = $document->path;

            // Supprimer le fichier du stockage
            Storage::disk('do')->delete($filePath);
            DB::table('documents')
                ->where('idDocument', $idDocument)
                ->delete();

            return response()->json([
                'success' => 'Document supprimé avec succès.',
            ]);
        } else {
            return response()->json([
                'error' => 'Document non trouvé.',
            ]);
        }
    }

    public function supprimeProjetInFolder($idDossier, $idProjet)
    {
        $updated = DB::table('projets')
            ->where('idProjet', $idProjet)
            ->update(['idDossier' => null]);

        if ($updated) {
            return response()->json([
                'success' => 'Projet supprimé dans ce dossier avec succès.',
            ]);
        } else {
            return response()->json([
                'error' => 'Échec de suppression du projet dans ce dossier.',
            ]);
        }
    }

    public function uploadFichier(Request $request)
    {
        try {
            // Validation des données du formulaire
            $request->validate([
                'myFile' => 'required|mimes:pdf,txt,ppt,pptx,csv,xls,xlsx|max:2048',
                'title' => 'required|string|max:200',
                'section_document' => 'required',
                'type_document' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Les données ne sont pas valides. Assurez-vous que le fichier est un PDF et que les sélections sont correctes.']);
        }

        $file = $request->file('myFile');
        $idDossier = $request->route('idDossier'); // Récupération de l'ID dossier depuis l'URL

        $fileSize = $file->getSize();
        $fileSizeInMb = round($fileSize / (1024 * 1024), 2); // Conversion en Mo

        $fileExtension = $file->extension();

        $maxFileSize = 2 * 1024 * 1024;
        if ($fileSize > $maxFileSize) {
            return response()->json(['error' => 'Le fichier dépasse la taille maximale autorisée de 2 Mo.'], 413);
        }

        try {
            $disk = Storage::disk('do');
            $path = $disk->putFile('document/' . $idDossier, $file);

            $url = $disk->url($path);
            $filename = $file->getClientOriginalName();

            // Insertion du fichier et des métadonnées dans la base de données
            DB::table('documents')->insert([
                'titre' => $request->input('title'),
                'path' => $path,
                'filename' => $filename,
                'extension' => $fileExtension,
                'taille' => $fileSizeInMb,
                'idDossier' => $idDossier,
                'idTypeDocument' => $request->input('type_document'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors du téléchargement du fichier : ' . $e->getMessage()], 500);
        }

        return response()->json([
            'success' => 'Document ajouté avec succès',
        ]);
    }

    function getDocumentProjet($idProjet)
    {
        // Récupérer l'idDossier à partir du projet
        $getIdDossier = DB::table('projets')
            ->select('idDossier')
            ->where('idProjet', $idProjet)
            ->first();

        $idDossier = $getIdDossier ? $getIdDossier->idDossier : null;

        if ($idDossier === null) {
            return response()->json(['message' => 'Pas de dossier associé à ce projet']);
        } else {
            // Récupérer les documents avec les informations supplémentaires
            $documents = DB::table('v_document_dossier')
                ->where('idDossier', $idDossier)
                ->get();

            $nomDossier = DB::table('v_document_dossier')
                ->select('nomDossier')
                ->where('idDossier', $idDossier)
                ->first();

            return response()->json([
                'documents' => $documents,
                'nomDossier' => $nomDossier
            ]);
        }
    }

    // Récupérer les sections de documents
    public function getSectionDocument()
    {
        $sectionDocument = DB::table('section_documents')
            ->get();
        return response()->json([
            'sectionDocument' => $sectionDocument
        ]);
    }

    // Récupérer les types de documents en fonction de la section sélectionnée
    public function getTypeDocument($idSectionDocument)
    {
        $typeDocuments = DB::table('type_documents')
            ->where('idSectionDocument', $idSectionDocument)
            ->get();
        return response()->json([
            'typeDocuments' => $typeDocuments
        ]);
    }

    public function getNombreDocument($idDossier)
    {
        $nombreDocument = DB::table('documents')
            ->select(DB::raw('count(*) as nombreDocument'))
            ->where('idDossier', $idDossier)
            ->first();

        return response()->json([
            'nombreDocument' => $nombreDocument,
        ]);
    }

    function loadDossier()
    {
        $dossiers = DB::table('dossiers')
            ->where('dossiers.idCfp', $this->idCfp())
            ->groupBy('dossiers.idDossier', 'dossiers.nomDossier')
            ->orderBy('dossiers.nomDossier', 'asc')
            ->get();

        return response()->json(['dossiers' => $dossiers]);
    }

    public function getProjectsFolder(?int $idDossier = null)
    {
        $projectsQuery = DB::table('v_projet_cfps')
            ->select(
                'idProjet',
                'dateDebut',
                'idEtp',
                'dateFin',
                'module_name',
                'etp_name',
                'project_type',
                'project_reference',
                DB::raw('DATE_FORMAT(dateDebut, "%M, %Y") AS headDate'),
                'idCfp_inter',
                'modalite',
                'idModule'
            )
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('idCfp', $this->idCfp())
                        ->orWhere('idCfp_inter', $this->idCfp())
                        ->orWhere('idSubContractor', $this->idCfp());
                });
            })
            ->where('headYear', Carbon::now()->format('Y'))
            ->where('project_is_trashed', 0);

        if (is_null($idDossier)) {
            $projectsQuery->whereNull('idDossier');
        } else {
            $projectsQuery->where('idDossier', $idDossier);
        }

        $projects = $projectsQuery->orderBy('dateDebut', 'asc')->get();

        $projets = $projects->map(function ($project) {
            return [
                'idProjet' => $project->idProjet,
                'idCfp_inter' => $project->idCfp_inter,
                'dateDebut' => $project->dateDebut,
                'dateFin' => $project->dateFin,
                'module_name' => $project->module_name,
                'etp_name' => $this->getEtpProjectInter($project->idProjet, $project->idCfp_inter),
                'idEtp' => $project->idEtp,
                'project_type' => $project->project_type,
                'project_reference' => $project->project_reference,
                'modalite' => $project->modalite,
                'etp_name_in_situ' => $project->etp_name,
                'idModule' => $project->idModule,
            ];
        });

        $projectDatesQuery = DB::table('v_projet_cfps')
            ->select(DB::raw('DATE_FORMAT(dateDebut, "%M, %Y") AS headDate'), DB::raw('COUNT(DISTINCT(idProjet)) AS projet_nb'))
            ->groupBy('headDate')
            ->orderBy('dateDebut', 'asc')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('idCfp', $this->idCfp())
                        ->orWhere('idCfp_inter', $this->idCfp())
                        ->orWhere('idSubContractor', $this->idCfp());
                });
            })
            ->where('headYear', Carbon::now()->format('Y'))
            ->where('project_is_trashed', 0);

        if (is_null($idDossier)) {
            $projectDatesQuery->whereNull('idDossier');
        } else {
            $projectDatesQuery->where('idDossier', $idDossier);
        }

        $projectDates = $projectDatesQuery->get();

        $projetCount = DB::table('v_projet_cfps')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('idCfp', $this->idCfp())
                        ->orWhere('idCfp_inter', $this->idCfp())
                        ->orWhere('idSubContractor', $this->idCfp());
                });
            })
            ->where('headYear', Carbon::now()->format('Y'))
            ->where('project_is_trashed', 0);

        if (is_null($idDossier)) {
            $projetCount->whereNull('idDossier');
        } else {
            $projetCount->where('idDossier', $idDossier);
        }

        return response()->json([
            'projets' => $projets,
            'projectDates' => $projectDates,
            'projetCount' => $projetCount->count(),
        ]);
    }

    public function getNombreProjet($idDossier)
    {
        return response()->json([
            'projet_count' => $this->dossierService->getNbProjetDossierDetail($idDossier)
        ]);
    }

    public function getOneDocumentByFolder()
    {
        $folderdocuments = DB::table('documents as d1')
            ->join(
                DB::raw('(SELECT idDossier, MAX(created_at) as latestDocument FROM documents GROUP BY idDossier) as d2'),
                function ($join) {
                    $join->on('d1.idDossier', '=', 'd2.idDossier')
                        ->on('d1.created_at', '=', 'd2.latestDocument');
                }
            )
            ->leftJoin('dossiers as ds', 'd1.idDossier', '=', 'ds.idDossier')
            ->where('ds.idCfp', '=', $this->idCfp())
            ->select('d1.*', 'ds.nomDossier', DB::raw("DATE_FORMAT(d1.created_at, '%b %d, %Y, %l:%i %p') as date"))
            ->get();

        return response()->json([
            'folderdocuments' => $folderdocuments
        ]);
    }

    public function moveProjet(Request $request)
    {
        $idProjet = $request->input('idProjet');
        $idDossier = $request->input('idDossier');

        DB::table('projets')
            ->where('idProjet', $idProjet)
            ->update(['idDossier' => $idDossier]);

        return response()->json(['message' => 'Projet déplacé avec succès.']);
    }

    public function getNote($idDossier)
    {
        try {
            $nomDossier = DB::table('v_document_dossier')
                ->select('nomDossier', 'idDossier')
                ->where('idDossier', $idDossier)
                ->first();

            $note = DB::table('dossiers')->where('idDossier', $idDossier)->value('note');
            return response()->json(['success' => true, 'note' => $note, 'nomDossier' => $nomDossier]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la récupération de la note.'], 500);
        }
    }

    public function updateNote($idDossier, Request $request)
    {
        // Valider la note
        $request->validate([
            'note' => 'nullable|string'
        ]);

        try {
            // Mettre à jour la note dans la base de données
            DB::table('dossiers')
                ->where('idDossier', $idDossier)
                ->update(['note' => $request->note]);

            return response()->json(['success' => true, 'message' => 'Note mise à jour avec succès.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour.']);
        }
    }

    public function getSelectedDossier($idProjet)
    {

        $dossiersProject = DB::table('dossiers AS d')
            ->select('p.idProjet', 'p.idDossier', 'd.nomDossier')
            ->join('projets AS p', 'd.idDossier', '=', 'p.idDossier')
            ->where('p.idProjet', $idProjet)
            ->first();

        return response()->json([
            'message' => 'Dossiers récupérés avec succès.',
            'dossiersProject' => $dossiersProject
        ]);
    }

    public function projectIsPaid($idProjet)
    {
        $isPaid = DB::table('invoice_details as ID')
            ->select('I.invoice_status')
            ->join('invoices as I', 'I.idInvoice', '=', 'ID.idInvoice')
            ->join('invoice_payments as IP', 'IP.invoice_id', '=', 'ID.idInvoice')
            ->where('ID.idProjet', $idProjet)
            ->whereNotExists(function ($query) {
                $query->select('IL.id')
                    ->from('invoice_deleted as IL')
                    ->whereRaw('IL.idInvoice = ID.idInvoice');
            })
            ->first();
        $status = $isPaid->invoice_status ?? null;
        return response()->json([
            'status' => $status
        ]);
    }

    public function folderIsPaid(Request $request)
    {
        $idProjets = DB::table('projets')
            ->where('idDossier', $request->idDossier)
            ->pluck('idProjet');

        $status = 6;

        foreach ($idProjets as $projet) {
            switch ($this->projectIsPaidFolder($projet)) {
                case 0:
                    $status = 6;
                case 6:
                    $status = 6;
                    break;
                case 5:
                    $status = 5;
                    break;
                case 4:
                    $status = 4;
                    break;
                default:
                    break;
            }
        }

        if ($idProjets = null) {
            $status = 6;
        }

        return response()->json([
            'status' => $status
        ]);
    }

    public function projectIsPaidFolder($idProjet)
    {
        $isPaid = DB::table('invoice_details as ID')
            ->select('I.invoice_status')
            ->join('invoices as I', 'I.idInvoice', '=', 'ID.idInvoice')
            ->join('invoice_payments as IP', 'IP.invoice_id', '=', 'ID.idInvoice')
            ->where('ID.idProjet', $idProjet)
            ->whereNotExists(function ($query) {
                $query->select('IL.id')
                    ->from('invoice_deleted as IL')
                    ->whereRaw('IL.idInvoice = ID.idInvoice');
            })
            ->first();
        return $isPaid->invoice_status ?? 0;
    }

    // fonction peremt de télécharger les fichiers du digital ocean

    public function listFiles()
    {
        $folderPath = 'img/employes/'; // Dossier cible

        try {
            // Récupérer les fichiers du dossier
            $files = Storage::disk('do')->files('img/', true);

            if (empty($files)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Aucun fichier trouvé dans le dossier.',
                    'files' => []
                ], 200);
            }

            // Retourner la liste des fichiers
            return response()->json([
                'status' => 'success',
                'message' => 'Fichiers trouvés.',
                'files' => $files
            ], 200);
        } catch (\Exception $e) {
            // Gestion des erreurs
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des fichiers : ' . $e->getMessage(),
            ], 500);
        }
    }

    public function convertImages()
    {
        try {
            $sourceDir = 'C:\\Users\\PROACTIF\\Downloads\\Documents\\imagedigitalocean\\employes';
            $destinationDir = 'C:\\Users\\PROACTIF\\Downloads\\Documents\\imagedigitalocean\\converted\\employes';

            $this->convertFilesToWebp($sourceDir, $destinationDir);

            return response()->json(['message' => 'Conversion terminée avec succès.']);
        } catch (\Exception $e) {
            // Log détaillé pour les erreurs globales
            error_log("Erreur générale : {$e->getMessage()}");
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function testegd()
    {
        $phpVersion = PHP_VERSION;
        echo "Version de PHP : " . $phpVersion . PHP_EOL;

        if (strpos($phpVersion, '8.1') === 0) {
            echo "Vous utilisez PHP 8.1." . PHP_EOL;
        } else {
            echo "Votre version de PHP n'est pas 8.1." . PHP_EOL;
        }

        if (extension_loaded('gd')) {
            echo "L'extension GD est installée." . PHP_EOL;

            if (function_exists('imageavif')) {
                echo "La fonction imageavif() est disponible. AVIF est pris en charge par GD.";
            } else {
                echo "La fonction imageavif() n'est pas disponible. AVIF n'est pas pris en charge par GD.";
            }
        } else {
            echo "L'extension GD n'est pas installée.";
        }

        echo 'Version GD : ' . gd_info()['GD Version'] . PHP_EOL;
    }

    public function updateImageNames()
    {
        // Récupérer toutes les images depuis la table avec le Query Builder
        $images = DB::table('images')->get();

        foreach ($images as $image) {
            // Extraire la partie finale du chemin
            $urlParts = explode('/', $image->path);
            $fileName = end($urlParts);

            // Modifier l'extension en .webp
            $newFileName = preg_replace('/\.jpg$/', '.webp', $fileName);

            // Mettre à jour la colonne nomImage avec le Query Builder
            DB::table('images')
                ->where('idImages', $image->idImages)
                ->update(['nomImage' => $newFileName]);
        }

        return response()->json(['message' => 'Les noms des images ont été mis à jour avec succès.']);
    }

    public function convertImagesRecursive()
    {
        try {
            $sourceDir = 'C:\\Users\\PROACTIF\\Downloads\\Documents\\imagedigitalocean\\momentum';
            $destinationDir = 'C:\\Users\\PROACTIF\\Downloads\\Documents\\imagedigitalocean\\converted\\momentum';

            $this->convertFilesToWebpRecursive($sourceDir, $destinationDir);

            return response()->json(['message' => 'Conversion terminée avec succès.']);
        } catch (\Exception $e) {
            error_log("Erreur générale : {$e->getMessage()}");
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateDatabaseImageExtensions()
    {
        try {
            $tableName = 'salles'; // Nom de la table
            $columnName = 'salle_image'; // Colonne contenant le chemin du fichier

            $this->updateImageExtensionsInDatabase($tableName, $columnName);

            return response()->json(['message' => 'Mise à jour des extensions terminée avec succès.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // modifier le lien dans la base de données 
    function updateImagePathsInDatabase(): void
    {
        $tableName = 'images'; // Nom de la table
        $columnName = 'url';
        // Vérifier si la table et la colonne existent
        if (!Schema::hasTable($tableName) || !Schema::hasColumn($tableName, $columnName)) {
            throw new \Exception("La table ou la colonne spécifiée n'existe pas : $tableName.$columnName");
        }

        // Récupérer toutes les entrées avec des URLs correspondant à un certain modèle
        $images = DB::table($tableName)
            ->where($columnName, 'like', 'https://formafusionmg.ams3.digitaloceanspaces.com/formafusionmg/img/momentum/%')
            ->get();

        // Parcourir les entrées et mettre à jour l'URL avec `idProjet` et le nom de fichier
        foreach ($images as $image) {
            $oldUrl = $image->{$columnName};
            $idProjet = $image->idProjet; // Récupérer l'ID du projet associé à l'image

            // Extraire le nom du fichier depuis l'URL
            // $segments = explode('/', $oldUrl);
            // $fileName = end($segments); // Dernier segment de l'URL
            $fileName = $image->nomImage;

            // Construire la nouvelle URL
            $newUrl = "https://formafusionmg.ams3.digitaloceanspaces.com/formafusionmg/img/momentum/{$idProjet}/{$fileName}";

            // Mettre à jour dans la base de données
            DB::table($tableName)
                ->where('idImages', $image->idImages) // Supposons que 'idImages' soit la clé primaire
                ->update([$columnName => $newUrl]);

            echo "Mis à jour : $fileName\n";
        }
    }

    function getNombreApprenant($idProjet)
    {
        return DB::table('detail_apprenants')
            ->where('idProjet', $idProjet)
            ->count();
    }

    public function getEtpProjectInter($idProjet, $idCfp_inter)
    {
        if ($idCfp_inter == null) {
            $etp = DB::table('v_projet_cfps')
                ->select('idEtp', 'etp_name', 'etp_logo', 'etp_initial_name')
                ->where('idProjet', $idProjet)
                ->orderBy('etp_name', 'asc')
                ->get();
        } elseif ($idCfp_inter != null) {
            $etp = DB::table('v_list_entreprise_inter')
                ->select('idEtp', 'etp_name', 'etp_logo', 'etp_initial_name')
                ->where('idProjet', $idProjet)
                ->where('etp_name', '!=', 'null')
                ->orderBy('etp_name', 'asc')
                ->groupBy('idEtp')
                ->get();
        }

        return $etp->toArray();
    }
}
