<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BankAcount;
use App\Models\Invoice;
use App\Models\Customer;
use Carbon\Carbon;

class FactureProfoController extends Controller
{
    public function getAllEtps()
    {
        $allEtps = DB::table('v_collaboration_cfp_etps')
            ->select('idEtp', 'etp_name', 'etp_email', 'etp_nif', 'etp_ville', 'etp_stat', 'etp_rcs', 'etp_addr_lot', 'etp_addr_quartier', 'etp_addr_code_postal', 'etp_phone')
            ->where('idCfp', Customer::idCustomer())
            ->get();

        return response()->json(['clients' => $allEtps]);
    }

    public function index(Request $request)
    {
        $invoices = $this->filterInvoices($request)
            ->doesntHave('deletedInvoices')
            ->orderBy('idInvoice', 'desc')
            ->paginate(12);

        $countInvoices = $invoices->total();

        $invoicesConvertis = Invoice::with(['entreprise', 'status'])
            ->where('idCustomer', Customer::idCustomer())
            ->standard()
            ->doesntHave('deletedInvoices')
            ->where('invoice_status', 7)
            ->orderBy('idInvoice', 'desc')
            ->get();
        $countInvoicesConvertis = count($invoicesConvertis);

        $invoicesPaPaye = $this->getFilteredInvoicesByStatus([5]);
        $countInvoicesPaPaye = $invoicesPaPaye->count();

        $invoicesDraft = $this->getFilteredInvoicesByStatus([1]);
        $countInvoicesDraft = $invoicesDraft->count();

        $entreprises = Invoice::with(['entrepriseFromVcollaboration', 'entrepriseFromVcfpAll'])
            ->proforma()
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
        $accounts = BankAcount::where('ba_idCustomer', Customer::idCustomer())->get();

        return view('CFP.facturesProforma.index', compact(
            'invoices',
            'countInvoices',
            'invoicesPaPaye',
            'countInvoicesPaPaye',
            'invoicesDraft',
            'countInvoicesDraft',
            'invoicesConvertis',
            'countInvoicesConvertis',
            'entreprises',
            'statuses'
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
            ->proforma();
    }

    private function getFilteredInvoicesByStatus(array $statuses)
    {
        return Invoice::with(['entrepriseFromVcollaboration', 'entrepriseFromVcfpAll', 'status'])
            ->where('idCustomer', Customer::idCustomer())
            ->proforma()
            ->doesntHave('deletedInvoices')
            ->whereIn('invoice_status', $statuses)
            ->orderBy('idInvoice', 'desc')
            ->get();
    }

    public function create()
    {
        $type_invoice = DB::table('type_factures')->get();
        $cours = DB::table('v_module_cfps')
            ->where('idCustomer', Customer::idCustomer())
            ->where('moduleStatut', 1)
            ->where('moduleName', '!=', 'Default module')
            ->get();

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
            ->where('idTypeFacture', 2)
            ->where('idCustomer', Customer::idCustomer())
            ->take(3)
            ->get();

        return view('CFP.facturesProforma.create', compact('customer', 'unites', 'fv', 'pm', 'cours', 'type_invoice', 'ville_codeds', 'accounts', 'number_invoice'));
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

        $invoiceDetailsCours = DB::table('invoice_details_profo')
            ->join('v_module_cfps', 'invoice_details_profo.idModule', '=', 'v_module_cfps.idModule')
            ->join('unites', 'invoice_details_profo.idUnite', '=', 'unites.idUnite')
            ->select('invoice_details_profo.*', 'unites.unite_name as unit_name', 'v_module_cfps.moduleName', 'v_module_cfps.idModule')
            ->where('idInvoice', $id)
            ->where('idItems', 0)
            ->get();

        $invoiceDetails = DB::table('invoice_details_profo')
            ->join('unites', 'invoice_details_profo.idUnite', '=', 'unites.idUnite')
            ->join('frais', 'invoice_details_profo.idItems', '=', 'frais.idFrais')
            ->select('invoice_details_profo.*', 'unites.unite_name as unit_name', 'frais.Frais')
            ->where('idInvoice', $id)
            ->orderBy('idItems', 'asc')
            ->get();

        $unites = DB::table('unites')
            ->select('idUnite', 'unite_name')
            ->get();

        $cours = DB::table('v_module_cfps')
            ->where('idCustomer', Customer::idCustomer())
            ->where('moduleStatut', 1)
            ->where('moduleName', '!=', 'Default module')
            ->select('idModule', 'moduleName')
            ->get();

        $fv = DB::table('frais')
            ->select('idFrais', 'Frais', 'exemple')
            ->get();

        $pm = DB::table('pm_types')->select('idTypePm', 'pm_type_name')->get();
        $type_invoice = DB::table('type_factures')->get();

        $ville_codeds = DB::table('ville_codeds')->get();
        $accounts = BankAcount::where('ba_idCustomer', Customer::idCustomer())->get();

        return view('CFP.facturesProforma.edit', compact('customer', 'invoice', 'entreprise', 'invoiceDetails', 'invoiceDetailsCours', 'unites', 'cours', 'fv', 'pm', 'type_invoice', 'ville_codeds', 'accounts'));
    }
}
