@props(['student'])

<div class="modal fade" id="editStudentModal{{ $student->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('admin.students.update', $student) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" 
                               value="{{ $student->name }}" required>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kelas <span class="text-danger">*</span></label>
                            <input type="text" name="grade" class="form-control" 
                                   value="{{ $student->grade }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sekolah <span class="text-danger">*</span></label>
                            <input type="text" name="school" class="form-control" 
                                   value="{{ $student->school }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Program Les <span class="text-danger">*</span></label>
                        <select name="program" class="form-select" required>
                            <option value="SD" {{ $student->program == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ $student->program == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMA" {{ $student->program == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="UTBK" {{ $student->program == 'UTBK' ? 'selected' : '' }}>UTBK</option>
                            <option value="Privat" {{ $student->program == 'Privat' ? 'selected' : '' }}>Privat</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Biaya Bulanan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="monthly_fee" class="form-control" 
                                   value="{{ $student->monthly_fee }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">No. Telepon Orang Tua <span class="text-danger">*</span></label>
                        <input type="tel" name="parent_phone" class="form-control" 
                               value="{{ $student->parent_phone }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email Orang Tua</label>
                        <input type="email" name="parent_email" class="form-control" 
                               value="{{ $student->parent_email }}">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" class="form-check-input" 
                                   id="is_active{{ $student->id }}" 
                                   {{ $student->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active{{ $student->id }}">
                                Siswa Aktif
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="2">{{ $student->address }}</textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>