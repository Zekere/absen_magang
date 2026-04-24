@extends('layout.admin.template')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
/*
 * PENTING: Semua selector diawali dengan .mon-page
 * agar tidak merembet ke sidebar/header/footer template.
 */

.mon-page {
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.mon-page * {
    font-family: 'Plus Jakarta Sans', sans-serif;
    box-sizing: border-box;
}

/* ─── Page Header ─── */
.mon-page .mon-header {
    background: linear-gradient(135deg, #3b6ff0 0%, #667eea 100%);
    border-radius: 18px;
    padding: 24px 28px;
    margin-bottom: 24px;
    color: #fff;
    box-shadow: 0 6px 24px rgba(59,111,240,0.25);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    position: relative;
    overflow: hidden;
}
.mon-page .mon-header::before {
    content: '';
    position: absolute;
    width: 220px; height: 220px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
    top: -80px; right: -60px;
    pointer-events: none;
}
.mon-page .mon-header::after {
    content: '';
    position: absolute;
    width: 130px; height: 130px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
    bottom: -50px; left: 40%;
    pointer-events: none;
}
.mon-page .mon-header-left { position: relative; z-index: 1; }
.mon-page .mon-header-icon {
    width: 48px; height: 48px;
    background: rgba(255,255,255,0.18);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 12px;
}
.mon-page .mon-header-icon i { font-size: 22px; }
.mon-page .mon-header-title { font-size: 22px; font-weight: 800; margin: 0 0 4px; }
.mon-page .mon-header-sub   { font-size: 13px; opacity: 0.8; font-weight: 500; }
.mon-page .mon-header-badge {
    position: relative; z-index: 1;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 50px;
    padding: 8px 18px;
    font-size: 13px;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 8px;
}
.mon-page .mon-header-badge i { font-size: 15px; }

/* ─── Date Picker Card ─── */
.mon-page .date-card {
    background: #fff;
    border-radius: 16px;
    padding: 18px 20px;
    margin-bottom: 20px;
    border: 1px solid rgba(99,130,220,0.12);
    box-shadow: 0 2px 14px rgba(59,111,240,0.07);
}
.mon-page .date-card-label {
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.mon-page .date-card-label i { font-size: 13px; color: #3b6ff0; }
.mon-page .date-nav {
    display: flex;
    align-items: center;
    gap: 10px;
}
.mon-page .date-nav-btn {
    width: 40px; height: 40px;
    border-radius: 10px;
    border: 1.5px solid rgba(99,130,220,0.2);
    background: #f8faff;
    color: #3b6ff0;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: all .2s;
    flex-shrink: 0;
    font-size: 14px;
}
.mon-page .date-nav-btn:hover { background: #eff6ff; border-color: #3b6ff0; }
.mon-page .date-input-wrap { position: relative; flex: 1; }
.mon-page .date-input-wrap i {
    position: absolute;
    left: 13px; top: 50%;
    transform: translateY(-50%);
    color: #3b6ff0;
    font-size: 16px;
    pointer-events: none;
}
.mon-page .date-input {
    width: 100%;
    padding: 10px 14px 10px 38px;
    border: 1.5px solid rgba(99,130,220,0.2);
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e2a4a;
    background: #f8faff;
    outline: none;
    cursor: pointer;
    transition: all .2s;
}
.mon-page .date-input:focus {
    border-color: #3b6ff0;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(59,111,240,0.1);
}
.mon-page .date-today-btn {
    padding: 9px 16px;
    border-radius: 10px;
    background: linear-gradient(135deg, #3b6ff0, #667eea);
    color: #fff;
    border: none;
    font-size: 12px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer;
    transition: all .2s;
    white-space: nowrap;
    box-shadow: 0 3px 10px rgba(59,111,240,0.25);
}
.mon-page .date-today-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 16px rgba(59,111,240,0.35);
}

/* ─── Stats Row ─── */
.mon-page .stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}
@media(max-width:768px){ .mon-page .stats-row { grid-template-columns: repeat(2,1fr); } }

.mon-page .stat-chip {
    background: #fff;
    border-radius: 14px;
    padding: 14px 16px;
    border: 1px solid rgba(99,130,220,0.12);
    box-shadow: 0 2px 10px rgba(59,111,240,0.06);
}
.mon-page .stat-chip-top {
    display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;
}
.mon-page .stat-chip-icon {
    width: 34px; height: 34px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
}
.mon-page .sci-blue   { background: #eff6ff; color: #2563eb; }
.mon-page .sci-green  { background: #f0fdf4; color: #16a34a; }
.mon-page .sci-red    { background: #fff1f2; color: #dc2626; }
.mon-page .sci-amber  { background: #fffbeb; color: #d97706; }
.mon-page .stat-chip-num  { font-size: 24px; font-weight: 800; color: #1e2a4a; line-height: 1; }
.mon-page .stat-chip-lbl  { font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.4px; margin-top: 4px; }

/* ─── Table Card ─── */
.mon-page .table-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid rgba(99,130,220,0.1);
    box-shadow: 0 2px 16px rgba(59,111,240,0.07);
    overflow: hidden;
}
.mon-page .table-card-header {
    padding: 16px 20px;
    border-bottom: 1px solid rgba(99,130,220,0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.mon-page .table-card-title {
    font-size: 14px;
    font-weight: 700;
    color: #1e2a4a;
    display: flex;
    align-items: center;
    gap: 8px;
}
.mon-page .table-card-title i { color: #3b6ff0; }
.mon-page .tbl-count {
    font-size: 12px;
    color: #64748b;
    background: #f0f4ff;
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: 600;
}

/* ─── Table ─── */
.mon-page .mon-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    table-layout: fixed;
}

.mon-page .mon-table thead tr { background: #f8faff; }
.mon-page .mon-table thead th {
    padding: 11px 10px;
    font-size: 10.5px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid rgba(99,130,220,0.1);
    white-space: nowrap;
    text-align: center;
    overflow: hidden;
}

/* Kolom No, NIP, Karyawan rata sesuai konten */
.mon-page .mon-table thead th:nth-child(1) { text-align: center; }  /* No */
.mon-page .mon-table thead th:nth-child(2) { text-align: left; }    /* NIP */
.mon-page .mon-table thead th:nth-child(3) { text-align: left; }    /* Karyawan/Pegawai */
.mon-page .mon-table thead th:nth-child(4) { text-align: left; }    /* Departemen */

.mon-page .mon-table tbody tr {
    border-bottom: 1px solid rgba(99,130,220,0.06);
    transition: background .15s;
}
.mon-page .mon-table tbody tr:hover { background: #f8faff; }
.mon-page .mon-table tbody tr:last-child { border-bottom: none; }
.mon-page .mon-table tbody td {
    padding: 10px 10px;
    vertical-align: middle;
    color: #374151;
    text-align: center;
    overflow: hidden;
}

/* Kolom teks rata kiri */
.mon-page .mon-table tbody td:nth-child(1) { text-align: center; } /* No */
.mon-page .mon-table tbody td:nth-child(2) { text-align: left; }   /* NIP */
.mon-page .mon-table tbody td:nth-child(3) { text-align: left; }   /* Karyawan */
.mon-page .mon-table tbody td:nth-child(4) { text-align: left; }   /* Departemen */

/* ─── Cell No ─── */
.mon-page .cell-no {
    font-size: 12px;
    font-weight: 700;
    color: #94a3b8;
}

/* ─── NIP cell ─── */
.mon-page .nip-text {
    font-size: 12.5px;
    font-weight: 700;
    color: #1e2a4a;
    letter-spacing: 0.3px;
    font-variant-numeric: tabular-nums;
}

/* ─── Karyawan/Pegawai ─── */
.mon-page .emp-wrap { display: flex; align-items: center; gap: 9px; }
.mon-page .emp-avatar {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b6ff0, #667eea);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800; color: #fff;
    flex-shrink: 0;
}
.mon-page .emp-name {
    font-size: 12.5px; font-weight: 700; color: #1e2a4a;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

/* ─── Departemen pill ─── */
.mon-page .dept-pill {
    display: inline-flex;
    padding: 3px 9px;
    border-radius: 20px;
    background: #eff6ff;
    color: #1d4ed8;
    font-size: 11px;
    font-weight: 700;
    border: 1px solid #bfdbfe;
    white-space: nowrap;
}

/* ─── Jam Masuk / Pulang ─── */
.mon-page .time-display {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    font-size: 12.5px;
    font-weight: 700;
    color: #1e2a4a;
}
.mon-page .time-display i { font-size: 12px; }
.mon-page .time-in  { color: #16a34a; }
.mon-page .time-out { color: #dc2626; }

/* ─── Foto thumbnail ─── */
.mon-page .foto-thumb {
    width: 40px; height: 40px;
    border-radius: 8px;
    object-fit: cover;
    border: 1.5px solid rgba(99,130,220,0.15);
    cursor: pointer;
    transition: transform .2s;
    display: block;
    margin: 0 auto;
}
.mon-page .foto-thumb:hover { transform: scale(1.1); }

/* ─── Lokasi badges ─── */
.mon-page .badge-dalam {
    display: inline-flex; align-items: center; gap: 4px;
    background: #f0fdf4; color: #16a34a;
    border: 1px solid #bbf7d0;
    padding: 4px 8px; border-radius: 20px;
    font-size: 10.5px; font-weight: 700; white-space: nowrap;
}
.mon-page .badge-luar {
    display: inline-flex; align-items: center; gap: 4px;
    background: #fffbeb; color: #b45309;
    border: 1px solid #fde68a;
    padding: 4px 8px; border-radius: 20px;
    font-size: 10.5px; font-weight: 700; white-space: nowrap;
}
.mon-page .badge-na {
    display: inline-flex; align-items: center; gap: 4px;
    background: #f8faff; color: #94a3b8;
    border: 1px solid rgba(99,130,220,0.15);
    padding: 4px 8px; border-radius: 20px;
    font-size: 10.5px; font-weight: 700;
}

/* ─── Status badges (satu badge gabungan) ─── */
/*  Hadir = tepat waktu  */
.mon-page .status-hadir      { background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; }
/*  Toleransi = masuk dalam rentang toleransi  */
.mon-page .status-toleransi  { background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; }
/*  Terlambat  */
.mon-page .status-terlambat  { background:#fffbeb; color:#b45309; border:1px solid #fde68a; }

.mon-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 11px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
    line-height: 1.2;
}
.mon-page .status-badge i { font-size: 11px; }

/* ─── Aksi buttons ─── */
.mon-page .act-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 6px 10px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: all .2s;
    text-decoration: none;
    font-family: 'Plus Jakarta Sans', sans-serif;
    white-space: nowrap;
}
.mon-page .act-btn:hover { transform: translateY(-1px); }

/* Tombol Map (kolom Maps) */
.mon-page .act-map {
    background: linear-gradient(135deg, #3b6ff0, #667eea);
    color: #fff;
    padding: 6px 12px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(59,111,240,0.3);
}
.mon-page .act-map:hover {
    box-shadow: 0 4px 14px rgba(59,111,240,0.45);
    color: #fff;
}

/* Tombol hapus (icon only) */
.mon-page .act-del {
    background: #fff1f2;
    color: #dc2626;
    border: 1.5px solid #fecdd3;
    padding: 6px 8px;
}
.mon-page .act-del:hover { background: #fecdd3; }

.mon-page .act-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    flex-wrap: nowrap;
}

/* ─── Empty / Loading ─── */
.mon-page .empty-row td { padding: 56px 20px; text-align: center; color: #94a3b8; }
.mon-page .empty-icon { font-size: 40px; display: block; margin: 0 auto 12px; }
.mon-page .empty-txt  { font-size: 14px; font-weight: 600; }

.mon-page .loading-row td { padding: 40px; text-align: center; }
.mon-page .spinner-ring {
    width: 36px; height: 36px;
    border: 3px solid rgba(59,111,240,0.15);
    border-top-color: #3b6ff0;
    border-radius: 50%;
    animation: monSpin .7s linear infinite;
    margin: 0 auto 10px;
}
@keyframes monSpin { to { transform: rotate(360deg); } }

.mon-page .table-wrap { overflow-x: auto; }
@media(max-width:992px) {
    .mon-page .mon-table { min-width: 980px; table-layout: auto; }
}

/* ─── Modal Map ─── */
#modal-map .modal-content {
    border: 2px solid rgba(59,111,240,0.25);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(59,111,240,0.2), 0 4px 20px rgba(0,0,0,0.15);
}
#modal-map .modal-header-custom {
    background: linear-gradient(135deg, #3b6ff0, #667eea);
    padding: 16px 20px;
    border: none;
    border-bottom: 2px solid rgba(255,255,255,0.15);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
#modal-map .modal-header-custom h5 {
    font-size: 15px;
    font-weight: 700;
    color: #fff;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
#modal-map .modal-header-custom .btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}
#modal-map .modal-body {
    padding: 0;
    border-top: none;
}
</style>

{{-- Semua konten dibungkus .mon-page --}}
<div class="mon-page">
<div class="container-fluid mt-4 px-3 px-md-4">

    {{-- ── Page Header ── --}}
    <div class="mon-header">
        <div class="mon-header-left">
            <div class="mon-header-icon">
                <i class="bi bi-eye-fill"></i>
            </div>
            <div class="mon-header-title">Monitoring Presensi</div>
            <div class="mon-header-sub">Pantau kehadiran karyawan secara real-time</div>
        </div>
        <div class="mon-header-badge">
            <i class="bi bi-calendar-check"></i>
            <span id="headerDateDisplay">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
        </div>
    </div>

    {{-- ── Date Navigator ── --}}
    <div class="date-card">
        <div class="date-card-label">
            <i class="bi bi-calendar3"></i>
            Pilih Tanggal
        </div>
        <div class="date-nav">
            <button class="date-nav-btn" id="btn-prev" title="Sebelumnya">
                <i class="bi bi-chevron-left"></i>
            </button>
            <div class="date-input-wrap">
                <i class="bi bi-calendar-event"></i>
                <input type="text" id="tanggal"
                       value="{{ date('Y-m-d') }}"
                       name="tanggal"
                       class="date-input"
                       placeholder="Pilih tanggal"
                       autocomplete="off"
                       readonly>
            </div>
            <button class="date-nav-btn" id="btn-next" title="Berikutnya">
                <i class="bi bi-chevron-right"></i>
            </button>
            <button class="date-today-btn" id="btn-today">
                <i class="bi bi-calendar2-check"></i> Hari Ini
            </button>
        </div>
    </div>

    {{-- ── Stats Row ── --}}
    <div class="stats-row" id="statsRow">
        <div class="stat-chip">
            <div class="stat-chip-top">
                <div class="stat-chip-icon sci-blue"><i class="bi bi-people-fill"></i></div>
            </div>
            <div class="stat-chip-num" id="statTotal">—</div>
            <div class="stat-chip-lbl">Total Hadir</div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-top">
                <div class="stat-chip-icon sci-green"><i class="bi bi-check-circle-fill"></i></div>
            </div>
            <div class="stat-chip-num" id="statHadir">—</div>
            <div class="stat-chip-lbl">Tepat Waktu</div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-top">
                <div class="stat-chip-icon sci-amber"><i class="bi bi-clock-fill"></i></div>
            </div>
            <div class="stat-chip-num" id="statTerlambat">—</div>
            <div class="stat-chip-lbl">Terlambat</div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-top">
                <div class="stat-chip-icon sci-red"><i class="bi bi-door-open-fill"></i></div>
            </div>
            <div class="stat-chip-num" id="statPulang">—</div>
            <div class="stat-chip-lbl">Sudah Pulang</div>
        </div>
    </div>

    {{-- ── Table Card ── --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <i class="bi bi-table"></i>
                Data Presensi
            </div>
            <span class="tbl-count" id="tblCount">Memuat...</span>
        </div>
        <div class="table-wrap">
            <table class="mon-table">
                {{--
                    URUTAN KOLOM (13 kolom):
                    1. No
                    2. NIP
                    3. Karyawan / Pegawai
                    4. Departemen
                    5. Masuk
                    6. Foto Masuk
                    7. Lokasi Masuk
                    8. Pulang
                    9. Foto Pulang
                    10. Lokasi Pulang
                    11. Status  (satu badge: Hadir / Toleransi / Terlambat)
                    12. Maps
                    13. Aksi
                --}}
                <colgroup>
                    <col>    {{-- 1. No --}}
                    <col>   {{-- 2. NIP --}}
                    <col>   {{-- 3. Karyawan / Pegawai --}}
                    <col>   {{-- 4. Departemen --}}
                    <col>    {{-- 5. Masuk --}}
                    <col>    {{-- 6. Foto Masuk --}}
                    <col>   {{-- 7. Lokasi Masuk --}}
                    <col>    {{-- 8. Pulang --}}
                    <col>    {{-- 9. Foto Pulang --}}
                    <col>   {{-- 10. Lokasi Pulang --}}
                    <col>   {{-- 11. Status --}}
                    <col>    {{-- 12. Maps --}}
                    <col>    {{-- 13. Aksi --}}
                </colgroup>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Karyawan / Pegawai</th>
                        <th>Departemen</th>
                        <th>Masuk</th>
                        <th>Foto Masuk</th>
                        <th>Lokasi Masuk</th>
                        <th>Pulang</th>
                        <th>Foto Pulang</th>
                        <th>Lokasi Pulang</th>
                        <th>Keterlambatan</th>
                        <th>Status</th>
                        <th>Maps</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="loadpresensi">
                    <tr class="loading-row">
                        <td colspan="13">
                            <div class="spinner-ring"></div>
                            <div style="font-size:13px;color:#94a3b8;font-weight:600">Memuat data...</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>{{-- end .mon-page --}}

<!-- PANDUAN: lihat README atau komentar di controller untuk struktur TR getpresensi -->

{{-- ── Modal Map ── --}}
<div class="modal fade" id="modal-map" tabindex="-1" aria-hidden="true"
     data-bs-backdrop="false" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-custom">
                <h5>
                    <i class="bi bi-geo-alt-fill"></i>
                    Lokasi Presensi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="loadmap"></div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
var bsModalMap = null;

document.addEventListener('DOMContentLoaded', function () {
    var modalMapEl = document.getElementById('modal-map');
    if (modalMapEl) bsModalMap = new bootstrap.Modal(modalMapEl);
});

$(function () {
    $("#tanggal").datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    $("#tanggal").change(function () { loadpresensi(); });

    $("#btn-prev").click(function (e) {
        e.preventDefault();
        var d = $("#tanggal").datepicker('getDate');
        d.setDate(d.getDate() - 1);
        $("#tanggal").datepicker('setDate', d);
        loadpresensi();
    });

    $("#btn-next").click(function (e) {
        e.preventDefault();
        var d = $("#tanggal").datepicker('getDate');
        d.setDate(d.getDate() + 1);
        $("#tanggal").datepicker('setDate', d);
        loadpresensi();
    });

    $("#btn-today").click(function (e) {
        e.preventDefault();
        $("#tanggal").datepicker('setDate', new Date());
        loadpresensi();
    });

    loadpresensi();
});

function loadpresensi() {
    var tanggal = $("#tanggal").val();

    ['statTotal','statHadir','statTerlambat','statPulang'].forEach(function (id) {
        document.getElementById(id).textContent = '—';
    });
    document.getElementById('tblCount').textContent = 'Memuat...';

    $("#loadpresensi").html(
        '<tr class="loading-row"><td colspan="13">' +
        '<div class="spinner-ring"></div>' +
        '<div style="font-size:13px;color:#94a3b8;font-weight:600">Memuat data...</div>' +
        '</td></tr>'
    );

    $.ajax({
        type: 'POST',
        url: '/getpresensi',
        data: { _token: "{{ csrf_token() }}", tanggal: tanggal },
        cache: false,
        success: function (respond) {
            $("#loadpresensi").html(respond);

            var rows = document.querySelectorAll('#loadpresensi tr');
            var total = 0, hadir = 0, terlambat = 0, sudahPulang = 0;

            rows.forEach(function (r) {
                if (r.querySelector('td[colspan]')) return;
                total++;

                /* Deteksi status dari badge class baru (satu badge gabungan) */
                var badgeHadir      = r.querySelector('.status-badge.status-hadir');
                var badgeToleransi  = r.querySelector('.status-badge.status-toleransi');
                var badgeTerlambat  = r.querySelector('.status-badge.status-terlambat');
                if (badgeHadir || badgeToleransi) hadir++;
                if (badgeTerlambat) terlambat++;

                /* Fallback: deteksi dari badge Bootstrap lama */
                if (!badgeHadir && !badgeToleransi && !badgeTerlambat) {
                    var bgSuccess = r.querySelector('.badge.bg-success');
                    var bgWarning = r.querySelector('.badge.bg-warning');
                    if (bgSuccess && bgSuccess.textContent.trim() === 'Hadir') hadir++;
                    if (bgWarning && bgWarning.textContent.includes('Terlambat')) terlambat++;
                }

                /*
                 * Urutan kolom baru (13 kolom, 0-based):
                 * 0=No | 1=NIP | 2=Karyawan | 3=Dept |
                 * 4=Masuk | 5=FotoMasuk | 6=LokasiMasuk |
                 * 7=Pulang | 8=FotoPulang | 9=LokasiPulang |
                 * 10=Status | 11=Maps | 12=Aksi
                 */
                var tds = r.querySelectorAll('td');
                if (tds.length > 7) {
                    var jamOut = tds[7] ? tds[7].textContent.trim() : '';
                    if (jamOut && jamOut !== '-' && jamOut.match(/\d{2}:\d{2}/)) sudahPulang++;
                }
            });

            document.getElementById('statTotal').textContent     = total;
            document.getElementById('statHadir').textContent     = hadir;
            document.getElementById('statTerlambat').textContent = terlambat;
            document.getElementById('statPulang').textContent    = sudahPulang;
            document.getElementById('tblCount').textContent      = total + ' karyawan';
        }
    });
}

function deletePresensi(id) {
    Swal.fire({
        title: 'Hapus data presensi?',
        text: 'Data akan dihapus permanen dan tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then(function (result) {
        if (result.isConfirmed) {
            Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: function () { Swal.showLoading(); } });
            $.ajax({
                type: 'POST',
                url: '/presensi/delete/' + id,
                data: { _token: "{{ csrf_token() }}", _method: 'DELETE' },
                cache: false,
                success: function () {
                    Swal.fire({ title: 'Berhasil!', text: 'Data presensi berhasil dihapus.', icon: 'success', confirmButtonColor: '#3b6ff0' })
                        .then(function () { loadpresensi(); });
                },
                error: function () {
                    Swal.fire({ title: 'Gagal!', text: 'Terjadi kesalahan saat menghapus data.', icon: 'error' });
                }
            });
        }
    });
}

function showImage(src) {
    Swal.fire({
        imageUrl: src,
        imageAlt: 'Foto Presensi',
        showConfirmButton: false,
        showCloseButton: true,
        background: '#0f172a',
        width: 'auto',
        padding: '16px'
    });
}

function tampilkanMap(id) {
    if (!bsModalMap) bsModalMap = new bootstrap.Modal(document.getElementById('modal-map'));
    document.getElementById('loadmap').innerHTML =
        '<div style="padding:48px;text-align:center">' +
        '<div class="mon-page"><div class="spinner-ring" style="margin:0 auto 12px"></div></div>' +
        '<div style="font-size:13px;color:#94a3b8;font-weight:600">Memuat peta...</div>' +
        '</div>';
    bsModalMap.show();

    $.ajax({
        type: 'POST',
        url: '/presensi/showmap',
        data: { _token: "{{ csrf_token() }}", id: id },
        cache: false,
        success: function (respond) {
            var container = document.getElementById('loadmap');
            container.innerHTML = respond;
            container.querySelectorAll('script').forEach(function (old) {
                var s = document.createElement('script');
                Array.from(old.attributes).forEach(function (a) { s.setAttribute(a.name, a.value); });
                s.textContent = old.textContent;
                old.parentNode.replaceChild(s, old);
            });
            setTimeout(function () { window.dispatchEvent(new Event('resize')); }, 600);
        },
        error: function () {
            document.getElementById('loadmap').innerHTML =
                '<div style="padding:24px">' +
                '<div style="background:#fff1f2;border:1px solid #fecdd3;border-radius:12px;padding:16px;color:#dc2626;font-weight:600;font-size:13px">' +
                'Gagal memuat peta. Silakan coba lagi.</div></div>';
        }
    });
}
</script>
@endpush