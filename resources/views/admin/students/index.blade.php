@extends('layouts.admin')

@section('title', 'Manajemen Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="bi bi-people me-2"></i>Manajemen Siswa
    </h1>
    <div>
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i>Tambah Siswa
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 fw-bold text-primary">Filter Siswa</h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Program</label>
                <select name="program" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Program</option>
                    <option value="SD" {{ request('program') == 'SD' ? 'selected' : '' }}>SD</option>
                    <option value="SMP" {{ request('program') == 'SMP' ? 'selected' : '' }}>SMP</option>
                    <option value="SMA" {{ request('program') == 'SMA' ? 'selected' : '' }}>SMA</option>
                    <option value="UTBK" {{ request('program') == 'UTBK' ? 'selected' : '' }}>UTBK</option>
                    <option value="Privat" {{ request('program') == 'Privat' ? 'selected' : '' }}>Privat</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Cari Siswa</label>
                <div class="input-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari berdasarkan nama, sekolah, atau telepon..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                    @if(request()->hasAny(['status', 'program', 'search']))
                    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
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
        <h6 class="m-0 fw-bold text-primary">Daftar Siswa</h6>
        <div>
            <span class="badge bg-primary">{{ $students->total() }} Siswa</span>
        </div>
    </div>
    <div class="card-body">
        @if($students->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kelas/Sekolah</th>
                        <th>Program</th>
                        <th>Biaya/Bulan</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 36px; height: 36px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <strong>{{ $student->name }}</strong>
                                    <br>
                                    <small class="text-muted">ID: {{ $student->student_id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ $student->grade }}<br>
                            <small class="text-muted">{{ $student->school }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $student->program }}</span>
                        </td>
                        <td>
                            <strong class="text-primary">
                                Rp {{ number_format($student->monthly_fee, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td>
                            <small>
                                <i class="bi bi-telephone"></i> {{ $student->parent_phone }}<br>
                                @if($student->parent_email)
                                <i class="bi bi-envelope"></i> {{ $student->parent_email }}
                                @endif
                            </small>
                        </td>
                        <td>
                            @if($student->is_active)
                            <span class="badge bg-success">Aktif</span>
                            @else
                            <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.students.show', $student) }}" 
                                   class="btn btn-outline-primary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.students.edit', $student) }}" 
                                   class="btn btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('admin.invoices.create', ['student_id' => $student->id]) }}" 
                                   class="btn btn-outline-success" title="Buat Tagihan">
                                    <i class="bi bi-receipt"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteStudentModal{{ $student->id }}"
                                        title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteStudentModal{{ $student->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menghapus siswa:</p>
                                    <div class="alert alert-danger">
                                        <strong>{{ $student->name }}</strong><br>
                                        {{ $student->grade }} - {{ $student->school }}
                                    </div>
                                    <p class="text-danger">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        Data yang dihapus tidak dapat dikembalikan.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus Siswa</button>
                                    </form>
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
                Menampilkan {{ $students->firstItem() }} - {{ $students->lastItem() }} 
                dari {{ $students->total() }} siswa
            </div>
            <div>
                {{ $students->links() }}
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
            <h4 class="mt-3">Belum ada data siswa</h4>
            <p class="text-muted">Tambahkan siswa baru untuk memulai manajemen pembayaran les.</p>
            <a href="{{ route('admin.students.create') }}" class="btn btn-primary mt-2">
                <i class="bi bi-person-plus me-1"></i>Tambah Siswa Pertama
            </a>
        </div>
        @endif
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body text-center">
                <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ $stats['total'] }}</h3>
                <p class="text-muted mb-0">Total Siswa</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body text-center">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ $stats['active'] }}</h3>
                <p class="text-muted mb-0">Siswa Aktif</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body text-center">
                <i class="bi bi-x-circle-fill text-danger" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ $stats['inactive'] }}</h3>
                <p class="text-muted mb-0">Siswa Nonaktif</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.datatable').DataTable({
            "order": [[0, "asc"]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
            },
            "dom": '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
            "pageLength": 25
        });
    });
</script>
@endpush
@endsection