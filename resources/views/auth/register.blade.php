@extends('layouts.guest')

@section('title', 'Registrasi - Sistem Pembayaran Les')

@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf
    
    <div class="mb-4">
        <h2 class="text-center">Buat Akun Baru</h2>
        <p class="text-center text-muted">Daftar sebagai admin baru</p>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" 
                   class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name') }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email') }}" required>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="mb-3">
        <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
        <input type="tel" name="phone" id="phone" 
               class="form-control @error('phone') is-invalid @enderror" 
               value="{{ old('phone') }}" required>
        @error('phone')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
            <input type="password" name="password" id="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   required>
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
            <input type="password" name="password_confirmation" id="password_confirmation" 
                   class="form-control" required>
        </div>
    </div>
    
    <div class="mb-4">
        <div class="form-check">
            <input type="checkbox" name="terms" id="terms" 
                   class="form-check-input @error('terms') is-invalid @enderror" 
                   required>
            <label for="terms" class="form-check-label">
                Saya setuju dengan <a href="#" class="text-decoration-none">Syarat & Ketentuan</a>
            </label>
            @error('terms')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
        <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
    </button>
    
    <div class="text-center">
        <p class="mb-0">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-decoration-none">Login disini</a>
        </p>
    </div>
</form>

<hr class="my-4">

<div class="text-center">
    <p class="text-muted mb-2">Registrasi untuk siswa/orang tua?</p>
    <a href="{{ route('student.register') }}" class="btn btn-outline-secondary w-100">
        <i class="bi bi-person me-2"></i>Daftar sebagai Siswa
    </a>
</div>
@endsection