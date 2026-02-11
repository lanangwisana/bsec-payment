@extends('layouts.admin')

@section('title', 'Edit Data Siswa - ' . $student->name)
@section('header', 'Perbarui Data Siswa')

@section('content')
<div style="margin-bottom: 32px;">
    <a href="{{ route('admin.students.show', $student->id) }}" style="text-decoration: none; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 8px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Profil
    </a>
</div>

<div class="stat-card" style="max-width: 800px; margin: 0 auto; padding: 40px;">
    <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px;">
            <h3 style="font-size: 1.125rem; font-weight: 700; border-bottom: 2px solid var(--primary); display: inline-block; padding-bottom: 8px; margin-bottom: 0; color: var(--primary);">
                <i class="bi bi-person-badge-fill" style="margin-right: 8px;"></i> Data Pribadi Siswa
            </h3>
            
            <div style="display: flex; align-items: center; gap: 12px; background: #f8fafc; padding: 8px 16px; border-radius: 12px; border: 1px solid var(--border);">
                <label style="font-size: 0.875rem; font-weight: 600;">Status Siswa Aktif?</label>
                <select name="is_active" class="search-input" style="width: 120px; padding: 6px 12px;">
                    <option value="1" {{ $student->is_active ? 'selected' : '' }}>Ya</option>
                    <option value="0" {{ !$student->is_active ? 'selected' : '' }}>Tidak</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
            <div style="grid-column: span 2;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Nama Lengkap Siswa</label>
                <input type="text" name="name" class="search-input uppercase-input" value="{{ $student->name }}" required>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Tempat, Tanggal Lahir</label>
                <input type="text" name="birth_place_date" class="search-input uppercase-input" value="{{ $student->birth_place_date }}">
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Jenjang Kelas</label>
                    <select name="grade" id="grade_edit_select" class="search-input" style="padding-left: 16px;" onchange="updateProgramOptionsEdit()" required>
                        <option value="SD" {{ $student->grade == 'SD' ? 'selected' : '' }}>SD</option>
                        <option value="SMP" {{ $student->grade == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ $student->grade == 'SMA' ? 'selected' : '' }}>SMA</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Kelas</label>
                    <input type="text" name="classroom" class="search-input uppercase-input" value="{{ $student->classroom }}" placeholder="Misal: 10 IPA 1">
                </div>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Sekolah/Asal Instansi</label>
                <input type="text" name="school" class="search-input uppercase-input" value="{{ $student->school }}" required>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Program yang Diambil</label>
                <select name="program" id="program_edit_select" class="search-input" style="padding-left: 16px;" data-selected="{{ $student->program }}" required>
                    <!-- Options will be populated by JS -->
                </select>
            </div>
        </div>

        <script>
            function updateProgramOptionsEdit() {
                const grade = document.getElementById('grade_edit_select').value;
                const programSelect = document.getElementById('program_edit_select');
                const selectedProgram = programSelect.getAttribute('data-selected');
                
                // Clear existing options
                programSelect.innerHTML = '';
                
                if (grade === 'SMA') {
                    const options = ['Reguler', 'SNBT'];
                    options.forEach(opt => {
                        const el = document.createElement('option');
                        el.value = opt;
                        el.textContent = opt;
                        if (opt === selectedProgram) el.selected = true;
                        programSelect.appendChild(el);
                    });
                } else {
                    const el = document.createElement('option');
                    el.value = 'Reguler';
                    el.textContent = 'Reguler';
                    programSelect.appendChild(el);
                }
            }
            
            // Initialize on load
            document.addEventListener('DOMContentLoaded', function() {
                updateProgramOptionsEdit();
            });
        </script>

        <h3 style="font-size: 1.125rem; font-weight: 700; border-bottom: 2px solid var(--primary); display: inline-block; padding-bottom: 8px; margin-bottom: 32px; color: var(--primary);">
            <i class="bi bi-house-door-fill" style="margin-right: 8px;"></i> Data Orang Tua & Alamat
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Nama Orang Tua / Wali</label>
                <input type="text" name="parent_name" class="search-input uppercase-input" value="{{ $student->parent_name }}" required>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">No. WhatsApp Orang Tua</label>
                <input type="text" name="parent_phone" class="search-input" value="{{ $student->parent_phone }}" required>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Email Orang Tua (Opsional)</label>
                <input type="email" name="parent_email" class="search-input" value="{{ $student->parent_email }}">
            </div>
            
            <div style="grid-column: span 2;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Alamat Lengkap</label>
                <textarea name="address" class="search-input uppercase-input" style="height: 100px; padding: 12px; resize: none;">{{ $student->address }}</textarea>
            </div>
        </div>

        <h3 style="font-size: 1.125rem; font-weight: 700; border-bottom: 2px solid var(--primary); display: inline-block; padding-bottom: 8px; margin-bottom: 32px; color: var(--primary);">
            <i class="bi bi-calendar-check-fill" style="margin-right: 8px;"></i> Pengaturan Tagihan Otomatis
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 40px; background: #f8fafc; padding: 24px; border-radius: 16px; border: 1px solid var(--border);">
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Tanggal Pendaftaran</label>
                <input type="date" name="registration_date" class="search-input" value="{{ $student->registration_date ? $student->registration_date->format('Y-m-d') : '' }}" required>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Biaya Kursus Per Bulan</label>
                <div class="input-group">
                    <span class="input-prefix">Rp</span>
                    <input type="hidden" name="monthly_fee" id="raw_monthly_fee" value="{{ (int)$student->monthly_fee }}">
                    <input type="text" id="display_monthly_fee" class="search-input" style="padding-left: 48px; font-weight: 700; color: var(--primary);" value="{{ number_format($student->monthly_fee, 0, ',', '.') }}" required oninput="formatFee(this)">
                </div>
            </div>
        </div>

        <script>
            function formatFee(input) {
                let value = input.value.replace(/[^0-9]/g, '');
                document.getElementById('raw_monthly_fee').value = value;
                if (value) {
                    input.value = new Intl.NumberFormat('id-ID').format(value);
                } else {
                    input.value = '';
                }
            }
        </script>

        <div style="display: flex; gap: 16px; justify-content: flex-end;">
            <button type="submit" class="btn-premium" style="padding: 12px 40px; font-size: 1rem;">
                <i class="bi bi-save-fill" style="margin-right: 8px;"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection