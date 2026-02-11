<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\Invoice;
use Carbon\Carbon;

class GenerateMonthlyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly invoices for students based on their registration date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $dayOfMonth = $today->day;
        
        // Find students whose registration day is today
        // OR if today is the last day of the month, find students whose registration day is > today's day
        // (to handle registration on 29, 30, 31 for months like February)
        $students = Student::where('is_active', true)
            ->where(function($query) use ($today, $dayOfMonth) {
                $query->whereDay('registration_date', $dayOfMonth);
                
                if ($today->isLastOfMonth()) {
                    $query->orWhereRaw('EXTRACT(DAY FROM registration_date) > ?', [$dayOfMonth]);
                }
            })
            ->get();

        $count = 0;
        foreach ($students as $student) {
            // Check if already generated for this month
            $exists = Invoice::where('student_id', $student->id)
                ->where('month_name', $today->translatedFormat('F'))
                ->where('year', $today->year)
                ->exists();

            if (!$exists) {
                $invoiceNumber = 'INV-' . $today->format('Ym') . '-' . str_pad($student->id, 4, '0', STR_PAD_LEFT);
                
                Invoice::create([
                    'student_id' => $student->id,
                    'invoice_number' => $invoiceNumber,
                    'amount' => $student->monthly_fee,
                    'month_name' => $today->translatedFormat('F'),
                    'year' => $today->year,
                    'status' => 'unpaid',
                    'due_date' => $today->copy()->addDays(7), // Due in 7 days
                ]);
                $count++;
            }
        }

        $this->info("Successfully generated $count invoices for " . $today->translatedFormat('F Y'));
    }
}
