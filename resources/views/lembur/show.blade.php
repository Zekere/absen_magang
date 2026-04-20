@extends('layout.presensi')

@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="{{ route('lembur.index') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Detail Lembur</div>
    <div class="right">
        <a href="{{ route('lembur.edit', $lembur->id) }}" class="headerButton">
            <ion-icon name="create-outline" style="font-size:20px"></ion-icon>
        </a>
    </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

* { box-sizing: border-box; }
body, .wrapper { background: #f0f4ff !important; font-family: 'Plus Jakarta Sans', sans-serif; }

.appHeader {
    background: linear-gradient(135deg, #3b6ff0, #667eea) !important;
    border-bottom: none;
    box-shadow: 0 2px 20px rgba(59,111,240,0.3) !important;
}
.appHeader .pageTitle {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700; font-size: 16px; color: #fff !important;
}

/* ─── Container ─── */
.detail-container {
    padding: 76px 16px 110px;
    max-width: 520px;
    margin: 0 auto;
}

/* ─── Hero Banner ─── */
.hero-banner {
    background: linear-gradient(135deg, #3b6ff0, #667eea);
    border-radius: 20px;
    padding: 24px 20px;
    margin-bottom: 16px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 6px 24px rgba(59,111,240,0.3);
}
.hero-banner::before {
    content: '';
    position: absolute;
    width: 160px; height: 160px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
    top: -50px; right: -40px;
}
.hero-banner::after {
    content: '';
    position: absolute;
    width: 100px; height: 100px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
    bottom: -30px; left: -20px;
}
.hero-banner-content { position: relative; z-index: 1; }
.hero-banner-icon {
    width: 56px; height: 56px;
    background: rgba(255,255,255,0.18);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
    backdrop-filter: blur(6px);
}
.hero-banner-icon ion-icon { font-size: 26px; color: #fff; }
.hero-banner-date {
    font-size: 22px;
    font-weight: 800;
    color: #fff;
    margin-bottom: 3px;
    line-height: 1.2;
}
.hero-banner-day {
    font-size: 13px;
    font-weight: 500;
    color: rgba(255,255,255,0.75);
    margin-bottom: 16px;
}
.hero-duration {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 50px;
    padding: 8px 16px;
    backdrop-filter: blur(6px);
}
.hero-duration ion-icon { font-size: 16px; color: rgba(255,255,255,0.85); }
.hero-duration-lbl { font-size: 11px; font-weight: 600; color: rgba(255,255,255,0.7); }
.hero-duration-val { font-size: 15px; font-weight: 800; color: #fff; }

/* ─── Info Card ─── */
.info-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid rgba(99,130,220,0.12);
    box-shadow: 0 2px 16px rgba(59,111,240,0.07);
    overflow: hidden;
    margin-bottom: 14px;
}
.info-card-header {
    padding: 13px 18px;
    border-bottom: 1px solid rgba(99,130,220,0.1);
    display: flex;
    align-items: center;
    gap: 10px;
}
.info-card-icon {
    width: 32px; height: 32px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.info-card-icon ion-icon { font-size: 15px; color: #fff; }
.ici-blue   { background: linear-gradient(135deg,#3b6ff0,#667eea); }
.ici-purple { background: linear-gradient(135deg,#8b5cf6,#7c3aed); }
.ici-green  { background: linear-gradient(135deg,#22c55e,#16a34a); }
.ici-amber  { background: linear-gradient(135deg,#f59e0b,#d97706); }
.ici-slate  { background: linear-gradient(135deg,#64748b,#475569); }
.info-card-title { font-size: 13px; font-weight: 700; color: #1e2a4a; }

.info-card-body { padding: 16px 18px; }

/* ─── Info Row ─── */
.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid rgba(99,130,220,0.08);
}
.info-row:last-child { border-bottom: none; padding-bottom: 0; }
.info-row:first-child { padding-top: 0; }
.info-row-lbl {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 6px;
}
.info-row-lbl ion-icon { font-size: 14px; color: #94a3b8; }
.info-row-val { font-size: 13px; font-weight: 700; color: #1e2a4a; text-align: right; }

/* jam pill */
.jam-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 800;
}
.jam-pill.masuk  { background: #dcfce7; color: #16a34a; }
.jam-pill.selesai{ background: #fee2e2; color: #dc2626; }

/* ─── Keterangan ─── */
.keterangan-box {
    background: #f8faff;
    border-radius: 12px;
    padding: 14px;
    border-left: 3px solid #3b6ff0;
    font-size: 13px;
    color: #374151;
    line-height: 1.6;
}

/* ─── Foto Bukti ─── */
.foto-wrap {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    cursor: pointer;
    border: 1px solid rgba(99,130,220,0.15);
}
.foto-wrap img {
    width: 100%;
    max-height: 240px;
    object-fit: cover;
    display: block;
    transition: transform .3s;
}
.foto-wrap:hover img { transform: scale(1.02); }
.foto-overlay {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.5));
    padding: 24px 14px 12px;
    display: flex;
    align-items: center;
    gap: 7px;
    color: rgba(255,255,255,0.9);
    font-size: 12px;
    font-weight: 600;
}
.foto-overlay ion-icon { font-size: 15px; }

/* ─── Meta info ─── */
.meta-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #94a3b8;
    padding: 4px 0;
}
.meta-row ion-icon { font-size: 14px; flex-shrink: 0; }

/* ─── Action Buttons ─── */
.action-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 4px;
}
.btn-act {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 13px;
    border-radius: 14px;
    font-size: 14px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    border: none;
    cursor: pointer;
    transition: all .2s;
    text-decoration: none;
    width: 100%;
}
.btn-act ion-icon { font-size: 17px; }
.btn-act:hover { transform: translateY(-2px); }
.btn-edit-act {
    background: #fefce8;
    color: #854d0e;
    border: 1.5px solid #fef08a;
}
.btn-edit-act:hover { background: #fef08a; color: #854d0e; }
.btn-del {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    box-shadow: 0 4px 14px rgba(220,38,38,0.25);
}
.btn-del:hover { box-shadow: 0 6px 20px rgba(220,38,38,0.35); color: #fff; }

/* ─── Delete Sheet ─── */
.del-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(2,6,23,0.5);
    z-index: 200;
    align-items: flex-end;
    justify-content: center;
    backdrop-filter: blur(4px);
}
.del-overlay.open { display: flex; animation: overlayIn .2s ease; }
@keyframes overlayIn { from{opacity:0}to{opacity:1} }
.del-sheet {
    background: #fff;
    border-radius: 24px 24px 0 0;
    padding: 0 20px 48px;
    width: 100%; max-width: 480px;
    animation: sheetUp .3s cubic-bezier(.32,.72,0,1);
}
@keyframes sheetUp { from{transform:translateY(100%)}to{transform:translateY(0)} }
.del-handle { width:36px;height:4px;background:rgba(0,0,0,0.1);border-radius:2px;margin:14px auto 20px; }
.del-icon-wrap {
    width:64px;height:64px;border-radius:50%;background:#fff1f2;
    display:flex;align-items:center;justify-content:center;margin:0 auto 14px;
}
.del-icon-wrap ion-icon { font-size:28px;color:#dc2626; }
.del-title { font-size:17px;font-weight:800;color:#1e2a4a;text-align:center;margin-bottom:6px; }
.del-desc  { font-size:13px;color:#64748b;text-align:center;margin-bottom:20px;line-height:1.5; }
.del-btn-row { display:grid;grid-template-columns:1fr 1fr;gap:10px; }
.del-cancel {
    padding:13px;border-radius:12px;border:1.5px solid rgba(99,130,220,0.2);
    background:#fff;font-size:14px;font-weight:700;color:#64748b;
    cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:all .2s;
}
.del-cancel:hover { background:#f8faff; }
.del-confirm {
    padding:13px;border-radius:12px;border:none;
    background:linear-gradient(135deg,#ef4444,#dc2626);
    font-size:14px;font-weight:700;color:#fff;
    cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;
    box-shadow:0 4px 14px rgba(220,38,38,0.3);transition:all .2s;
}
.del-confirm:hover { transform:translateY(-1px);box-shadow:0 6px 20px rgba(220,38,38,0.4); }
</style>
@endsection

@section('content')

{{-- Delete Sheet --}}
<div class="del-overlay" id="delOverlay" onclick="handleDelClose(event)">
    <div class="del-sheet">
        <div class="del-handle"></div>
        <div class="del-icon-wrap">
            <ion-icon name="trash-outline"></ion-icon>
        </div>
        <div class="del-title">Hapus Data Lembur?</div>
        <div class="del-desc">Data lembur tanggal <strong>{{ date('d M Y', strtotime($lembur->tanggal_lembur)) }}</strong> akan dihapus permanen dan tidak dapat dikembalikan.</div>
        <div class="del-btn-row">
            <button class="del-cancel" onclick="closeDelModal()">Batal</button>
            <button class="del-confirm" onclick="document.getElementById('delForm').submit()">Ya, Hapus</button>
        </div>
    </div>
</div>
<form id="delForm" action="{{ route('lembur.destroy', $lembur->id) }}" method="POST" style="display:none">
    @csrf
    @method('DELETE')
</form>

@php
    $jam   = floor($lembur->durasi_menit / 60);
    $menit = $lembur->durasi_menit % 60;
    $hariMap = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'];
    $bulanMap = ['January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April','May'=>'Mei','June'=>'Juni','July'=>'Juli','August'=>'Agustus','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'];
    $hariInd  = $hariMap[date('l', strtotime($lembur->tanggal_lembur))] ?? date('l', strtotime($lembur->tanggal_lembur));
    $bulanInd = $bulanMap[date('F', strtotime($lembur->tanggal_lembur))] ?? date('F', strtotime($lembur->tanggal_lembur));
@endphp

<div class="detail-container">

    {{-- ── Hero Banner ── --}}
    <div class="hero-banner">
        <div class="hero-banner-content">
            <div class="hero-banner-icon">
                <ion-icon name="briefcase-outline"></ion-icon>
            </div>
            <div class="hero-banner-date">
                {{ date('d', strtotime($lembur->tanggal_lembur)) }} {{ $bulanInd }} {{ date('Y', strtotime($lembur->tanggal_lembur)) }}
            </div>
            <div class="hero-banner-day">{{ $hariInd }}</div>
            <div class="hero-duration">
                <ion-icon name="hourglass-outline"></ion-icon>
                <div>
                    <div class="hero-duration-lbl">Total Durasi</div>
                    <div class="hero-duration-val">{{ $jam }} jam {{ $menit > 0 ? $menit.' menit' : '' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Jam Lembur ── --}}
    <div class="info-card">
        <div class="info-card-header">
            <div class="info-card-icon ici-purple">
                <ion-icon name="time-outline"></ion-icon>
            </div>
            <span class="info-card-title">Jam Lembur</span>
        </div>
        <div class="info-card-body">
            <div class="info-row">
                <div class="info-row-lbl">
                    <ion-icon name="log-in-outline"></ion-icon>
                    Jam Mulai
                </div>
                <div class="jam-pill masuk">
                    {{ date('H:i', strtotime($lembur->jam_mulai)) }} WIB
                </div>
            </div>
            <div class="info-row">
                <div class="info-row-lbl">
                    <ion-icon name="log-out-outline"></ion-icon>
                    Jam Selesai
                </div>
                <div class="jam-pill selesai">
                    {{ date('H:i', strtotime($lembur->jam_selesai)) }} WIB
                </div>
            </div>
            <div class="info-row">
                <div class="info-row-lbl">
                    <ion-icon name="hourglass-outline"></ion-icon>
                    Total Durasi
                </div>
                <div class="info-row-val">{{ $jam }} jam {{ $menit }} menit</div>
            </div>
        </div>
    </div>

    {{-- ── Keterangan ── --}}
    <div class="info-card">
        <div class="info-card-header">
            <div class="info-card-icon ici-green">
                <ion-icon name="document-text-outline"></ion-icon>
            </div>
            <span class="info-card-title">Keterangan</span>
        </div>
        <div class="info-card-body">
            <div class="keterangan-box">{{ $lembur->keterangan }}</div>
        </div>
    </div>

    {{-- ── Bukti Foto ── --}}
    @if($lembur->bukti_foto)
    <div class="info-card">
        <div class="info-card-header">
            <div class="info-card-icon ici-amber">
                <ion-icon name="image-outline"></ion-icon>
            </div>
            <span class="info-card-title">Bukti Foto</span>
        </div>
        <div class="info-card-body">
            <div class="foto-wrap"
                 onclick="window.open('{{ asset('storage/uploads/lembur/' . $lembur->bukti_foto) }}', '_blank')">
                <img src="{{ asset('storage/uploads/lembur/' . $lembur->bukti_foto) }}"
                     alt="Bukti Lembur">
                <div class="foto-overlay">
                    <ion-icon name="expand-outline"></ion-icon>
                    Tap untuk perbesar
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Info Tambahan ── --}}
    <div class="info-card">
        <div class="info-card-header">
            <div class="info-card-icon ici-slate">
                <ion-icon name="information-circle-outline"></ion-icon>
            </div>
            <span class="info-card-title">Informasi Tambahan</span>
        </div>
        <div class="info-card-body">
            <div class="info-row">
                <div class="info-row-lbl">
                    <ion-icon name="create-outline"></ion-icon>
                    Dibuat pada
                </div>
                <div class="info-row-val">{{ date('d M Y, H:i', strtotime($lembur->created_at)) }}</div>
            </div>
            @if($lembur->updated_at && $lembur->updated_at != $lembur->created_at)
            <div class="info-row">
                <div class="info-row-lbl">
                    <ion-icon name="refresh-outline"></ion-icon>
                    Diperbarui
                </div>
                <div class="info-row-val">{{ date('d M Y, H:i', strtotime($lembur->updated_at)) }}</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-row-lbl">
                    <ion-icon name="image-outline"></ion-icon>
                    Bukti Foto
                </div>
                <div class="info-row-val">
                    {{ $lembur->bukti_foto ? '✓ Ada' : '— Tidak ada' }}
                </div>
            </div>
        </div>
    </div>

    {{-- ── Action Buttons ── --}}
    <div class="action-grid">
        <a href="{{ route('lembur.edit', $lembur->id) }}" class="btn-act btn-edit-act">
            <ion-icon name="create-outline"></ion-icon>
            Edit
        </a>
        <button type="button" class="btn-act btn-del" onclick="openDelModal()">
            <ion-icon name="trash-outline"></ion-icon>
            Hapus
        </button>
    </div>

</div>

<script>
function openDelModal() {
    document.getElementById('delOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeDelModal() {
    document.getElementById('delOverlay').classList.remove('open');
    document.body.style.overflow = '';
}
function handleDelClose(e) {
    if (e.target === document.getElementById('delOverlay')) closeDelModal();
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDelModal(); });
</script>

@endsection