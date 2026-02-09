@extends('layouts.guest')

@section('title', 'Sistem Pembayaran Les Online')

@section('content')
<!-- Hero Section -->
<div class="hero-section bg-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">Sistem Pembayaran Les Online</h1>
                <p class="lead mb-4">Membantu bimbingan belajar mengelola pembayaran dengan mudah, cepat, dan terorganisir.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('student.login') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login Siswa
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-shield-lock me-2"></i>Login Admin
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="bi bi-credit-card-2-front" style="font-size: 10rem; opacity: 0.8;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container py-5">
    <h2 class="text-center mb-5">Fitur Unggulan</h2>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-receipt" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="card-title">Tagihan Otomatis</h4>
                    <p class="card-text">Generate tagihan bulanan otomatis untuk semua siswa dengan sekali klik.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-credit-card" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="card-title">Multi Pembayaran</h4>
                    <p class="card-text">Transfer bank, QRIS, e-wallet, dan tunai. Lengkap dengan bukti pembayaran.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-bell" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="card-title">Notifikasi Real-time</h4>
                    <p class="card-text">Notifikasi WhatsApp & email untuk tagihan baru, pengingat, dan konfirmasi.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- How It Works -->
<div class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">Cara Kerja</h2>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <span class="h4 mb-0">1</span>
                    </div>
                    <h5>Admin Buat Tagihan</h5>
                    <p class="text-muted">Admin membuat tagihan bulanan untuk siswa</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <span class="h4 mb-0">2</span>
                    </div>
                    <h5>Siswa Terima Notifikasi</h5>
                    <p class="text-muted">Siswa/orang tua dapat notifikasi via WhatsApp</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <span class="h4 mb-0">3</span>
                    </div>
                    <h5>Bayar & Upload Bukti</h5>
                    <p class="text-muted">Siswa bayar dan upload bukti di website</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <span class="h4 mb-0">4</span>
                    </div>
                    <h5>Admin Konfirmasi</h5>
                    <p class="text-muted">Admin verifikasi dan konfirmasi pembayaran</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="container py-5 text-center">
    <h2 class="mb-4">Mulai Kelola Pembayaran Les dengan Mudah</h2>
    <p class="lead mb-4">Daftarkan bimbingan belajar Anda sekarang dan rasakan kemudahan mengelola pembayaran.</p>
    <div class="d-flex justify-content-center gap-3">
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-person-plus me-2"></i>Daftar sebagai Admin
        </a>
        <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-lg">
            <i class="bi bi-chat-left-text me-2"></i>Konsultasi Gratis
        </a>
    </div>
</div>
@endsection