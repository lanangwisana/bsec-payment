@extends('layouts.admin')

@section('title', 'Kelola Tagihan')
@section('header', 'Pengelolaan Tagihan')

@section('content')
<div class="data-table-container" style="margin-bottom: 24px;">
    <div style="padding: 24px; border-bottom: 1px solid var(--border);">
        <form action="{{ route('admin.invoices.index') }}" method="GET" style="display: flex; gap: 16px; flex-wrap: wrap;">
            <div class="search-container" style="flex: 1; min-width: 300px;">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="search" class="search-input" placeholder="Cari Nama Siswa atau No. Invoice..." value="{{ request('search') }}">
            </div>
            
            <select name="status" class="search-input" style="width: 200px; padding-left: 16px;">
                <option value="">Semua Status</option>
                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
            </select>
            
            <button type="submit" class="btn-premium">Filter</button>
            <a href="{{ route('admin.invoices.index') }}" style="padding: 12px 20px; border-radius: 10px; border: 1px solid var(--border); background: white; color: var(--text-main); text-decoration: none; font-weight: 600; display: flex; align-items: center;">Reset</a>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No. Invoice</th>
                <th>Nama Siswa</th>
                <th>Periode</th>
                <th>Total Tagihan</th>
                <th>Status</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
            <tr>
                <td style="font-family: monospace; font-weight: 600; color: var(--primary);">{{ $invoice->invoice_number }}</td>
                <td>
                    <div style="font-weight: 600;">{{ $invoice->student->name }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $invoice->student->grade }} - {{ $invoice->student->school }}</div>
                </td>
                <td>{{ $invoice->month_name }} {{ $invoice->year }}</td>
                <td style="font-weight: 700;">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                <td>
                    <span class="badge badge-{{ $invoice->status }}">
                        {{ $invoice->status == 'paid' ? 'Lunas' : ($invoice->status == 'pending' ? 'Menunggu' : 'Belum Lunas') }}
                    </span>
                </td>
                <td style="text-align: right; display: flex; justify-content: flex-end; gap: 8px; align-items: center;">
                    @if($invoice->status == 'unpaid')
                        <button onclick="openPaymentModal({{ $invoice->id }}, '{{ $invoice->invoice_number }}', {{ $invoice->amount }})" class="btn-premium" style="padding: 8px 16px; font-size: 0.875rem;">
                            <i class="bi bi-cash-stack"></i> Bayar
                        </button>
                    @elseif($invoice->status == 'pending')
                        <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn-premium" style="padding: 8px 16px; font-size: 0.875rem; background: var(--warning); text-decoration: none; color: white;">
                            <i class="bi bi-shield-check"></i> Konfirmasi
                        </a>
                    @endif
                    <a href="{{ route('admin.invoices.show', $invoice->id) }}" style="padding: 8px; color: var(--text-muted); text-decoration: none;"><i class="bi bi-eye"></i></a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 60px; color: var(--text-muted);">
                    <i class="bi bi-search" style="font-size: 2rem; display: block; margin-bottom: 12px; opacity: 0.3;"></i>
                    Data tagihan tidak ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($invoices->hasPages())
    <div style="padding: 24px; border-top: 1px solid var(--border); display: flex; justify-content: center;">
        {{ $invoices->links() }}
    </div>
    @endif
</div>

<!-- Payment Modal Overlay -->
<div id="paymentModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; width: 100%; max-width: 500px; border-radius: 24px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); animation: modalIn 0.3s ease-out;">
        <div style="padding: 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
            <h3 style="font-size: 1.25rem; font-weight: 700;">Input Pembayaran</h3>
            <button onclick="closePaymentModal()" style="background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-muted);">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <form id="paymentForm" method="POST" enctype="multipart/form-data" style="padding: 32px;">
            @csrf
            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px; color: var(--text-muted);">No. Invoice</label>
                <input type="text" id="modal_invoice_number" class="search-input" style="background: #f8fafc; cursor: not-allowed;" readonly>
            </div>
            
            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Nominal Pembayaran</label>
                <div class="input-group">
                    <span class="input-prefix">Rp</span>
                    <input type="hidden" name="amount" id="raw_amount">
                    <input type="text" id="display_amount" class="search-input" style="font-size: 1.25rem; font-weight: 700; color: var(--primary);" placeholder="0" required oninput="formatCurrency(this)">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Metode</label>
                    <select name="method" class="search-input" style="padding-left: 16px;">
                        <option value="Cash">Tunai (Cash)</option>
                        <option value="Transfer">Transfer Bank</option>
                        <option value="QRIS">QRIS</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Tanggal</label>
                    <input type="date" name="paid_at" class="search-input" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Unggah Bukti Bayar (Opsional)</label>
                <input type="file" name="proof" style="display: block; width: 100%; padding: 8px; border: 1px dashed var(--border); border-radius: 12px; background: #f8fafc; font-size: 0.875rem;">
                <small style="color: var(--text-muted); display: block; margin-top: 4px;">Format: JPG, PNG. Maks: 2MB</small>
            </div>
            
            <div style="margin-bottom: 32px;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px;">Catatan (Opsional)</label>
                <textarea name="notes" class="search-input uppercase-input" style="height: 100px; padding: 12px; resize: none;"></textarea>
            </div>
            
            <button type="submit" class="btn-premium" style="width: 100%; padding: 16px; font-size: 1rem; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.4);">
                <i class="bi bi-send-fill" style="margin-right: 8px;"></i> Kirim Untuk Verifikasi
            </button>
        </form>
    </div>
</div>

<style>
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95) translateY(-20px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
</style>

<script>
function formatCurrency(input) {
    // Ambil angka saja
    let value = input.value.replace(/[^0-9]/g, '');
    
    // Simpan nilai asli ke hidden input untuk backend
    document.getElementById('raw_amount').value = value;
    
    // Format tampilan dengan titik
    if (value) {
        input.value = new Intl.NumberFormat('id-ID').format(value);
    } else {
        input.value = '';
    }
}

function openPaymentModal(invoiceId, invoiceNumber, amount) {
    const modal = document.getElementById('paymentModal');
    const form = document.getElementById('paymentForm');
    const displayInput = document.getElementById('display_amount');
    
    document.getElementById('modal_invoice_number').value = invoiceNumber;
    
    // Set nilai awal
    displayInput.value = amount;
    formatCurrency(displayInput); // Trigger format awal
    
    form.action = `/admin/payments/${invoiceId}`;
    modal.style.display = 'flex';
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('paymentModal');
    if (event.target == modal) {
        closePaymentModal();
    }
}
</script>
@endsection