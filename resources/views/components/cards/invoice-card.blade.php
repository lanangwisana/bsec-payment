@props(['invoice', 'showActions' => true])

@php
    $statusColors = [
        'pending' => 'warning',
        'paid' => 'success',
        'overdue' => 'danger',
        'cancelled' => 'secondary'
    ];
    
    $statusTexts = [
        'pending' => 'Menunggu Pembayaran',
        'paid' => 'Lunas',
        'overdue' => 'Terlambat',
        'cancelled' => 'Dibatalkan'
    ];
@endphp

<div class="card mb-3 border-{{ $statusColors[$invoice->status] }} border-start border-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h5 class="card-title mb-1">
                    Tagihan {{ $invoice->month_name }} {{ $invoice->year }}
                </h5>
                <p class="card-text text-muted mb-1">
                    <i class="bi bi-calendar me-1"></i> 
                    Jatuh tempo: {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}
                </p>
                <p class="card-text mb-1">
                    <i class="bi bi-book me-1"></i> 
                    {{ $invoice->student->program ?? 'Program' }}
                </p>
                <small class="text-muted">
                    No. Invoice: {{ $invoice->invoice_number }}
                </small>
            </div>
            <div class="text-end">
                <h4 class="text-primary fw-bold mb-2">
                    Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                </h4>
                <span class="badge bg-{{ $statusColors[$invoice->status] }}">
                    {{ $statusTexts[$invoice->status] }}
                </span>
            </div>
        </div>
        
        @if($showActions && in_array($invoice->status, ['pending', 'overdue']))
        <div class="mt-3 pt-3 border-top">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary btn-sm flex-fill" 
                        data-bs-toggle="modal" data-bs-target="#payInvoiceModal{{ $invoice->id }}">
                    <i class="bi bi-credit-card me-1"></i> Bayar Sekarang
                </button>
                <a href="{{ route('student.invoices.show', $invoice) }}" 
                   class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-eye me-1"></i> Detail
                </a>
                <a href="{{ route('student.invoices.download', $invoice) }}" 
                   class="btn btn-outline-info btn-sm">
                    <i class="bi bi-download me-1"></i> PDF
                </a>
            </div>
        </div>
        @endif
        
        @if($invoice->status == 'paid' && $invoice->payment)
        <div class="mt-2 pt-2 border-top">
            <small class="text-success">
                <i class="bi bi-check-circle me-1"></i>
                Dibayar pada {{ \Carbon\Carbon::parse($invoice->payment->paid_at)->format('d M Y') }}
                via {{ $invoice->payment->method }}
            </small>
        </div>
        @endif
    </div>
</div>

@if($showActions && in_array($invoice->status, ['pending', 'overdue']))
@include('components.modals.confirm-payment', ['invoice' => $invoice])
@endif