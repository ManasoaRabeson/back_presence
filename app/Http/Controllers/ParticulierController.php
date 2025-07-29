<?php

namespace App\Http\Controllers;

use App\Mail\ParticulierMail;
use App\Models\Customer;
use App\Models\Particulier;
use App\Services\ParticulierService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\Response;

class ParticulierController extends Controller
{
    public function idCfp()
    {
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        return $customer[0]->idCustomer;
    }

    public function store(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'part_cin' => 'required|max:50|unique:users,cin',
            'part_name' => 'required|max:240',
            'part_email' => 'required|max:150|unique:users,email'
        ]);

        if ($validate->fails()) {
            return response(['error' => $validate->messages()]);
        } else {
            DB::transaction(function () use ($req) {
                $idUser = DB::table('users')->insertGetId([
                    'name' => $req->part_name,
                    'firstName' => $req->part_firstname,
                    'email' => $req->part_email,
                    'cin' => $req->part_cin,
                    'password' => Hash::make('0000@#')
                ]);

                DB::table('particuliers')->insert([
                    'idParticulier' => $idUser,
                    'idTypeParticulier' => 2
                ]);

                DB::table('part_dependents')->insert([
                    'idParticulier' => $idUser,
                    'idCfp' => $this->idCfp()
                ]);

                DB::table('role_users')->insert([
                    'role_id'  => 10,
                    'user_id'  => $idUser,
                    'isActive' => 1,
                    'hasRole' => 1,
                    'user_is_in_service' => 1
                ]);

                $cfp = DB::table('customers')->select('idCustomer', 'customerName', 'customer_addr_lot AS customerAdress')->where('idCustomer', $this->idCfp())->first();
                $password = '0000@#';
                $mail = $req->part_email;

                Mail::to($req->part_email)->send(new ParticulierMail($cfp, $password, $mail));
            });

            return response(['success' => 'Ajouté avec succès'], Response::HTTP_CREATED);
        }
    }

    public function getFormAdded($idProjet)
    {
        $forms = DB::table('v_formateur_cfps')
            ->select('idProjet', 'idFormateur', 'name AS form_name', 'firstName AS form_firstname', 'photoForm AS form_photo', 'email AS form_email', 'initialNameForm AS form_initial_name', 'form_phone')
            ->groupBy('idProjet', 'idFormateur', 'name', 'firstName', 'photoForm', 'email', 'initialNameForm')
            ->where('idProjet', $idProjet)
            ->get();

        return response()->json(['forms' => $forms]);
    }

    public function getMiniCV($idFormateur)
    {
        try {
            if (!Auth::check()) {
                throw new Exception('User is not authenticated.');
            }

            // Vérifier que l'utilisateur a accès aux informations demandées
            $userId = Auth::user()->id;

            $form = DB::table('users')
                ->select('id', 'name', 'email', 'firstName', 'phone', 'photo')
                ->where('id', $idFormateur)
                ->first();

            // Expériences
            $exp = DB::table('experiences')
                ->select('id', 'idFormateur', 'Lieu_de_stage', 'Fonction', 'Date_debut', 'Date_fin', 'Lieu')
                ->where('idFormateur', $idFormateur)
                ->get();

            // Diplômes
            $dp = DB::table('diplomes')
                ->select('id', 'idFormateur', 'Ecole', 'Diplome', 'Domaine', 'Date_debut', 'Date_fin')
                ->where('idFormateur', $idFormateur)
                ->get();

            // Compétences
            $cpc = DB::table('competences')
                ->select('id', 'idFormateur', 'Competence', 'note')
                ->where('idFormateur', $idFormateur)
                ->get();

            // Langues
            $lg = DB::table('langues')
                ->select('id', 'idFormateur', 'Langue', 'note')
                ->where('idFormateur', $idFormateur)
                ->get();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => ['message' => $e->getMessage()]], 500);
        }

        // Retourner les données au format JSON
        return response()->json([
            'form' => $form,
            'experiences' => $exp,
            'diplomes' => $dp,
            'competences' => $cpc,
            'langues' => $lg
        ]);
    }

    public function index(ParticulierService $part)
    {
        $particuliers = $part->getAll(Customer::idCustomer());
        $url = 'cfp/invites/etp/list/';

        return view('Particuliers.index', compact('particuliers', 'url'));
    }

    public function projetParticulier()
    {
        return view('Particuliers.liste');
    }

    public function calendarParticulier()
    {
        // return view('formateurs.agendas.index');
        return view('Particuliers.agenda');
    }


    public function indexPart()
    {
        if (!Auth::check()) {
            abort(401, 'Vous devez être authentifié pour accéder à cette ressource.');
        }

        $userId = Auth::user()->id;

        $projects = DB::table('v_projet_particulier')
            ->select('idProjet', 'dateDebut', 'dateFin', 'module_name', 'etp_name', 'ville', 'project_status', 'project_description', 'project_type', DB::raw('DATE_FORMAT(dateDebut, "%M, %Y") AS headDate'), 'module_image', 'etp_logo', 'etp_initial_name', 'salle_name', 'salle_quartier', 'salle_code_postal', 'ville', 'modalite')
            ->where('idParticulier', $userId)
            ->where('headYear', Carbon::now()->format('Y'))
            ->orderBy('dateDebut', 'asc')
            ->get();
        // dd($projects);
        $projets = [];
        foreach ($projects as $project) {
            $projets[] = [
                'seanceCount' => $this->getSessionProject($project->idProjet),
                'formateurs' => $this->getFormProject($project->idProjet),
                // 'apprCount' => $this->getApprenantProject($project->idProjet, $project->idCfp_inter),
                'projectTotalPrice' => $this->getProjectTotalPrice($project->idProjet),
                'totalSessionHour' => $this->getSessionHour($project->idProjet),
                'idProjet' => $project->idProjet,
                'dateDebut' => $project->dateDebut,
                'dateFin' => $project->dateFin,
                'module_name' => $project->module_name,
                // 'etp_name' => $this->getEtpProjectInter($project->idProjet, $project->idCfp_inter),
                'ville' => $project->ville,
                'project_status' => $project->project_status,
                'project_type' => $project->project_type,
                // 'paiement' => $project->paiement,
                'modalite' => $project->modalite,
                'project_description' => $project->project_description,
                'headDate' => $project->headDate,
                'module_image' => $project->module_image,
                'etp_logo' => $project->etp_logo,
                'etp_initial_name' => $project->etp_initial_name,
                'salle_name' => $project->salle_name,
                'salle_quartier' => $project->salle_quartier,
                'salle_code_postal' => $project->salle_code_postal,
                'ville' => $project->ville,
                'etp_name_in_situ' => $project->etp_name
            ];
        }

        $projectDates = DB::table('v_projet_particulier')
            ->select(DB::raw('DATE_FORMAT(dateDebut, "%M, %Y") AS headDate'))
            ->groupBy('headDate')
            ->orderBy('dateDebut', 'asc')
            ->where('idParticulier', $userId)
            ->where('headYear', Carbon::now()->format('Y'))
            ->get();

        $projetCount = DB::table('v_projet_particulier')
            ->where('idParticulier', $userId)
            ->where('headYear', Carbon::now()
                ->format('Y'))
            ->count();

        return view('Particuliers.projets.index', compact('projects', 'projets', 'projectDates', 'projetCount'));
    }

    public function getSessionProject($idProjet)
    {
        $countSession = DB::table('v_seances')
            ->select('idSeance', 'dateSeance', 'heureDebut', 'id_google_seance', 'heureFin', 'idSalle', 'idProjet', 'salle_name', 'salle_quartier', 'project_title', 'project_description', 'idModule', 'module_name', 'ville')
            ->where('idProjet', $idProjet)
            ->get();

        return count($countSession);
    }

    public function getFormProject($idProjet)
    {
        $forms = DB::table('v_formateur_cfps')
            ->select('idFormateur', 'name AS form_name', 'firstName AS form_firstname', 'photoForm AS form_photo', 'initialNameForm AS form_initial_name')
            ->groupBy('idFormateur', 'name', 'firstName', 'photoForm', 'initialNameForm')
            ->where('idProjet', $idProjet)->get();

        return $forms->toArray();
    }

    public function getApprenantProject($idProjet, $idCfp_inter)
    {
        if ($idCfp_inter == null) {
            $apprs = DB::table('v_list_apprenants')
                ->select('idEmploye', 'emp_initial_name', 'emp_name', 'emp_firstname', 'emp_email', 'emp_photo', 'emp_matricule', 'emp_phone', 'etp_name')
                ->where('idProjet', $idProjet)
                ->orderBy('emp_name', 'asc')
                ->get();
        } elseif ($idCfp_inter != null) {
            $apprs = DB::table('v_list_apprenant_inter_added')
                ->select('idEmploye', 'emp_name', 'emp_firstname', 'emp_fonction', 'emp_email', 'emp_photo', 'emp_matricule', 'etp_name', 'idEtp')
                ->where('idProjet', $idProjet)
                ->orderBy('emp_name', 'asc')
                ->get();
        }

        return count($apprs);
    }

    public function getProjectTotalPrice($idProjet)
    {
        $projectPrice = DB::table('v_projet_cfps')
            ->select(DB::raw('SUM(project_price_pedagogique + project_price_annexe) AS project_total_price'))
            ->where('idProjet', $idProjet)
            ->first();

        return $projectPrice->project_total_price;
    }

    public function getProgramme($idModule)
    {
        $programmes = DB::table('programmes')->select('program_title', 'program_description', 'idModule')->where('idModule', $idModule)->get();

        return response()->json(['programmes' => $programmes]);
    }

    public function getModuleRessourceProject($idModule)
    {
        $module_ressources = DB::table('module_ressources')
            ->select('idModuleRessource', 'module_ressource_name', 'module_ressource_extension', 'idModule')
            ->where('idModule', $idModule)
            ->get();

        return response()->json(['module_ressources' => $module_ressources]);
    }
    public function getSessionHour($idProjet)
    {
        $countSessionHour = DB::table('v_seances')
            ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(intervalle_raw))) as sumHourSession')
            ->where('idProjet', $idProjet)
            ->first();

        return $countSessionHour->sumHourSession;
    }

    public function show($idProjet)
    {
        $projet = DB::table('v_projet_particulier')
            ->select('idProjet', 'dateDebut', 'dateFin', 'project_title', 'etp_name', 'ville', 'project_status', 'project_description',  'project_type', 'project_reference', 'modalite', 'etp_initial_name', 'etp_logo', 'idModule', 'module_name', 'module_image', 'module_description', 'salle_name', 'salle_rue', 'salle_quartier', 'salle_code_postal', 'ville')
            ->where('idProjet', $idProjet)
            ->first();

        $villes = DB::table('villes')->select('idVille', 'ville')->get();

        $seances = DB::table('v_seances')
            ->select('idSeance', 'dateSeance', 'heureDebut', 'heureFin', 'idProjet', 'idModule', 'intervalle_raw')
            ->where('idProjet', $idProjet)
            ->orderBy('dateSeance', 'asc')
            ->get();

        $countDate = DB::table('v_seances')
            ->select('idProjet', 'dateSeance', DB::raw('COUNT(*) as count'))
            ->where('idProjet', $idProjet)
            ->groupBy('dateSeance')
            ->get();

        $totalSession = DB::table('v_seances')
            ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(intervalle_raw))) as sumHourSession')
            ->where('idProjet', $idProjet)
            ->groupBy('idProjet')
            ->first();

        $modules = DB::table('mdls')
            ->select('idModule', 'moduleName AS module_name')
            ->where('moduleName', '!=', 'Default module')
            ->orderBy('moduleName', 'asc')
            ->get();

        $apprs = DB::table('v_list_apprenants')
            ->select('idEmploye', 'emp_initial_name', 'emp_name', 'emp_firstname', 'emp_fonction', 'emp_email', 'emp_photo', 'emp_matricule', 'etp_name')
            ->where('idProjet', $idProjet)
            ->orderBy('emp_name', 'asc')
            ->get();

        $materiels = DB::table('prestation_modules')
            ->select('idPrestation', 'prestation_name', 'idModule')
            ->get();

        $objectifs = DB::table('objectif_modules')->select('idObjectif', 'objectif', 'idModule')->get();

        return view('Particuliers.projets.details', compact('projet', 'villes', 'seances', 'modules', 'materiels', 'objectifs', 'apprs', 'totalSession', 'countDate'));
    }

    public function edit($id)
    {
        $particulier = DB::table('users')
            ->select('id', 'name', 'firstName', 'email', 'cin', 'phone', 'photo')
            ->where('id', $id)
            ->first();

        if (!$particulier) {
            return response([
                'status' => 404,
                'message' => "Aucun résultat trouvé !"
            ]);
        }

        return response([
            'status' => 200,
            'particulier' => $particulier
        ]);
    }

    public function update(Request $req, $idParticulier)
    {
        $validate = Validator::make($req->all(), [
            'name' => 'min:2|max:100',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        } else {
            DB::beginTransaction();
            DB::table('users')->where('id', $idParticulier)->update([
                'name' => $req->name,
                'firstname' => $req->firstname,
                'cin' => $req->cin,
                'phone' => $req->phone,
            ]);
            DB::commit();
            return response()->json(['success' => 'Succès']);
        }
    }

    public function destroy($id)
    {
        $particulier = Particulier::find($id);

        if (!$particulier) {
            return response([
                'status' => 404,
                'message' => "Aucun résultat trouvé !"
            ]);
        }

        try {
            DB::transaction(function () use ($id) {
                DB::table('part_dependents')->where('idParticulier', $id)->where('idCfp', Customer::idCustomer())->delete();
                DB::table('particuliers')->where('idParticulier', $id)->delete();
                DB::table('users')->where('id', $id)->delete();
                DB::table('role_users')->where('user_id', $id)->delete();
            });

            // Retourner un message de succès avec une redirection
            return back()->with('success', 'Particulier supprimé avec succès.');
        } catch (Exception $e) {
            return back()->with('error', 'Suppréssion impossible !');
        }
    }

    public function updatePhoto(Request $req, $idParticulier)
    {
        $driver = new Driver();

        $manager = new ImageManager($driver);
        $referent = DB::table('users')->select('photo')->where('id', $idParticulier)->first();

        if ($referent != null) {
            if (!empty($referent->photo)) {
                Storage::disk('do')->delete('img/particuliers/' . $referent->photo);
            }

            $folderPath = public_path('img/particuliers/');

            $image_parts = explode(";base64,", $req->image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $image = $manager->read($image_base64)->toWebp(25);

            $imageName = uniqid() . '.webp';
            $filePath = 'img/particuliers/' . $imageName;

            // Upload the image to DigitalOcean Space
            Storage::disk('do')->put($filePath, $image, 'public');

            DB::table('users')->where('id', $idParticulier)->update([
                'photo' => $imageName,
            ]);
            return response()->json([
                'success' => 'Image Uploaded Successfully',
                'imageName' =>  $imageName
            ]);
        }
    }
}
