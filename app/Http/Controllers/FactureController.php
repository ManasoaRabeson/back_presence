<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreFactureRequest;
use App\Http\Requests\UpdateFactureRequest;
use App\Mail\InvoiceMail;
use App\Models\BankAcount;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoicePayment;
use App\Models\InvoiceDeleted;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Models\Customer;
use App\Models\InvoiceDetailProfo;
use App\Models\MobileMoneyAcount;
use Carbon\Carbon;
use Exception;

class FactureController extends Controller
{
    public function index(Request $request)
    {
        // Invoice::where('invoice_status', '!=', 8)
        //     ->whereDate('invoice_date_pm', '<', Carbon::now())
        //     ->update(['invoice_status' => 8]);

        $invoices = $this->filterInvoices($request)
            ->orderBy('idInvoice', 'desc')
            ->paginate(10);
        $countInvoices = $invoices->total();

        [$total_montant, $restantDu, $remaining_due_past_due] = $this->calculateInvoiceTotals();

        $invoicesNonPaye = $this->getFilteredInvoicesByStatus([2, 3, 5, 6, 7, 8]);
        $countInvoicesNonPaye = $invoicesNonPaye->count();

        $invoicesEchues = $this->getInvoicesEchues();
        $countInvoicesEchues = $invoicesEchues->count();

        $invoicesDraft = $this->getFilteredInvoicesByStatus([1]);
        $countInvoicesDraft = $invoicesDraft->count();


        $entreprises = Invoice::with(['entrepriseFromVcollaboration', 'entrepriseFromVcfpAll'])
            ->standard()
            ->doesntHave('deletedInvoices')
            ->where('idCustomer', Customer::idCustomer())
            ->select('idEntreprise')
            ->distinct()
            ->get()
            ->flatMap(function ($invoice) {
                return [
                    $invoice->entrepriseFromVcollaboration,
                    $invoice->entrepriseFromVcfpAll
                ];
            })
            ->filter() // Supprime les nulls
            ->unique(function ($entreprise) {
                return $entreprise->idEtp ?? $entreprise->idCfp; // Unifie le critère d'unicité
            })
            ->sortBy(function ($entreprise) {
                return $entreprise->etp_name ?? $entreprise->customerName; // Prend le nom de l'entreprise
            });



        $statuses = DB::table('invoice_status')->select('idInvoiceStatus', 'invoice_status_name')->get();
        $pm_types = DB::table('pm_types')->select('idTypePm', 'pm_type_name')->get();

        $ba_accounts = BankAcount::where('ba_idCustomer', Customer::idCustomer())->get();
        $mm_accounts = MobileMoneyAcount::where('mm_idCustomer', Customer::idCustomer())->get();

        return view('CFP.factures.index', compact(
            'invoices',
            'countInvoices',
            'invoicesNonPaye',
            'countInvoicesNonPaye',
            'invoicesEchues',
            'countInvoicesEchues',
            'invoicesDraft',
            'countInvoicesDraft',
            'entreprises',
            'statuses',
            'ba_accounts',
            'restantDu',
            'total_montant',
            'remaining_due_past_due',
            'pm_types',
            'mm_accounts'
        ));
    }

    private function filterInvoices($request)
    {
        return Invoice::with(['entrepriseFromVcollaboration', 'entrepriseFromVcfpAll', 'status', 'payments'])
            ->when($request->idEntreprise, function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('idEntreprise', $request->idEntreprise)
                        ->orWhereHas('entrepriseFromVcollaboration', function ($q) use ($request) {
                            $q->where('idEtp', $request->idEntreprise);
                        })
                        ->orWhereHas('entrepriseFromVcfpAll', function ($q) use ($request) {
                            $q->where('idCustomer', $request->idEntreprise);
                        });
                });
            })
            ->when($request->invoice_status, function ($query) use ($request) {
                $query->where('invoice_status', $request->invoice_status);
            })
            ->when($request->invoice_number, function ($query) use ($request) {
                $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
            })
            ->when($request->invoice_date_pm, function ($query) use ($request) {
                $query->whereDate('invoice_date_pm', $request->invoice_date_pm);
            })
            ->where('idCustomer', Customer::idCustomer())
            ->doesntHave('deletedInvoices')
            ->standard();
    }

    private function getFilteredInvoicesByStatus(array $statuses)
    {
        return Invoice::with(['entrepriseFromVcollaboration', 'entrepriseFromVcfpAll', 'status'])
            ->where('idCustomer', Customer::idCustomer())
            ->standard()
            ->doesntHave('deletedInvoices')
            ->whereIn('invoice_status', $statuses)
            ->orderBy('idInvoice', 'desc')
            ->get();
    }

    private function getInvoicesEchues()
    {
        return Invoice::with(['entrepriseFromVcollaboration', 'entrepriseFromVcfpAll', 'status'])
            ->where('idCustomer', Customer::idCustomer())
            ->standard()
            ->doesntHave('deletedInvoices')
            ->whereNotIn('invoice_status', [1, 4, 9])
            ->orderBy('idInvoice', 'desc')
            ->where('invoice_date_pm', '<', Carbon::now())
            ->get();
    }

    private function calculateInvoiceTotals()
    {
        // Récupération des factures
        $invoicesQuery = Invoice::with(['entrepriseFromVcollaboration', 'entrepriseFromVcfpAll', 'status'])
            ->where('idCustomer', Customer::idCustomer())
            ->standard()
            ->doesntHave('deletedInvoices')
            ->whereNotIn('invoice_status', [1, 4, 9])
            ->orderBy('idInvoice', 'desc');

        $invoices = $invoicesQuery->get();

        // Calcul des totaux
        $total_montant = $invoices->sum('invoice_total_amount');
        $total_paye = $invoices->pluck('payments')->flatten()->sum('amount');
        $restantDu = $total_montant - $total_paye;

        // Calcul des paiements pour les factures échues (facture efa niotra ny date de paiement)
        $pastDueInvoices = $invoices->where('invoice_date_pm', '<', Carbon::now());
        $total_montant_echu = $pastDueInvoices->sum('invoice_total_amount');
        $total_paid_past_due = $pastDueInvoices->pluck('payments')->flatten()->sum('amount');
        $remaining_due_past_due = $total_montant_echu - $total_paid_past_due;

        return [$total_montant, $restantDu, $remaining_due_past_due];
    }


    public function getAllEtps()
    {
        $allClients = DB::table('v_collaboration_cfp_etps')
            ->select('idEtp', 'etp_name', 'etp_email', 'etp_nif', 'etp_ville', 'etp_stat', 'etp_rcs', 'etp_addr_lot', 'etp_addr_quartier', 'etp_addr_code_postal')
            ->where('idCfp', Customer::idCustomer())
            ->union(
                DB::table('v_list_sub_contractors')
                    ->select(
                        'idCfp as idEtp',
                        'cfp_name as etp_name',
                        'cfp_email as etp_email',
                        'cfp_nif as etp_nif',
                        'cfp_ville as etp_ville',
                        'cfp_stat as etp_stat',
                        'cfp_rcs as etp_rcs',
                        'cfp_addr_lot as etp_addr_lot',
                        'cfp_addr_quartier as etp_addr_quartier',
                        'cfp_addr_code_postal as etp_addr_code_postal'
                    )
                    ->join('users', 'idCfp', '=', 'users.id')
                    ->where('idSubContractor', Customer::idCustomer())
            )
            ->orderBy('etp_name', 'asc')
            ->get();
        return response()->json(['clients' => $allClients]);
    }

    public function getProjectsByClient($clientId)
    {
        $typeCustomer = DB::table('customers')
            ->where('idCustomer', $clientId)
            ->value('idTypeCustomer');

        if (!in_array($typeCustomer, [1, 2])) {
            return response()->json([]); // Si le type de client est inconnu, retourne une réponse vide
        }

        $projets = $this->getProjects($clientId, $typeCustomer);

        return response()->json($projets);
    }

    public function getProjects($clientId, $typeCustomer)
    {
        $query = DB::table('v_projet_cfps')
            ->select('idProjet', 'module_name', 'project_reference', 'dateDebut')
            ->where('project_is_active', '=', 1)
            ->where('module_name', '!=', 'Default module');

        if ($typeCustomer == 2) {
            // Récupérer les projets inter
            $projet_inter = DB::table('inter_entreprises')
                ->where('idEtp', $clientId)
                ->pluck('idProjet');

            // projets intra + inter
            $query->where('idCustomer', Customer::idCustomer())
                ->where(function ($q) use ($clientId, $projet_inter) {
                    $q->where('idEtp', $clientId)  // Projets intra
                        ->orWhereIn('idProjet', $projet_inter); // Projets inter
                });
        } elseif ($typeCustomer == 1) {
            $idProjetSubContractors = DB::table('project_sub_contracts')
                ->where('idSubContractor', Customer::idCustomer())
                ->pluck('idProjet'); // Récupère les projets où l'utilisateur est sous-traitant

            $query->whereIn('idProjet', $idProjetSubContractors)
                ->where('idCfp', $clientId);
        }

        return $query->get();
    }

    public function getDossierByClient($clientId)
    {
        $dossiers = DB::table('dossiers')
            ->leftJoin('v_projet_cfps', 'dossiers.idDossier', '=', 'v_projet_cfps.idDossier')
            ->where('v_projet_cfps.idEtp', $clientId)
            ->select(
                'dossiers.idDossier',
                'dossiers.nomDossier'
            )
            ->groupBy('dossiers.idDossier', 'dossiers.nomDossier')
            ->orderBy('dossiers.nomDossier', 'asc')
            ->get();

        return response()->json($dossiers);
    }

    function getProjetByDossier($idDossier)
    {
        $projets = DB::table('v_projet_cfps')
            ->select(
                'idProjet',
                'dateDebut',
                'module_name',
                'project_reference'
            )
            ->where('idCustomer', Customer::idCustomer())
            ->where('idDossier', $idDossier)
            ->where('project_is_trashed', 0)
            ->orderBy('dateDebut', 'asc')
            ->get();
        return response()->json($projets);
    }


    public function create()
    {
        $type_invoice = DB::table('type_factures')->get();

        $customer = DB::table('v_detail_customers')
            ->select('idCustomer', 'initialName', 'customerName', 'customer_addr_quartier', 'customer_addr_rue', 'customer_addr_lot', 'customer_addr_code_postal', 'nif', 'stat', 'rcs', 'customerPhone', 'customerEmail', 'siteWeb', 'description', 'logo', 'customer_slogan')
            ->where('idCustomer', Customer::idCustomer())
            ->first();

        $unites = DB::table('unites')
            ->select('idUnite', 'unite_name')
            ->get();

        $fv = DB::table('frais')
            ->select('idFrais', 'Frais', 'exemple')
            ->get();

        $pm = DB::table('pm_types')->select('idTypePm', 'pm_type_name')->get();
        $ville_codeds = DB::table('ville_codeds')->get();

        $accounts = BankAcount::where('ba_idCustomer', Customer::idCustomer())->get();

        $number_invoice = DB::table('invoices')
            ->select('invoice_number', 'invoice_date')
            ->orderBy('idInvoice', 'desc')
            ->where('idTypeFacture', 1)
            ->where('idCustomer', Customer::idCustomer())
            ->take(3)
            ->get();

        return view('CFP.factures.create', compact('customer', 'unites', 'fv', 'pm', 'type_invoice', 'ville_codeds', 'accounts', 'number_invoice'));
    }

    public function store(StoreFactureRequest $request)
    {
        $validated = $request->validated();

        try {

            DB::transaction(function () use ($validated, $request, &$redirectUrl) {
                $idPaiement = DB::table('mode_paiements')->insertGetId([
                    'idTypePm' => $validated['idPaiement']
                ]);
                $subTotal = 0;

                $invoiceId = DB::table('invoices')->insertGetId([
                    'invoice_number' => $validated['invoice_number'],
                    'invoice_bc' => $validated['invoice_bc'],
                    'invoice_date' => $validated['invoice_date'],
                    'invoice_date_pm' => $validated['invoice_date_pm'],
                    'invoice_status' => $validated['invoice_status'],
                    'invoice_condition' => $validated['invoice_condition'],
                    'invoice_reduction' => $validated['invoice_reduction'],
                    'invoice_tva' => $validated['invoice_tva'],
                    'invoice_total_amount' => $validated['invoice_total_amount'],
                    'invoice_letter' => $validated['invoice_letter'],
                    'idCustomer' => $validated['idCustomer'],
                    'idEntreprise' => $validated['idEntreprise'],
                    'idPaiement' => $idPaiement,
                    'idTypeFacture' => $validated['idTypeFacture'],
                    'idBankAcount' => $validated['idBankAcount'] ?? null,
                ]);

                if ($request->idTypeFacture == 1) {
                    //mampiditra invoice_details
                    foreach ($request->items as $item) {
                        $itemTotalPrice = ($item['item_qty'] * $item['item_unit_price']);
                        $subTotal += $itemTotalPrice;
                        InvoiceDetail::create([
                            'idInvoice' => $invoiceId,
                            'idItems' => $item['idItems'],
                            'idProjet' => $item['idProjet'],
                            'item_qty' => $item['item_qty'],
                            'item_description' => $item['item_description'],
                            'item_unit_price' => $item['item_unit_price'],
                            'idUnite' => $item['idUnite'],
                            'item_total_price' => $itemTotalPrice,
                        ]);
                    }
                } else {
                    //mampiditra invoice_details_profo
                    foreach ($request->items as $item) {
                        $itemTotalPrice = ($item['item_qty'] * $item['item_unit_price']);
                        $subTotal += $itemTotalPrice;
                        InvoiceDetailProfo::create([
                            'idInvoice' => $invoiceId,
                            'idItems' => $item['idItems'],
                            'idModule' => $item['idModule'],
                            'item_qty' => $item['item_qty'],
                            'item_description' => $item['item_description'],
                            'item_unit_price' => $item['item_unit_price'],
                            'idUnite' => $item['idUnite'],
                            'item_total_price' => $itemTotalPrice,
                        ]);
                    }
                }

                DB::table('invoices')
                    ->where('idInvoice', $invoiceId)
                    ->update(['invoice_sub_total' => $subTotal]);

                if (isset($validated['acomptes'])) {
                    DB::table('invoice_acomptes')->insert([
                        'idInvoice' => $invoiceId,
                        'percent' => $validated['acomptes']['percent']
                    ]);
                }

                if (isset($validated['standards'])) {
                    DB::table('invoice_standards')->insert([
                        'idInvoice' => $invoiceId
                    ]);
                }

                $redirectUrl = route('cfp.factures.view', $invoiceId);
            });
            return response()->json(['redirect' => $redirectUrl], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la création de la facture.'], 500);
        }
    }

    public function view($invoiceId)
    {
        $invoice = DB::table('invoices')
            ->join('mode_paiements', 'invoices.idPaiement', '=', 'mode_paiements.idPaiement')
            ->join('pm_types', 'mode_paiements.idTypePm', '=', 'pm_types.idTypePm')
            ->join('customers', 'invoices.idCustomer', '=', 'customers.idCustomer')
            ->join('type_factures', 'invoices.idTypeFacture', '=', 'type_factures.idTypeFacture')
            ->join('ville_codeds as vc', 'customers.idVilleCoded', 'vc.id')
            ->leftJoin('bankacounts', 'invoices.idBankAcount', 'bankacounts.id')
            ->leftJoin('ville_codeds', 'bankacounts.ba_idPostal', 'ville_codeds.id')
            ->select('invoices.*', 'pm_types.*', 'customers.*', 'type_factures.*', 'vc.vi_code_postal as customer_addr_code_postal', 'bankacounts.*', 'ville_codeds.*')
            ->where('idInvoice', $invoiceId)
            ->first();

        $typeCustomer = DB::table('customers')
            ->where('idCustomer', $invoice->idEntreprise)
            ->value('idTypeCustomer');

        if ($typeCustomer == 2) {
            $entreprise = DB::table('v_collaboration_cfp_etps')
                ->select('etp_name', 'etp_nif', 'etp_stat', 'etp_addr_quartier', 'etp_addr_code_postal', 'etp_addr_lot', 'idEtp')
                ->where('idEtp', $invoice->idEntreprise)
                ->first();
        } elseif ($typeCustomer == 1) {
            $entreprise = DB::table('v_cfp_all')
                ->select(
                    'customerName as etp_name',
                    'nif as etp_nif',
                    'stat as etp_stat',
                    'customer_addr_quartier as etp_addr_quartier',
                    'customer_addr_code_postal as etp_addr_code_postal',
                    'customer_addr_lot as etp_addr_lot',
                    'idCfp as idEtp'
                )
                ->where('idEtp', $invoice->idEntreprise)
                ->first();
        }

        if ($invoice->idTypeFacture == 1) {
            // Récupérer PROJET if STANDARD
            $invoiceDetails = DB::table('invoice_details')
                ->join('v_projet_cfps', 'invoice_details.idProjet', '=', 'v_projet_cfps.idProjet')
                ->join('unites', 'invoice_details.idUnite', '=', 'unites.idUnite')
                ->select('invoice_details.*', 'unites.unite_name as unit_name', 'v_projet_cfps.module_name as module_name')
                ->where('idInvoice', $invoiceId)
                ->where('idItems', 0)
                ->get();
            // Récupérer les détails de la facture avec les noms des unités
            $invoiceDetailsOther = DB::table('invoice_details')
                ->join('unites', 'invoice_details.idUnite', '=', 'unites.idUnite')
                ->join('frais', 'invoice_details.idItems', '=', 'frais.idFrais')
                ->select('invoice_details.*', 'unites.unite_name as unit_name', 'frais.*')
                ->where('idInvoice', $invoiceId)
                ->orderBy('idItems', 'asc')
                ->get();
        } else {
            // Récupérer COURS if PROFORMA
            $invoiceDetails = DB::table('invoice_details_profo')
                ->join('v_module_cfps', 'invoice_details_profo.idModule', '=', 'v_module_cfps.idModule')
                ->join('unites', 'invoice_details_profo.idUnite', '=', 'unites.idUnite')
                ->select('invoice_details_profo.*', 'unites.unite_name as unit_name', 'v_module_cfps.moduleName as module_name')
                ->where('idInvoice', $invoiceId)
                ->where('idItems', 0)
                ->get();

            $invoiceDetailsOther = DB::table('invoice_details_profo')
                ->join('unites', 'invoice_details_profo.idUnite', '=', 'unites.idUnite')
                ->join('frais', 'invoice_details_profo.idItems', '=', 'frais.idFrais')
                ->select('invoice_details_profo.*', 'unites.unite_name as unit_name', 'frais.*')
                ->where('idInvoice', $invoiceId)
                ->orderBy('idItems', 'asc')
                ->get();
        }

        return view('CFP.factures.approuveIndex', compact('invoice', 'invoiceDetails', 'invoiceDetailsOther', 'entreprise'));
    }

    public function show($id)
    {
        $invoice = DB::table('invoices')
            ->join('mode_paiements', 'invoices.idPaiement', '=', 'mode_paiements.idPaiement')
            ->join('pm_types', 'mode_paiements.idTypePm', '=', 'pm_types.idTypePm')
            ->join('customers', 'invoices.idCustomer', '=', 'customers.idCustomer')
            ->join('type_factures', 'invoices.idTypeFacture', '=', 'type_factures.idTypeFacture')
            ->join('ville_codeds as vc', 'customers.idVilleCoded', 'vc.id')
            ->join('invoice_status', 'invoices.invoice_status', 'invoice_status.idInvoiceStatus')
            ->leftJoin('bankacounts', 'invoices.idBankAcount', 'bankacounts.id')
            ->leftJoin('ville_codeds', 'bankacounts.ba_idPostal', 'ville_codeds.id')
            ->select('invoices.*', 'pm_types.*', 'customers.*', 'type_factures.*', 'vc.vi_code_postal as customer_addr_code_postal', 'invoice_status.*', 'bankacounts.*', 'ville_codeds.*')
            ->where('idInvoice', $id)
            ->first();
        // dd($invoice);

        $typeCustomer = DB::table('customers')
            ->where('idCustomer', $invoice->idEntreprise)
            ->value('idTypeCustomer');

        if ($typeCustomer == 2) {
            $entreprise = DB::table('v_collaboration_cfp_etps')
                ->select('etp_name', 'etp_nif', 'etp_stat', 'etp_addr_quartier', 'etp_addr_code_postal', 'etp_addr_lot', 'idEtp')
                ->where('idEtp', $invoice->idEntreprise)
                ->first();
        } elseif ($typeCustomer == 1) {
            $entreprise = DB::table('v_cfp_all')
                ->select(
                    'customerName as etp_name',
                    'nif as etp_nif',
                    'stat as etp_stat',
                    'customer_addr_quartier as etp_addr_quartier',
                    'customer_addr_code_postal as etp_addr_code_postal',
                    'customer_addr_lot as etp_addr_lot',
                    'idCfp as idEtp'
                )
                ->where('idCfp', $invoice->idEntreprise)
                ->first();
        }

        if ($invoice->idTypeFacture == 1) {
            // Récupérer PROJET if STANDARD
            $invoiceDetails = DB::table('invoice_details')
                ->join('v_projet_cfps', 'invoice_details.idProjet', '=', 'v_projet_cfps.idProjet')
                ->join('unites', 'invoice_details.idUnite', '=', 'unites.idUnite')
                ->select('invoice_details.*', 'unites.unite_name as unit_name', 'v_projet_cfps.module_name as module_name')
                ->where('idInvoice', $id)
                ->where('idItems', 0)
                ->get();
            // Récupérer les détails de la facture avec les noms des unités
            $invoiceDetailsOther = DB::table('invoice_details')
                ->join('unites', 'invoice_details.idUnite', '=', 'unites.idUnite')
                ->join('frais', 'invoice_details.idItems', '=', 'frais.idFrais')
                ->select('invoice_details.*', 'unites.unite_name as unit_name', 'frais.*')
                ->where('idInvoice', $id)
                ->orderBy('idItems', 'asc')
                ->get();
        } else {
            // Récupérer COURS if PROFORMA
            $invoiceDetails = DB::table('invoice_details_profo')
                ->join('v_module_cfps', 'invoice_details_profo.idModule', '=', 'v_module_cfps.idModule')
                ->join('unites', 'invoice_details_profo.idUnite', '=', 'unites.idUnite')
                ->select('invoice_details_profo.*', 'unites.unite_name as unit_name', 'v_module_cfps.moduleName as module_name')
                ->where('idInvoice', $id)
                ->where('idItems', 0)
                ->get();

            $invoiceDetailsOther = DB::table('invoice_details_profo')
                ->join('unites', 'invoice_details_profo.idUnite', '=', 'unites.idUnite')
                ->join('frais', 'invoice_details_profo.idItems', '=', 'frais.idFrais')
                ->select('invoice_details_profo.*', 'unites.unite_name as unit_name', 'frais.*')
                ->where('idInvoice', $id)
                ->orderBy('idItems', 'asc')
                ->get();
        }

        return view('CFP.factures.show', compact('invoice', 'invoiceDetails', 'invoiceDetailsOther', 'entreprise'));
    }

    public function edit($id)
    {
        $customer = DB::table('v_detail_customers')
            ->select('idCustomer', 'initialName', 'customerName', 'customer_addr_quartier', 'customer_addr_rue', 'customer_addr_lot', 'customer_addr_code_postal', 'nif', 'stat', 'rcs', 'customerPhone', 'customerEmail', 'siteWeb', 'description', 'logo', 'customer_slogan')
            ->where('idCustomer', Customer::idCustomer())
            ->first();

        $invoice = DB::table('invoices')
            ->join('mode_paiements', 'invoices.idPaiement', '=', 'mode_paiements.idPaiement')
            ->join('pm_types', 'mode_paiements.idTypePm', '=', 'pm_types.idTypePm')
            ->join('type_factures', 'invoices.idTypeFacture', '=', 'type_factures.idTypeFacture')
            ->select('invoices.*', 'mode_paiements.idTypePm', 'pm_types.*', 'type_factures.*')
            ->where('idInvoice', $id)
            ->first();

        $typeCustomer = DB::table('customers')
            ->where('idCustomer', $invoice->idEntreprise)
            ->value('idTypeCustomer');

        if ($typeCustomer == 2) {
            $entreprise = DB::table('v_collaboration_cfp_etps')
                ->select('idEtp', 'etp_name', 'etp_email', 'etp_nif', 'etp_ville', 'etp_stat', 'etp_rcs', 'etp_addr_lot', 'etp_addr_quartier', 'etp_addr_code_postal', 'etp_phone')
                ->where('idEtp', $invoice->idEntreprise)
                ->first();
        } elseif ($typeCustomer == 1) {
            $entreprise = DB::table('v_cfp_all')
                ->select(
                    'customerName as etp_name',
                    'nif as etp_nif',
                    'stat as etp_stat',
                    'rcs as etp_rcs',
                    'customerEmail as etp_email',
                    'customerPhone as etp_phone',
                    'customer_addr_quartier as etp_addr_quartier',
                    'customer_ville as etp_ville',
                    'customer_addr_code_postal as etp_addr_code_postal',
                    'customer_addr_lot as etp_addr_lot',
                    'idCfp as idEtp'
                )
                ->where('idCfp', $invoice->idEntreprise)
                ->first();
        }

        $invoiceDetailsProjets = DB::table('invoice_details')
            ->join('v_projet_cfps', 'invoice_details.idProjet', '=', 'v_projet_cfps.idProjet')
            ->join('unites', 'invoice_details.idUnite', '=', 'unites.idUnite')
            ->select('invoice_details.*', 'unites.idUnite as idUnite', 'unites.unite_name as unit_name', 'v_projet_cfps.idProjet')
            ->where('idInvoice', $id)
            ->where('idItems', 0)
            ->get();

        $invoiceDetails = DB::table('invoice_details')
            ->join('unites', 'invoice_details.idUnite', '=', 'unites.idUnite')
            ->join('frais', 'invoice_details.idItems', '=', 'frais.idFrais')
            ->select('invoice_details.*', 'unites.unite_name as unit_name', 'frais.Frais')
            ->where('idInvoice', $id)
            ->orderBy('idItems', 'asc')
            ->get();

        $unites = DB::table('unites')
            ->select('idUnite', 'unite_name')
            ->get();

        // $projets = DB::table('v_projet_cfps')
        //     ->select('idProjet', 'module_name', 'project_reference', 'dateDebut')
        //     ->where('idCustomer', Customer::idCustomer())
        //     ->where('idEtp', $invoice->idEntreprise)
        //     ->where('module_name', '!=', 'Default module')
        //     ->get();
        $projets = $this->getProjects($invoice->idEntreprise, $typeCustomer);

        $fv = DB::table('frais')
            ->select('idFrais', 'Frais', 'exemple')
            ->get();

        $pm = DB::table('pm_types')->select('idTypePm', 'pm_type_name')->get();
        $type_invoice = DB::table('type_factures')->get();
        $ville_codeds = DB::table('ville_codeds')->get();

        $accounts = BankAcount::where('ba_idCustomer', Customer::idCustomer())->get();

        return view('CFP.factures.edit', compact('customer', 'invoice', 'entreprise', 'invoiceDetails', 'invoiceDetailsProjets', 'unites', 'projets', 'fv', 'pm', 'type_invoice', 'ville_codeds', 'accounts'));
    }


    public function update(UpdateFactureRequest $request, $idInvoice)
    {
        $validated = $request->validated();
        try {
            DB::transaction(function () use ($validated, $request, $idInvoice, &$redirectUrl) {
                // Mise à jour du mode de paiement
                $idPaiement = DB::table('mode_paiements')
                    ->where('idPaiement', function ($query) use ($idInvoice) {
                        $query->select('idPaiement')
                            ->from('invoices')
                            ->where('idInvoice', $idInvoice)
                            ->limit(1);
                    })
                    ->update([
                        'idTypePm' => $validated['idPaiement']
                    ]);

                $subTotal = 0;

                // Mise à jour de la facture
                DB::table('invoices')
                    ->where('idInvoice', $idInvoice)
                    ->update([
                        'invoice_number' => $validated['invoice_number'],
                        'invoice_bc' => $validated['invoice_bc'],
                        'invoice_date' => $validated['invoice_date'],
                        'invoice_date_pm' => $validated['invoice_date_pm'],
                        'invoice_status' => $validated['invoice_status'],
                        'invoice_condition' => $validated['invoice_condition'],
                        'invoice_reduction' => $validated['invoice_reduction'],
                        'invoice_tva' => $validated['invoice_tva'],
                        'invoice_total_amount' => $validated['invoice_total_amount'],
                        'invoice_letter' => $validated['invoice_letter'],
                        'idCustomer' => $validated['idCustomer'],
                        'idEntreprise' => $validated['idEntreprise'],
                        'idPaiement' => $validated['idPay'],
                        'idTypeFacture' => $validated['idTypeFacture'],
                        'idBankAcount' => $validated['idBankAcount'] ?? null,
                    ]);

                // Mise à jour des détails de la facture
                if ($request->idTypeFacture == 1) {
                    DB::table('invoice_details')->where('idInvoice', $idInvoice)->delete();
                    //mampiditra invoice_details
                    foreach ($request->items as $item) {
                        $itemTotalPrice = ($item['item_qty'] * $item['item_unit_price']);
                        $subTotal += $itemTotalPrice;
                        InvoiceDetail::create([
                            'idInvoice' => $idInvoice,
                            'idItems' => $item['idItems'],
                            'idProjet' => $item['idProjet'],
                            'item_qty' => $item['item_qty'],
                            'item_description' => $item['item_description'],
                            'item_unit_price' => $item['item_unit_price'],
                            'idUnite' => $item['idUnite'],
                            'item_total_price' => $itemTotalPrice,
                        ]);
                    }
                } else {
                    DB::table('invoice_details_profo')->where('idInvoice', $idInvoice)->delete();

                    //mampiditra invoice_details_profo
                    foreach ($request->items as $item) {
                        $itemTotalPrice = ($item['item_qty'] * $item['item_unit_price']);
                        $subTotal += $itemTotalPrice;
                        InvoiceDetailProfo::create([
                            'idInvoice' => $idInvoice,
                            'idItems' => $item['idItems'],
                            'idModule' => $item['idModule'],
                            'item_qty' => $item['item_qty'],
                            'item_description' => $item['item_description'],
                            'item_unit_price' => $item['item_unit_price'],
                            'idUnite' => $item['idUnite'],
                            'item_total_price' => $itemTotalPrice,
                        ]);
                    }
                }

                // Mise à jour du sous-total de la facture
                DB::table('invoices')
                    ->where('idInvoice', $idInvoice)
                    ->update(['invoice_sub_total' => $subTotal]);

                // Mise à jour des acomptes
                if (isset($validated['acomptes'])) {
                    DB::table('invoice_acomptes')
                        ->where('idInvoice', $idInvoice)
                        ->delete();

                    DB::table('invoice_acomptes')->insert([
                        'idInvoice' => $idInvoice,
                        'percent' => $validated['acomptes']['percent']
                    ]);
                }

                // Mise à jour des standards
                if (isset($validated['standards'])) {
                    DB::table('invoice_standards')
                        ->where('idInvoice', $idInvoice)
                        ->delete();

                    DB::table('invoice_standards')->insert([
                        'idInvoice' => $idInvoice
                    ]);
                }

                $redirectUrl = route('cfp.factures.view', $idInvoice);
            });

            return response()->json(['message' => 'Facture mise à jour avec succès!', 'redirect' => $redirectUrl], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour de la facture.'], 500);
        }
    }


    public function approve($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->invoice_status++;
        $invoice->save();

        return redirect()->back();
    }

    public function cancel($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->invoice_status = 9;
        $invoice->save();

        return redirect()->back();
    }

    public function convertir($id)
    {
        DB::beginTransaction();

        try {
            $invoice = Invoice::findOrFail($id);

            // Mettre à jour le statut de la facture
            $invoice->invoice_status = 7; // Statut "converti"
            $invoice->idTypeFacture = 1; // type Standard
            $invoice->save();

            // Récupérer les détails de la facture
            $invoiceDetailsProjets = DB::table('invoice_details')
                ->join('v_projet_cfps', 'invoice_details.idProjet', '=', 'v_projet_cfps.idProjet')
                ->join('unites', 'invoice_details.idUnite', '=', 'unites.idUnite')
                ->select('invoice_details.*', 'unites.unite_name as unit_name', 'v_projet_cfps.module_name')
                ->where('idInvoice', $id)
                ->where('idItems', 0)
                ->get();

            // Récupérer une salle par défaut
            $salle = DB::table('v_list_salles')->select('idSalle')->where('idCustomer', Customer::idCustomer())->first();

            if (!$salle) {
                throw new Exception('Aucune salle disponible pour associer au projet.');
            }

            foreach ($invoiceDetailsProjets as $detail) {
                // Créer un projet
                $idProjet = DB::table('projets')->insertGetId([
                    'project_title' => $detail->module_name,
                    'idModalite' => 1,
                    'idCustomer' => Customer::idCustomer(),
                    'idModule' => $detail->idProjet,
                    'idVilleCoded' => 1,
                    'project_is_active' => 0,
                    'idSalle' => $salle->idSalle,
                ]);

                // Insérer dans la table intras
                DB::table('intras')->insert([
                    'idProjet' => $idProjet,
                    'idPaiement' => 3,
                    'idEtp' => $invoice->idEntreprise,
                    'idCfp' => Customer::idCustomer(),
                ]);

                DB::table('invoice_details')
                    ->where('idInvoice', $id)
                    ->where('idItems', 0)
                    ->update(['idProjet' => $idProjet]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Facture convertie et projet créé avec succès.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de la conversion : ' . $e->getMessage());
        }
    }

    public function exportInvoicePdf($id)
    {
        $invoice = DB::table('invoices')
            ->join('mode_paiements', 'invoices.idPaiement', '=', 'mode_paiements.idPaiement')
            ->join('pm_types', 'mode_paiements.idTypePm', '=', 'pm_types.idTypePm')
            ->join('customers', 'invoices.idCustomer', '=', 'customers.idCustomer')
            ->join('type_factures', 'invoices.idTypeFacture', '=', 'type_factures.idTypeFacture')
            ->join('ville_codeds as vc', 'customers.idVilleCoded', 'vc.id')
            ->leftJoin('bankacounts', 'invoices.idBankAcount', 'bankacounts.id')
            ->leftJoin('ville_codeds', 'bankacounts.ba_idPostal', 'ville_codeds.id')
            ->select('invoices.*', 'pm_types.*', 'customers.*', 'type_factures.*', 'vc.vi_code_postal as customer_addr_code_postal', 'bankacounts.*', 'ville_codeds.*')
            ->where('idInvoice', $id)
            ->first();

        $typeCustomer = DB::table('customers')
            ->where('idCustomer', $invoice->idEntreprise)
            ->value('idTypeCustomer');

        if ($typeCustomer == 2) {
            $entreprise = DB::table('v_collaboration_cfp_etps')
                ->select('etp_name', 'etp_nif', 'etp_stat', 'etp_addr_quartier', 'etp_addr_code_postal', 'etp_addr_lot', 'idEtp')
                ->where('idEtp', $invoice->idEntreprise)
                ->first();
        } elseif ($typeCustomer == 1) {
            $entreprise = DB::table('v_cfp_all')
                ->select(
                    'customerName as etp_name',
                    'nif as etp_nif',
                    'stat as etp_stat',
                    'customer_addr_quartier as etp_addr_quartier',
                    'customer_addr_code_postal as etp_addr_code_postal',
                    'customer_addr_lot as etp_addr_lot',
                    'idCfp as idEtp'
                )
                ->where('idCfp', $invoice->idEntreprise)
                ->first();
        }

        if ($invoice->idTypeFacture == 1) {
            // Récupérer PROJET if STANDARD
            $invoiceDetails = DB::table('invoice_details')
                ->join('v_projet_cfps', 'invoice_details.idProjet', '=', 'v_projet_cfps.idProjet')
                ->join('unites', 'invoice_details.idUnite', '=', 'unites.idUnite')
                ->select('invoice_details.*', 'unites.unite_name as unit_name', 'v_projet_cfps.module_name as module_name')
                ->where('idInvoice', $id)
                ->where('idItems', 0)
                ->get();
            // Récupérer les détails de la facture avec les noms des unités
            $invoiceDetailsOther = DB::table('invoice_details')
                ->join('unites', 'invoice_details.idUnite', '=', 'unites.idUnite')
                ->join('frais', 'invoice_details.idItems', '=', 'frais.idFrais')
                ->select('invoice_details.*', 'unites.unite_name as unit_name', 'frais.*')
                ->where('idInvoice', $id)
                ->orderBy('idItems', 'asc')
                ->get();
        } else {
            // Récupérer COURS if PROFORMA
            $invoiceDetails = DB::table('invoice_details_profo')
                ->join('v_module_cfps', 'invoice_details_profo.idModule', '=', 'v_module_cfps.idModule')
                ->join('unites', 'invoice_details_profo.idUnite', '=', 'unites.idUnite')
                ->select('invoice_details_profo.*', 'unites.unite_name as unit_name', 'v_module_cfps.moduleName as module_name')
                ->where('idInvoice', $id)
                ->where('idItems', 0)
                ->get();

            $invoiceDetailsOther = DB::table('invoice_details_profo')
                ->join('unites', 'invoice_details_profo.idUnite', '=', 'unites.idUnite')
                ->join('frais', 'invoice_details_profo.idItems', '=', 'frais.idFrais')
                ->select('invoice_details_profo.*', 'unites.unite_name as unit_name', 'frais.*')
                ->where('idInvoice', $id)
                ->orderBy('idItems', 'asc')
                ->get();
        }

        $pdf = Pdf::loadView('CFP.factures.components.ApprouverFacture.preview1', [
            'invoice' => $invoice,
            'entreprise' => $entreprise,
            'invoiceDetails' => $invoiceDetails,
            'invoiceDetailsOther' => $invoiceDetailsOther
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream($invoice->invoice_number . '.pdf');
    }

    public function sendInvoiceEmail($id)
    {
        $customer = DB::table('v_detail_customers')
            ->select('idCustomer', 'initialName', 'customerName', 'customer_addr_quartier', 'customer_addr_rue', 'customer_addr_lot', 'customer_addr_code_postal', 'nif', 'stat', 'rcs', 'customerPhone', 'customerEmail', 'siteWeb', 'description', 'logo', 'customer_slogan')
            ->where('idCustomer', Customer::idCustomer())
            ->first();

        $invoice = DB::table('invoices')
            ->join('mode_paiements', 'invoices.idPaiement', '=', 'mode_paiements.idPaiement')
            ->join('pm_types', 'mode_paiements.idTypePm', '=', 'pm_types.idTypePm')
            ->join('customers', 'invoices.idCustomer', '=', 'customers.idCustomer')
            ->join('type_factures', 'invoices.idTypeFacture', '=', 'type_factures.idTypeFacture')
            ->join('ville_codeds as vc', 'customers.idVilleCoded', 'vc.id')
            ->leftJoin('bankacounts', 'invoices.idBankAcount', 'bankacounts.id')
            ->leftJoin('ville_codeds', 'bankacounts.ba_idPostal', 'ville_codeds.id')
            ->select('invoices.*', 'pm_types.*', 'customers.*', 'type_factures.*', 'vc.vi_code_postal as customer_addr_code_postal', 'bankacounts.*', 'ville_codeds.*')
            ->where('idInvoice', $id)
            ->first();

        $typeCustomer = DB::table('customers')
            ->where('idCustomer', $invoice->idEntreprise)
            ->value('idTypeCustomer');

        if ($typeCustomer == 2) {
            $entreprise = DB::table('v_collaboration_cfp_etps')
                ->select('etp_name', 'etp_nif', 'etp_stat', 'etp_addr_quartier', 'etp_addr_code_postal', 'etp_addr_lot', 'etp_email', 'idEtp')
                ->where('idEtp', $invoice->idEntreprise)
                ->first();
        } elseif ($typeCustomer == 1) {
            $entreprise = DB::table('v_cfp_all')
                ->select(
                    'customerName as etp_name',
                    'nif as etp_nif',
                    'stat as etp_stat',
                    'customerEmail as etp_email',
                    'customer_addr_quartier as etp_addr_quartier',
                    'customer_addr_code_postal as etp_addr_code_postal',
                    'customer_addr_lot as etp_addr_lot',
                    'idCfp as idEtp'
                )
                ->where('idCfp', $invoice->idEntreprise)
                ->first();
        }

        if ($invoice->idTypeFacture == 1) {
            // Récupérer PROJET if STANDARD
            $invoiceDetails = DB::table('invoice_details')
                ->join('v_projet_cfps', 'invoice_details.idProjet', '=', 'v_projet_cfps.idProjet')
                ->join('unites', 'invoice_details.idUnite', '=', 'unites.idUnite')
                ->select('invoice_details.*', 'unites.unite_name as unit_name', 'v_projet_cfps.module_name as module_name')
                ->where('idInvoice', $id)
                ->where('idItems', 0)
                ->get();
            // Récupérer les détails de la facture avec les noms des unités
            $invoiceDetailsOther = DB::table('invoice_details')
                ->join('unites', 'invoice_details.idUnite', '=', 'unites.idUnite')
                ->join('frais', 'invoice_details.idItems', '=', 'frais.idFrais')
                ->select('invoice_details.*', 'unites.unite_name as unit_name', 'frais.*')
                ->where('idInvoice', $id)
                ->orderBy('idItems', 'asc')
                ->get();
        } else {
            // Récupérer COURS if PROFORMA
            $invoiceDetails = DB::table('invoice_details_profo')
                ->join('v_module_cfps', 'invoice_details_profo.idModule', '=', 'v_module_cfps.idModule')
                ->join('unites', 'invoice_details_profo.idUnite', '=', 'unites.idUnite')
                ->select('invoice_details_profo.*', 'unites.unite_name as unit_name', 'v_module_cfps.moduleName as module_name')
                ->where('idInvoice', $id)
                ->where('idItems', 0)
                ->get();

            $invoiceDetailsOther = DB::table('invoice_details_profo')
                ->join('unites', 'invoice_details_profo.idUnite', '=', 'unites.idUnite')
                ->join('frais', 'invoice_details_profo.idItems', '=', 'frais.idFrais')
                ->select('invoice_details_profo.*', 'unites.unite_name as unit_name', 'frais.*')
                ->where('idInvoice', $id)
                ->orderBy('idItems', 'asc')
                ->get();
        }

        // Générer le PDF pour l'email
        $pdf = Pdf::loadView('CFP.factures.components.ApprouverFacture.preview1', [
            'customer' => $customer,
            'invoice' => $invoice,
            'entreprise' => $entreprise,
            'invoiceDetailsOther' => $invoiceDetailsOther,
            'invoiceDetails' => $invoiceDetails
        ])->output();

        // Envoyer l'email avec la facture en pièce jointe
        Mail::to($entreprise->etp_email)->send(new InvoiceMail($customer, $invoice, $entreprise, $invoiceDetailsOther, $invoiceDetails, $pdf));

        $invoices = Invoice::findOrFail($id);
        $invoices->invoice_status++;
        $invoices->save();

        return redirect()->back()->with('success', 'Facture a été envoyé avec succès');
    }

    public function destroy($id)
    {
        DB::table('invoice_deleted')->insert([
            'idInvoice' => $id,
        ]);

        return redirect()->back()->with('success', 'Facture supprimée avec succès')->with('deletedInvoiceId', $id);
    }

    public function restore($id)
    {
        $deletedInvoice = InvoiceDeleted::where('idInvoice', $id)->first();
        if ($deletedInvoice) {
            $deletedInvoice->delete();
            return redirect()->back()->with('success', 'La facture a été restaurée avec succès.');
        } else {
            return redirect()->back()->with('error', 'La facture n\'est pas marquée comme supprimée.');
        }
    }

    public function getTresor()
    {
        $colors = [];
        $countInvoices = [];
        $mois = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];

        $invoiceStatus = DB::table('invoice_status')
            ->select('idInvoiceStatus', 'invoice_status_name')
            //->whereIn('idInvoiceStatus', [2, 3, 5, 6, 7, 8, 9])
            ->get();

        $countInvoiceStatus = count($invoiceStatus);

        foreach ($invoiceStatus as $key => $value) {
            $colors[$key] = $this->getColorHexa(++$key);
            $invoices = Invoice::with(['entreprise', 'status'])
                ->doesntHave('deletedInvoices')
                ->where('invoice_status', $key)
                ->orderBy('idInvoice', 'desc')
                ->get();
            $countInvoices[$key] = count($invoices);
        }

        // dd($invoiceStatus, $colors, $countInvoices);
        return view('CFP.factures.tresor', compact('invoiceStatus', 'colors', 'countInvoices', 'countInvoiceStatus', 'mois'));
    }

    private function getEntreprise($idEtp)
    {
        $entreprise = DB::table('v_collaboration_cfp_etps')
            ->select('etp_name', 'etp_nif', 'etp_stat', 'etp_addr_quartier', 'etp_addr_code_postal', 'etp_addr_lot', 'etp_email', 'idEtp', 'idCfp')
            ->where('idEtp', $idEtp)
            ->first();
        return $entreprise;
    }

    private function getColorHexa($idInvoice)
    {
        $color = "";
        if ($idInvoice == 1) { //Brouillon
            $color = '#808080';
        } else  if ($idInvoice == 2) { //Non Envoyé
            $color = '#f472b6';
        } else  if ($idInvoice == 3) { //Envoyé
            $color = '#06b6d4';
        } else  if ($idInvoice == 4) { //Payé
            $color = '#22d3ee';
        } else  if ($idInvoice == 5) { //Partiel
            $color = '#facc15';
        } else  if ($idInvoice == 6) { //Impayé
            $color = '#ef4444';
        } else  if ($idInvoice == 7) { //Convertis
            $color = '#0891b2';
        } else  if ($idInvoice == 8) { //Expiré
            $color = '#dc2626';
        } else  if ($idInvoice == 9) { //Annulé
            $color = '#f9a08d';
        }
        return $color;
    }

    private function getStatusInvoice($idInvoice)
    {
        $status = DB::table('invoice_status')
            ->select('invoice_status_name')
            ->where('idInvoiceStatus', $idInvoice)
            ->first();
        return $status;
    }

    public function getEvents()
    {
        $factures = [];
        $factures = Invoice::with(['entreprise', 'status'])
            ->doesntHave('deletedInvoices')
            ->where('idCustomer', Customer::idCustomer())
            ->whereIn('invoice_status', [2, 3, 5, 6, 7, 8, 9])
            ->orderBy('idInvoice', 'desc')
            ->get();

        if (count($factures) > 0) {
            foreach ($factures as $facture) {

                $events[] =  [

                    'idFacture' => $facture->idInvoice,

                    'idInvoiceStatus' => $facture->invoice_status,

                    'idNumber' => $facture->invoice_number,

                    'idEntreprise' => $facture->idEntreprise,

                    'nameEtp' => $this->getEntreprise($facture->idEntreprise)->etp_name,

                    'end' => $facture->invoice_date_pm,

                    'start' => $facture->invoice_date,

                    'status' => $this->getStatusInvoice($facture->invoice_status)->invoice_status_name,

                    'total' => $facture->invoice_total_amount,

                ];
            }
        } else {

            return response()->json(['pas de donnee']);
        }

        //dd($events);
        return response()->json(['factures' => $events]);
    }
}
