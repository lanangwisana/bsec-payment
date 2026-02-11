@extends('layouts.admin')

@section('title', 'Pendaftaran Siswa Baru')
@section('header', 'Formulir Pendaftaran')

@section('content')
<div style="margin-bottom: 32px;">
    <a href="{{ route('admin.students.index') }}" style="text-decoration: none; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 8px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Siswa
    </a>
</div>

<div class="stat-card" style="max-width: 800px; margin: 0 auto; padding: 40px;">
    <form action="{{ route('admin.students.store') }}" method="POST">
        @csrf
        
        <h3 style="font-size: 1.125rem; font-weight: 700; border-bottom: 2px solid var(--primary); display: inline-block; padding-bottom: 8px; margin-bottom: 32px; color: var(--primary);">
            <i class="bi bi-person-badge-fill" style="margin-right: 8px;"></i> Data Pribadi Siswa
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
            <div style="grid-column: span 2;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Nama Lengkap Siswa</label>
                <input type="text" name="name" class="search-input uppercase-input" placeholder="Masukkan nama lengkap siswa" required>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Tempat, Tanggal Lahir</label>
                <input type="text" name="birth_place_date" class="search-input uppercase-input" placeholder="Contoh: Bandung, 12 Mei 2010">
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Jenjang Kelas</label>
                    <select name="grade" id="grade_select" class="search-input" style="padding-left: 16px;" onchange="updateProgramOptions()" required>
                        <option value="">Pilih Jenjang</option>
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA">SMA</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Kelas</label>
                    <input type="text" name="classroom" class="search-input uppercase-input" placeholder="Misal: 10 IPA 1">
                </div>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Sekolah/Asal Instansi</label>
                <input type="text" name="school" class="search-input uppercase-input" placeholder="Contoh: SMAN 1 Bandung" required>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Program yang Diambil</label>
                <select name="program" id="program_select" class="search-input" style="padding-left: 16px;" required>
                    <option value="Reguler">Reguler</option>
                </select>
            </div>
        </div>

        <script>
            function updateProgramOptions() {
                const grade = document.getElementById('grade_select').value;
                const programSelect = document.getElementById('program_select');
                
                // Clear existing options
                programSelect.innerHTML = '';
                
                if (grade === 'SMA') {
                    const options = ['Reguler', 'SNBT'];
                    options.forEach(opt => {
                        const el = document.createElement('option');
                        el.value = opt;
                        el.textContent = opt;
                        programSelect.appendChild(el);
                    });
                } else {
                    const el = document.createElement('option');
                    el.value = 'Reguler';
                    el.textContent = 'Reguler';
                    programSelect.appendChild(el);
                }
            }
        </script>

        <h3 style="font-size: 1.125rem; font-weight: 700; border-bottom: 2px solid var(--primary); display: inline-block; padding-bottom: 8px; margin-bottom: 32px; color: var(--primary);">
            <i class="bi bi-house-door-fill" style="margin-right: 8px;"></i> Data Orang Tua & Alamat
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Nama Orang Tua / Wali</label>
                <input type="text" name="parent_name" class="search-input uppercase-input" placeholder="Masukkan nama orang tua" required>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">No. WhatsApp Orang Tua</label>
                <input type="text" name="parent_phone" class="search-input" placeholder="Contoh: 081234567890" required>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Email Orang Tua (Opsional)</label>
                <input type="email" name="parent_email" class="search-input" placeholder="orangtua@email.com">
            </div>
            
            <div style="grid-column: span 2;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Alamat Lengkap</label>
                <textarea name="address" class="search-input uppercase-input" style="height: 100px; padding: 12px; resize: none;" placeholder="Masukkan alamat lengkap rumah"></textarea>
            </div>
        </div>

        <h3 style="font-size: 1.125rem; font-weight: 700; border-bottom: 2px solid var(--primary); display: inline-block; padding-bottom: 8px; margin-bottom: 32px; color: var(--primary);">
            <i class="bi bi-calendar-check-fill" style="margin-right: 8px;"></i> Pengaturan Tagihan Otomatis
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 40px; background: #f8fafc; padding: 24px; border-radius: 16px; border: 1px solid var(--border);">
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Tanggal Pendaftaran</label>
                <input type="date" name="registration_date" class="search-input" value="{{ date('Y-m-d') }}" required>
                <small style="color: var(--text-muted); display: block; margin-top: 8px;">Tanggal ini akan menjadi pemicu tagihan bulanan.</small>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Biaya Kursus Per Bulan</label>
                <div class="input-group">
                    <span class="input-prefix">Rp</span>
                    <input type="hidden" name="monthly_fee" id="raw_monthly_fee">
                    <input type="text" id="display_monthly_fee" class="search-input" style="padding-left: 48px; font-weight: 700; color: var(--primary);" placeholder="0" required oninput="formatFee(this)">
                </div>
                <small style="color: var(--text-muted); display: block; margin-top: 8px;">Nominal tagihan yang digenerate otomatis.</small>
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
            <button type="reset" style="padding: 12px 24px; border-radius: 10px; border: 1px solid var(--border); background: white; font-weight: 600; cursor: pointer;">Reset Form</button>
            <button type="submit" class="btn-premium" style="padding: 12px 40px; font-size: 1rem;">
                <i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i> Selesaikan Pendaftaran
            </button>
        </div>
    </form>
</div>
@endsection