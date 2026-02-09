@props(['dismissible' => true])

<div class="alert alert-info alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center">
        <i class="bi bi-info-circle-fill me-2 fs-4"></i>
        <div>
            {{ $slot }}
        </div>
    </div>
    @if($dismissible)
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    @endif
</div>