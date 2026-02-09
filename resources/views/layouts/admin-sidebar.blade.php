<!-- Sidebar admin -->
 <div class="sidebar bg-dark text-white vh-100">
    <div class="p-3">
        <h5 class="mb-0">Admin Menu</h5>
    </div>
    <hr class="bg-light">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" 
               href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/students*') ? 'active' : '' }}" 
               href="{{ route('admin.students.index') }}">
                <i class="bi bi-people me-2"></i> Siswa
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/invoices*') ? 'active' : '' }}" 
               href="{{ route('admin.invoices.index') }}">
                <i class="bi bi-receipt me-2"></i> Tagihan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/payments*') ? 'active' : '' }}" 
               href="{{ route('admin.payments.index') }}">
                <i class="bi bi-credit-card me-2"></i> Pembayaran
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/reports*') ? 'active' : '' }}" 
               href="{{ route('admin.reports.monthly') }}">
                <i class="bi bi-graph-up me-2"></i> Laporan
            </a>
        </li>
    </ul>
</div>