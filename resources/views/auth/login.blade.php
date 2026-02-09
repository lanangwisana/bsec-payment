@extends('layouts.guest')

@section('title', 'Login - Sistem Pembayaran Les')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf
    
    <div class="mb-4">
        <h2 class="text-center">Login</h2>
        <p class="text-center text-muted">Masuk ke akun Anda</p>
    </div>
    
    @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif
    
    @error('email')
    <div class="alert alert-danger">
        {{ $message }}
    </div>
    @enderror
    
    <div class="mb-3">
        <label for="email" class="form-label">Email atau Username</label>
        <input type="text" name="email" id="email" 
               class="form-control @error('email') is-invalid @enderror" 
               value="{{ old('email') }}" 
               required autofocus>
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" 
               class="form-control @error('password') is-invalid @enderror" 
               required>
        <div class="form-text">
            <a href="{{ route('password.request') }}" class="text-decoration-none">Lupa password?</a>
        </div>
    </div>
    
    <div class="mb-3 form-check">
        <input type="checkbox" name="remember" id="remember" class="form-check-input">
        <label for="remember" class="form-check-label">Ingat saya</label>
    </div>
    
    <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
        <i class="bi bi-box-arrow-in-right me-2"></i>Login
    </button>
    
    <div class="text-center">
        <p class="mb-0">
            Login sebagai siswa/orang tua?
            <a href="{{ route('student.login') }}" class="text-decoration-none">Klik disini</a>
        </p>
    </div>
</form>

<hr class="my-4">

<div class="text-center">
    <p class="text-muted mb-2">Atau login dengan</p>
    <div class="d-grid gap-2">
        <a href="#" class="btn btn-outline-primary">
            <i class="bi bi-whatsapp me-2"></i>WhatsApp OTP
        </a>
    </div>
</div>
@endsection