@extends('layouts.admin')

@section('title', 'Buat Tagihan Baru')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-plus-circle me-2"></i>Buat Tagihan Baru
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.invoices.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Pilih Siswa <span class="text-danger">*</span></label>
                        <select name="student_id" class="form-select select2" required id="studentSelect">
                            <option value="">Pilih siswa...</option>
                            @foreach($students as $student)
                            <option value="{{ $student->id }}" 
                                    data-fee="{{ $student->monthly_fee }}"
                                    {{ old('student_id', request('student_id')) == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->program }}) - 
                                Rp {{ number_format($student->monthly_fee, 0, ',', '.') }}/bln
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bulan <span class="text-danger">*</span></label>
                            <select name="month" class="form-select" required>
                                <option value="">Pilih bulan...</option>
                                @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('month', date('n')) == $i ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun <span class="text-danger">*</span></label>
                            <input type="number" name="year" class="form-control" 
                                   value="{{ old('year', date('Y')) }}" min="2023" max="2030" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" class="form-control" 
                                   value="{{ old('due_date', date('Y-m-10')) }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Tagihan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="amount" class="form-control" 
                                       id="amount" value="{{ old('amount') }}" required>
                            </div>
                            <div class="form-text">
                                <a href="#" id="useDefaultFee" class="text-decoration-none">
                                    <i class="bi bi-arrow-clockwise"></i> Gunakan biaya bulanan siswa
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="3" 
                                  placeholder="Contoh: Termasuk biaya materi, ujian, dll.">{{ old('notes') }}</textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Informasi:</strong> Tagihan akan langsung dikirim ke siswa/orang tua 
                        via WhatsApp dan email setelah dibuat.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Buat Tagihan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Student Info Card -->
        <div class="card shadow mb-4" id="studentInfoCard" style="display: none;">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 fw-bold">
                    <i class="bi bi-person me-2"></i>Informasi Siswa
                </h6>
            </div>
            <div class="card-body">
                <div id="studentInfo">
                    <!-- Info will be loaded here -->
                </div>
            </div>
        </div>
        
        <!-- Recent Invoices -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-clock-history me-2"></i>Tagihan Terbaru
                </h6>
            </div>
            <div class="card-body">
                @if($recentInvoices->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($recentInvoices as $invoice)
                    <div class="list-group-item px-0">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $invoice->student->name }}</h6>
                            <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : 'warning' }}">
                                {{ $invoice->status == 'paid' ? 'Lunas' : 'Pending' }}
                            </span>
                        </div>
                        <p class="mb-1">
                            {{ $invoice->month_name }} {{ $invoice->year }} | 
                            Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                        </p>
                        <small class="text-muted">
                            {{ $invoice->created_at->diffForHumans() }}
                        </small>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted text-center py-3">Belum ada tagihan</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            placeholder: "Pilih siswa...",
            width: '100%'
        });
        
        // Get student info when selected
        $('#studentSelect').on('change', function() {
            const studentId = $(this).val();
            const selectedOption = $(this).find('option:selected');
            const monthlyFee = selectedOption.data('fee');
            
            if (studentId) {
                // Show student info card
                $('#studentInfoCard').show();
                
                // Load student info via AJAX
                $.ajax({
                    url: '/admin/students/' + studentId + '/info',
                    method: 'GET',
                    success: function(data) {
                        $('#studentInfo').html(data);
                    }
                });
                
                // Set amount to monthly fee
                if (monthlyFee) {
                    $('#amount').val(monthlyFee);
                }
            } else {
                $('#studentInfoCard').hide();
                $('#studentInfo').html('');
            }
        });
        
        // Trigger change on page load if student is pre-selected
        if ($('#studentSelect').val()) {
            $('#studentSelect').trigger('change');
        }
        
        // Use default fee button
        $('#useDefaultFee').on('click', function(e) {
            e.preventDefault();
            const selectedOption = $('#studentSelect').find('option:selected');
            const monthlyFee = selectedOption.data('fee');
            
            if (monthlyFee) {
                $('#amount').val(monthlyFee);
            } else {
                alert('Pilih siswa terlebih dahulu');
            }
        });
    });
</script>
@endpush
@endsection