@extends('layouts.admin')

@section('title', 'Master Data Siswa')
@section('header', 'Data Siswa')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <div class="search-container" style="flex: 1; min-width: 300px;">
        <form action="{{ route('admin.students.index') }}" method="GET">
            <i class="bi bi-search search-icon"></i>
            <input type="text" name="search" class="search-input uppercase-input" placeholder="Cari Nama Siswa atau Orang Tua..." value="{{ request('search') }}">
        </form>
    </div>
    <a href="{{ route('admin.students.create') }}" class="btn-premium" style="text-decoration: none; display: flex; align-items: center; gap: 8px;">
        <i class="bi bi-person-plus-fill"></i> Tambah Siswa Baru
    </a>
</div>

<div class="data-table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>Orang Tua</th>
                <th>Tgl. Daftar</th>
                <th>Biaya/Bulan</th>
                <th>Status</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
            <tr>
                <td>
                    <div style="font-weight: 600;">{{ $student->name }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $student->grade }} | {{ $student->program }}</div>
                </td>
                <td>
                    <div style="font-weight: 500;">{{ $student->parent_name }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $student->parent_phone }}</div>
                </td>
                <td>
                    <div style="font-weight: 600;">{{ $student->registration_date ? $student->registration_date->format('d M Y') : '-' }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Tgl Tagih: {{ $student->registration_date ? $student->registration_date->format('d') : '-' }} tiap bulan</div>
                </td>
                <td style="font-weight: 700;">Rp {{ number_format($student->monthly_fee, 0, ',', '.') }}</td>
                <td>
                    @if($student->is_active)
                        <span class="badge badge-paid">Aktif</span>
                    @else
                        <span class="badge badge-unpaid">Non-Aktif</span>
                    @endif
                </td>
                <td style="text-align: right;">
                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                        <a href="{{ route('admin.students.show', $student->id) }}" style="padding: 8px; color: var(--primary); text-decoration: none;"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('admin.students.edit', $student->id) }}" style="padding: 8px; color: var(--secondary); text-decoration: none;"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; padding: 8px; color: var(--danger); cursor: pointer;"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 60px; color: var(--text-muted);">
                    <i class="bi bi-people" style="font-size: 2rem; display: block; margin-bottom: 12px; opacity: 0.3;"></i>
                    Belum ada data siswa.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($students->hasPages())
    <div style="padding: 24px; border-top: 1px solid var(--border); display: flex; justify-content: center;">
        {{ $students->links() }}
    </div>
    @endif
</div>
@endsection