@extends('layouts.admin')

@section('title', 'Tambah Siswa Baru')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-person-plus me-2"></i>Tambah Siswa Baru
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.students.store') }}" method="POST" id="studentForm">
                    @csrf
                    
                    <h6 class="text-primary mb-3"><i class="bi bi-person me-2"></i>Data Pribadi</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name') }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="gender" class="form-select">
                                <option value="">Pilih...</option>
                                <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="form-control" 
                                   value="{{ old('birth_date') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="birth_place" class="form-control" 
                                   value="{{ old('birth_place') }}">
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6 class="text-primary mb-3"><i class="bi bi-mortarboard me-2"></i>Data Pendidikan</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelas <span class="text-danger">*</span></label>
                            <input type="text" name="grade" class="form-control" 
                                   value="{{ old('grade') }}" required 
                                   placeholder="Contoh: 6 SD, 9 SMP, 12 SMA">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sekolah <span class="text-danger">*</span></label>
                            <input type="text" name="school" class="form-control" 
                                   value="{{ old('school') }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Program Les <span class="text-danger">*</span></label>
                            <select name="program" class="form-select" required>
                                <option value="">Pilih Program...</option>
                                <option value="SD" {{ old('program') == 'SD' ? 'selected' : '' }}>SD</option>
                                <option value="SMP" {{ old('program') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA" {{ old('program') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                <option value="UTBK" {{ old('program') == 'UTBK' ? 'selected' : '' }}>UTBK</option>
                                <option value="Privat" {{ old('program') == 'Privat' ? 'selected' : '' }}>Privat</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Biaya Bulanan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="monthly_fee" class="form-control" 
                                       value="{{ old('monthly_fee') }}" required 
                                       placeholder="500000">
                            </div>
                            <div class="form-text">Biaya les per bulan</div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6 class="text-primary mb-3"><i class="bi bi-telephone me-2"></i>Kontak Orang Tua/Wali</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Orang Tua/Wali <span class="text-danger">*</span></label>
                            <input type="text" name="parent_name" class="form-control" 
                                   value="{{ old('parent_name') }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hubungan <span class="text-danger">*</span></label>
                            <select name="parent_relation" class="form-select" required>
                                <option value="">Pilih...</option>
                                <option value="Ibu" {{ old('parent_relation') == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                                <option value="Ayah" {{ old('parent_relation') == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                                <option value="Wali" {{ old('parent_relation') == 'Wali' ? 'selected' : '' }}>Wali</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                            <input type="tel" name="parent_phone" class="form-control" 
                                   value="{{ old('parent_phone') }}" required 
                                   placeholder="0812-3456-7890">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="parent_email" class="form-control" 
                                   value="{{ old('parent_email') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6 class="text-primary mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Tambahan</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Bergabung <span class="text-danger">*</span></label>
                            <input type="date" name="join_date" class="form-control" 
                                   value="{{ old('join_date', date('Y-m-d')) }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input type="checkbox" name="is_active" class="form-check-input" 
                                       id="is_active" checked>
                                <label class="form-check-label" for="is_active">Siswa Aktif</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Informasi Kesehatan</label>
                        <textarea name="health_info" class="form-control" rows="2" 
                                  placeholder="Contoh: Alergi, kondisi khusus, dll.">{{ old('health_info') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2" 
                                  placeholder="Catatan khusus tentang siswa">{{ old('notes') }}</textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Informasi Login:</strong> 
                        Sistem akan secara otomatis membuat akun login untuk siswa/orang tua 
                        dengan mengirimkan kredensial ke nomor telepon yang terdaftar.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan Data Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Quick Guide -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-lightbulb me-2"></i>Panduan Pengisian
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Perhatian!</h6>
                    <p class="mb-0">Pastikan data yang diisi akurat untuk menghindari kesalahan dalam pembuatan tagihan.</p>
                </div>
                
                <h6>Tips:</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Isi nomor telepon dengan benar untuk notifikasi
                    </li>
                    <li class="list-group-item px-0">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Biaya bulanan menentukan nominal tagihan
                    </li>
                    <li class="list-group-item px-0">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Program menentukan pengelompokan siswa
                    </li>
                    <li class="list-group-item px-0">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Status aktif untuk siswa yang masih les
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Recent Students -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-clock-history me-2"></i>Siswa Terbaru
                </h6>
            </div>
            <div class="card-body">
                @if($recentStudents->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($recentStudents as $student)
                    <div class="list-group-item px-0">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $student->name }}</h6>
                            <small>{{ $student->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1">{{ $student->grade }} | {{ $student->program }}</p>
                        <small class="text-muted">
                            Rp {{ number_format($student->monthly_fee, 0, ',', '.') }}/bln
                        </small>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted text-center py-3">Belum ada data siswa</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Generate student ID
    function generateStudentId() {
        const year = new Date().getFullYear().toString().substr(-2);
        const random = Math.floor(1000 + Math.random() * 9000);
        return `SIS${year}${random}`;
    }
    
    // Auto-fill student ID if not exists
    document.getElementById('studentForm').addEventListener('submit', function(e) {
        const studentIdInput = document.querySelector('input[name="student_id"]');
        if (!studentIdInput || !studentIdInput.value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'student_id';
            input.value = generateStudentId();
            this.appendChild(input);
        }
    });
</script>
@endpush
@endsection