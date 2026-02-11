@extends('layouts.admin')

@section('title', 'Detail Invoice #' . $invoice->invoice_number)
@section('header', 'Detail Tagihan')

@section('content')
<div style="margin-bottom: 32px;">
    <a href="{{ route('admin.invoices.index') }}" style="text-decoration: none; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 8px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Tagihan
    </a>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <!-- LEFT SIDE: Invoice Info -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <div class="stat-card">
            <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border); padding-bottom: 20px;">
                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">#{{ $invoice->invoice_number }}</h3>
                <div style="margin-top: 8px;">
                    <span class="badge badge-{{ $invoice->status }}" style="font-size: 0.875rem;">
                        {{ $invoice->status == 'paid' ? 'Sudah Lunas' : ($invoice->status == 'pending' ? 'Menunggu Konfirmasi' : 'Belum Lunas') }}
                    </span>
                </div>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Informasi Siswa</div>
                    <div style="font-weight: 600; margin-top: 4px; font-size: 1.125rem;">{{ $invoice->student->name }}</div>
                    <div style="font-size: 0.875rem; color: var(--text-muted);">{{ $invoice->student->grade }} - {{ $invoice->student->school }}</div>
                </div>
                
                <div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Periode Tagihan</div>
                    <div style="font-weight: 600; margin-top: 4px;">{{ $invoice->month_name }} {{ $invoice->year }}</div>
                </div>
                
                <div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Batas Waktu Bayar</div>
                    <div style="font-weight: 600; margin-top: 4px; color: var(--danger);">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d F Y') }}</div>
                </div>
                
                <div style="background: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid var(--border);">
                    <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Total Tagihan</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-top: 4px;">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        @if($invoice->status != 'paid')
        <div class="stat-card" style="background: #fff7ed; border-color: #fdba74;">
             <h4 style="font-size: 1rem; font-weight: 700; margin-bottom: 16px; color: #9a3412;">
                <i class="bi bi-lightning-charge-fill"></i> Tindakan Cepat
             </h4>
             <div style="display: flex; flex-direction: column; gap: 12px;">
                 <button class="btn-premium" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="bi bi-printer"></i> Cetak Kwitansi Tagihan
                 </button>
                 <a href="https://wa.me/{{ $invoice->student->parent_phone }}" target="_blank" style="width: 100%; padding: 12px; background: #22c55e; color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="bi bi-whatsapp"></i> Chat WA Orang Tua
                 </a>
             </div>
        </div>
        @endif
    </div>

    <!-- RIGHT SIDE: History -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <div class="data-table-container">
            <div style="padding: 24px; border-bottom: 1px solid var(--border); background: #f8fafc;">
                <h3 style="font-size: 1.125rem; font-weight: 700;">Riwayat Pembayaran</h3>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th style="padding-left: 24px;">Tanggal & Waktu</th>
                        <th>Nominal</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoice->payments as $payment)
                    <tr>
                        <td style="padding-left: 24px;">
                            <div style="font-weight: 600;">{{ $payment->paid_at->format('d M Y') }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">Pukul {{ $payment->paid_at->format('H:i') }}</div>
                        </td>
                        <td style="font-weight: 800; color: var(--text-main);">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            @if($payment->proof_path)
                                <a href="{{ asset('storage/' . $payment->proof_path) }}" target="_blank" style="margin-left: 8px; color: var(--primary);"><i class="bi bi-image"></i></a>
                            @endif
                        </td>
                        <td>
                            <span style="background: #eff6ff; color: #1d4ed8; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">
                                {{ $payment->method }}
                            </span>
                        </td>
                        <td>
                            @if($payment->status == 'confirmed')
                                <span class="badge" style="background: #dcfce7; color: #166534;"><i class="bi bi-check-circle-fill" style="margin-right: 4px;"></i> Terverifikasi</span>
                            @else
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span class="badge" style="background: #fef9c3; color: #854d0e;"><i class="bi bi-clock-history" style="margin-right: 4px;"></i> Pending</span>
                                    <form action="{{ route('admin.payments.confirm', $payment->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" style="background: var(--success); color: white; border: none; padding: 4px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 600; cursor: pointer;">
                                            Verifikasi
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 24px; height: 24px; border-radius: 50%; background: #e2e8f0; font-size: 0.625rem; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                    {{ substr($payment->recordedBy->name ?? 'S', 0, 1) }}
                                </div>
                                <span style="font-size: 0.875rem;">{{ $payment->recordedBy->name ?? 'System' }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 80px; color: var(--text-muted);">
                            <i class="bi bi-shield-slash" style="font-size: 3rem; opacity: 0.2; display: block; margin-bottom: 16px;"></i>
                            Belum ada catatan pembayaran untuk invoice ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoice->payments->count() > 0)
        <div style="background: white; padding: 24px; border-radius: 16px; border: 1px solid var(--border);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--text-muted);">Total Terbayar</div>
                    <div style="font-size: 1.25rem; font-weight: 700; color: var(--success);">Rp {{ number_format($invoice->payments->where('status', 'confirmed')->sum('amount'), 0, ',', '.') }}</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 0.875rem; color: var(--text-muted);">Sisa Tagihan</div>
                    <div style="font-size: 1.25rem; font-weight: 700; color: {{ $invoice->status == 'paid' ? 'var(--success)' : 'var(--danger)' }};">
                        Rp {{ number_format(max(0, $invoice->amount - $invoice->payments->where('status', 'confirmed')->sum('amount')), 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
