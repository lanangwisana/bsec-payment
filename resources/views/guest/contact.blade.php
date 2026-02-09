@extends('layouts.guest')

@section('title', 'Hubungi Kami')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 fw-bold text-primary">
                    <i class="bi bi-chat-left-text me-2"></i>Kirim Pesan kepada Kami
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('contact.send') }}">
                    @csrf
                    
                    @if(session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name') }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" 
                                   value="{{ old('email') }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control" 
                                   value="{{ old('phone') }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Subjek <span class="text-danger">*</span></label>
                            <select name="subject" class="form-select" required>
                                <option value="">Pilih subjek...</option>
                                <option value="Pendaftaran Siswa Baru" {{ old('subject') == 'Pendaftaran Siswa Baru' ? 'selected' : '' }}>Pendaftaran Siswa Baru</option>
                                <option value="Informasi Program Les" {{ old('subject') == 'Informasi Program Les' ? 'selected' : '' }}>Informasi Program Les</option>
                                <option value="Masalah Pembayaran" {{ old('subject') == 'Masalah Pembayaran' ? 'selected' : '' }}>Masalah Pembayaran</option>
                                <option value="Keluhan/Saran" {{ old('subject') == 'Keluhan/Saran' ? 'selected' : '' }}>Keluhan/Saran</option>
                                <option value="Lainnya" {{ old('subject') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Pesan <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" rows="5" required 
                                  placeholder="Tulis pesan Anda di sini...">{{ old('message') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="agree" id="agree" 
                                   class="form-check-input" required>
                            <label class="form-check-label" for="agree">
                                Saya setuju dengan <a href="#" class="text-decoration-none">kebijakan privasi</a>
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send me-2"></i>Kirim Pesan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Contact Info -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h5 class="m-0 fw-bold">
                    <i class="bi bi-info-circle me-2"></i>Informasi Kontak
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="fw-bold"><i class="bi bi-geo-alt me-2"></i>Alamat</h6>
                    <p class="mb-0">
                        Jl. Pendidikan No. 123<br>
                        Jakarta Selatan 12540<br>
                        DKI Jakarta, Indonesia
                    </p>
                </div>
                
                <div class="mb-4">
                    <h6 class="fw-bold"><i class="bi bi-telephone me-2"></i>Telepon</h6>
                    <p class="mb-1">(021) 123-4567</p>
                    <p class="mb-0">0812-3456-7890 (WhatsApp)</p>
                </div>
                
                <div class="mb-4">
                    <h6 class="fw-bold"><i class="bi bi-envelope me-2"></i>Email</h6>
                    <p class="mb-1">info@bimbelku.com</p>
                    <p class="mb-0">admin@bimbelku.com</p>
                </div>
                
                <div>
                    <h6 class="fw-bold"><i class="bi bi-clock me-2"></i>Jam Operasional</h6>
                    <p class="mb-1">Senin - Jumat: 08:00 - 17:00</p>
                    <p class="mb-0">Sabtu: 08:00 - 12:00</p>
                </div>
            </div>
        </div>
        
        <!-- Quick Links -->
        <div class="card shadow">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Akses Cepat</h6>
                <div class="list-group list-group-flush">
                    <a href="{{ route('guest.home') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-house me-2"></i>Beranda
                    </a>
                    <a href="{{ route('payment.check') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-search me-2"></i>Cek Pembayaran
                    </a>
                    <a href="{{ route('student.login') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login Siswa
                    </a>
                    <a href="{{ route('login') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-shield-lock me-2"></i>Login Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map Section -->
<div class="card shadow mt-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bi bi-map me-2"></i>Lokasi Kami</h6>
        <div class="ratio ratio-16x9">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.8195613506864!3d-6.194741395493371!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5390917b759%3A0x6b45e67356080477!2sJakarta%2C%20Indonesia!5e0!3m2!1sen!2s!4v1603862297746!5m2!1sen!2s" 
                style="border:0;" 
                allowfullscreen="" 
                aria-hidden="false" 
                tabindex="0">
            </iframe>
        </div>
    </div>
</div>
@endsection