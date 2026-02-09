@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                            Tagihan Bulan Ini
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            Rp {{ number_format($currentInvoice->amount ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-receipt fs-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">
                            Total Dibayar
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            Rp {{ number_format($totalPaid, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-credit-card fs-1 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                            Tagihan Tertunda
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ $pendingCount }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-clock-history fs-1 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-info text-uppercase mb-1">
                            Status
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ $student->is_active ? 'Aktif' : 'Nonaktif' }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-person-check fs-1 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Current Invoice -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-receipt me-2"></i>Tagihan Terbaru
                </h6>
                <a href="{{ route('student.invoices.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($currentInvoice)
                    @include('components.cards.invoice-card', ['invoice' => $currentInvoice])
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Tidak ada tagihan aktif</h5>
                        <p class="text-muted">Semua pembayaran Anda sudah lunas</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Recent Payments -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Pembayaran Terakhir
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Tanggal Bayar</th>
                                <th>Metode</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $payment)
                            <tr>
                                <td>{{ $payment->invoice->month_name }} {{ $payment->invoice->year }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y') }}</td>
                                <td>{{ ucfirst($payment->method) }}</td>
                                <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $payment->status == 'confirmed' ? 'success' : 'warning' }}">
                                        {{ $payment->status == 'confirmed' ? 'Dikonfirmasi' : 'Menunggu' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="bi bi-receipt text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mt-2 mb-0">Belum ada riwayat pembayaran</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Student Profile -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-person-circle me-2"></i>Profil Siswa
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-person" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold">{{ $student->name }}</h5>
                <p class="text-muted mb-2">
                    <i class="bi bi-mortarboard me-1"></i>
                    {{ $student->grade }} | {{ $student->school }}
                </p>
                <p class="text-muted mb-3">
                    <i class="bi bi-book me-1"></i>
                    {{ $student->program }}
                </p>
                
                <div class="border-top pt-3">
                    <h6 class="fw-bold">Informasi Kontak</h6>
                    <p class="mb-1">
                        <i class="bi bi-telephone me-1"></i>
                        {{ $student->parent_phone }}
                    </p>
                    @if($student->parent_email)
                    <p class="mb-0">
                        <i class="bi bi-envelope me-1"></i>
                        {{ $student->parent_email }}
                    </p>
                    @endif
                </div>
                
                <div class="mt-3">
                    <a href="{{ route('student.profile') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit Profil
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-lightning me-2"></i>Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($currentInvoice)
                    <button class="btn btn-primary" data-bs-toggle="modal" 
                            data-bs-target="#payInvoiceModal{{ $currentInvoice->id }}">
                        <i class="bi bi-credit-card me-2"></i>Bayar Tagihan Sekarang
                    </button>
                    @endif
                    
                    <a href="{{ route('student.invoices.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-receipt me-2"></i>Lihat Semua Tagihan
                    </a>
                    
                    <a href="{{ route('student.payments.history') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-clock-history me-2"></i>Riwayat Pembayaran
                    </a>
                    
                    <a href="{{ route('student.profile') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-person me-2"></i>Ubah Profil
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Upcoming Due Dates -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-calendar-check me-2"></i>Jadwal Tagihan
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @foreach($upcomingInvoices as $invoice)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <small class="fw-bold">{{ $invoice->month_name }}</small><br>
                            <small class="text-muted">Jatuh tempo: {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M') }}</small>
                        </div>
                        <span class="badge bg-{{ $invoice->status == 'pending' ? 'warning' : 'success' }}">
                            {{ $invoice->status == 'pending' ? 'Rp ' . number_format($invoice->amount, 0, ',', '.') : 'LUNAS' }}
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@if($currentInvoice)
@include('components.modals.confirm-payment', ['invoice' => $currentInvoice])
@endif

@push('scripts')
<script>
    // Auto-check for new notifications
    setInterval(function() {
        fetch('{{ route("student.notifications.check") }}')
            .then(response => response.json())
            .then(data => {
                if(data.has_new_invoice) {
                    location.reload();
                }
            });
    }, 60000); // Check every minute
</script>
@endpush
@endsection