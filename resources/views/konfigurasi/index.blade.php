@extends('layout.admin.template')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    * { font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif; }

    :root {
        --primary: #667eea;
        --primary-end: #764ba2;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --surface: #f1f5f9;
        --card: #ffffff;
        --border: #e2e8f0;
        --text: #1e293b;
        --muted: #64748b;
    }

    body, .page-wrapper { background: var(--surface); }

    /* ── Hero ── */
    .page-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 28px 36px;
        margin-bottom: 28px;
    }
    .hero-title {
        color: #fff;
        font-size: 22px;
        font-weight: 800;
        margin: 0 0 4px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .hero-sub { color: rgba(255,255,255,.8); font-size: 13px; margin: 0; }

    /* ── Card ── */
    .g-card {
        background: var(--card);
        border-radius: 16px;
        box-shadow: 0 2px 16px rgba(0,0,0,.07);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .g-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #fff;
    }
    .g-card-header-title {
        font-size: 15px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .g-card-body { padding: 24px; }

    /* ── Table ── */
    .lokasi-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
    .lokasi-table thead tr {
        background: #f8fafc;
        border-bottom: 2px solid var(--border);
    }
    .lokasi-table th {
        padding: 12px 14px;
        text-align: left;
        font-weight: 700;
        color: var(--muted);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .lokasi-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background .15s;
    }
    .lokasi-table tbody tr:last-child { border-bottom: none; }
    .lokasi-table tbody tr:hover { background: #f8fafc; }
    .lokasi-table td { padding: 14px; color: var(--text); vertical-align: middle; }

    /* Badge */
    .badge-utama {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }
    .badge-cabang {
        background: #eff6ff;
        color: #3b82f6;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid #bfdbfe;
    }

    /* Koordinat text */
    .koordinat-text {
        font-family: 'Courier New', monospace;
        font-size: 12px;
        color: var(--muted);
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 6px;
    }

    /* Radius badge */
    .radius-badge {
        background: #fef3c7;
        color: #d97706;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid #fde68a;
    }

    /* ── Buttons ── */
    .btn-add {
        background: rgba(255,255,255,.2);
        color: #fff;
        border: 1.5px solid rgba(255,255,255,.5);
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all .2s;
        text-decoration: none;
    }
    .btn-add:hover { background: rgba(255,255,255,.35); color: #fff; }

    .btn-edit {
        background: #eff6ff;
        color: #3b82f6;
        border: 1px solid #bfdbfe;
        padding: 6px 12px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s;
    }
    .btn-edit:hover { background: #3b82f6; color: #fff; }

    .btn-del {
        background: #fef2f2;
        color: #ef4444;
        border: 1px solid #fecaca;
        padding: 6px 12px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s;
    }
    .btn-del:hover { background: #ef4444; color: #fff; }

    /* ── Form fields ── */
    .f-label {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .f-label i { color: var(--primary); }
    .f-input {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid var(--border);
        border-radius: 9px;
        font-size: 13.5px;
        font-family: inherit;
        color: var(--text);
        outline: none;
        transition: border-color .2s, box-shadow .2s;
        box-sizing: border-box;
    }
    .f-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(102,126,234,.12);
    }
    .f-hint {
        font-size: 11.5px;
        color: var(--muted);
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border: none;
        padding: 12px 28px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all .25s;
        box-shadow: 0 4px 14px rgba(102,126,234,.3);
    }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(102,126,234,.4); }

    .btn-cancel {
        background: #f1f5f9;
        color: var(--muted);
        border: 1.5px solid var(--border);
        padding: 12px 22px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s;
    }
    .btn-cancel:hover { background: var(--border); color: var(--text); }

    /* ── Modal ── */
    .modal-overlay {
        position: fixed; inset: 0;
        background: rgba(15,23,42,.55);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity .25s;
    }
    .modal-overlay.show { opacity: 1; pointer-events: all; }
    .modal-box {
        background: #fff;
        border-radius: 18px;
        width: 100%;
        max-width: 520px;
        padding: 32px;
        box-shadow: 0 20px 60px rgba(0,0,0,.2);
        transform: translateY(20px) scale(.97);
        transition: transform .25s;
        position: relative;
    }
    .modal-overlay.show .modal-box { transform: translateY(0) scale(1); }
    .modal-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .modal-title i { color: var(--primary); }
    .modal-close {
        position: absolute;
        top: 18px; right: 20px;
        background: #f1f5f9;
        border: none;
        width: 32px; height: 32px;
        border-radius: 50%;
        font-size: 16px;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: var(--muted);
        transition: all .2s;
    }
    .modal-close:hover { background: #e2e8f0; color: var(--text); }

    /* ── Alert ── */
    .alert-custom {
        padding: 13px 16px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
    }
    .alert-success { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .alert-warning { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .alert-danger   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    /* ── Info tip ── */
    .info-tip {
        background: #eff6ff;
        border-left: 4px solid #3b82f6;
        border-radius: 8px;
        padding: 13px 16px;
        font-size: 13px;
        color: #1e40af;
        margin-bottom: 22px;
        display: flex;
        gap: 10px;
    }

    /* ── Empty state ── */
    .empty-row td {
        text-align: center;
        padding: 40px;
        color: var(--muted);
        font-size: 14px;
    }

    /* Mobile */
    @media (max-width: 768px) {
        .page-hero { padding: 20px 18px; }
        .g-card-body { padding: 16px; }
        .lokasi-table { font-size: 12px; }
        .lokasi-table th, .lokasi-table td { padding: 10px 8px; }
        .hide-mobile { display: none; }
        .modal-box { margin: 16px; padding: 22px; }
    }
</style>

<!-- Hero -->
<div class="page-hero">
    <h1 class="hero-title"><i class="bi bi-pin-map-fill"></i> Konfigurasi Lokasi Kantor</h1>
    <p class="hero-sub">Kelola semua titik lokasi kantor & radius presensi</p>
</div>

<div class="container-fluid px-3 px-md-4 pb-5">

    {{-- Alert Messages --}}
    @if(Session::get('success'))
    <div class="alert-custom alert-success"><i class="bi bi-check-circle-fill"></i>{{ Session::get('success') }}</div>
    @endif
    @if(Session::get('warning'))
    <div class="alert-custom alert-warning"><i class="bi bi-exclamation-triangle-fill"></i>{{ Session::get('warning') }}</div>
    @endif
    @if(Session::get('error'))
    <div class="alert-custom alert-danger"><i class="bi bi-x-circle-fill"></i>{{ Session::get('error') }}</div>
    @endif

    <!-- ===== TABEL LOKASI ===== -->
    <div class="g-card">
        <div class="g-card-header">
            <span class="g-card-header-title">
                <i class="bi bi-geo-alt-fill"></i> Daftar Lokasi Kantor
                <span style="background:rgba(255,255,255,.25);padding:2px 10px;border-radius:20px;font-size:12px;">
                    {{ $lokasi_list->count() }} lokasi
                </span>
            </span>
            <button class="btn-add" onclick="openModal('modalTambah')">
                <i class="bi bi-plus-circle-fill"></i> Tambah Lokasi
            </button>
        </div>
        <div class="g-card-body" style="padding:0;">
            <div style="overflow-x:auto;">
                <table class="lokasi-table">
                    <thead>
                        <tr>
                            <th width="40">#</th>
                            <th>Nama Kantor</th>
                            <th class="hide-mobile">Koordinat</th>
                            <th>Radius</th>
                            <th>Tipe</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lokasi_list as $i => $lok)
                        <tr>
                            <td style="color:var(--muted);font-weight:600;">{{ $i + 1 }}</td>
                            <td>
                                <div style="font-weight:700;color:var(--text);">{{ $lok->nama_kantor ?? 'Kantor Pusat' }}</div>
                            </td>
                            <td class="hide-mobile">
                                <span class="koordinat-text">{{ $lok->lokasi_kantor }}</span>
                            </td>
                            <td>
                                <span class="radius-badge"><i class="bi bi-bullseye"></i> {{ $lok->radius }} m</span>
                            </td>
                            <td>
                                @if($lok->id == 1)
                                    <span class="badge-utama"><i class="bi bi-star-fill"></i> Utama</span>
                                @else
                                    <span class="badge-cabang"><i class="bi bi-building"></i> Cabang</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:6px;">
                                    <button class="btn-edit"
                                        onclick="openEdit({{ $lok->id }}, '{{ addslashes($lok->nama_kantor ?? 'Kantor Pusat') }}', '{{ $lok->lokasi_kantor }}', {{ $lok->radius }})">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    @if($lok->id != 1)
                                    <button class="btn-del" onclick="confirmDelete({{ $lok->id }}, '{{ addslashes($lok->nama_kantor) }}')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="empty-row">
                            <td colspan="6">
                                <i class="bi bi-geo-alt" style="font-size:32px;display:block;margin-bottom:8px;opacity:.3;"></i>
                                Belum ada lokasi kantor
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ===== INFO CARA PAKAI ===== -->
    <div class="g-card">
        <div class="g-card-header" style="background:linear-gradient(135deg,#06b6d4,#0891b2);">
            <span class="g-card-header-title"><i class="bi bi-info-circle-fill"></i> Cara Mendapatkan Koordinat</span>
        </div>
        <div class="g-card-body">
            <div class="info-tip">
                <i class="bi bi-lightbulb-fill" style="font-size:18px;flex-shrink:0;margin-top:1px;"></i>
                <div>Koordinat lokasi bisa didapatkan dari Google Maps. Format yang digunakan: <strong>Latitude, Longitude</strong> (contoh: <code>-7.250445, 112.768845</code>)</div>
            </div>
            <ol style="font-size:14px;color:var(--text);line-height:2;padding-left:18px;margin:0;">
                <li>Buka <a href="https://maps.google.com" target="_blank" style="color:var(--primary);font-weight:600;">Google Maps</a></li>
                <li>Cari atau navigasi ke lokasi kantor</li>
                <li>Klik kanan tepat di titik lokasi kantor</li>
                <li>Koordinat akan muncul di bagian atas menu konteks — klik untuk menyalin</li>
                <li>Paste koordinat tersebut ke form di atas</li>
            </ol>
        </div>
    </div>

</div>

<!-- ===================================================== -->
<!-- MODAL TAMBAH LOKASI -->
<!-- ===================================================== -->
<div class="modal-overlay" id="modalTambah">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modalTambah')"><i class="bi bi-x"></i></button>
        <div class="modal-title"><i class="bi bi-plus-circle-fill"></i> Tambah Lokasi Kantor</div>

        <form action="{{ url('/konfigurasi/storelokasi') }}" method="POST" id="formTambah">
            @csrf
            <div style="margin-bottom:18px;">
                <label class="f-label"><i class="bi bi-building"></i> Nama Kantor</label>
                <input type="text" name="nama_kantor" class="f-input" placeholder="Contoh: Kantor Bidang Cipta Karya" required>
                <div class="f-hint"><i class="bi bi-info-circle"></i> Nama unik untuk membedakan tiap lokasi</div>
            </div>
            <div style="margin-bottom:18px;">
                <label class="f-label"><i class="bi bi-geo-alt-fill"></i> Koordinat Lokasi</label>
                <input type="text" name="lokasi_kantor" class="f-input" placeholder="Contoh: -7.250445, 112.768845" required id="tambahKoordinat">
                <div class="f-hint"><i class="bi bi-lightbulb"></i> Format: Latitude, Longitude</div>
            </div>
            <div style="margin-bottom:26px;">
                <label class="f-label"><i class="bi bi-bullseye"></i> Radius (meter)</label>
                <input type="number" name="radius" class="f-input" placeholder="Contoh: 100" min="1" required>
                <div class="f-hint"><i class="bi bi-lightbulb"></i> Jarak maksimum karyawan dari lokasi agar dianggap hadir</div>
            </div>
            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <button type="button" class="btn-cancel" onclick="closeModal('modalTambah')">Batal</button>
                <button type="submit" class="btn-save"><i class="bi bi-check-circle-fill"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ===================================================== -->
<!-- MODAL EDIT LOKASI -->
<!-- ===================================================== -->
<div class="modal-overlay" id="modalEdit">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modalEdit')"><i class="bi bi-x"></i></button>
        <div class="modal-title"><i class="bi bi-pencil-fill"></i> Edit Lokasi Kantor</div>

        <form id="formEdit" method="POST">
            @csrf
            @method('POST')
            <div style="margin-bottom:18px;">
                <label class="f-label"><i class="bi bi-building"></i> Nama Kantor</label>
                <input type="text" name="nama_kantor" id="editNama" class="f-input" required>
            </div>
            <div style="margin-bottom:18px;">
                <label class="f-label"><i class="bi bi-geo-alt-fill"></i> Koordinat Lokasi</label>
                <input type="text" name="lokasi_kantor" id="editKoordinat" class="f-input" required>
                <div class="f-hint"><i class="bi bi-lightbulb"></i> Format: Latitude, Longitude</div>
            </div>
            <div style="margin-bottom:26px;">
                <label class="f-label"><i class="bi bi-bullseye"></i> Radius (meter)</label>
                <input type="number" name="radius" id="editRadius" class="f-input" min="1" required>
            </div>
            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <button type="button" class="btn-cancel" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn-save"><i class="bi bi-check-circle-fill"></i> Update</button>
            </div>
        </form>
    </div>
</div>

<!-- ===================================================== -->
<!-- MODAL KONFIRMASI DELETE -->
<!-- ===================================================== -->
<div class="modal-overlay" id="modalDelete">
    <div class="modal-box" style="max-width:420px;">
        <button class="modal-close" onclick="closeModal('modalDelete')"><i class="bi bi-x"></i></button>
        <div style="text-align:center;padding:10px 0 20px;">
            <div style="font-size:52px;margin-bottom:14px;">🗑️</div>
            <div style="font-size:18px;font-weight:800;color:var(--text);margin-bottom:8px;">Hapus Lokasi?</div>
            <div style="font-size:14px;color:var(--muted);margin-bottom:24px;">
                Lokasi <strong id="deleteNama"></strong> akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.
            </div>
        </div>
        <form id="formDelete" method="POST">
            @csrf
            {{-- HAPUS @method('DELETE') karena route pakai POST --}}
            <div style="display:flex;gap:12px;justify-content:center;">
                <button type="button" class="btn-cancel" onclick="closeModal('modalDelete')">Batal</button>
                <button type="submit" style="background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;border:none;padding:12px 28px;border-radius:9px;font-size:14px;font-weight:700;cursor:pointer;box-shadow:0 4px 14px rgba(239,68,68,.3);">
                    <i class="bi bi-trash-fill"></i> Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // ── Modal helpers ──
    function openModal(id) {
        document.getElementById(id).classList.add('show');
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
    }
    // Klik luar modal untuk tutup
    document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) overlay.classList.remove('show');
        });
    });

    // ── Buka modal edit & isi data ──
    function openEdit(id, nama, koordinat, radius) {
        document.getElementById('editNama').value     = nama;
        document.getElementById('editKoordinat').value = koordinat;
        document.getElementById('editRadius').value   = radius;
        document.getElementById('formEdit').action    = '/konfigurasi/updatelokasi/' + id;
        openModal('modalEdit');
    }

    // ── Konfirmasi delete ──
    function confirmDelete(id, nama) {
        document.getElementById('deleteNama').textContent = nama;
        document.getElementById('formDelete').action      = '/konfigurasi/deletelokasi/' + id;
        openModal('modalDelete');
    }

    // ── Validasi format koordinat ──
    function validasiKoordinat(input) {
        const pattern = /^-?\d+\.?\d*,\s*-?\d+\.?\d*$/;
        return pattern.test(input.trim());
    }

    document.getElementById('formTambah').addEventListener('submit', function(e) {
        const k = document.getElementById('tambahKoordinat').value;
        if (!validasiKoordinat(k)) {
            e.preventDefault();
            alert('Format koordinat tidak valid!\nGunakan format: Latitude, Longitude\nContoh: -7.250445, 112.768845');
        }
    });

    document.getElementById('formEdit').addEventListener('submit', function(e) {
        const k = document.getElementById('editKoordinat').value;
        if (!validasiKoordinat(k)) {
            e.preventDefault();
            alert('Format koordinat tidak valid!\nGunakan format: Latitude, Longitude\nContoh: -7.250445, 112.768845');
        }
    });
</script>

@endsection