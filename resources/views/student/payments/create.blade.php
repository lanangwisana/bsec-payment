@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3 bg-primary text-white">
                <h5 class="m-0 fw-bold">
                    <i class="bi bi-credit-card me-2"></i>Konfirmasi Pembayaran
                </h5>
            </div>
            <div class="card-body">
                <!-- Invoice Summary -->
                <div class="alert alert-primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-1">Tagihan #{{ $invoice->invoice_number }}</h6>
                            <p class="mb-0">{{ $invoice->month_name }} {{ $invoice->year }}</p>
                        </div>
                        <div class="text-end">
                            <h4 class="fw-bold mb-0 text-primary">
                                Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                            </h4>
                            <small class="text-muted">Jatuh tempo: {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</small>
                        </div>
                    </div>
                </div>
                
                @if($invoice->is_overdue)
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Tagihan ini terlambat {{ $invoice->days_overdue }} hari.
                    @if($invoice->late_fee > 0)
                    <br>Denda keterlambatan: <strong>Rp {{ number_format($invoice->late_fee, 0, ',', '.') }}</strong>
                    @endif
                </div>
                @endif
                
                <form action="{{ route('student.payments.store', $invoice) }}" 
                      method="POST" enctype="multipart/form-data" id="paymentForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select name="method" class="form-select" required id="paymentMethod">
                                    <option value="">Pilih metode...</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="qris">QRIS</option>
                                    <option value="gopay">GoPay</option>
                                    <option value="dana">DANA</option>
                                    <option value="ovo">OVO</option>
                                    <option value="cash">Tunai</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Pembayaran <span class="text-danger">*</span></label>
                                <input type="date" name="paid_at" class="form-control" 
                                       value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                                <div class="form-text">Tanggal saat Anda melakukan pembayaran</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bank Transfer Details -->
                    <div id="bankDetails" class="d-none">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Informasi Rekening Tujuan</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Bank BCA</strong></p>
                                        <p class="mb-1">No. Rek: 123-456-7890</p>
                                        <p class="mb-0">a.n. BimbelKu Education</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Bank Mandiri</strong></p>
                                        <p class="mb-1">No. Rek: 098-765-4321</p>
                                        <p class="mb-0">a.n. BimbelKu Education</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- E-Wallet Details -->
                    <div id="ewalletDetails" class="d-none">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Informasi E-Wallet</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>GoPay</strong></p>
                                        <p class="mb-0">0812-3456-7890</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>DANA</strong></p>
                                        <p class="mb-0">0812-3456-7890</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>OVO</strong></p>
                                        <p class="mb-0">0812-3456-7890</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Bukti Pembayaran <span class="text-danger">*</span></label>
                        <input type="file" name="proof" class="form-control" 
                               accept="image/*,.pdf" required id="proofFile">
                        <div class="form-text">
                            Upload screenshot/scan bukti pembayaran (format: JPG, PNG, PDF, max 2MB)
                        </div>
                        <div class="mt-2">
                            <img id="proofPreview" class="img-thumbnail d-none" 
                                 style="max-height: 200px;">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Catatan Pembayaran (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="3" 
                                  placeholder="Contoh: Transfer dari BCA, nama pengirim: Budi Santoso, tanggal transfer: 10 Juni 2023"></textarea>
                        <div class="form-text">Tambahkan informasi penting untuk mempermudah verifikasi</div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Informasi Penting:</strong><br>
                        1. Pembayaran akan diverifikasi oleh admin dalam 1x24 jam<br>
                        2. Pastikan bukti pembayaran jelas terbaca<br>
                        3. Status akan berubah menjadi "Lunas" setelah dikonfirmasi
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('student.invoices.show', $invoice) }}" 
                           class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Konfirmasi Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Tampilkan detail berdasarkan metode pembayaran
    document.getElementById('paymentMethod').addEventListener('change', function() {
        const method = this.value;
        document.getElementById('bankDetails').classList.add('d-none');
        document.getElementById('ewalletDetails').classList.add('d-none');
        
        if (method === 'transfer') {
            document.getElementById('bankDetails').classList.remove('d-none');
        } else if (['gopay', 'dana', 'ovo', 'qris'].includes(method)) {
            document.getElementById('ewalletDetails').classList.remove('d-none');
        }
    });
    
    // Preview gambar bukti pembayaran
    document.getElementById('proofFile').addEventListener('change', function(e) {
        const preview = document.getElementById('proofPreview');
        const file = e.target.files[0];
        
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('d-none');
        }
    });
    
    // Validasi form
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const fileInput = document.getElementById('proofFile');
        const maxSize = 2 * 1024 * 1024; // 2MB
        
        if (fileInput.files[0] && fileInput.files[0].size > maxSize) {
            e.preventDefault();
            alert('Ukuran file maksimal 2MB');
            fileInput.focus();
            return false;
        }
        
        const method = document.getElementById('paymentMethod').value;
        if (!method) {
            e.preventDefault();
            alert('Pilih metode pembayaran terlebih dahulu');
            return false;
        }
    });
</script>
@endpush
@endsection