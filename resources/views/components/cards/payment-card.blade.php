@props(['payment'])

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="card-title mb-1">
                    Pembayaran {{ $payment->invoice->month_name }} {{ $payment->invoice->year }}
                </h6>
                <p class="card-text text-muted mb-1">
                    <i class="bi bi-clock me-1"></i>
                    {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y H:i') }}
                </p>
                <p class="card-text mb-0">
                    <i class="bi bi-person me-1"></i>
                    {{ $payment->invoice->student->name }}
                </p>
            </div>
            <div class="text-end">
                <h5 class="text-success fw-bold mb-1">
                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                </h5>
                <span class="badge bg-info">
                    {{ ucfirst($payment->method) }}
                </span>
            </div>
        </div>
        
        @if($payment->proof_path)
        <div class="mt-3">
            <a href="{{ asset('storage/' . $payment->proof_path) }}" 
               target="_blank" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-image me-1"></i> Lihat Bukti
            </a>
        </div>
        @endif
        
        @if($payment->notes)
        <div class="mt-2">
            <small class="text-muted">
                <i class="bi bi-chat-left-text me-1"></i>
                {{ $payment->notes }}
            </small>
        </div>
        @endif
    </div>
</div>