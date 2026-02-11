<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = \App\Models\Student::create([
            'name' => 'Budi Santoso',
            'is_active' => true,
            'grade' => 'Kelas 10',
            'school' => 'SMA Negeri 1 Bandung',
            'program' => 'IPA',
            'parent_phone' => '081234567890',
            'parent_email' => 'budi.parent@example.com',
        ]);

        $invoice1 = \App\Models\Invoice::create([
            'student_id' => $student->id,
            'invoice_number' => 'INV-2026-001',
            'amount' => 500000,
            'month_name' => 'Januari',
            'year' => 2026,
            'status' => 'paid',
            'due_date' => '2026-01-10',
        ]);

        \App\Models\Payment::create([
            'invoice_id' => $invoice1->id,
            'paid_at' => '2026-01-05 10:00:00',
            'method' => 'BCA Transfer',
            'amount' => 500000,
            'status' => 'confirmed',
        ]);

        // Student 2
        $student2 = \App\Models\Student::create([
            'name' => 'Siti Aminah',
            'is_active' => true,
            'grade' => 'Kelas 12',
            'school' => 'SMA Negeri 3 Jakarta',
            'program' => 'IPS',
            'parent_phone' => '08987654321',
            'parent_email' => 'siti.parent@example.com',
        ]);

        \App\Models\Invoice::create([
            'student_id' => $student2->id,
            'invoice_number' => 'INV-2026-004',
            'amount' => 750000,
            'month_name' => 'Februari',
            'year' => 2026,
            'status' => 'pending',
            'due_date' => '2026-02-15',
        ]);

        // Student 3
        $student3 = \App\Models\Student::create([
            'name' => 'Lanang Wisana',
            'is_active' => true,
            'grade' => 'Kelas 11',
            'school' => 'SMK Telkom',
            'program' => 'TKJ',
            'parent_phone' => '081122334455',
            'parent_email' => 'lanang@example.com',
        ]);

        \App\Models\Invoice::create([
            'student_id' => $student3->id,
            'invoice_number' => 'INV-2026-005',
            'amount' => 600000,
            'month_name' => 'Februari',
            'year' => 2026,
            'status' => 'unpaid',
            'due_date' => '2026-02-10',
        ]);
    }
}
