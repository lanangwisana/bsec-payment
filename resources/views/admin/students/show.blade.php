@extends('layouts.admin')

@section('title', 'Profil Siswa - ' . $student->name)
@section('header', 'Detail Siswa')

@section('content')
<div style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: center;">
    <a href="{{ route('admin.students.index') }}" style="text-decoration: none; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 8px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Siswa
    </a>
    <a href="{{ route('admin.students.edit', $student->id) }}" class="btn-premium" style="background: white; border: 1px solid var(--border); color: var(--text-main); text-decoration: none;">
        <i class="bi bi-pencil" style="margin-right: 8px;"></i> Edit Profil
    </a>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 32px;">
    <!-- Profile Card -->
    <div>
        <div class="stat-card" style="padding: 32px; text-align: center;">
            <div style="width: 100px; height: 100px; border-radius: 24px; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: 700; margin: 0 auto 24px;">
                {{ substr($student->name, 0, 1) }}
            </div>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 8px;">{{ $student->name }}</h2>
            <span class="badge {{ $student->is_active ? 'badge-paid' : 'badge-unpaid' }}" style="font-size: 0.875rem;">
                {{ $student->is_active ? 'Siswa Aktif' : 'Non-Aktif' }}
            </span>
            
            <div style="margin-top: 32px; border-top: 1px solid var(--border); padding-top: 32px; text-align: left;">
                <div style="margin-bottom: 20px;">
                    <label style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Orang Tua / Wali</label>
                    <div style="font-weight: 600; font-size: 1rem;">{{ $student->parent_name }}</div>
                    <div style="font-size: 0.875rem; color: var(--primary); font-weight: 500;">{{ $student->parent_phone }}</div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Program & Kelas</label>
                    <div style="font-weight: 600;">{{ $student->program }} {{ $student->classroom ? '('.$student->classroom.')' : '' }}</div>
                    <div style="font-size: 0.875rem;">{{ $student->grade }} - {{ $student->school }}</div>
                </div>
                
                <div style="margin-bottom: 0;">
                    <label style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Alamat</label>
                    <div style="font-size: 0.875rem; line-height: 1.5;">{{ $student->address ?? 'Alamat tidak diisi' }}</div>
                </div>
            </div>
        </div>

        <div class="stat-card" style="margin-top: 24px; background: #f8fafc; border: 1px dashed var(--border);">
            <h4 style="font-size: 0.875rem; font-weight: 700; margin-bottom: 12px; color: var(--text-main);">Informasi Tagihan Otomatis</h4>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.875rem; color: var(--text-muted);">Biaya Bulanan</span>
                <span style="font-weight: 700; color: var(--primary);">Rp {{ number_format($student->monthly_fee, 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                <span style="font-size: 0.875rem; color: var(--text-muted);">Tanggal Tagih</span>
                <span style="font-weight: 700; color: var(--text-main);">Tiap tanggal {{ $student->registration_date ? $student->registration_date->format('d') : '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Invoices List -->
    <div class="data-table-container">
        <div style="padding: 24px; border-bottom: 1px solid var(--border);">
            <h3 style="font-size: 1.125rem; font-weight: 700;">Riwayat Tagihan Bulanan</h3>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Periode</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($student->invoices as $invoice)
                <tr>
                    <td style="font-family: monospace; font-weight: 600; color: var(--primary);">{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->month_name }} {{ $invoice->year }}</td>
                    <td style="font-weight: 700;">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge badge-{{ $invoice->status }}">
                            {{ $invoice->status == 'paid' ? 'Lunas' : ($invoice->status == 'pending' ? 'Menunggu' : 'Belum Lunas') }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.invoices.show', $invoice->id) }}" style="padding: 8px; color: var(--text-muted); text-decoration: none;"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 48px; color: var(--text-muted);">
                        Belum ada riwayat tagihan untuk siswa ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
