@extends('layouts.admin')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow">
            <div class="card-header py-3 bg-success text-white">
                <h5 class="m-0 fw-bold">
                    <i class="bi bi-check-circle me-2"></i>Konfirmasi Pembayaran
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Payment Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Informasi Pembayaran</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="40%">Tanggal Bayar</td>
                                        <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Metode</td>
                                        <td>{{ ucfirst($payment->method) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah</td>
                                        <td><strong class="text-primary">Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>
                                            <span class="badge bg-warning">Menunggu Konfirmasi</span>
                                        </td>
                                    </tr>
                                    @if($payment->notes)
                                    <tr>
                                        <td>Catatan</td>
                                        <td>{{ $payment->notes }}</td>
                                    </tr>
                                    @endif
                                </table>
                                
                                @if($payment->proof_path)
                                <div class="mt-3">
                                    <h6>Bukti Pembayaran</h6>
                                    <a href="{{ asset('storage/' . $payment->proof_path) }}" 
                                       target="_blank" class="btn btn-outline-primary">
                                        <i class="bi bi-image me-1"></i>Lihat Bukti
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Student Information -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Informasi Siswa</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="40%">Nama</td>
                                        <td>{{ $payment->invoice->student->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kelas/Sekolah</td>
                                        <td>{{ $payment->invoice->student->grade }} - {{ $payment->invoice->student->school }}</td>
                                    </tr>
                                    <tr>
                                        <td>Program</td>
                                        <td>{{ $payment->invoice->student->program }}</td>
                                    </tr>
                                    <tr>
                                        <td>Telepon</td>
                                        <td>{{ $payment->invoice->student->parent_phone }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>{{ $payment->invoice->student->parent_email ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <!-- Invoice Information -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Informasi Tagihan</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="40%">No. Invoice</td>
                                        <td><strong>{{ $payment->invoice->invoice_number }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Bulan/Tahun</td>
                                        <td>{{ $payment->invoice->month_name }} {{ $payment->invoice->year }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jatuh Tempo</td>
                                        <td>{{ \Carbon\Carbon::parse($payment->invoice->due_date)->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Status Tagihan</td>
                                        <td>
                                            @if($payment->invoice->status == 'paid')
                                            <span class="badge bg-success">Lunas</span>
                                            @elseif($payment->invoice->status == 'overdue')
                                            <span class="badge bg-danger">Terlambat</span>
                                            @else
                                            <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah Tagihan</td>
                                        <td><strong>Rp {{ number_format($payment->invoice->amount, 0, ',', '.') }}</strong></td>
                                    </tr>
                                    @if($payment->invoice->late_fee > 0)
                                    <tr class="table-danger">
                                        <td>Denda Keterlambatan</td>
                                        <td>Rp {{ number_format($payment->invoice->late_fee, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    <tr class="table-primary">
                                        <td><strong>Total Harus Bayar</strong></td>
                                        <td><strong>Rp {{ number_format($payment->invoice->total_amount, 0, ',', '.') }}</strong></td>
                                    </tr>
                                </table>
                                
                                <div class="alert alert-info mt-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Verifikasi:</strong> Pastikan jumlah pembayaran sesuai dengan total tagihan.
                                </div>
                            </div>
                        </div>
                        
                        <!-- Confirmation Form -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Konfirmasi Pembayaran</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.payments.process-confirm', $payment) }}" method="POST" id="confirmForm">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Status Pembayaran</label>
                                        <div class="alert alert-success">
                                            <i class="bi bi-check-circle me-2"></i>
                                            Pembayaran valid dan akan dikonfirmasi.
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Catatan Konfirmasi (Opsional)</label>
                                        <textarea name="confirmation_notes" class="form-control" rows="3" 
                                                  placeholder="Contoh: Pembayaran sudah sesuai, terima kasih."></textarea>
                                    </div>
                                    
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <strong>Perhatian:</strong> Setelah dikonfirmasi:
                                        <ul class="mb-0 mt-2">
                                            <li>Status invoice akan berubah menjadi "LUNAS"</li>
                                            <li>Siswa akan menerima notifikasi konfirmasi</li>
                                            <li>Data tidak dapat diubah tanpa pembatalan</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left me-1"></i>Kembali
                                        </a>
                                        
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal">
                                                <i class="bi bi-x me-1"></i>Tolak
                                            </button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="bi bi-check me-1"></i>Konfirmasi Pembayaran
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Verification -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">Verifikasi Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="verifyAmount" checked>
                                    <label class="form-check-label" for="verifyAmount">
                                        Jumlah pembayaran sesuai
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="verifyProof" checked>
                                    <label class="form-check-label" for="verifyProof">
                                        Bukti pembayaran valid
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="verifyDate" checked>
                                    <label class="form-check-label" for="verifyDate">
                                        Tanggal pembayaran sesuai
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.payments.reject', $payment) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Anda akan menolak pembayaran ini:</p>
                    <div class="alert alert-danger">
                        <strong>{{ $payment->invoice->student->name }}</strong><br>
                        Invoice: {{ $payment->invoice->invoice_number }}<br>
                        Jumlah: Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="3" required 
                                  placeholder="Contoh: Bukti transfer tidak jelas, nominal tidak sesuai, dll."></textarea>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        Siswa akan menerima notifikasi penolakan dan harus mengulang pembayaran.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Validate verification before confirming
    document.getElementById('confirmForm').addEventListener('submit', function(e) {
        const verifyAmount = document.getElementById('verifyAmount').checked;
        const verifyProof = document.getElementById('verifyProof').checked;
        const verifyDate = document.getElementById('verifyDate').checked;
        
        if (!verifyAmount || !verifyProof || !verifyDate) {
            e.preventDefault();
            alert('Harap centang semua verifikasi sebelum mengonfirmasi.');
            return false;
        }
        
        return confirm('Konfirmasi pembayaran ini? Setelah dikonfirmasi, status tidak dapat diubah tanpa pembatalan.');
    });
</script>
@endpush
@endsection