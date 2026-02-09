@props(['invoice'])

<div class="modal fade" id="invoiceDetailModal{{ $invoice->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Invoice #{{ $invoice->invoice_number }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Tagihan</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="40%">Bulan/Tahun</td>
                                <td><strong>{{ $invoice->month_name }} {{ $invoice->year }}</strong></td>
                            </tr>
                            <tr>
                                <td>Tanggal Dibuat</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td>Jatuh Tempo</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>
                                    <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : 'warning' }}">
                                        {{ $invoice->status == 'paid' ? 'LUNAS' : 'BELUM LUNAS' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h6>Informasi Siswa</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="40%">Nama Siswa</td>
                                <td><strong>{{ $invoice->student->name }}</strong></td>
                            </tr>
                            <tr>
                                <td>Kelas/Sekolah</td>
                                <td>{{ $invoice->student->grade }} - {{ $invoice->student->school }}</td>
                            </tr>
                            <tr>
                                <td>Program</td>
                                <td>{{ $invoice->student->program }}</td>
                            </tr>
                            <tr>
                                <td>No. Telepon</td>
                                <td>{{ $invoice->student->parent_phone }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <h6>Rincian Biaya</h6>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Deskripsi</th>
                            <th width="30%">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Biaya Les Bulan {{ $invoice->month_name }} {{ $invoice->year }}</td>
                            <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                        </tr>
                        @if($invoice->late_fee > 0)
                        <tr class="table-danger">
                            <td>Denda Keterlambatan</td>
                            <td>Rp {{ number_format($invoice->late_fee, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="table-primary">
                            <td><strong>TOTAL</strong></td>
                            <td><strong>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                
                @if($invoice->notes)
                <div class="alert alert-secondary">
                    <h6><i class="bi bi-chat-left-text me-2"></i>Catatan</h6>
                    <p class="mb-0">{{ $invoice->notes }}</p>
                </div>
                @endif
                
                @if($invoice->payment)
                <hr>
                <h6>Informasi Pembayaran</h6>
                <table class="table table-sm">
                    <tr>
                        <td width="30%">Tanggal Bayar</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->payment->paid_at)->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Metode</td>
                        <td>{{ ucfirst($invoice->payment->method) }}</td>
                    </tr>
                    <tr>
                        <td>Dikonfirmasi Oleh</td>
                        <td>{{ $invoice->payment->confirmed_by ?? 'Sistem' }}</td>
                    </tr>
                    <tr>
                        <td>Waktu Konfirmasi</td>
                        <td>{{ $invoice->payment->confirmed_at ? \Carbon\Carbon::parse($invoice->payment->confirmed_at)->format('d M Y H:i') : 'Menunggu' }}</td>
                    </tr>
                </table>
                @endif
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                @if($invoice->status == 'pending')
                <a href="{{ route('student.payments.create', $invoice) }}" class="btn btn-primary">
                    <i class="bi bi-credit-card me-1"></i> Bayar Sekarang
                </a>
                @endif
                <a href="{{ route('student.invoices.download', $invoice) }}" class="btn btn-outline-primary" target="_blank">
                    <i class="bi bi-download me-1"></i> Unduh PDF
                </a>
            </div>
        </div>
    </div>
</div>