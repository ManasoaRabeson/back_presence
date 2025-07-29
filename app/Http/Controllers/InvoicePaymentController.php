<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoicePaymentController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,idInvoice',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_method_id' => 'required|string',
            'payment_bank_id' => 'nullable',
            'payment_mobilemoney_id' => 'nullable',
            'payment_description' => 'nullable|string|max:200',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);

        InvoicePayment::create([
            'invoice_id' => $id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_method_id' => $request->payment_method_id,
            'payment_bank_id' => $request->payment_bank_id,
            'payment_mobilemoney_id' => $request->payment_mobilemoney_id,
            'payment_description' => $request->payment_description,
        ]);

        // Montant total déjà payé
        $totalPaid = InvoicePayment::where('invoice_id', $invoice->idInvoice)->sum('amount');

        if ($totalPaid >= $invoice->invoice_total_amount) {
            $invoice->update(['invoice_status' => 4]);
        } elseif ($totalPaid < $invoice->invoice_total_amount) {
            $invoice->update(['invoice_status' => 5]);
        }

        return redirect()->route('cfp.factures.index')->with('success', 'Le paiement a été enregistré avec succès');
    }

    public function update(Request $request, $paymentId)
    {
        $validatedData = $request->validate([
            'payment_date' => 'date|nullable',
            'payment_method_id' => 'string|nullable',
            'payment_bank_id' => 'nullable',
            'payment_mobilemoney_id' => 'nullable',
            'payment_description' => 'string|nullable',
            'amount' => 'numeric|nullable',
        ]);

        $payment = InvoicePayment::findOrFail($paymentId);
        $payment->update($validatedData);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $payment = InvoicePayment::findOrFail($id);
        $payment->delete();
        return response()->json(['success' => true, 'message' => 'Paiement supprimé avec succès.']);
    }
}
