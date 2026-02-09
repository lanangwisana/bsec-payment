@props(['student', 'showActions' => true])

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                     style="width: 60px; height: 60px;">
                    <i class="bi bi-person" style="font-size: 1.5rem;"></i>
                </div>
            </div>
            <div class="flex-grow-1 ms-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-1">{{ $student->name }}</h5>
                        <p class="card-text text-muted mb-1">
                            <i class="bi bi-mortarboard me-1"></i>
                            {{ $student->grade }} | {{ $student->school }}
                        </p>
                        <p class="card-text mb-1">
                            <i class="bi bi-book me-1"></i>
                            {{ $student->program }}
                            <span class="badge bg-secondary ms-2">
                                Rp {{ number_format($student->monthly_fee, 0, ',', '.') }}/bln
                            </span>
                        </p>
                        <small class="text-muted">
                            <i class="bi bi-telephone me-1"></i>
                            {{ $student->parent_phone }}
                        </small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-{{ $student->is_active ? 'success' : 'danger' }}">
                            {{ $student->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        <p class="text-muted mt-2 mb-0">
                            Bergabung: {{ \Carbon\Carbon::parse($student->created_at)->format('d M Y') }}
                        </p>
                    </div>
                </div>
                
                @if($showActions)
                <div class="mt-3 pt-3 border-top">
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.students.edit', $student) }}" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                        <a href="{{ route('admin.invoices.create', ['student_id' => $student->id]) }}" 
                           class="btn btn-outline-success btn-sm">
                            <i class="bi bi-receipt me-1"></i> Buat Tagihan
                        </a>
                        <a href="tel:{{ $student->parent_phone }}" 
                           class="btn btn-outline-info btn-sm">
                            <i class="bi bi-telephone me-1"></i> Telepon
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>