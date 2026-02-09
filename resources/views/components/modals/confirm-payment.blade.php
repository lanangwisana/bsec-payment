@props(['invoice'])

<div class="modal fade" id="payInvoiceModal{{ $invoice->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-credit-card me-2"></i>Konfirmasi Pembayaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('student.payments.store', $invoice) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  id="paymentForm{{ $invoice->id }}">
                @csrf
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 class="card-title">Detail Tagihan</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Bulan</td>
                                            <td><strong>{{ $invoice->month_name }} {{ $invoice->year }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Jatuh Tempo</td>
                                            <td><strong>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Program</td>
                                            <td>{{ $invoice->student->program }}</td>
                                        </tr>
                                        <tr>
                                            <td>No. Invoice</td>
                                            <td>{{ $invoice->invoice_number }}</td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td><strong>Total</strong></td>
                                            <td><strong class="fs-5">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select name="method" class="form-select" required>
                                    <option value="">Pilih metode...</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="qris">QRIS</option>
                                    <option value="gopay">GoPay</option>
                                    <option value="dana">DANA</option>
                                    <option value="ovo">OVO</option>
                                    <option value="cash">Tunai</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tanggal Pembayaran <span class="text-danger">*</span></label>
                                <input type="date" name="paid_at" class="form-control" 
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Bukti Pembayaran <span class="text-danger">*</span></label>
                                <input type="file" name="proof" class="form-control" 
                                       accept="image/*,.pdf" required>
                                <div class="form-text">
                                    Upload screenshot/scan bukti transfer (max 2MB)
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Catatan (Opsional)</label>
                                <textarea name="notes" class="form-control" rows="2" 
                                          placeholder="Contoh: Transfer dari BCA, nama pengirim, dll."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Pembayaran akan diverifikasi oleh admin dalam 1x24 jam.
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Konfirmasi Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('paymentForm{{ $invoice->id }}').addEventListener('submit', function(e) {
        const fileInput = this.querySelector('input[name="proof"]');
        const maxSize = 2 * 1024 * 1024; // 2MB
        
        if (fileInput.files[0] && fileInput.files[0].size > maxSize) {
            e.preventDefault();
            alert('Ukuran file maksimal 2MB');
            return false;
        }
    });
</script>
@endpush