@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Ringkasan Sistem')

@section('content')
@if($stats['pending_payments_count'] > 0)
<div style="background: #fffbeb; border: 1px solid #fcd34d; padding: 16px; border-radius: 12px; margin-bottom: 32px; display: flex; justify-content: space-between; align-items: center;">
    <div style="display: flex; align-items: center; gap: 12px; color: #92400e;">
        <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.25rem;"></i>
        <div>
            <span style="font-weight: 700;">{{ $stats['pending_payments_count'] }} Pembayaran Menunggu Verifikasi</span>
            <p style="margin: 0; font-size: 0.875rem;">Ada pembayaran yang baru dicatat dan memerlukan konfirmasi Anda agar masuk ke dalam laporan pendapatan.</p>
        </div>
    </div>
    <a href="{{ route('admin.invoices.index') }}" class="btn-premium" style="background: #92400e; padding: 8px 16px; font-size: 0.875rem;">Cek Sekarang</a>
</div>
@endif

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 40px;">
    <!-- Stat Cards -->
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(79, 70, 229, 0.1); color: var(--primary);">
            <i class="bi bi-people"></i>
        </div>
        <div style="color: var(--text-muted); font-size: 0.875rem;">Siswa Aktif</div>
        <div style="font-size: 1.75rem; font-weight: 700; margin-top: 8px;">{{ $stats['total_students'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">
            <i class="bi bi-clock-history"></i>
        </div>
        <div style="color: var(--text-muted); font-size: 0.875rem;">Tagihan Menunggu</div>
        <div style="font-size: 1.75rem; font-weight: 700; margin-top: 8px;">{{ $stats['unpaid_invoices'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
            <i class="bi bi-wallet2"></i>
        </div>
        <div style="color: var(--text-muted); font-size: 0.875rem;">Revenue Hari Ini</div>
        <div style="font-size: 1.5rem; font-weight: 700; margin-top: 8px;">Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(6, 182, 212, 0.1); color: var(--info);">
            <i class="bi bi-graph-up"></i>
        </div>
        <div style="color: var(--text-muted); font-size: 0.875rem;">Revenue Bulan Ini</div>
        <div style="font-size: 1.5rem; font-weight: 700; margin-top: 8px;">Rp {{ number_format($stats['month_revenue'], 0, ',', '.') }}</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <!-- Recent Transactions -->
    <div class="data-table-container">
        <div style="padding: 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.125rem; font-weight: 600;">Transaksi Pembayaran Terbaru</h3>
            <a href="{{ route('admin.invoices.index') }}" style="color: var(--primary); text-decoration: none; font-size: 0.875rem; font-weight: 600;">Lihat Semua</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Metode</th>
                    <th>Nominal</th>
                    <th>Waktu</th>
                    <th>Admin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_payments as $payment)
                <tr>
                    <td>
                        <div style="font-weight: 600;">{{ $payment->invoice->student->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">#{{ $payment->invoice->invoice_number }}</div>
                    </td>
                    <td><span class="badge" style="background: #e0f2fe; color: #0369a1;">{{ $payment->method }}</span></td>
                    <td style="font-weight: 700;">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td style="font-size: 0.875rem; color: var(--text-muted);">{{ $payment->paid_at->format('d M, H:i') }}</td>
                    <td><span class="badge" style="background: #f1f5f9; color: #475569;">{{ $payment->recordedBy->name ?? 'System' }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 48px;">
                        <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 12px; opacity: 0.5;"></i>
                        Belum ada transaksi hari ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Quick Payment Entry -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <div class="stat-card" style="background: linear-gradient(135deg, var(--primary) 0%, #4338ca 100%); color: white;">
            <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 12px;">Pencatatan Cepat</h3>
            <p style="color: rgba(255,255,255,0.8); font-size: 0.875rem; margin-bottom: 24px;">Admin dapat langsung mencatatkan pembayaran murid yang datang secara tunai.</p>
            
            <a href="{{ route('admin.invoices.index') }}" class="btn-premium" style="background: white; color: var(--primary); text-align: center; text-decoration: none; display: block;">
                <i class="bi bi-plus-circle-fill" style="margin-right: 8px;"></i> Cari Data Siswa
            </a>
        </div>

        <div class="stat-card">
            <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 16px;">Link Cepat</h3>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <button style="background: #f8fafc; border: 1px solid var(--border); padding: 10px 16px; border-radius: 8px; font-weight: 500; text-align: left; display: flex; align-items: center; gap: 12px; cursor: pointer;">
                    <i class="bi bi-printer text-muted"></i> Laporan Harian
                </button>
                <button style="background: #f8fafc; border: 1px solid var(--border); padding: 10px 16px; border-radius: 8px; font-weight: 500; text-align: left; display: flex; align-items: center; gap: 12px; cursor: pointer;">
                    <i class="bi bi-file-earmark-pdf text-muted"></i> Kebutuhan Audit
                </button>
            </div>
        </div>
    </div>
</div>
@endsection