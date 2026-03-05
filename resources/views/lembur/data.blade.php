@extends('layout.admin.template')
@section('content')

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
    .card-header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 10px 10px 0 0;
    }

    .filter-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .table-custom {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .table thead th {
        background: #2d3748;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        padding: 15px;
        border: none;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.005);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .badge-duration {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
        color: white;
    }

    .btn-photo {
        background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .btn-photo:hover {
        transform: scale(1.15);
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.4);
        color: white;
    }

    .btn-detail {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .btn-detail:hover {
        transform: scale(1.15);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .btn-no-photo {
        color: #cbd5e0;
        font-size: 22px;
    }

    .modal-header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
    }

    .modal-photo-preview {
        max-width: 100%;
        max-height: 70vh;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .modal-detail-item {
        padding: 15px;
        border-bottom: 1px solid #e5e7eb;
        transition: background 0.2s ease;
    }

    .modal-detail-item:hover {
        background: #f9fafb;
    }

    .modal-detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #4b5563;
        font-size: 13px;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-value {
        color: #1f2937;
        font-size: 14px;
    }

    .icon-clock { color: #3b82f6; }
    .icon-calendar { color: #8b5cf6; }
    .icon-person { color: #10b981; }
    .icon-building { color: #f59e0b; }
    .icon-hourglass { color: #ef4444; }
    .icon-text { color: #6366f1; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .empty-state i {
        font-size: 80px;
        margin-bottom: 20px;
        opacity: 0.3;
        display: block;
    }

    .photo-zoom {
        cursor: zoom-in;
        transition: transform 0.3s ease;
    }

    .photo-zoom:hover {
        transform: scale(1.02);
    }

    .badge-total {
        background: white;
        color: #667eea;
        font-weight: 700;
        padding: 8px 16px;
        border-radius: 20px;
    }
</style>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card table-custom">
                <div class="card-header card-header-gradient">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="bi bi-briefcase-fill" style="font-size: 24px; vertical-align: middle;"></i>
                            Data Lembur Karyawan
                        </h4>
                        <span class="badge badge-total">
                            <i class="bi bi-people-fill"></i> Total: {{ $lembur->count() }} data
                        </span>
                    </div>
                </div>
                <div class="card-body">

                    @if (Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill" style="font-size: 20px; vertical-align: middle;"></i>
                            <strong>Berhasil!</strong> {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filter Section -->
                    <div class="filter-section">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-calendar3 icon-calendar"></i> Bulan
                                </label>
                                <select class="form-select" id="filterBulan">
                                    <option value="">Semua Bulan</option>
                                    <option value="01">Januari</option>
                                    <option value="02">Februari</option>
                                    <option value="03">Maret</option>
                                    <option value="04">April</option>
                                    <option value="05">Mei</option>
                                    <option value="06">Juni</option>
                                    <option value="07">Juli</option>
                                    <option value="08">Agustus</option>
                                    <option value="09">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-calendar-range icon-calendar"></i> Tahun
                                </label>
                                <select class="form-select" id="filterTahun">
                                    <option value="">Semua Tahun</option>
                                    @for($i = date('Y'); $i >= date('Y')-2; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-search icon-person"></i> Pencarian
                                </label>
                                <input type="text" class="form-control" id="searchBox" placeholder="Cari NIK / Nama / Departemen...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold opacity-0">Reset</label>
                                <button class="btn btn-secondary w-100" onclick="resetFilter()">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        @if($lembur->count() > 0)
                        <table class="table table-hover table-striped" id="tableLembur">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Departemen</th>
                                    <th width="110">Tanggal</th>
                                    <th>Jam</th>
                                    <th width="110">Durasi</th>
                                    <th>Keterangan</th>
                                    <th width="70" class="text-center">Foto</th>
                                    <th width="70" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lembur as $index => $d)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $d->nik }}</strong></td>
                                    <td>{{ $d->nama_lengkap }}</td>
                                    <td>
                                        <i class="bi bi-building icon-building"></i>
                                        {{ $d->nama_dept }}
                                    </td>
                                    <td>
                                        <i class="bi bi-calendar-event icon-calendar"></i>
                                        {{ date('d/m/Y', strtotime($d->tanggal_lembur)) }}
                                    </td>
                                    <td>
                                        <i class="bi bi-clock icon-clock"></i>
                                        {{ date('H:i', strtotime($d->jam_mulai)) }} - {{ date('H:i', strtotime($d->jam_selesai)) }}
                                    </td>
                                    <td>
                                        @php
                                            $jam = floor($d->durasi_menit / 60);
                                            $menit = $d->durasi_menit % 60;
                                        @endphp
                                        <span class="badge badge-duration">
                                            <i class="bi bi-hourglass-split"></i> {{ $jam }}j {{ $menit }}m
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($d->keterangan, 50) }}</td>
                                    <td class="text-center">
                                        @if($d->bukti_foto)
                                            <button class="btn btn-photo" onclick="showPhoto('{{ asset('storage/uploads/lembur/' . $d->bukti_foto) }}', '{{ $d->nama_lengkap }}', '{{ date('d F Y', strtotime($d->tanggal_lembur)) }}')">
                                                <i class="bi bi-image"></i>
                                            </button>
                                        @else
                                            <i class="bi bi-image btn-no-photo"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-detail" onclick="showDetail('{{ $d->nik }}', '{{ $d->nama_lengkap }}', '{{ $d->nama_dept }}', '{{ date('d F Y', strtotime($d->tanggal_lembur)) }}', '{{ date('l', strtotime($d->tanggal_lembur)) }}', '{{ date('H:i', strtotime($d->jam_mulai)) }}', '{{ date('H:i', strtotime($d->jam_selesai)) }}', {{ $jam }}, {{ $menit }}, '{{ addslashes($d->keterangan) }}', '{{ $d->bukti_foto ? asset('storage/uploads/lembur/' . $d->bukti_foto) : '' }}')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="empty-state">
                            <i class="bi bi-folder2-open"></i>
                            <h4>Belum Ada Data Lembur</h4>
                            <p>Data lembur akan muncul di sini setelah karyawan menginput lembur</p>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Lembur -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-gradient">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle-fill" style="font-size: 20px; vertical-align: middle;"></i>
                    Detail Lembur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="modalDetailContent">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Foto -->
<div class="modal fade" id="modalPhoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-gradient">
                <h5 class="modal-title">
                    <i class="bi bi-image-fill" style="font-size: 20px; vertical-align: middle;"></i>
                    <span id="photoTitle">Bukti Foto Lembur</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img src="" alt="Bukti Foto" class="modal-photo-preview photo-zoom" id="photoPreview">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Tutup
                </button>
                <a href="" target="_blank" id="photoDownload" class="btn btn-primary">
                    <i class="bi bi-download"></i> Download Foto
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('filterBulan').addEventListener('change', filterTable);
    document.getElementById('filterTahun').addEventListener('change', filterTable);
    document.getElementById('searchBox').addEventListener('keyup', filterTable);

    function filterTable() {
        const bulan = document.getElementById('filterBulan').value;
        const tahun = document.getElementById('filterTahun').value;
        const search = document.getElementById('searchBox').value.toLowerCase();

        const table = document.getElementById('tableLembur');
        if (!table) return;

        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');

            const nik = cells[1].textContent.toLowerCase();
            const nama = cells[2].textContent.toLowerCase();
            const dept = cells[3].textContent.toLowerCase();
            const tanggal = cells[4].textContent.trim();

            const tanggalParts = tanggal.split('/');
            const rowBulan = tanggalParts[1]?.trim();
            const rowTahun = tanggalParts[2]?.trim();

            let showRow = true;

            if (bulan && rowBulan !== bulan) showRow = false;
            if (tahun && rowTahun !== tahun) showRow = false;
            if (search && !nik.includes(search) && !nama.includes(search) && !dept.includes(search)) showRow = false;

            row.style.display = showRow ? '' : 'none';
        }
    }

    function resetFilter() {
        document.getElementById('filterBulan').value = '';
        document.getElementById('filterTahun').value = '';
        document.getElementById('searchBox').value = '';
        filterTable();
    }

    function showDetail(nik, nama, dept, tanggal, hari, jamMulai, jamSelesai, jam, menit, keterangan, foto) {
        let fotoSection = '';
        if (foto) {
            fotoSection = `
                <div class="modal-detail-item">
                    <div class="detail-label">
                        <i class="bi bi-image icon-text"></i> Bukti Foto
                    </div>
                    <div class="detail-value mt-2">
                        <img src="${foto}" class="img-thumbnail photo-zoom" style="max-width: 200px; cursor: pointer;" onclick="showPhoto('${foto}', '${nama}', '${tanggal}')">
                        <br>
                        <small class="text-muted">Klik foto untuk memperbesar</small>
                    </div>
                </div>
            `;
        }

        const content = `
            <div class="modal-detail-item">
                <div class="detail-label">
                    <i class="bi bi-person-badge icon-person"></i> NIK
                </div>
                <div class="detail-value">${nik}</div>
            </div>
            <div class="modal-detail-item">
                <div class="detail-label">
                    <i class="bi bi-person icon-person"></i> Nama Karyawan
                </div>
                <div class="detail-value">${nama}</div>
            </div>
            <div class="modal-detail-item">
                <div class="detail-label">
                    <i class="bi bi-building icon-building"></i> Departemen
                </div>
                <div class="detail-value">${dept}</div>
            </div>
            <div class="modal-detail-item">
                <div class="detail-label">
                    <i class="bi bi-calendar-event icon-calendar"></i> Tanggal Lembur
                </div>
                <div class="detail-value">${tanggal} (${hari})</div>
            </div>
            <div class="modal-detail-item">
                <div class="detail-label">
                    <i class="bi bi-clock icon-clock"></i> Jam Lembur
                </div>
                <div class="detail-value">${jamMulai} - ${jamSelesai} WIB</div>
            </div>
            <div class="modal-detail-item">
                <div class="detail-label">
                    <i class="bi bi-hourglass-split icon-hourglass"></i> Durasi
                </div>
                <div class="detail-value">
                    <span class="badge badge-duration">${jam} jam ${menit} menit</span>
                </div>
            </div>
            <div class="modal-detail-item">
                <div class="detail-label">
                    <i class="bi bi-file-text icon-text"></i> Keterangan
                </div>
                <div class="detail-value">${keterangan}</div>
            </div>
            ${fotoSection}
        `;

        document.getElementById('modalDetailContent').innerHTML = content;

        const modal = new bootstrap.Modal(document.getElementById('modalDetail'));
        modal.show();
    }

    function showPhoto(url, nama, tanggal) {
        document.getElementById('photoPreview').src = url;
        document.getElementById('photoTitle').textContent = 'Bukti Foto - ' + nama + ' (' + tanggal + ')';
        document.getElementById('photoDownload').href = url;

        const modal = new bootstrap.Modal(document.getElementById('modalPhoto'));
        modal.show();
    }
</script>

@endsection