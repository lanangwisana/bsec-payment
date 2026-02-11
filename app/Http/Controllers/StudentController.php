<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = \App\Models\Student::first();
        if (!$student) return "Data siswa tidak ditemukan.";

        $currentInvoice = $student->invoices()->where('status', 'pending')->orderBy('due_date', 'asc')->first();
        $totalPaid = $student->invoices()->where('status', 'paid')->sum('amount');
        $pendingCount = $student->invoices()->where('status', 'pending')->count();
        
        $recentPayments = \App\Models\Payment::whereHas('invoice', fn($q) => $q->where('student_id', $student->id))
            ->with('invoice')->orderBy('paid_at', 'desc')->limit(5)->get();

        $upcomingInvoices = $student->invoices()->where('status', 'pending')->orderBy('due_date', 'asc')->limit(5)->get();

        return view('student.dashboard', compact('student', 'currentInvoice', 'totalPaid', 'pendingCount', 'recentPayments', 'upcomingInvoices'));
    }

    public function invoices()
    {
        $student = \App\Models\Student::first();
        $invoices = $student->invoices()->orderBy('due_date', 'desc')->get();
        return view('student.invoices.index', compact('invoices', 'student'));
    }

    public function showInvoice(\App\Models\Invoice $invoice)
    {
        $invoice->load('student', 'payments');
        return view('student.invoices.show', compact('invoice'));
    }

    public function paymentHistory()
    {
        $student = \App\Models\Student::first();
        $payments = \App\Models\Payment::whereHas('invoice', fn($q) => $q->where('student_id', $student->id))
            ->with('invoice')->orderBy('paid_at', 'desc')->get();
        return view('student.payments.history', compact('payments', 'student'));
    }

    public function profile()
    {
        $student = \App\Models\Student::first();
        return view('student.profile', compact('student'));
    }

    public function storePayment(Request $request, \App\Models\Invoice $invoice)
    {
        $request->validate([
            'method' => 'required',
            'paid_at' => 'required|date',
            'proof' => 'required|image|max:2048',
        ]);

        $proofPath = $request->file('proof')->store('payments', 'public');

        \App\Models\Payment::create([
            'invoice_id' => $invoice->id,
            'method' => $request->input('method'),
            'paid_at' => $request->input('paid_at'),
            'amount' => $invoice->amount,
            'proof_path' => $proofPath,
            'notes' => $request->input('notes'),
            'status' => 'pending'
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Konfirmasi pembayaran berhasil dikirim!');
    }
}
