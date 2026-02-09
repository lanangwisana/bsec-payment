@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin
    </h1>
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-plus-circle me-2"></i>Tambah Data
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('admin.students.create') }}">
                <i class="bi bi-person-plus me-2"></i>Tambah Siswa
            </a></li>
            <li><a class="dropdown-item" href="{{ route('admin.invoices.create') }}">
                <i class="bi bi-receipt me-2"></i>Tambah Tagihan
            </a></li>
            <li><a class="dropdown-item" href="{{ route('admin.invoices.bulk-create') }}">
                <i class="bi bi-gear me-2"></i>Generate Tagihan
            </a></li>
        </ul>
    </div>
</div>

<!-- Statistics Row -->
<div class="row">
    <!-- Total Students Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                            Total Siswa
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['total_students'] }}</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-success me-2">
                                <i class="bi bi-arrow-up"></i> {{ $stats['active_students'] }} aktif
                            </span>
                            <span class="text-danger">
                                <i class="bi bi-arrow-down"></i> {{ $stats['inactive_students'] }} nonaktif
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people fs-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">
                            Pendapatan Bulan Ini
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}
                        </div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-success me-2">
                                <i class="bi bi-arrow-up"></i> {{ $stats['paid_invoices'] }} invoice lunas
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-cash-coin fs-1 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Payments Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                            Pembayaran Menunggu
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['pending_payments'] }}</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-warning me-2">
                                <i class="bi bi-clock"></i> Perlu konfirmasi
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-clock-history fs-1 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Invoices Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-danger text-uppercase mb-1">
                            Tagihan Terlambat
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['overdue_invoices'] }}</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-danger me-2">
                                <i class="bi bi-exclamation-triangle"></i> Perlu ditagih
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-exclamation-triangle fs-1 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Payments -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-clock-history me-2"></i>Pembayaran Terbaru
                </h6>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Siswa</th>
                                <th>Invoice</th>
                                <th>Metode</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $payment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y') }}</td>
                                <td>{{ $payment->invoice->student->name }}</td>
                                <td><small>{{ $payment->invoice->invoice_number }}</small></td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($payment->method) }}</span>
                                </td>
                                <td>
                                    <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($payment->status == 'confirmed')
                                    <span class="badge bg-success">Dikonfirmasi</span>
                                    @elseif($payment->status == 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                    @else
                                    <span class="badge bg-warning">Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if($payment->status == 'pending')
                                        <a href="{{ route('admin.payments.confirm', $payment) }}" 
                                           class="btn btn-outline-success" title="Konfirmasi">
                                            <i class="bi bi-check"></i>
                                        </a>
                                        @endif
                                        <a href="#" class="btn btn-outline-primary" 
                                           data-bs-toggle="modal" 
                                           data-bs-target="#paymentDetailModal{{ $payment->id }}">
                                            <i class="bi bi-eye"></i>
                                        </a>
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
                                                                <span class="badge bg-{{ $payment->status == 'confirmed' ? 'success' : ($payment->status == 'rejected' ? 'danger' : 'warning') }}">
                                                                    {{ $payment->status == 'confirmed' ? 'Dikonfirmasi' : ($payment->status == 'rejected' ? 'Ditolak' : 'Menunggu') }}
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
                                                    <h6>Informasi Siswa</h6>
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <td width="40%">Nama</td>
                                                            <td>{{ $payment->invoice->student->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Invoice</td>
                                                            <td>{{ $payment->invoice->invoice_number }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bulan</td>
                                                            <td>{{ $payment->invoice->month_name }} {{ $payment->invoice->year }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            
                                            @if($payment->notes)
                                            <div class="alert alert-secondary mt-3">
                                                <h6><i class="bi bi-chat-left-text me-2"></i>Catatan Pembayaran</h6>
                                                <p class="mb-0">{{ $payment->notes }}</p>
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
                                            @if($payment->status == 'pending')
                                            <div class="btn-group">
                                                <a href="{{ route('admin.payments.confirm', $payment) }}" 
                                                   class="btn btn-success">
                                                    <i class="bi bi-check me-1"></i>Konfirmasi
                                                </a>
                                                <button type="button" class="btn btn-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectPaymentModal{{ $payment->id }}">
                                                    <i class="bi bi-x me-1"></i>Tolak
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Reject Payment Modal -->
                            @if($payment->status == 'pending')
                            <div class="modal fade" id="rejectPaymentModal{{ $payment->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tolak Pembayaran</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.payments.reject', $payment) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <p>Anda akan menolak pembayaran dari:</p>
                                                <div class="alert alert-info">
                                                    <strong>{{ $payment->invoice->student->name }}</strong><br>
                                                    Invoice: {{ $payment->invoice->invoice_number }}<br>
                                                    Jumlah: Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                                    <textarea name="rejection_reason" class="form-control" rows="3" 
                                                              placeholder="Contoh: Bukti transfer tidak jelas, nominal tidak sesuai, dll." required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-credit-card text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mt-2 mb-0">Belum ada pembayaran</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Recent Invoices -->
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-receipt me-2"></i>Tagihan Terbaru
                </h6>
                <a href="{{ route('admin.invoices.index') }}" class="btn btn-sm btn-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Siswa</th>
                                <th>Bulan</th>
                                <th>Jatuh Tempo</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentInvoices as $invoice)
                            <tr>
                                <td><small>{{ $invoice->invoice_number }}</small></td>
                                <td>{{ $invoice->student->name }}</td>
                                <td>{{ $invoice->month_name }} {{ $invoice->year }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</td>
                                <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($invoice->status == 'paid')
                                    <span class="badge bg-success">Lunas</span>
                                    @elseif($invoice->status == 'overdue')
                                    <span class="badge bg-danger">Terlambat</span>
                                    @else
                                    <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.invoices.show', $invoice) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.invoices.edit', $invoice) }}" 
                                           class="btn btn-outline-secondary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-receipt text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mt-2 mb-0">Belum ada tagihan</p>
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
        <!-- Quick Actions -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-lightning me-2"></i>Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.invoices.bulk-create') }}" class="btn btn-primary">
                        <i class="bi bi-gear me-2"></i>Generate Tagihan Bulanan
                    </a>
                    <a href="{{ route('admin.students.create') }}" class="btn btn-outline-primary">
                        <i class="bi bi-person-plus me-2"></i>Tambah Siswa Baru
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-warning">
                        <i class="bi bi-clock-history me-2"></i>Konfirmasi Pembayaran
                    </a>
                    <a href="{{ route('admin.reports.monthly') }}" class="btn btn-outline-success">
                        <i class="bi bi-graph-up me-2"></i>Lihat Laporan
                    </a>
                    <a href="#" class="btn btn-outline-info" data-bs-toggle="modal" 
                       data-bs-target="#sendReminderModal">
                        <i class="bi bi-bell me-2"></i>Kirim Pengingat
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Overdue Invoices -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-danger text-white">
                <h6 class="m-0 fw-bold">
                    <i class="bi bi-exclamation-triangle me-2"></i>Tagihan Terlambat
                </h6>
            </div>
            <div class="card-body">
                @if($overdueInvoices->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($overdueInvoices as $invoice)
                    <div class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $invoice->student->name }}</h6>
                            <small>{{ $invoice->days_overdue }} hari</small>
                        </div>
                        <p class="mb-1">
                            {{ $invoice->month_name }} {{ $invoice->year }} | 
                            Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                        </p>
                        <small class="text-muted">
                            Jatuh tempo: {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}
                        </small>
                        <div class="mt-2">
                            <a href="https://wa.me/{{ $invoice->student->parent_phone }}?text=Tagihan%20les%20bulan%20{{ $invoice->month_name }}%20{{ $invoice->year }}%20sudah%20terlambat%20{{ $invoice->days_overdue }}%20hari.%20Silakan%20lakukan%20pembayaran%20segera." 
                               target="_blank" class="btn btn-sm btn-success">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </a>
                            <a href="tel:{{ $invoice->student->parent_phone }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-telephone"></i> Telepon
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                    <p class="text-muted mt-2 mb-0">Tidak ada tagihan terlambat</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Monthly Stats -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-bar-chart me-2"></i>Statistik {{ date('F Y') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Tagihan Terbayar</span>
                        <span>{{ $stats['paid_invoices'] }}/{{ $stats['total_invoices'] }}</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" 
                             style="width: {{ $stats['total_invoices'] > 0 ? ($stats['paid_invoices'] / $stats['total_invoices'] * 100) : 0 }}%">
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Siswa Aktif</span>
                        <span>{{ $stats['active_students'] }}/{{ $stats['total_students'] }}</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-primary" 
                             style="width: {{ $stats['total_students'] > 0 ? ($stats['active_students'] / $stats['total_students'] * 100) : 0 }}%">
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6>Target Pendapatan</h6>
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-0 text-primary">
                                Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}
                            </h4>
                            <small class="text-muted">Terpenuhi</small>
                        </div>
                        <div class="text-end">
                            <h4 class="mb-0 text-success">
                                {{ $stats['target_percentage'] }}%
                            </h4>
                            <small class="text-muted">dari target</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Send Reminder Modal -->
<div class="modal fade" id="sendReminderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kirim Pengingat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.send-reminder') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Siswa</label>
                        <select name="student_id" class="form-select">
                            <option value="">Semua Siswa dengan Tagihan Tertunda</option>
                            @foreach($pendingStudents as $student)
                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Metode</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="methods[]" value="whatsapp" id="whatsapp" checked>
                                <label class="form-check-label" for="whatsapp">
                                    <i class="bi bi-whatsapp text-success"></i> WhatsApp
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="methods[]" value="sms" id="sms">
                                <label class="form-check-label" for="sms">
                                    <i class="bi bi-chat-left-text"></i> SMS
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Pesan (Opsional)</label>
                        <textarea name="message" class="form-control" rows="3" 
                                  placeholder="Pesan default akan digunakan jika kosong"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Pengingat</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-refresh dashboard every 60 seconds
    setInterval(function() {
        location.reload();
    }, 60000);
</script>
@endpush
@endsection