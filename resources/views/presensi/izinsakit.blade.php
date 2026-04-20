@extends('layout.admin.template')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
/* ── Semua scoped ke .isc-admin agar tidak merembet ke template ── */
.isc-admin {
    font-family: 'DM Sans', sans-serif;
    padding: 24px 20px 48px;
}
.isc-admin * { box-sizing: border-box; font-family: 'DM Sans', sans-serif; }

/* ─── HEADER ─── */
.isc-admin .ia-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 22px;
}
.isc-admin .ia-header-left {}
.isc-admin .ia-title {
    font-size: 20px;
    font-weight: 700;
    color: #0f1c3f;
    letter-spacing: -0.3px;
    margin: 0 0 3px;
    display: flex;
    align-items: center;
    gap: 9px;
}
.isc-admin .ia-title i { color: #3b6ff0; font-size: 20px; }
.isc-admin .ia-sub { font-size: 13px; color: #8492a6; font-weight: 500; }

/* ─── STAT CHIPS ─── */
.isc-admin .ia-stats {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}
.isc-admin .ia-chip {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    border: 1px solid #e8edf5;
    border-radius: 12px;
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
}
.isc-admin .ia-chip-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}
.isc-admin .ia-chip-num {
    font-size: 16px;
    font-weight: 700;
    color: #0f1c3f;
}

/* ─── FILTER BAR ─── */
.isc-admin .ia-filters {
    background: #fff;
    border: 1px solid #e8edf5;
    border-radius: 16px;
    padding: 16px 18px;
    margin-bottom: 16px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: flex-end;
}
.isc-admin .ia-filter-group { display: flex; flex-direction: column; gap: 5px; flex: 1; min-width: 130px; }
.isc-admin .ia-filter-label {
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.isc-admin .ia-select,
.isc-admin .ia-input {
    padding: 9px 12px;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    color: #111;
    background: #f8f9fa;
    outline: none;
    font-family: 'DM Sans', sans-serif;
    transition: border-color .2s, box-shadow .2s;
    width: 100%;
}
.isc-admin .ia-select:focus,
.isc-admin .ia-input:focus {
    border-color: #3b6ff0;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(59,111,240,0.1);
}
.isc-admin .ia-input { min-width: 180px; }

/* ─── TABLE CARD ─── */
.isc-admin .ia-table-card {
    background: #fff;
    border: 1px solid #e8edf5;
    border-radius: 18px;
    overflow: hidden;
    margin-bottom: 16px;
}
.isc-admin .ia-table-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    border-bottom: 1px solid #f0f3f9;
    flex-wrap: wrap;
    gap: 10px;
}
.isc-admin .ia-table-title {
    font-size: 14px;
    font-weight: 700;
    color: #0f1c3f;
    display: flex;
    align-items: center;
    gap: 7px;
}
.isc-admin .ia-table-title i { color: #3b6ff0; }
.isc-admin .ia-count-badge {
    background: #f0f4ff;
    color: #3b6ff0;
    font-size: 12px;
    font-weight: 700;
    padding: 3px 12px;
    border-radius: 20px;
}

.isc-admin .ia-table-wrap { overflow-x: auto; }

.isc-admin .ia-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    min-width: 900px;
}
.isc-admin .ia-table thead tr { background: #f8faff; }
.isc-admin .ia-table thead th {
    padding: 11px 14px;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border-bottom: 1px solid #edf0f7;
    white-space: nowrap;
    text-align: left;
}
.isc-admin .ia-table thead th.tc { text-align: center; }

.isc-admin .ia-table tbody tr {
    border-bottom: 1px solid #f5f7fc;
    transition: background .12s;
}
.isc-admin .ia-table tbody tr:hover { background: #f8faff; }
.isc-admin .ia-table tbody tr:last-child { border-bottom: none; }

.isc-admin .ia-table tbody td {
    padding: 11px 14px;
    vertical-align: middle;
    color: #374151;
}
.isc-admin .ia-table tbody td.tc { text-align: center; }

/* row nomor */
.isc-admin .ia-no {
    font-size: 12px;
    font-weight: 700;
    color: #94a3b8;
    min-width: 36px;
}

/* nama karyawan */
.isc-admin .ia-name { font-size: 13px; font-weight: 700; color: #0f1c3f; white-space: nowrap; }
.isc-admin .ia-job  { font-size: 11px; color: #94a3b8; margin-top: 1px; }

/* badges jenis */
.isc-admin .ia-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
.isc-admin .ib-izin   { background: #e8f0fe; color: #1d4ed8; }
.isc-admin .ib-sakit  { background: #fef9e7; color: #b45309; }
.isc-admin .ib-cuti   { background: #e8faf0; color: #16a34a; }
.isc-admin .ib-other  { background: #f1f5f9; color: #64748b; }

/* approval badges */
.isc-admin .ib-approved { background: #e8faf0; color: #16a34a; border: 1px solid #bbf7d0; }
.isc-admin .ib-rejected { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
.isc-admin .ib-pending  {
    background: #fef9e7; color: #b45309; border: 1px solid #fde68a;
    animation: iaPendingPulse 2s ease-in-out infinite;
}
@keyframes iaPendingPulse {
    0%,100% { box-shadow: none; }
    50%      { box-shadow: 0 0 0 3px rgba(245,158,11,0.2); }
}

/* keterangan truncate */
.isc-admin .ia-ket {
    max-width: 180px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
    color: #555;
    font-size: 12px;
}

/* action buttons */
.isc-admin .ia-act-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 5px 10px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    text-decoration: none;
    font-family: 'DM Sans', sans-serif;
    transition: opacity .15s, transform .1s;
}
.isc-admin .ia-act-btn:active { transform: scale(0.96); }
.isc-admin .ia-btn-view { background: #e8f0fe; color: #1d4ed8; }
.isc-admin .ia-btn-dl   { background: #e8faf0; color: #16a34a; }
.isc-admin .ia-btn-proc { background: #3b6ff0; color: #fff; }
.isc-admin .ia-btn-cancel { background: #fef2f2; color: #dc2626; }

/* ─── PAGINATION ─── */
.isc-admin .ia-pager {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    padding: 12px 20px;
    border-top: 1px solid #f0f3f9;
}
.isc-admin .ia-pager-info {
    font-size: 12px;
    color: #94a3b8;
    font-weight: 500;
}
.isc-admin .ia-pagination {
    display: flex;
    gap: 4px;
    list-style: none;
    margin: 0; padding: 0;
}
.isc-admin .ia-pagination li a,
.isc-admin .ia-pagination li span {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid #e8edf5;
    background: #fff;
    color: #374151;
    cursor: pointer;
    text-decoration: none;
    transition: background .15s, color .15s;
}
.isc-admin .ia-pagination li a:hover { background: #f0f4ff; color: #3b6ff0; }
.isc-admin .ia-pagination li.active a { background: #3b6ff0; color: #fff; border-color: #3b6ff0; }
.isc-admin .ia-pagination li.disabled span { color: #d1d5db; pointer-events: none; }

/* ─── MODAL ─── */
.isc-admin-modal .modal-content {
    border: none;
    border-radius: 18px;
    overflow: hidden;
    font-family: 'DM Sans', sans-serif;
}
.isc-admin-modal .modal-content * { font-family: 'DM Sans', sans-serif; }
.isc-admin-modal .ia-modal-head {
    background: #0f1c3f;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.isc-admin-modal .ia-modal-head-title {
    font-size: 15px;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 8px;
}
.isc-admin-modal .ia-modal-head-title i { font-size: 17px; color: #60a5fa; }
.isc-admin-modal .ia-modal-close {
    width: 32px; height: 32px;
    background: rgba(255,255,255,0.12);
    border: none; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: #fff;
    font-size: 18px;
}
.isc-admin-modal .modal-body { padding: 20px; }

.isc-admin-modal .ia-info-block {
    background: #f8faff;
    border: 1px solid #e8edf5;
    border-radius: 12px;
    padding: 13px 15px;
    margin-bottom: 18px;
    font-size: 13px;
    color: #555;
    line-height: 1.7;
}
.isc-admin-modal .ia-info-block strong { color: #0f1c3f; font-weight: 700; }

.isc-admin-modal .ia-field-label {
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 7px;
}
.isc-admin-modal .ia-modal-select,
.isc-admin-modal .ia-modal-textarea {
    width: 100%;
    padding: 11px 13px;
    border: 1.5px solid #e5e7eb;
    border-radius: 11px;
    font-size: 14px;
    color: #111;
    background: #f8f9fa;
    font-family: 'DM Sans', sans-serif;
    outline: none;
    transition: border-color .2s;
    margin-bottom: 16px;
}
.isc-admin-modal .ia-modal-select:focus,
.isc-admin-modal .ia-modal-textarea:focus {
    border-color: #3b6ff0;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(59,111,240,0.1);
}
.isc-admin-modal .ia-modal-textarea { resize: none; line-height: 1.55; }
.isc-admin-modal .ia-modal-hint { font-size: 11px; color: #aaa; margin-top: -10px; margin-bottom: 16px; }
.isc-admin-modal .ia-alasan-label {
    font-size: 12px; font-weight: 700; color: #dc2626;
    text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 7px;
}
.isc-admin-modal #ia_alasan_block { display: none; }
.isc-admin-modal #ia_alasan_block.show { display: block; }

.isc-admin-modal .ia-modal-btns { display: flex; gap: 10px; }
.isc-admin-modal .ia-modal-btn {
    flex: 1; padding: 13px; border-radius: 12px; border: none;
    font-size: 14px; font-weight: 700; cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: opacity .15s;
}
.isc-admin-modal .ia-modal-btn:active { opacity: .8; }
.isc-admin-modal .ia-cancel-btn { background: #f1f5f9; color: #64748b; }
.isc-admin-modal .ia-submit-btn { background: #3b6ff0; color: #fff; }
.isc-admin-modal .ia-submit-btn.danger { background: #dc2626; }
</style>

<div class="isc-admin">

    {{-- ─── HEADER ─── --}}
    <div class="ia-header">
        <div class="ia-header-left">
            <div class="ia-title">
                <i class="bi bi-clipboard2-data"></i>
                Izin Sakit Cuti
            </div>
            <div class="ia-sub">Pencatatan &amp; persetujuan izin, sakit, dan cuti karyawan</div>
        </div>
    </div>

    {{-- ─── STAT CHIPS ─── --}}
    @php
        $total    = count($izinsakit);
        $pending  = $izinsakit->where('status_approved', 0)->count();
        $approved = $izinsakit->where('status_approved', 1)->count();
        $rejected = $izinsakit->where('status_approved', 2)->count();
    @endphp
    <div class="ia-stats">
        <div class="ia-chip">
            <span class="ia-chip-dot" style="background:#3b6ff0"></span>
            <span class="ia-chip-num">{{ $total }}</span>
            Total
        </div>
        <div class="ia-chip">
            <span class="ia-chip-dot" style="background:#f59e0b"></span>
            <span class="ia-chip-num">{{ $pending }}</span>
            Pending
        </div>
        <div class="ia-chip">
            <span class="ia-chip-dot" style="background:#16a34a"></span>
            <span class="ia-chip-num">{{ $approved }}</span>
            Disetujui
        </div>
        <div class="ia-chip">
            <span class="ia-chip-dot" style="background:#dc2626"></span>
            <span class="ia-chip-num">{{ $rejected }}</span>
            Ditolak
        </div>
    </div>

    {{-- ─── FILTER BAR ─── --}}
    <div class="ia-filters">
        <div class="ia-filter-group" style="max-width:110px;">
            <div class="ia-filter-label">Tampilkan</div>
            <select id="entriesPerPage" class="ia-select">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="all">Semua</option>
            </select>
        </div>
        <div class="ia-filter-group">
            <div class="ia-filter-label">Jenis</div>
            <select id="filterStatus" class="ia-select">
                <option value="">Semua Jenis</option>
                <option value="1">Izin</option>
                <option value="2">Sakit</option>
                <option value="3">Cuti</option>
            </select>
        </div>
        <div class="ia-filter-group">
            <div class="ia-filter-label">Persetujuan</div>
            <select id="filterApproved" class="ia-select">
                <option value="">Semua Status</option>
                <option value="0">Pending</option>
                <option value="1">Disetujui</option>
                <option value="2">Ditolak</option>
            </select>
        </div>
        <div class="ia-filter-group" style="flex:2;">
            <div class="ia-filter-label">Cari</div>
            <input type="text" id="searchInput" class="ia-input" placeholder="NIK, Nama, Jabatan...">
        </div>
    </div>

    {{-- ─── TABLE ─── --}}
    <div class="ia-table-card">
        <div class="ia-table-head">
            <div class="ia-table-title">
                <i class="bi bi-table"></i>
                Data Pengajuan
            </div>
            <span class="ia-count-badge" id="tblCount">{{ $total }} data</span>
        </div>

        <div class="ia-table-wrap">
            <table class="ia-table" id="dataTable">
                <thead>
                    <tr>
                        <th class="tc">No</th>
                        <th>Tanggal</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th class="tc">Bukti</th>
                        <th class="tc">Persetujuan</th>
                        <th class="tc">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @foreach ($izinsakit as $d)
                    <tr data-status="{{ $d->status }}"
                        data-approved="{{ $d->status_approved }}"
                        data-nik="{{ $d->nik }}">

                        <td class="ia-no tc">{{ $loop->iteration }}</td>
                        <td style="white-space:nowrap;font-weight:600;color:#374151;">
                            {{ date('d M Y', strtotime($d->tgl_izin)) }}
                        </td>
                        <td style="font-size:12px;color:#64748b;font-weight:600;">{{ $d->nik }}</td>
                        <td>
                            <div class="ia-name">{{ $d->nama_lengkap }}</div>
                            <div class="ia-job">{{ $d->jabatan }}</div>
                        </td>
                        <td>
                            @if ($d->status == '1')
                                <span class="ia-badge ib-izin">
                                    <i class="bi bi-calendar2" style="font-size:10px;"></i> Izin
                                </span>
                            @elseif ($d->status == '2')
                                <span class="ia-badge ib-sakit">
                                    <i class="bi bi-thermometer-half" style="font-size:10px;"></i> Sakit
                                </span>
                            @elseif ($d->status == '3')
                                <span class="ia-badge ib-cuti">
                                    <i class="bi bi-airplane" style="font-size:10px;"></i> Cuti
                                </span>
                            @else
                                <span class="ia-badge ib-other">—</span>
                            @endif
                        </td>
                        <td class="ia-ket"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="{{ $d->keterangan }}">
                            {{ Str::limit($d->keterangan, 40, '...') }}
                        </td>
                        <td class="tc">
                            @if($d->bukti_surat)
                                <a href="/presensi/{{ $d->id }}/lihatbukti"
                                   class="ia-act-btn ia-btn-view" title="Lihat Bukti">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="/presensi/{{ $d->id }}/downloadbukti"
                                   class="ia-act-btn ia-btn-dl" title="Unduh">
                                    <i class="bi bi-download"></i>
                                </a>
                            @else
                                <span style="font-size:11px;color:#d1d5db;font-weight:600;">—</span>
                            @endif
                        </td>
                        <td class="tc">
                            @if ($d->status_approved == 1)
                                <span class="ia-badge ib-approved ia-badge">
                                    <i class="bi bi-check-circle" style="font-size:10px;"></i> Disetujui
                                </span>
                            @elseif ($d->status_approved == 2)
                                <span class="ia-badge ib-rejected">
                                    <i class="bi bi-x-circle" style="font-size:10px;"></i> Ditolak
                                </span>
                            @else
                                <span class="ia-badge ib-pending">
                                    <i class="bi bi-clock" style="font-size:10px;"></i> Pending
                                </span>
                            @endif
                        </td>
                        <td class="tc">
                            @if($d->status_approved == 0)
                                <a href="#"
                                   class="ia-act-btn ia-btn-proc approved"
                                   id_izinsakit="{{ $d->id }}"
                                   data-nik="{{ $d->nik }}"
                                   data-nama="{{ $d->nama_lengkap }}"
                                   data-tanggal="{{ date('d-m-Y', strtotime($d->tgl_izin)) }}"
                                   title="Proses Persetujuan">
                                    <i class="bi bi-check2-square"></i> Proses
                                </a>
                            @else
                                <a href="/presensi/{{ $d->id }}/batalkanizinsakit"
                                   class="ia-act-btn ia-btn-cancel"
                                   title="Batalkan Persetujuan">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINATION ─── --}}
        <div class="ia-pager">
            <div class="ia-pager-info" id="paginationInfo">
                Menampilkan 1–10 dari {{ count($izinsakit) }} data
            </div>
            <ul class="ia-pagination" id="pagination"></ul>
        </div>
    </div>

</div>{{-- end .isc-admin --}}

{{-- ─── MODAL PERSETUJUAN ─── --}}
<div class="modal fade isc-admin-modal" id="modal-izinsakit"
     tabindex="-1" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="ia-modal-head">
                <div class="ia-modal-head-title">
                    <i class="bi bi-check-circle"></i>
                    Proses Persetujuan
                </div>
                <button class="ia-modal-close" data-bs-dismiss="modal">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="/presensi/approved" method="POST" id="formApproval">
                    @csrf
                    <input type="hidden" id="id_izinsakit_form" name="id_izinsakit_form">
                    <input type="hidden" id="nik_karyawan" name="nik_karyawan">

                    <div class="ia-info-block" id="ia_info_block">
                        <strong id="info_nama">—</strong><br>
                        <span style="font-size:12px;color:#94a3b8;" id="info_tanggal">—</span>
                    </div>

                    <div class="ia-field-label">Status Persetujuan</div>
                    <select name="status_approved" id="status_approved" class="ia-modal-select">
                        <option value="1">✅ Setujui</option>
                        <option value="2">❌ Tolak</option>
                    </select>

                    <div id="ia_alasan_block">
                        <div class="ia-alasan-label">Alasan Penolakan</div>
                        <textarea name="alasan_tolak" id="alasan_tolak"
                                  class="ia-modal-textarea" rows="3"
                                  placeholder="Contoh: Kuota cuti sudah habis, tidak sesuai jadwal..."></textarea>
                        <div class="ia-modal-hint">Karyawan akan menerima notifikasi dengan alasan ini</div>
                    </div>

                    <div class="ia-modal-btns">
                        <button type="button" class="ia-modal-btn ia-cancel-btn"
                                data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="ia-modal-btn ia-submit-btn" id="ia_submit_btn">
                            Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    var currentPage    = 1;
    var entriesPerPage = 10;
    var allRows        = [];
    var filteredRows   = [];

    function init() {
        allRows      = Array.from(document.querySelectorAll('#tableBody tr'));
        filteredRows = allRows.slice();
        applyFilters();
    }

    function applyFilters() {
        var search   = $('#searchInput').val().toLowerCase();
        var status   = $('#filterStatus').val();
        var approved = $('#filterApproved').val();

        filteredRows = allRows.filter(function (row) {
            var text   = row.textContent.toLowerCase();
            var rStatus   = row.getAttribute('data-status');
            var rApproved = row.getAttribute('data-approved');
            return text.includes(search)
                && (!status   || rStatus   === status)
                && (!approved || rApproved === approved);
        });

        currentPage = 1;
        displayPage();
    }

    function displayPage() {
        var showAll     = entriesPerPage === 'all';
        var perPage     = showAll ? filteredRows.length : parseInt(entriesPerPage);
        var start       = showAll ? 0 : (currentPage - 1) * perPage;
        var end         = start + perPage;
        var total       = filteredRows.length;

        allRows.forEach(function (r) { r.style.display = 'none'; });

        filteredRows.slice(start, end).forEach(function (row, idx) {
            row.style.display = '';
            row.querySelector('td:first-child').textContent = start + idx + 1;
        });

        /* info */
        var showing = total === 0 ? 0 : start + 1;
        var to      = Math.min(end, total);
        $('#paginationInfo').text(
            'Menampilkan ' + showing + '–' + to + ' dari ' + total + ' data' +
            (allRows.length !== total ? ' (difilter dari ' + allRows.length + ')' : '')
        );
        $('#tblCount').text(total + ' data');

        buildPagination(showAll ? 1 : Math.ceil(total / perPage));
    }

    function buildPagination(totalPages) {
        var $pg = $('#pagination').empty();
        if (totalPages <= 1) return;

        $pg.append('<li class="' + (currentPage === 1 ? 'disabled' : '') + '">' +
            '<a href="#" data-page="' + (currentPage - 1) + '">‹</a></li>');

        for (var i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                $pg.append('<li class="' + (i === currentPage ? 'active' : '') + '">' +
                    '<a href="#" data-page="' + i + '">' + i + '</a></li>');
            } else if (i === currentPage - 3 || i === currentPage + 3) {
                $pg.append('<li class="disabled"><span>…</span></li>');
            }
        }

        $pg.append('<li class="' + (currentPage === totalPages ? 'disabled' : '') + '">' +
            '<a href="#" data-page="' + (currentPage + 1) + '">›</a></li>');
    }

    /* ── Event Listeners ── */
    $('#entriesPerPage').on('change', function () { entriesPerPage = $(this).val(); currentPage = 1; displayPage(); });
    $('#searchInput').on('keyup', applyFilters);
    $('#filterStatus, #filterApproved').on('change', applyFilters);

    $(document).on('click', '#pagination a', function (e) {
        e.preventDefault();
        var page = parseInt($(this).attr('data-page'));
        if (page && page !== currentPage) { currentPage = page; displayPage(); }
    });

    /* ── Modal Approval ── */
    $(document).on('click', '.approved', function (e) {
        e.preventDefault();
        $('#id_izinsakit_form').val($(this).attr('id_izinsakit'));
        $('#nik_karyawan').val($(this).attr('data-nik'));
        $('#info_nama').text($(this).attr('data-nama'));
        $('#info_tanggal').text($(this).attr('data-tanggal'));
        $('#status_approved').val('1');
        $('#alasan_tolak').val('');
        $('#ia_alasan_block').removeClass('show');
        $('#ia_submit_btn').removeClass('danger').text('Setujui');
        $('#modal-izinsakit').modal('show');
    });

    $('#status_approved').on('change', function () {
        if ($(this).val() === '2') {
            $('#ia_alasan_block').addClass('show');
            $('#alasan_tolak').prop('required', true);
            $('#ia_submit_btn').addClass('danger').text('Tolak');
        } else {
            $('#ia_alasan_block').removeClass('show');
            $('#alasan_tolak').prop('required', false);
            $('#ia_submit_btn').removeClass('danger').text('Setujui');
        }
    });

    /* ── Tooltips ── */
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
        new bootstrap.Tooltip(el);
    });

    init();
});
</script>
@endpush