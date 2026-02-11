<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - BSEC Payments</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin-premium.css') }}">
    
    @stack('styles')
</head>
<body>
    <div style="display: flex;">
        <!-- Sidebar -->
        <aside class="sidebar" style="width: 260px; position: sticky; top: 0; height: 100vh;">
            <div style="padding: 24px;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: white;">BSEC <span style="color: var(--primary);">PAY</span></h2>
            </div>
            
            <nav style="margin-top: 24px;">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.students.index') }}" class="sidebar-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Data Siswa</span>
                </a>
                <a href="{{ route('admin.invoices.index') }}" class="sidebar-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt-cutoff"></i>
                    <span>Kelola Tagihan</span>
                </a>
                <!-- Future features -->
                <a href="#" class="sidebar-link">
                    <i class="bi bi-gear-fill"></i>
                    <span>Settings</span>
                </a>
            </nav>
            
            <div style="position: absolute; bottom: 0; width: 100%; padding: 20px; border-top: 1px solid rgba(255,255,255,0.05);">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: #94a3b8; display: flex; align-items: center; cursor: pointer; width: 100%; padding: 10px; border-radius: 8px;">
                        <i class="bi bi-box-arrow-left" style="margin-right: 12px; font-size: 1.25rem;"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main style="flex: 1; padding: 40px; min-height: 100vh;">
            <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
                <div>
                    <h1 style="font-size: 1.5rem; font-weight: 700;">@yield('header')</h1>
                    <p style="color: var(--text-muted);">Selamat datang kembali, {{ Auth::user()->name ?? 'Admin' }}</p>
                </div>
                
                <div style="display: flex; align-items: center; gap: 20px;">
                    <button style="background: white; border: 1px solid var(--border); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-bell"></i>
                    </button>
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                </div>
            </header>

            @if(session('success'))
                <div style="background: #dcfce7; color: #166534; padding: 16px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center;">
                    <i class="bi bi-check-circle-fill" style="margin-right: 12px;"></i>
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('uppercase-input')) {
                e.target.value = e.target.value.toUpperCase();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>