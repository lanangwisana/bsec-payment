@extends('layouts.admin')

@section('title', 'Edit Siswa: ' . $student->name)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 fw-bold">
                    <i class="bi bi-pencil-square me-2"></i>Edit Data Siswa
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.students.update', $student) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ID Siswa</label>
                            <input type="text" class="form-control" value="{{ $student->student_id }}" readonly>
                            <div class="form-text">ID siswa tidak dapat diubah</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', $student->name) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelas <span class="text-danger">*</span></label>
                            <input type="text" name="grade" class="form-control" 
                                   value="{{ old('grade', $student->grade) }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sekolah <span class="text-danger">*</span></label>
                            <input type="text" name="school" class="form-control" 
                                   value="{{ old('school', $student->school) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Program <span class="text-danger">*</span></label>
                            <select name="program" class="form-select" required>
                                <option value="SD" {{ $student->program == 'SD' ? 'selected' : '' }}>SD</option>
                                <option value="SMP" {{ $student->program == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA" {{ $student->program == 'SMA' ? 'selected' : '' }}>SMA</option>
                                <option value="UTBK" {{ $student->program == 'UTBK' ? 'selected' : '' }}>UTBK</option>
                                <option value="Privat" {{ $student->program == 'Privat' ? 'selected' : '' }}>Privat</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Biaya Bulanan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="monthly_fee" class="form-control" 
                                       value="{{ old('monthly_fee', $student->monthly_fee) }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon Orang Tua <span class="text-danger">*</span></label>
                            <input type="tel" name="parent_phone" class="form-control" 
                                   value="{{ old('parent_phone', $student->parent_phone) }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Orang Tua</label>
                            <input type="email" name="parent_email" class="form-control" 
                                   value="{{ old('parent_email', $student->parent_email) }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $student->address) }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" class="form-check-input" 
                                   id="is_active" {{ $student->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Siswa Aktif</label>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> Mengubah biaya bulanan hanya mempengaruhi tagihan yang akan datang, 
                        tidak mengubah tagihan yang sudah dibuat.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Student Statistics -->
        <div class="card shadow mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-graph-up me-2"></i>Statistik Siswa
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h4 class="text-primary">{{ $stats['total_invoices'] }}</h4>
                        <p class="text-muted mb-0">Total Tagihan</p>
                    </div>
                    <div class="col-md-3">
                        <h4 class="text-success">{{ $stats['paid_invoices'] }}</h4>
                        <p class="text-muted mb-0">Lunas</p>
                    </div>
                    <div class="col-md-3">
                        <h4 class="text-warning">{{ $stats['pending_invoices'] }}</h4>
                        <p class="text-muted mb-0">Menunggu</p>
                    </div>
                    <div class="col-md-3">
                        <h4 class="text-danger">{{ $stats['overdue_invoices'] }}</h4>
                        <p class="text-muted mb-0">Terlambat</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Student Information -->
        <div class="card shadow mb-4">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-person" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <h4>{{ $student->name }}</h4>
                <p class="text-muted mb-2">
                    <i class="bi bi-mortarboard me-1"></i>
                    {{ $student->grade }} | {{ $student->school }}
                </p>
                <p class="text-muted mb-3">
                    <i class="bi bi-book me-1"></i>
                    {{ $student->program }}
                </p>
                
                <div class="border-top pt-3">
                    <h6>Informasi</h6>
                    <table class="table table-sm">
                        <tr>
                            <td>Bergabung</td>
                            <td>{{ $student->created_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                <span class="badge bg-{{ $student->is_active ? 'success' : 'danger' }}">
                                    {{ $student->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td>{{ $student->parent_phone }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-lightning me-2"></i>Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.invoices.create', ['student_id' => $student->id]) }}" 
                       class="btn btn-primary">
                        <i class="bi bi-receipt me-2"></i>Buat Tagihan
                    </a>
                    <a href="https://wa.me/{{ $student->parent_phone }}" 
                       target="_blank" class="btn btn-success">
                        <i class="bi bi-whatsapp me-2"></i>WhatsApp
                    </a>
                    <a href="tel:{{ $student->parent_phone }}" 
                       class="btn btn-outline-primary">
                        <i class="bi bi-telephone me-2"></i>Telepon
                    </a>
                    <button type="button" class="btn btn-outline-warning" 
                            data-bs-toggle="modal" 
                            data-bs-target="#resetPasswordModal">
                        <i class="bi bi-key me-2"></i>Reset Password
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Recent Invoices -->
        <div class="card shadow mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-clock-history me-2"></i>Tagihan Terakhir
                </h6>
            </div>
            <div class="card-body">
                @if($recentInvoices->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($recentInvoices as $invoice)
                    <div class="list-group-item px-0">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $invoice->month_name }} {{ $invoice->year }}</h6>
                            <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : 'warning' }}">
                                {{ $invoice->status == 'paid' ? 'Lunas' : 'Pending' }}
                            </span>
                        </div>
                        <p class="mb-1">
                            Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                            <small class="text-muted">| Jatuh tempo: {{ $invoice->due_date->format('d M') }}</small>
                        </p>
                        <small class="text-muted">
                            {{ $invoice->created_at->diffForHumans() }}
                        </small>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted text-center py-3">Belum ada tagihan</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset Password Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.students.reset-password', $student) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Reset password untuk:</p>
                    <div class="alert alert-info">
                        <strong>{{ $student->name }}</strong><br>
                        {{ $student->parent_phone }}
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="text" name="password" class="form-control" 
                               value="{{ \Illuminate\Support\Str::random(8) }}" readonly>
                        <div class="form-text">Password akan dikirim via WhatsApp</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Reset & Kirim Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection