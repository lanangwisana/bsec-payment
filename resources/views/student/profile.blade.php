@extends('layouts.app')

@section('title', 'Profil Siswa')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <!-- Profile Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 fw-bold">
                    <i class="bi bi-person-circle me-2"></i>Profil Siswa
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 100px;">
                        <i class="bi bi-person" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-1">{{ $student->name }}</h4>
                <p class="text-muted mb-3">
                    <i class="bi bi-mortarboard me-1"></i>
                    {{ $student->grade }} | {{ $student->school }}
                </p>
                
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" 
                            data-bs-target="#changePasswordModal">
                        <i class="bi bi-key me-1"></i>Ganti Password
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Status Card -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-info-circle me-2"></i>Status
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Status Siswa
                        <span class="badge bg-{{ $student->is_active ? 'success' : 'danger' }}">
                            {{ $student->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Bergabung Sejak
                        <span>{{ \Carbon\Carbon::parse($student->created_at)->format('d M Y') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Program
                        <span>{{ $student->program }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Biaya/Bulan
                        <span class="fw-bold">Rp {{ number_format($student->monthly_fee, 0, ',', '.') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- Edit Profile Form -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-pencil-square me-2"></i>Edit Profil
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('student.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ $student->name }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelas <span class="text-danger">*</span></label>
                            <input type="text" name="grade" class="form-control" 
                                   value="{{ $student->grade }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sekolah <span class="text-danger">*</span></label>
                            <input type="text" name="school" class="form-control" 
                                   value="{{ $student->school }}" required>
                        </div>
                        
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
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon Orang Tua <span class="text-danger">*</span></label>
                            <input type="tel" name="parent_phone" class="form-control" 
                                   value="{{ $student->parent_phone }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Orang Tua</label>
                            <input type="email" name="parent_email" class="form-control" 
                                   value="{{ $student->parent_email }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="3">{{ $student->address }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Informasi Kesehatan (Opsional)</label>
                        <textarea name="health_info" class="form-control" rows="2" 
                                  placeholder="Contoh: Alergi, kondisi khusus, dll.">{{ $student->health_info }}</textarea>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Emergency Contact -->
        <div class="card shadow mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-telephone me-2"></i>Kontak Darurat
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('student.emergency.update') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Kontak Darurat</label>
                            <input type="text" name="emergency_contact_name" class="form-control" 
                                   value="{{ $student->emergency_contact_name }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hubungan</label>
                            <input type="text" name="emergency_contact_relation" class="form-control" 
                                   value="{{ $student->emergency_contact_relation }}" 
                                   placeholder="Contoh: Ibu, Ayah, Wali">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon Darurat</label>
                            <input type="tel" name="emergency_contact_phone" class="form-control" 
                                   value="{{ $student->emergency_contact_phone }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-outline-primary">
                                    Simpan Kontak Darurat
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ganti Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('student.password.update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ganti Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection