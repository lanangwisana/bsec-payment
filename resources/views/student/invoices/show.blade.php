@extends('layouts.app')

@section('title', 'Detail Tagihan #' . $invoice->invoice_number)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Invoice Details -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-receipt me-2"></i>Detail Tagihan
                </h6>
                <div class="btn-group">
                    <a href="{{ route('student.invoices.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    <a href="{{ route('student.invoices.download', $invoice) }}" 
                       class="btn btn-sm btn-primary" target="_blank">
                        <i class="bi bi-download me-1"></i>Unduh PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Tagihan #{{ $invoice->invoice_number }}</h5>
                        <p class="text-muted mb-0">Bulan: {{ $invoice->month_name }} {{ $invoice->year }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h4 class="text-primary fw-bold">
                            Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                        </h4>
                        <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : 'warning' }} fs-6">
                            {{ $invoice->status == 'paid' ? 'LUNAS' : 'BELUM LUNAS' }}
                        </span>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Tagihan</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="40%">Tanggal Dibuat</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td>Jatuh Tempo</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td>Terlambat</td>
                                <td>
                                    @if($invoice->is_overdue)
                                    <span class="text-danger">
                                        {{ $invoice->days_overdue }} hari
                                    </span>
                                    @else
                                    <span class="text-success">Tidak</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h6>Informasi Siswa</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="40%">Nama</td>
                                <td>{{ $student->name }}</td>
                            </tr>
                            <tr>
                                <td>Kelas/Sekolah</td>
                                <td>{{ $student->grade }} - {{ $student->school }}</td>
                            </tr>
                            <tr>
                                <td>Program</td>
                                <td>{{ $student->program }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <h6>Rincian Biaya</h6>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th width="70%">Deskripsi</th>
                            <th width="30%">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Biaya Les Bulan {{ $invoice->month_name }} {{ $invoice->year }}</td>
                            <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                        </tr>
                        @if($invoice->late_fee > 0)
                        <tr class="table-danger">
                            <td>
                                Denda Keterlambatan 
                                <small class="text-muted">({{ $invoice->days_overdue }} hari)</small>
                            </td>
                            <td>Rp {{ number_format($invoice->late_fee, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="table-primary">
                            <td><strong>TOTAL YANG HARUS DIBAYAR</strong></td>
                            <td>
                                <strong class="fs-5">
                                    Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                                </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                @if($invoice->notes)
                <div class="alert alert-secondary mt-3">
                    <h6><i class="bi bi-chat-left-text me-2"></i>Catatan</h6>
                    <p class="mb-0">{{ $invoice->notes }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Payment Information (if paid) -->
        @if($invoice->status == 'paid' && $invoice->payment)
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-success">
                    <i class="bi bi-check-circle me-2"></i>Informasi Pembayaran
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td width="40%">Tanggal Bayar</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->payment->paid_at)->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td>Metode</td>
                                <td>{{ ucfirst($invoice->payment->method) }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>
                                    <span class="badge bg-{{ $invoice->payment->status == 'confirmed' ? 'success' : 'warning' }}">
                                        {{ $invoice->payment->status == 'confirmed' ? 'Dikonfirmasi' : 'Menunggu Konfirmasi' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        @if($invoice->payment->notes)
                        <div class="mb-3">
                            <label class="form-label">Catatan Pembayaran</label>
                            <p class="mb-0">{{ $invoice->payment->notes }}</p>
                        </div>
                        @endif
                        
                        @if($invoice->payment->proof_path)
                        <div>
                            <label class="form-label">Bukti Pembayaran</label><br>
                            <a href="{{ asset('storage/' . $invoice->payment->proof_path) }}" 
                               target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-image me-1"></i>Lihat Bukti
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-lg-4">
        <!-- Payment Action Card -->
        @if(in_array($invoice->status, ['pending', 'overdue']))
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 fw-bold">
                    <i class="bi bi-credit-card me-2"></i>Bayar Tagihan Ini
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <i class="bi bi-wallet2 text-primary" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</h4>
                    <p class="text-muted">Total yang harus dibayar</p>
                </div>
                
                @if($invoice->is_overdue)
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Tagihan ini terlambat {{ $invoice->days_overdue }} hari.
                    @if($invoice->late_fee > 0)
                    <br>Denda: Rp {{ number_format($invoice->late_fee, 0, ',', '.') }}
                    @endif
                </div>
                @endif
                
                <div class="mb-3">
                    <label class="form-label">Jatuh Tempo</label>
                    <div class="alert alert-warning mb-0">
                        {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary btn-lg" 
                            data-bs-toggle="modal" 
                            data-bs-target="#payInvoiceModal{{ $invoice->id }}">
                        <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                    </button>
                    
                    <div class="text-center mt-2">
                        <small class="text-muted">
                            Pilih metode pembayaran dan upload bukti transfer
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Payment Instructions -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-info-circle me-2"></i>Petunjuk Pembayaran
                </h6>
            </div>
            <div class="card-body">
                <div class="accordion" id="paymentInstructions">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" 
                                    data-bs-toggle="collapse" data-bs-target="#transfer">
                                Transfer Bank
                            </button>
                        </h2>
                        <div id="transfer" class="accordion-collapse collapse show" 
                             data-bs-parent="#paymentInstructions">
                            <div class="accordion-body">
                                <p><strong>BCA</strong><br>
                                123-456-7890<br>
                                a.n. BimbelKu Education</p>
                                
                                <p><strong>Mandiri</strong><br>
                                098-765-4321<br>
                                a.n. BimbelKu Education</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" 
                                    data-bs-toggle="collapse" data-bs-target="#ewallet">
                                E-Wallet
                            </button>
                        </h2>
                        <div id="ewallet" class="accordion-collapse collapse" 
                             data-bs-parent="#paymentInstructions">
                            <div class="accordion-body">
                                <p><strong>GoPay</strong><br>
                                0812-3456-7890</p>
                                
                                <p><strong>DANA</strong><br>
                                0812-3456-7890</p>
                                
                                <p><strong>OVO</strong><br>
                                0812-3456-7890</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3 mb-0">
                    <i class="bi bi-clock me-2"></i>
                    Pembayaran akan diverifikasi dalam 1x24 jam.
                </div>
            </div>
        </div>
        
        <!-- Contact Support -->
        <div class="card shadow mt-4">
            <div class="card-body text-center">
                <h6>Butuh Bantuan?</h6>
                <p class="text-muted">Hubungi admin untuk pertanyaan tentang tagihan</p>
                <a href="https://wa.me/6281234567890?text=Halo,%20saya%20butuh%20bantuan%20dengan%20tagihan%20{{ $invoice->invoice_number }}" 
                   target="_blank" class="btn btn-success">
                    <i class="bi bi-whatsapp me-2"></i>WhatsApp Admin
                </a>
            </div>
        </div>
    </div>
</div>

@include('components.modals.confirm-payment', ['invoice' => $invoice])
@endsection