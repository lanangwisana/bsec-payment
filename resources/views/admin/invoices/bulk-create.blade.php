@extends('layouts.admin')

@section('title', 'Generate Tagihan Bulanan')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-gear me-2"></i>Generate Tagihan Bulanan
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.invoices.bulk-store') }}" method="POST" id="bulkInvoiceForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bulan <span class="text-danger">*</span></label>
                            <select name="month" class="form-select" required id="monthSelect">
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
                                   value="{{ old('year', date('Y')) }}" min="2023" max="2030" required 
                                   id="yearInput">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                        <input type="date" name="due_date" class="form-control" 
                               value="{{ old('due_date', date('Y-m-10')) }}" required>
                        <div class="form-text">Tanggal jatuh tempo untuk semua tagihan</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label d-flex justify-content-between">
                            <span>Pilih Siswa</span>
                            <span class="badge bg-primary" id="selectedCount">0 siswa dipilih</span>
                        </label>
                        
                        <div class="card">
                            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                <div class="mb-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-2" 
                                            onclick="selectAll()">
                                        <i class="bi bi-check-all"></i> Pilih Semua
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                            onclick="deselectAll()">
                                        <i class="bi bi-x"></i> Hapus Semua
                                    </button>
                                </div>
                                
                                <div id="studentsList">
                                    <!-- Students will be loaded here -->
                                    <div class="text-center py-4">
                                        <i class="bi bi-people text-muted" style="font-size: 2rem;"></i>
                                        <p class="text-muted mt-2">Pilih bulan dan tahun terlebih dahulu</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Perhatian:</strong> Proses ini akan membuat tagihan untuk semua siswa terpilih. 
                        Pastikan bulan dan tahun sudah benar.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary" id="generateBtn" disabled>
                            <i class="bi bi-gear me-1"></i>Generate Tagihan (<span id="generateCount">0</span>)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Statistics -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-bar-chart me-2"></i>Statistik
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Siswa Aktif</span>
                        <span id="activeCount">0</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-primary" id="activeProgress" style="width: 0%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Sudah Ada Tagihan</span>
                        <span id="invoicedCount">0</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" id="invoicedProgress" style="width: 0%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Belum Ada Tagihan</span>
                        <span id="uninvoicedCount">0</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-warning" id="uninvoicedProgress" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Preview -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-eye me-2"></i>Pratinjau
                </h6>
            </div>
            <div class="card-body">
                <div id="previewSection" class="d-none">
                    <h6 id="previewTitle">Tagihan untuk: Juni 2023</h6>
                    <div class="list-group list-group-flush" id="previewList">
                        <!-- Preview items will be loaded here -->
                    </div>
                    
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between">
                            <span>Total Estimasi Pendapatan:</span>
                            <strong class="text-primary" id="totalEstimate">Rp 0</strong>
                        </div>
                    </div>
                </div>
                
                <div id="noPreview" class="text-center py-4">
                    <i class="bi bi-receipt text-muted" style="font-size: 2rem;"></i>
                    <p class="text-muted mt-2">Pilih bulan dan tahun untuk melihat pratinjau</p>
                </div>
            </div>
        </div>
        
        <!-- Recent Generated -->
        <div class="card shadow mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-clock-history me-2"></i>Generate Terakhir
                </h6>
            </div>
            <div class="card-body">
                @if($recentGenerations->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($recentGenerations as $generation)
                    <div class="list-group-item px-0">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $generation->month_name }} {{ $generation->year }}</h6>
                            <small>{{ $generation->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1">{{ $generation->invoice_count }} tagihan dibuat</p>
                        <small class="text-muted">oleh {{ $generation->user->name }}</small>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted text-center py-3">Belum ada generate</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selectedStudents = new Set();
    let allStudents = [];
    
    // Load students when month/year changes
    $('#monthSelect, #yearInput').on('change', function() {
        const month = $('#monthSelect').val();
        const year = $('#yearInput').val();
        
        if (month && year) {
            loadStudents(month, year);
            updatePreview(month, year);
        } else {
            $('#studentsList').html(`
                <div class="text-center py-4">
                    <i class="bi bi-people text-muted" style="font-size: 2rem;"></i>
                    <p class="text-muted mt-2">Pilih bulan dan tahun terlebih dahulu</p>
                </div>
            `);
            $('#noPreview').removeClass('d-none');
            $('#previewSection').addClass('d-none');
            $('#generateBtn').prop('disabled', true);
        }
    });
    
    // Load active students
    function loadStudents(month, year) {
        $.ajax({
            url: '/admin/invoices/active-students',
            method: 'GET',
            data: { month: month, year: year },
            success: function(response) {
                allStudents = response.students;
                updateStudentsList();
                updateStatistics(response.stats);
            }
        });
    }
    
    // Update students list
    function updateStudentsList() {
        if (allStudents.length === 0) {
            $('#studentsList').html(`
                <div class="text-center py-4">
                    <i class="bi bi-people text-muted" style="font-size: 2rem;"></i>
                    <p class="text-muted mt-2">Tidak ada siswa aktif</p>
                </div>
            `);
            return;
        }
        
        let html = '';
        allStudents.forEach(student => {
            const isChecked = selectedStudents.has(student.id);
            const hasInvoice = student.has_invoice ? 'border-success' : 'border-warning';
            
            html += `
                <div class="form-check mb-2">
                    <input class="form-check-input student-checkbox" 
                           type="checkbox" 
                           value="${student.id}"
                           id="student_${student.id}"
                           ${isChecked ? 'checked' : ''}
                           onchange="toggleStudent(${student.id}, this.checked)">
                    <label class="form-check-label" for="student_${student.id}">
                        ${student.name}
                        <span class="badge bg-secondary ms-2">
                            Rp ${formatCurrency(student.monthly_fee)}
                        </span>
                        ${student.has_invoice ? 
                            '<span class="badge bg-success ms-1">Sudah ada</span>' : 
                            '<span class="badge bg-warning ms-1">Belum ada</span>'}
                    </label>
                </div>
            `;
        });
        
        $('#studentsList').html(html);
        updateSelectedCount();
    }
    
    // Update preview
    function updatePreview(month, year) {
        const monthName = $('#monthSelect option:selected').text();
        $('#previewTitle').text(`Tagihan untuk: ${monthName} ${year}`);
        
        if (selectedStudents.size === 0) {
            $('#noPreview').removeClass('d-none');
            $('#previewSection').addClass('d-none');
            return;
        }
        
        let total = 0;
        let html = '';
        
        allStudents.forEach(student => {
            if (selectedStudents.has(student.id)) {
                total += student.monthly_fee;
                html += `
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span>${student.name}</span>
                            <span>Rp ${formatCurrency(student.monthly_fee)}</span>
                        </div>
                    </div>
                `;
            }
        });
        
        $('#previewList').html(html);
        $('#totalEstimate').text(`Rp ${formatCurrency(total)}`);
        $('#noPreview').addClass('d-none');
        $('#previewSection').removeClass('d-none');
    }
    
    // Update statistics
    function updateStatistics(stats) {
        $('#activeCount').text(stats.active_count);
        $('#invoicedCount').text(stats.invoiced_count);
        $('#uninvoicedCount').text(stats.uninvoiced_count);
        
        const total = stats.active_count;
        const invoicedPercent = total > 0 ? (stats.invoiced_count / total * 100) : 0;
        const uninvoicedPercent = total > 0 ? (stats.uninvoiced_count / total * 100) : 0;
        
        $('#activeProgress').css('width', '100%');
        $('#invoicedProgress').css('width', invoicedPercent + '%');
        $('#uninvoicedProgress').css('width', uninvoicedPercent + '%');
    }
    
    // Toggle student selection
    function toggleStudent(studentId, isChecked) {
        if (isChecked) {
            selectedStudents.add(studentId);
        } else {
            selectedStudents.delete(studentId);
        }
        
        updateSelectedCount();
        updateGenerateButton();
        
        const month = $('#monthSelect').val();
        const year = $('#yearInput').val();
        if (month && year) {
            updatePreview(month, year);
        }
    }
    
    // Select all students
    function selectAll() {
        allStudents.forEach(student => {
            selectedStudents.add(student.id);
        });
        updateStudentsList();
        updateSelectedCount();
        updateGenerateButton();
        
        const month = $('#monthSelect').val();
        const year = $('#yearInput').val();
        if (month && year) {
            updatePreview(month, year);
        }
    }
    
    // Deselect all students
    function deselectAll() {
        selectedStudents.clear();
        updateStudentsList();
        updateSelectedCount();
        updateGenerateButton();
        updatePreview();
    }
    
    // Update selected count
    function updateSelectedCount() {
        $('#selectedCount').text(`${selectedStudents.size} siswa dipilih`);
    }
    
    // Update generate button
    function updateGenerateButton() {
        const count = selectedStudents.size;
        $('#generateCount').text(count);
        $('#generateBtn').prop('disabled', count === 0);
    }
    
    // Format currency
    function formatCurrency(amount) {
        return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // Submit form
    $('#bulkInvoiceForm').on('submit', function(e) {
        if (selectedStudents.size === 0) {
            e.preventDefault();
            alert('Pilih minimal satu siswa');
            return false;
        }
        
        // Add selected students to form
        selectedStudents.forEach(studentId => {
            $(this).append(`<input type="hidden" name="students[]" value="${studentId}">`);
        });
        
        return true;
    });
    
    // Initialize if month/year already selected
    $(document).ready(function() {
        const month = $('#monthSelect').val();
        const year = $('#yearInput').val();
        if (month && year) {
            loadStudents(month, year);
            updatePreview(month, year);
        }
    });
</script>
@endpush
@endsection