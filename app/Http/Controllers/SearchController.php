<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Traits\CourseQuery;
use App\Traits\EmployeQuery;
use App\Traits\EvaluationQuery;
use App\Traits\FolderQuery;
use App\Traits\PlaceQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Traits\Project;
use App\Traits\ReferentQuery;
use Carbon\Carbon;
use App\Traits\SearchQuery;
use App\Traits\TrainerQuery;

class SearchController extends Controller
{
    use Project;

    use SearchQuery;

    use PlaceQuery;

    use TrainerQuery;

    use ReferentQuery;

    use CourseQuery;

    use FolderQuery;

    use EvaluationQuery;

    use EmployeQuery;

    public function searchGenerality(Request $request)
    {
        $key = $request->key;
        Session::put('key', $key);

        $project = [
            'project' => [
                'count' => count($this->getProject($key)),
                'route' => route('searchIndexProjet')
            ]
        ];

        $trainer = [
            'trainer' => [
                'count' => count($this->getTrainer($key)),
                'route' => route('searchIndexFormateur')
            ]
        ];

        $entreprise = [
            'entreprise' => [
                'count' => count($this->getEntreprise($key)),
                'route' => route('searchIndexClient')
            ]
        ];

        $learner = [
            'learner' => [
                'count' => count($this->getLearner($key)),
                'route' => route('searchIndexApprenant')
            ]
        ];

        $referent = [
            'referent' => [
                'count' => count($this->getReferent($key)),
                'route' => route('searchIndexReferent')
            ]
        ];

        $course = [
            'course' => [
                'count' => count($this->getCourse($key)),
                'route' => route('searchIndexCourse')
            ]
        ];

        $referent_customer = [
            'referent' => [
                'count' => count($this->getReferentCustomer($key)),
                'route' => route('searchIndexReferentCustomer')
            ]
        ];

        $project_reference = [
            'project' => [
                'count' => count($this->getProjectByReference($key)),
                'route' => route('searchIndexReferenceProject')
            ]
        ];

        $projectCity = [
            'project' => [
                'count' => count($this->getProjectByCity($key)),
                'route' => route('searchIndexProjectByCity')
            ]
        ];

        $projectPlace = [
            'project' => [
                'count' => count($this->getProjectByPlace($key)),
                'route' => route('searchIndexProjectByPlace')
            ]
        ];

        $projectNeighborhood = [
            'project' => [
                'count' => count($this->getProjectByNeighborhood($key)),
                'route' => route('searchIndexProjectByNeighborhood')
            ]
        ];

        $folder = [
            'folder' => [
                'count' => count($this->getFolder($key)),
                'route' => route('searchIndexFolder')
            ]
        ];

        // $particular = [
        //     'particular' => [
        //         'count' => count($this->getParticular($key)),
        //         'route' => route('searchIndexParticular')
        //     ]
        // ];


        // $place = [
        //     'place' => [
        //         'count' => count($this->getPlace($key)),
        //         'route' => route('searchIndexLieu')
        //     ]
        // ];

        $project_with_client = $this->getProjectWithEtp($key);

        $ville_codeds = DB::table('ville_codeds')->get();

        return view('searchGenerality', compact('ville_codeds', 'project', 'trainer', 'entreprise', 'learner', 'referent', 'project_with_client', 'course', 'referent_customer', 'project_reference', 'projectNeighborhood', 'projectPlace', 'projectCity', 'folder'));
    }

    public function searchIndexParticular()
    {
        $particulars = $this->getParticular(Session::get('key'));
        $particular_count = count($particulars);
        return view('recherche.searchIndexParticular', compact('particulars', 'particular_count'));
    }

    // private function getParticular($key)
    // {

    //     $particulars = DB::table('v_list_particuliers')
    //         ->select('idParticulier', 'part_name', 'part_firstname', 'part_cin', 'part_email', 'part_photo')
    //         ->where(function ($query) use ($key) {
    //             $query->where('part_name', 'like', "%$key%")
    //                 ->orWhere('part_firstname', 'like', "%$key%")
    //                 ->orWhere(DB::raw('CONCAT(part_name, " ", COALESCE(part_firstname, ""))'), 'like', "%$key%");
    //         })
    //         // ->where('idCfp', Customer::idCustomer())
    //         ->get();


    //     return $particulars;
    // }

    public function searchIndexApprenant(Request $request)
    {
        $learners = $this->getLearner(Session::get('key'));
        $learner_count = count($learners);
        return view('recherche.searchIndexApprenant', compact('learners', 'learner_count'));
    }

    public function searchIndexFolder()
    {
        $folders = $this->getFolder(Session::get('key'));
        $folder_count = count($folders);
        return view('recherche.searchIndexFolder', compact('folders', 'folder_count'));
    }

    public function searchIndexProjectByCity()
    {
        $projects = $this->getProjectByCity(Session::get('key'));
        $project_count = count($projects);
        return view('recherche.searchIndexProjectByPlace', compact('projects', 'project_count'));
    }

    public function searchIndexProjectByNeighborhood()
    {
        $projects = $this->getProjectByNeighborhood(Session::get('key'));
        $project_count = count($projects);
        return view('recherche.searchIndexProjectByPlace', compact('projects', 'project_count'));
    }

    public function searchIndexProjectByPlace()
    {
        $projects = $this->getProjectByPlace(Session::get('key'));
        $project_count = count($projects);
        return view('recherche.searchIndexProjectByPlace', compact('projects', 'project_count'));
    }

    public function searchIndexReferenceProject()
    {
        $projects = $this->getProjectByReference(Session::get('key'));
        $project_count = count($projects);
        return view('recherche.searchIndexProjectByReference', compact('projects', 'project_count'));
    }

    public function searchIndexCourse()
    {
        $courses = $this->getCourse(Session::get('key'));
        $course_count = count($courses);
        return view('recherche.searchIndexCourse', compact('courses', 'course_count'));
    }

    public function searchGeneralityEtp(Request $request)
    {
        $key = $request->key;
        Session::put('key', $key);

        $project = [
            'project' => [
                'count' => count($this->getProject($key)),
                'route' => route('searchIndexProjetEtp')
            ]
        ];

        $trainer = [
            'trainer' => [
                'count' => count($this->getTrainer($key)),
                'route' => route('searchIndexFormateurEtp')
            ]
        ];

        $cfp = [
            'cfp' => [
                'count' => count($this->getCfp($key)),
                'route' => route('serachIndexCfp')
            ]
        ];

        $employe = [
            'employe' => [
                'count' => count($this->getEmploye($key)),
                'route' => route('searchIndexEmployeEtp')
            ]
        ];

        $referent = [
            'referent' => [
                'count' => count($this->getReferent($key)),
                'route' => route('searchIndexReferentEtp')
            ]
        ];


        // $place = [
        //     'place' => [
        //         'count' => count($this->getPlace($key)),
        //         'route' => route('searchIndexLieuEtp')
        //     ]
        // ];

        return view('searchGeneralityEtp', compact('project', 'trainer', 'cfp', 'employe', 'referent'));
    }

    public function searchIndexReferentCustomer()
    {
        $referents = $this->getReferentCustomer(Session::get('key'));
        $referent_count = count($referents);
        return view('recherche.searchIndexReferentCustomer', compact('referents', 'referent_count'));
    }

    public function searchIndexReferent(Request $request)
    {
        $referents = $this->getReferent(Session::get('key'));
        $referent_count = count($referents);
        return view('recherche.searchIndexReferent', compact('referents', 'referent_count'));
    }

    public function searchIndexFormateur(Request $request)
    {
        $trainers = $this->getTrainer(Session::get('key'));
        $trainer_count = count($trainers);
        return view('recherche.searchIndexFormateur', compact('trainers', 'trainer_count'));
    }

    public function searchIndexEmploye(Request $request)
    {
        $employees = $this->getEmploye(Session::get('key'));
        $employeeCount = count($employees);
        return view('recherche.indexEmploye', compact('employees', 'employeeCount'));
    }

    public function searchIndexClient(Request $request)
    {
        $entreprises = $this->getEntreprise(Session::get('key'));
        $entreprise_count = count($entreprises);
        return view('recherche.searchIndexClient', compact('entreprises', 'entreprise_count'));
    }

    public function searchIndexCfp()
    {
        $cfps =  $this->getCfp(Session::get('key'));

        $countCfp = count($cfps);

        return view('recherche.searchIndexCfp', compact('cfps', 'countCfp'));
    }

    public function searchIndexLieu(Request $request)
    {
        $places = $this->getPlace(Session::get('key'));
        $place_count = count($places);
        return view('recherche.searchIndexLieu', compact('places', 'place_count'));
    }

    public function searchIndexProjet(Request $request)
    {
        $projects = $this->getProject(Session::get('key'));
        $project_count = count($projects);

        return view('recherche.searchIndexProjet', compact('projects', 'project_count'));
    }

    public function getProjectEtp($idEtp)
    {
        $projects = $this->projectEtp($idEtp);

        $etp_name = DB::table('customers')->where('idCustomer', $idEtp)->value('customerName');

        $project_count = count($projects);

        return view('recherche.projectWithCustomer', compact('projects', 'etp_name', 'project_count'));
    }

    public function keySuggestion(Request $req)
    {
        $key = $req->key;

        $result = $this->getKeySuggestion($key);

        return response()->json(array_slice($result, 0, 10));
    }
}
