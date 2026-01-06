@if($histori->isEmpty())
<!-- Empty State -->
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <div class="empty-icon mb-3">
            <ion-icon name="calendar-outline" style="font-size: 80px; color: #cbd5e1;"></ion-icon>
        </div>
        <h5 class="fw-bold text-dark mb-2">Tidak Ada Data</h5>
        <p class="text-muted mb-0">Tidak ada histori presensi untuk periode yang dipilih.</p>
    </div>
</div>
@else
<!-- Histori Card -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-gradient text-white py-3">
        <h6 class="fw-bold mb-0 d-flex align-items-center justify-content-between">
            <span>
                <ion-icon name="time-outline" class="me-2" style="font-size: 20px;"></ion-icon>
                Histori Presensi
            </span>
            <span class="badge bg-white text-primary">{{ $histori->count() }} Data</span>
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 histori-table">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Tanggal</th>
                        <th class="text-center">Foto</th>
                        <th class="text-center">Jam Masuk</th>
                        <th class="text-center">Jam Pulang</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($histori as $index => $d)
                    @php
                        $path_in = Storage::url('uploads/absensi/'.$d->foto_in);
                        $path_out = $d->foto_out ? Storage::url('uploads/absensi/'.$d->foto_out) : null;
                        $tanggal = date('d-m-Y', strtotime($d->tgl_presensi));
                        $jamOut = $d->jam_out ?? 'Belum Absen';
                    @endphp
                    <tr class="histori-row">
                        <td class="text-center fw-bold">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="date-icon me-2">
                                    <ion-icon name="calendar" style="font-size: 20px; color: #667eea;"></ion-icon>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">{{ $tanggal }}</div>
                                    <small class="text-muted">{{ date('l', strtotime($d->tgl_presensi)) }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <!-- Foto Masuk -->
                                <div class="foto-thumbnail foto-preview" 
                                     onclick="openLightbox('{{ $path_in }}', '{{ $tanggal }}', '{{ $d->jam_in }}', '{{ $jamOut }}', 'in')"
                                     title="Klik untuk melihat foto masuk">
                                    <img src="{{ $path_in }}" alt="Foto Masuk">
                                    <div class="foto-overlay">
                                        <ion-icon name="eye-outline"></ion-icon>
                                        <span>Masuk</span>
                                    </div>
                                </div>

                                <!-- Foto Pulang -->
                                @if($path_out)
                                <div class="foto-thumbnail foto-preview" 
                                     onclick="openLightbox('{{ $path_out }}', '{{ $tanggal }}', '{{ $d->jam_in }}', '{{ $d->jam_out }}', 'out')"
                                     title="Klik untuk melihat foto pulang">
                                    <img src="{{ $path_out }}" alt="Foto Pulang">
                                    <div class="foto-overlay">
                                        <ion-icon name="eye-outline"></ion-icon>
                                        <span>Pulang</span>
                                    </div>
                                </div>
                                @else
                                <div class="foto-thumbnail foto-empty">
                                    <ion-icon name="camera-outline"></ion-icon>
                                    <span>Belum</span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge-time badge-in">
                                <ion-icon name="log-in-outline" class="me-1"></ion-icon>
                                {{ $d->jam_in }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($d->jam_out)
                            <span class="badge-time badge-out">
                                <ion-icon name="log-out-outline" class="me-1"></ion-icon>
                                {{ $d->jam_out }}
                            </span>
                            @else
                            <span class="badge-time badge-pending">
                                <ion-icon name="time-outline" class="me-1"></ion-icon>
                                Belum Absen
                            </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($d->jam_in > '07:30')
                            <span class="badge-status badge-late">
                                <ion-icon name="warning-outline" class="me-1"></ion-icon>
                                Terlambat
                            </span>
                            @else
                            <span class="badge-status badge-ontime">
                                <ion-icon name="checkmark-circle-outline" class="me-1"></ion-icon>
                                Tepat Waktu
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<style>
/* Background gradient untuk header */
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Table Styling */
.histori-table {
    font-size: 14px;
}

.histori-table thead th {
    background-color: #f8f9fa;
    color: #495057;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 10px;
    border-bottom: 2px solid #e9ecef;
}

.histori-table tbody td {
    padding: 12px 10px;
    vertical-align: middle;
}

.histori-row {
    transition: all 0.3s ease;
}

.histori-row:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
}

/* Foto Thumbnail Styling */
.foto-thumbnail {
    position: relative;
    width: 60px;
    height: 60px;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
    display: inline-block;
}

.foto-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.foto-thumbnail.foto-preview {
    cursor: pointer;
}

.foto-thumbnail.foto-preview:hover {
    transform: scale(1.1);
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.foto-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    color: white;
    font-size: 10px;
    font-weight: 600;
}

.foto-thumbnail:hover .foto-overlay {
    opacity: 1;
}

.foto-overlay ion-icon {
    font-size: 24px;
    margin-bottom: 2px;
}

.foto-empty {
    background: #f3f4f6;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    cursor: default;
}

.foto-empty ion-icon {
    font-size: 28px;
    margin-bottom: 2px;
}

.foto-empty span {
    font-size: 10px;
    font-weight: 600;
}

/* Badge Time Styling */
.badge-time {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
}

.badge-in {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border: 1px solid #6ee7b7;
}

.badge-out {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.badge-pending {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    border: 1px solid #fcd34d;
}

/* Badge Status Styling */
.badge-status {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.badge-ontime {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border: 1px solid #6ee7b7;
}

.badge-late {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.badge-status ion-icon {
    font-size: 16px;
}

/* Empty State */
.empty-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 120px;
    height: 120px;
    background: #f1f5f9;
    border-radius: 50%;
    margin: 0 auto;
}

/* Date Icon */
.date-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: #ede9fe;
    border-radius: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .histori-table {
        font-size: 12px;
    }

    .histori-table thead th {
        font-size: 11px;
        padding: 10px 6px;
    }

    .histori-table tbody td {
        padding: 10px 6px;
    }

    .foto-thumbnail {
        width: 50px;
        height: 50px;
    }

    .badge-time, .badge-status {
        font-size: 11px;
        padding: 4px 8px;
    }

    .date-icon {
        width: 30px;
        height: 30px;
    }

    .date-icon ion-icon {
        font-size: 16px !important;
    }
}

@media (max-width: 576px) {
    /* Ubah table menjadi card pada mobile */
    .table-responsive {
        overflow-x: auto;
    }

    .histori-table {
        min-width: 700px;
    }

    .foto-thumbnail {
        width: 45px;
        height: 45px;
    }
}

/* Animation */
.histori-row {
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>