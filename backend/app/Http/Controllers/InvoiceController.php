<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->hasRole('freelancer')) {
            $invoices = Invoice::where('freelancer_id', $user->id)->get();
        } elseif ($user->hasRole('company')) {
            $invoices = Invoice::where('company_id', $user->id)->get();
        } else {
            $invoices = Invoice::all();
        }

        return response()->json($invoices);
    }

    public function createForProject(Request $request, $projectId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'currency' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        // find project and related users (skipped linking for brevity)
        $number = 'INV-' . strtoupper(Str::random(8));

        $invoice = Invoice::create([
            'project_id' => $projectId,
            'freelancer_id' => $request->user()->id,
            'company_id' => $request->input('company_id'),
            'number' => $number,
            'amount' => $request->input('amount'),
            'currency' => $request->input('currency', 'USD'),
            'description' => $request->input('description', ''),
            'status' => 'pending'
        ]);

        // simple audit log
        if (class_exists('\App\Models\AuditLog')) {
            \App\Models\AuditLog::create([
                'user_id' => $request->user()->id,
                'action' => 'invoice_created',
                'meta' => ['invoice_id' => $invoice->id, 'project_id' => $projectId]
            ]);
        }

        return response()->json($invoice, 201);
    }

    public function markPaid(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->status = 'paid';
        $invoice->save();

        return response()->json(['message' => 'Invoice marked as paid', 'invoice' => $invoice]);
    }

    public function releaseFunds(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        // Authorization: only admin, ceo, or the company who owns the invoice can release
        $user = $request->user();
        if (!($user->hasRole('admin') || $user->hasRole('ceo') || $invoice->company_id == $user->id)) {
            return response()->json(['message' => 'Unauthorized to release funds'], 403);
        }

        $invoice->status = 'released';
        $invoice->save();

        // Audit
        if (class_exists('\App\Models\AuditLog')) {
            \App\Models\AuditLog::create([
                'user_id' => $user->id,
                'action' => 'invoice_released',
                'meta' => ['invoice_id' => $invoice->id]
            ]);
        }

        return response()->json(['message' => 'Funds released', 'invoice' => $invoice]);
    }
}
