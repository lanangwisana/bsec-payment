<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Menghapus pencatatan awal pembayaran (Status: Pending)
     */
    public function store(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string',
            'paid_at' => 'required|date',
            'proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = Storage::disk('public')->putFile('payment_proofs', $request->file('proof'));
        }

        Payment::create([
            'invoice_id' => $invoice->id,
            'user_id' => Auth::id() ?? 1,
            'amount' => $request->input('amount'),
            'method' => $request->input('method'),
            'paid_at' => $request->input('paid_at'),
            'proof_path' => $proofPath,
            'notes' => $request->input('notes'),
            'status' => 'pending',
        ]);

        // Status invoice tetap 'pending' atau 'unpaid', tidak langsung lunas
        $invoice->update(['status' => 'pending']);

        return redirect()->back()->with('success', 'Informasi pembayaran berhasil dicatat. Silakan cek mutasi dan lakukan konfirmasi lunas.');
    }

    /**
     * Verifikasi Pembayaran (Status: Confirmed)
     */
    public function confirm(Payment $payment)
    {
        $payment->update([
            'status' => 'confirmed'
        ]);

        $invoice = $payment->invoice;
        $totalConfirmed = $invoice->payments()->where('status', 'confirmed')->sum('amount');
        
        if ($totalConfirmed >= $invoice->amount) {
            $invoice->update(['status' => 'paid']);
        }

        return redirect()->back()->with('success', 'Pembayaran #' . $payment->id . ' telah diverifikasi. Status tagihan diperbarui.');
    }
}
