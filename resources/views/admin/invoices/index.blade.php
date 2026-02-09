@extends('layouts.admin')

@section('title', 'Manajemen Tagihan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="bi bi-receipt me-2"></i>Manajemen Tagihan
    </h1>
    <div>
        <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary me-2">
            <i class="bi bi-plus-circle me-1"></i>Tambah Tagihan
        </a>
        <a href="{{ route('admin.invoices.bulk-create') }}" class="btn btn-success">
            <i class="bi bi-gear me-1"></i>Generate Tagihan
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
                <label class="form-label">Bulan</label>
                <select name="month" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                    @endfor
                </select>
            </div>
            
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
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Siswa</label>
                <select name="student_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Siswa</option>
                    @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                        {{ $student->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-12 mt-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari berdasarkan no invoice, nama siswa..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                    @if(request()->hasAny(['month', 'year', 'status', 'student_id', 'search']))
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary">
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
        <h6 class="m-0 fw-bold text-primary">Daftar Tagihan</h6>
        <div>
            <span class="badge bg-primary">{{ $invoices->total() }} Tagihan</span>
        </div>
    </div>
    <div class="card-body">
        @if($invoices->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Siswa</th>
                        <th>Bulan/Tahun</th>
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
                            <strong>{{ $invoice->invoice_number }}</strong><br>
                            <small class="text-muted">{{ $invoice->created_at->format('d/m/Y') }}</small>
                        </td>
                        <td>
                            <strong>{{ $invoice->student->name }}</strong><br>
                            <small class="text-muted">{{ $invoice->student->program }}</small>
                        </td>
                        <td>
                            {{ $invoice->month_name }} {{ $invoice->year }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}
                            @if($invoice->is_overdue)
                            <br>
                            <small class="text-danger">
                                <i class="bi bi-exclamation-triangle"></i> 
                                {{ $invoice->days_overdue }} hari
                            </small>
                            @endif
                        </td>
                        <td>
                            <strong class="text-primary">
                                Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                            </strong>
                            @if($invoice->late_fee > 0)
                            <br>
                            <small class="text-danger">
                                + denda: Rp {{ number_format($invoice->late_fee, 0, ',', '.') }}
                            </small>
                            @endif
                        </td>
                        <td>
                            @if($invoice->status == 'paid')
                            <span class="badge bg-success">LUNAS</span>
                            @elseif($invoice->status == 'overdue')
                            <span class="badge bg-danger">TERLAMBAT</span>
                            @else
                            <span class="badge bg-warning">MENUNGGU</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.invoices.show', $invoice) }}" 
                                   class="btn btn-outline-primary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.invoices.edit', $invoice) }}" 
                                   class="btn btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('admin.invoices.download', $invoice) }}" 
                                   class="btn btn-outline-info" title="Download PDF" target="_blank">
                                    <i class="bi bi-download"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteInvoiceModal{{ $invoice->id }}"
                                        title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteInvoiceModal{{ $invoice->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menghapus tagihan:</p>
                                    <div class="alert alert-danger">
                                        <strong>#{{ $invoice->invoice_number }}</strong><br>
                                        {{ $invoice->student->name }} - {{ $invoice->month_name }} {{ $invoice->year }}<br>
                                        Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                                    </div>
                                    @if($invoice->payment)
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        Tagihan ini sudah memiliki pembayaran. 
                                        Hapus pembayaran terlebih dahulu.
                                    </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    @if(!$invoice->payment)
                                    <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus Tagihan</button>
                                    </form>
                                    @endif
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
            <p class="text-muted">Buat tagihan baru atau generate tagihan untuk bulan ini.</p>
            <div class="mt-3">
                <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary me-2">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Tagihan
                </a>
                <a href="{{ route('admin.invoices.bulk-create') }}" class="btn btn-success">
                    <i class="bi bi-gear me-1"></i>Generate Tagihan
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Summary Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card shadow border-left-primary">
            <div class="card-body">
                <div class="text-primary fw-bold">Total Tagihan</div>
                <h3 class="mt-2">{{ $summary['total'] }}</h3>
                <div class="text-muted">Semua waktu</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow border-left-success">
            <div class="card-body">
                <div class="text-success fw-bold">Lunas</div>
                <h3 class="mt-2">{{ $summary['paid'] }}</h3>
                <div class="text-muted">{{ $summary['paid_percentage'] }}% dari total</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow border-left-warning">
            <div class="card-body">
                <div class="text-warning fw-bold">Pending</div>
                <h3 class="mt-2">{{ $summary['pending'] }}</h3>
                <div class="text-muted">Menunggu pembayaran</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow border-left-danger">
            <div class="card-body">
                <div class="text-danger fw-bold">Terlambat</div>
                <h3 class="mt-2">{{ $summary['overdue'] }}</h3>
                <div class="text-muted">Perlu ditagih</div>
            </div>
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
            "pageLength": 50,
            "dom": '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>'
        });
    });
</script>
@endpush
@endsection