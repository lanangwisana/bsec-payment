<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_students' => Student::where('is_active', true)->count(),
            'unpaid_invoices' => Invoice::where('status', '!=', 'paid')->count(),
            'today_revenue' => Payment::whereDate('paid_at', Carbon::today())->where('status', 'confirmed')->sum('amount'),
            'month_revenue' => Payment::whereMonth('paid_at', Carbon::now()->month)
                                     ->whereYear('paid_at', Carbon::now()->year)
                                     ->where('status', 'confirmed')
                                     ->sum('amount'),
            'pending_payments_count' => Payment::where('status', 'pending')->count(),
        ];

        $recent_payments = Payment::with(['invoice.student', 'recordedBy'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_payments'));
    }
}
