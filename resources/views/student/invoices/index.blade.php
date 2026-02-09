@extends('layouts.app')

@section('title', 'Daftar Tagihan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="bi bi-receipt me-2"></i>Daftar Tagihan
    </h1>
    <div>
        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 fw-bold text-primary">Filter Tagihan</h6>
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
                        Menunggu Pembayaran
                    </option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>
                        Lunas
                    </option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>
                        Terlambat
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
                    <a href="{{ route('student.invoices.index') }}" class="btn btn-outline-secondary">
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
        <h6 class="m-0 fw-bold text-primary">Semua Tagihan</h6>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" 
                    data-bs-toggle="dropdown">
                <i class="bi bi-download me-1"></i>Ekspor
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"><i class="bi bi-file-pdf me-2"></i>PDF</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-file-excel me-2"></i>Excel</a></li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        @if($invoices->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Bulan/Tahun</th>
                        <th>No. Invoice</th>
                        <th>Jatuh Tempo</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    <tr>
                        <td>
                            <strong>{{ $invoice->month_name }} {{ $invoice->year }}</strong>
                        </td>
                        <td>
                            <small class="text-muted">{{ $invoice->invoice_number }}</small>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}
                        </td>
                        <td>
                            <strong class="text-primary">
                                Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'paid' => 'success',
                                    'overdue' => 'danger'
                                ];
                                $statusTexts = [
                                    'pending' => 'Menunggu',
                                    'paid' => 'Lunas',
                                    'overdue' => 'Terlambat'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$invoice->status] }}">
                                {{ $statusTexts[$invoice->status] }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#invoiceDetailModal{{ $invoice->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @if(in_array($invoice->status, ['pending', 'overdue']))
                                <button type="button" class="btn btn-outline-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#payInvoiceModal{{ $invoice->id }}">
                                    <i class="bi bi-credit-card"></i>
                                </button>
                                @endif
                                <a href="{{ route('student.invoices.download', $invoice) }}" 
                                   class="btn btn-outline-info" target="_blank">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Detail Modal -->
                    @include('components.modals.invoice-detail', ['invoice' => $invoice])
                    
                    <!-- Payment Modal -->
                    @if(in_array($invoice->status, ['pending', 'overdue']))
                    @include('components.modals.confirm-payment', ['invoice' => $invoice])
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div>
                Menampilkan {{ $invoices->firstItem() }} - {{ $invoices->lastItem() }} 
                dari {{ $invoices->total() }} tagihan
            </div>
            <div>
                {{ $invoices->links() }}
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
            <h4 class="mt-3">Belum ada tagihan</h4>
            <p class="text-muted">Tagihan akan muncul di sini ketika admin membuat tagihan baru.</p>
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