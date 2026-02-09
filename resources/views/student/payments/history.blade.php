@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="bi bi-clock-history me-2"></i>Riwayat Pembayaran
    </h1>
    <div>
        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 fw-bold text-primary">Filter Pembayaran</h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="year" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Tahun</option>
                    @for($i = date('Y'); $i >= 2020; $i--)
                    <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                        Menunggu Konfirmasi
                    </option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                        Dikonfirmasi
                    </option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                        Ditolak
                    </option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Cari</label>
                <div class="input-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari berdasarkan bulan/no invoice..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                    @if(request()->hasAny(['year', 'status', 'search']))
                    <a href="{{ route('student.payments.history') }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-primary">Semua Pembayaran</h6>
        <div>
            <span class="badge bg-primary">
                Total: {{ $payments->total() }} pembayaran
            </span>
        </div>
    </div>
    <div class="card-body">
        @if($payments->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Invoice</th>
                        <th>Bulan</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td>
                            {{ \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y') }}
                        </td>
                        <td>
                            <small class="text-muted">{{ $payment->invoice->invoice_number }}</small>
                        </td>
                        <td>
                            {{ $payment->invoice->month_name }} {{ $payment->invoice->year }}
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{ ucfirst($payment->method) }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-primary">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'confirmed' => 'success',
                                    'rejected' => 'danger'
                                ];
                                $statusTexts = [
                                    'pending' => 'Menunggu',
                                    'confirmed' => 'Dikonfirmasi',
                                    'rejected' => 'Ditolak'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$payment->status] }}">
                                {{ $statusTexts[$payment->status] }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#paymentDetailModal{{ $payment->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @if($payment->proof_path)
                                <a href="{{ asset('storage/' . $payment->proof_path) }}" 
                                   target="_blank" class="btn btn-outline-info">
                                    <i class="bi bi-image"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Payment Detail Modal -->
                    <div class="modal fade" id="paymentDetailModal{{ $payment->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Pembayaran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Informasi Pembayaran</h6>
                                            <table class="table table-sm">
                                                <tr>
                                                    <td width="40%">Tanggal Bayar</td>
                                                    <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y H:i') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Metode</td>
                                                    <td>{{ ucfirst($payment->method) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Status</td>
                                                    <td>
                                                        <span class="badge bg-{{ $statusColors[$payment->status] }}">
                                                            {{ $statusTexts[$payment->status] }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Jumlah</td>
                                                    <td><strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Tagihan</h6>
                                            <table class="table table-sm">
                                                <tr>
                                                    <td width="40%">Bulan/Tahun</td>
                                                    <td>{{ $payment->invoice->month_name }} {{ $payment->invoice->year }}</td>
                                                </tr>
                                                <tr>
                                                    <td>No. Invoice</td>
                                                    <td>{{ $payment->invoice->invoice_number }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Jatuh Tempo</td>
                                                    <td>{{ \Carbon\Carbon::parse($payment->invoice->due_date)->format('d M Y') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    @if($payment->notes)
                                    <div class="alert alert-secondary mt-3">
                                        <h6><i class="bi bi-chat-left-text me-2"></i>Catatan</h6>
                                        <p class="mb-0">{{ $payment->notes }}</p>
                                    </div>
                                    @endif
                                    
                                    @if($payment->rejection_reason && $payment->status == 'rejected')
                                    <div class="alert alert-danger mt-3">
                                        <h6><i class="bi bi-x-circle me-2"></i>Alasan Penolakan</h6>
                                        <p class="mb-0">{{ $payment->rejection_reason }}</p>
                                    </div>
                                    @endif
                                    
                                    @if($payment->proof_path)
                                    <div class="mt-3">
                                        <h6>Bukti Pembayaran</h6>
                                        <a href="{{ asset('storage/' . $payment->proof_path) }}" 
                                           target="_blank" class="btn btn-outline-primary">
                                            <i class="bi bi-image me-1"></i>Lihat Bukti
                                        </a>
                                    </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div>
                Menampilkan {{ $payments->firstItem() }} - {{ $payments->lastItem() }} 
                dari {{ $payments->total() }} pembayaran
            </div>
            <div>
                {{ $payments->links() }}
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-clock-history text-muted" style="font-size: 3rem;"></i>
            <h4 class="mt-3">Belum ada riwayat pembayaran</h4>
            <p class="text-muted">Riwayat pembayaran akan muncul di sini setelah Anda melakukan pembayaran.</p>
            <a href="{{ route('student.invoices.index') }}" class="btn btn-primary mt-2">
                <i class="bi bi-receipt me-1"></i>Lihat Tagihan
            </a>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
</style>
@endpush
@endsection