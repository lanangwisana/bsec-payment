@extends('layouts.guest')

@section('title', 'Cek Status Pembayaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow">
            <div class="card-header py-3 bg-primary text-white">
                <h5 class="m-0 fw-bold text-center">
                    <i class="bi bi-search me-2"></i>Cek Status Pembayaran
                </h5>
            </div>
            <div class="card-body p-5">
                <form method="POST" action="{{ route('payment.check') }}">
                    @csrf
                    
                    <div class="mb-4 text-center">
                        <i class="bi bi-credit-card text-primary" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">Cek Status Pembayaran Anda</h4>
                        <p class="text-muted">Masukkan nomor invoice atau ID siswa untuk melihat status pembayaran</p>
                    </div>
                    
                    @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                    </div>
                    @endif
                    
                    @if(session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                    @endif
                    
                    <div class="mb-4">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="search_type" 
                                   id="search_invoice" value="invoice" checked>
                            <label class="form-check-label" for="search_invoice">
                                Cari dengan No. Invoice
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="search_type" 
                                   id="search_student" value="student">
                            <label class="form-check-label" for="search_student">
                                Cari dengan ID Siswa
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-4" id="invoice-field">
                        <label for="invoice_number" class="form-label">Nomor Invoice</label>
                        <input type="text" name="invoice_number" id="invoice_number" 
                               class="form-control form-control-lg" 
                               placeholder="Contoh: INV-2023-06-001"
                               value="{{ old('invoice_number') }}">
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            No. invoice dapat dilihat di tagihan yang dikirim via WhatsApp/email
                        </div>
                    </div>
                    
                    <div class="mb-4 d-none" id="student-field">
                        <label for="student_id" class="form-label">ID Siswa</label>
                        <input type="text" name="student_id" id="student_id" 
                               class="form-control form-control-lg" 
                               placeholder="Contoh: SIS23001"
                               value="{{ old('student_id') }}">
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            ID siswa dapat dilihat di kartu siswa atau tanya admin
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="phone" class="form-label">No. Telepon Orang Tua</label>
                        <input type="tel" name="phone" id="phone" 
                               class="form-control form-control-lg" 
                               placeholder="0812-3456-7890"
                               value="{{ old('phone') }}" required>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Masukkan nomor telepon yang terdaftar
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-search me-2"></i>Cek Status Pembayaran
                        </button>
                    </div>
                </form>
                
                <hr class="my-4">
                
                <div class="text-center">
                    <p class="text-muted mb-2">Sudah memiliki akun?</p>
                    <a href="{{ route('student.login') }}" class="btn btn-outline-primary">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login ke Dashboard
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Help Section -->
        <div class="card shadow mt-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-question-circle me-2"></i>Butuh Bantuan?
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Hubungi Admin:</strong></p>
                        <p class="mb-1"><i class="bi bi-whatsapp text-success me-2"></i>0812-3456-7890</p>
                        <p class="mb-0"><i class="bi bi-telephone me-2"></i>(021) 123-4567</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Jam Operasional:</strong></p>
                        <p class="mb-1">Senin - Jumat: 08:00 - 17:00</p>
                        <p class="mb-0">Sabtu: 08:00 - 12:00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle search fields
    document.querySelectorAll('input[name="search_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'invoice') {
                document.getElementById('invoice-field').classList.remove('d-none');
                document.getElementById('student-field').classList.add('d-none');
                document.getElementById('invoice_number').focus();
            } else {
                document.getElementById('invoice-field').classList.add('d-none');
                document.getElementById('student-field').classList.remove('d-none');
                document.getElementById('student_id').focus();
            }
        });
    });
    
    // Auto-format phone number
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 3 && value.length <= 6) {
            value = value.replace(/(\d{3})(\d{1,3})/, '$1-$2');
        } else if (value.length > 6 && value.length <= 9) {
            value = value.replace(/(\d{3})(\d{3})(\d{1,3})/, '$1-$2-$3');
        } else if (value.length > 9) {
            value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
        }
        e.target.value = value;
    });
</script>
@endpush
@endsection