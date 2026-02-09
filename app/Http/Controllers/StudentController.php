<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        // Placeholder data agar view bisa tampil tanpa error
        $student = (object)[
            'name' => 'Siswa Contoh',
            'is_active' => true,
            'grade' => 'Kelas 10',
            'school' => 'SMA Negeri 1',
            'program' => 'IPA',
            'parent_phone' => '08123456789',
            'parent_email' => 'parent@example.com'
        ];

        $currentInvoice = (object)[
            'id' => 1,
            'amount' => 500000,
            'month_name' => 'Februari',
            'year' => 2026,
            'status' => 'pending'
        ];

        $totalPaid = 1500000;
        $pendingCount = 1;
        $recentPayments = collect([]);
        $upcomingInvoices = collect([]);

        return view('student.dashboard', compact(
            'student', 
            'currentInvoice', 
            'totalPaid', 
            'pendingCount', 
            'recentPayments', 
            'upcomingInvoices'
        ));
    }
}
