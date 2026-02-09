@extends('layouts.admin')

@section('title', 'Manajemen Pembayaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="bi bi-credit-card me-2"></i>Manajemen Pembayaran
    </h1>
    <div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="bi bi-filter me-1"></i>Filter Lanjutan
        </button>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 fw-bold text-primary">Filter Pembayaran</h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Metode</label>
                <select name="method" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Metode</option>
                    <option value="transfer" {{ request('method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="qris" {{ request('method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="gopay" {{ request('method') == 'gopay' ? 'selected' : '' }}>GoPay</option>
                    <option value="dana" {{ request('method') == 'dana' ? 'selected' : '' }}>DANA</option>
                    <option value="ovo" {{ request('method') == 'ovo' ? 'selected' : '' }}>OVO</option>
                    <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" 
                       value="{{ request('start_date') }}" onchange="this.form.submit()">
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" 
                       value="{{ request('end_date') }}" onchange="this.form.submit()">
            </div>
            
            <div class="col-md-12 mt-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari berdasarkan nama siswa, no invoice..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                    @if(request()->hasAny(['status', 'method', 'start_date', 'end_date', 'search']))
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
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
        <h6 class="m-0 fw-bold text-primary">Daftar Pembayaran</h6>
        <div>
            <span class="badge bg-primary">{{ $payments->total() }} Pembayaran</span>
            <span class="badge bg-success ms-2">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
        </div>
    </div>
    <div class="card-body">
        @if($payments->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Invoice</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td>
                            {{ \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y') }}<br>
                            <small class="text-muted">{{ $payment->paid_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <strong>{{ $payment->invoice->student->name }}</strong><br>
                            <small class="text-muted">{{ $payment->invoice->student->program }}</small>
                        </td>
                        <td>
                            <small>{{ $payment->invoice->invoice_number }}</small><br>
                            <small class="text-muted">{{ $payment->invoice->month_name }} {{ $payment->invoice->year }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($payment->method) }}</span>
                        </td>
                        <td>
                            <strong class="text-primary">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td>
                            @if($payment->status == 'confirmed')
                            <span class="badge bg-success">Dikonfirmasi</span><br>
                            <small class="text-muted">
                                {{ $payment->confirmed_at->format('d/m/Y H:i') }}
                            </small>
                            @elseif($payment->status == 'rejected')
                            <span class="badge bg-danger">Ditolak</span><br>
                            @if($payment->rejection_reason)
                            <small class="text-muted" title="{{ $payment->rejection_reason }}">
                                {{ Str::limit($payment->rejection_reason, 20) }}
                            </small>
                            @endif
                            @else
                            <span class="badge bg-warning">Menunggu</span>
                            @endif
                        </td>
                        <td>
                            @if($payment->proof_path)
                            <a href="{{ asset('storage/' . $payment->proof_path) }}" 
                               target="_blank" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-image"></i>
                            </a>
                            @else
                            <span class="badge bg-secondary">Tidak ada</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#paymentDetailModal{{ $payment->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                
                                @if($payment->status == 'pending')
                                <a href="{{ route('admin.payments.confirm', $payment) }}" 
                                   class="btn btn-outline-success" title="Konfirmasi">
                                    <i class="bi bi-check"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#rejectPaymentModal{{ $payment->id }}">
                                    <i class="bi bi-x"></i>
                                </button>
                                @endif
                                
                                @if($payment->status == 'confirmed')
                                <button type="button" class="btn btn-outline-warning" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#undoConfirmModal{{ $payment->id }}">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Detail Modal -->
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
                                                @if($payment->confirmed_by)
                                                <tr>
                                                    <td>Dikonfirmasi Oleh</td>
                                                    <td>{{ $payment->confirmed_by }}</td>
                                                </tr>
                                                @endif
                                                @if($payment->confirmed_at)
                                                <tr>
                                                    <td>Waktu Konfirmasi</td>
                                                    <td>{{ $payment->confirmed_at->format('d M Y H:i') }}</td>
                                                </tr>
                                                @endif
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
                                                    <td>Bulan/Tahun</td>
                                                    <td>{{ $payment->invoice->month_name }} {{ $payment->invoice->year }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Telepon</td>
                                                    <td>{{ $payment->invoice->student->parent_phone }}</td>
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
                                    
                                    @if($payment->rejection_reason)
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

                    <!-- Reject Modal -->
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
                                            <textarea name="rejection_reason" class="form-control" rows="3" required 
                                                      placeholder="Contoh: Bukti transfer tidak jelas, nominal tidak sesuai, dll."></textarea>
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

                    <!-- Undo Confirm Modal -->
                    @if($payment->status == 'confirmed')
                    <div class="modal fade" id="undoConfirmModal{{ $payment->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Batalkan Konfirmasi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.payments.undo-confirm', $payment) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <p>Anda akan membatalkan konfirmasi pembayaran:</p>
                                        <div class="alert alert-warning">
                                            <strong>{{ $payment->invoice->student->name }}</strong><br>
                                            Invoice: {{ $payment->invoice->invoice_number }}<br>
                                            Jumlah: Rp {{ number_format($payment->amount, 0, ',', '.') }}<br>
                                            Dikonfirmasi: {{ $payment->confirmed_at->format('d M Y H:i') }}
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Alasan Pembatalan</label>
                                            <textarea name="reason" class="form-control" rows="2" 
                                                      placeholder="Opsional: Berikan alasan pembatalan"></textarea>
                                        </div>
                                        
                                        <div class="alert alert-danger">
                                            <i class="bi bi-exclamation-triangle"></i>
                                            Status invoice akan kembali menjadi "pending" setelah pembatalan.
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-warning">Batalkan Konfirmasi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
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
            <i class="bi bi-credit-card text-muted" style="font-size: 3rem;"></i>
            <h4 class="mt-3">Belum ada pembayaran</h4>
            <p class="text-muted">Pembayaran akan muncul di sini setelah siswa melakukan konfirmasi pembayaran.</p>
        </div>
        @endif
    </div>
</div>

<!-- Summary Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card shadow border-left-primary">
            <div class="card-body">
                <div class="text-primary fw-bold">Total Pembayaran</div>
                <h3 class="mt-2">{{ $summary['total'] }}</h3>
                <div class="text-muted">Semua waktu</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow border-left-success">
            <div class="card-body">
                <div class="text-success fw-bold">Terkonfirmasi</div>
                <h3 class="mt-2">{{ $summary['confirmed'] }}</h3>
                <div class="text-muted">Rp {{ number_format($summary['confirmed_amount'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow border-left-warning">
            <div class="card-body">
                <div class="text-warning fw-bold">Menunggu</div>
                <h3 class="mt-2">{{ $summary['pending'] }}</h3>
                <div class="text-muted">Perlu dikonfirmasi</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow border-left-danger">
            <div class="card-body">
                <div class="text-danger fw-bold">Ditolak</div>
                <h3 class="mt-2">{{ $summary['rejected'] }}</h3>
                <div class="text-muted">{{ $summary['rejected_percentage'] }}% dari total</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Lanjutan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Minimum Amount</label>
                            <input type="number" name="min_amount" class="form-control" 
                                   value="{{ request('min_amount') }}" placeholder="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Maximum Amount</label>
                            <input type="number" name="max_amount" class="form-control" 
                                   value="{{ request('max_amount') }}" placeholder="1000000">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Program</label>
                            <select name="program" class="form-select">
                                <option value="">Semua Program</option>
                                <option value="SD" {{ request('program') == 'SD' ? 'selected' : '' }}>SD</option>
                                <option value="SMP" {{ request('program') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA" {{ request('program') == 'SMA' ? 'selected' : '' }}>SMA</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dikonfirmasi Oleh</label>
                            <input type="text" name="confirmed_by" class="form-control" 
                                   value="{{ request('confirmed_by') }}" placeholder="Nama admin">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Urut Berdasarkan</label>
                        <div class="row">
                            <div class="col-md-6">
                                <select name="sort_by" class="form-select">
                                    <option value="paid_at" {{ request('sort_by') == 'paid_at' ? 'selected' : '' }}>Tanggal Bayar</option>
                                    <option value="amount" {{ request('sort_by') == 'amount' ? 'selected' : '' }}>Jumlah</option>
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Buat</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select name="sort_order" class="form-select">
                                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Turun</option>
                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Naik</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.datatable').DataTable({
            "order": [[0, "desc"]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
            },
            "pageLength": 50
        });
        
        // Auto-submit date inputs
        $('input[type="date"]').on('change', function() {
            if ($(this).val()) {
                $(this).closest('form').submit();
            }
        });
    });
</script>
@endpush
@endsection