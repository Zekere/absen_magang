@extends('layout.presensi')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
* { box-sizing: border-box; }
body, .wrapper { background: #f0f4ff !important; font-family: 'Plus Jakarta Sans', sans-serif; }

/* ── Scrollbar ── */
::-webkit-scrollbar { width: 0; }

/* ─────────────────────────────────────
   HERO HEADER
───────────────────────────────────── */
.hero-header {
    position: relative;
    overflow: hidden;
    padding: 56px 20px 80px;
    transition: background 1.2s ease;
}

/* Time themes */
.hero-header.time-morning  { background: linear-gradient(160deg, #0ea5e9 0%, #38bdf8 40%, #7dd3fc 70%, #bae6fd 100%); }
.hero-header.time-noon     { background: linear-gradient(160deg, #0369a1 0%, #0284c7 40%, #38bdf8 100%); }
.hero-header.time-afternoon{ background: linear-gradient(160deg, #c2410c 0%, #ea580c 35%, #fb923c 65%, #fed7aa 100%); }
.hero-header.time-night    { background: linear-gradient(160deg, #020617 0%, #0f172a 40%, #1e293b 70%, #334155 100%); }

/* Decorative blobs */
.hero-blob {
    position: absolute;
    border-radius: 50%;
    opacity: 0.15;
    pointer-events: none;
}
.hero-blob-1 { width: 200px; height: 200px; top: -60px; right: -60px; background: rgba(255,255,255,0.6); }
.hero-blob-2 { width: 120px; height: 120px; bottom: 20px; left: -30px; background: rgba(255,255,255,0.4); }
.hero-blob-3 { width: 70px; height: 70px; top: 30px; left: 30%; background: rgba(255,255,255,0.3); }

/* Stars for night */
.hero-stars { position: absolute; inset: 0; pointer-events: none; }
.hero-star {
    position: absolute;
    width: 2px; height: 2px;
    background: #fff;
    border-radius: 50%;
    animation: starTwinkle 2.5s ease-in-out infinite;
}
@keyframes starTwinkle { 0%,100%{opacity:.2} 50%{opacity:1} }

/* Sun / Moon */
.hero-sun {
    position: absolute;
    top: 18px; right: 24px;
    width: 72px; height: 72px;
    border-radius: 50%;
    background: radial-gradient(circle, #fef08a 0%, #fde047 45%, #facc15 100%);
    box-shadow: 0 0 0 12px rgba(253,224,71,0.15), 0 0 0 24px rgba(253,224,71,0.08);
    animation: sunPulse 3s ease-in-out infinite;
}
@keyframes sunPulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.06)} }

.hero-moon {
    position: absolute;
    top: 18px; right: 24px;
    width: 56px; height: 56px;
    border-radius: 50%;
    background: radial-gradient(circle at 35% 35%, #f1f5f9, #cbd5e1);
    box-shadow: 0 0 20px rgba(203,213,225,0.3);
}
.hero-moon::after {
    content: '';
    position: absolute;
    top: 6px; left: 16px;
    width: 44px; height: 44px;
    border-radius: 50%;
    background: #1e293b;
    opacity: 0.85;
}

/* Clouds */
.hero-cloud {
    position: absolute;
    background: rgba(255,255,255,0.7);
    border-radius: 50px;
    animation: cloudFloat linear infinite;
}
.hero-cloud::before, .hero-cloud::after {
    content: '';
    position: absolute;
    background: rgba(255,255,255,0.7);
    border-radius: 50%;
}
.hero-cloud.c1 { width:70px;height:22px;top:15px;left:-70px;animation-duration:28s; }
.hero-cloud.c1::before{width:32px;height:32px;top:-16px;left:10px;}
.hero-cloud.c1::after{width:42px;height:38px;top:-20px;right:8px;}
.hero-cloud.c2 { width:55px;height:18px;top:50px;left:-55px;animation-duration:36s;animation-delay:7s; }
.hero-cloud.c2::before{width:26px;height:26px;top:-13px;left:8px;}
.hero-cloud.c2::after{width:34px;height:30px;top:-16px;right:6px;}
@keyframes cloudFloat { 0%{left:-100px} 100%{left:110%} }

/* Hero content */
.hero-content {
    position: relative;
    z-index: 10;
    display: flex;
    align-items: center;
    gap: 14px;
}
.hero-avatar-wrap {
    position: relative;
    flex-shrink: 0;
}
.hero-avatar {
    width: 62px; height: 62px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255,255,255,0.8);
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}
.hero-avatar-dot {
    position: absolute;
    bottom: 2px; right: 2px;
    width: 14px; height: 14px;
    border-radius: 50%;
    background: #22c55e;
    border: 2px solid #fff;
}
.hero-text { flex: 1; min-width: 0; }
.hero-greeting {
    font-size: 12px;
    font-weight: 600;
    color: rgba(255,255,255,0.8);
    letter-spacing: 0.4px;
    margin-bottom: 2px;
}
.hero-name {
    font-size: 18px;
    font-weight: 800;
    color: #fff;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    text-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
.hero-role {
    font-size: 12px;
    font-weight: 500;
    color: rgba(255,255,255,0.75);
    margin-top: 2px;
}
.hero-logout-btn {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.3);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    backdrop-filter: blur(6px);
    text-decoration: none;
    transition: all .2s;
}
.hero-logout-btn:hover { background: rgba(255,255,255,0.28); }
.hero-logout-btn ion-icon { font-size: 18px; }

/* ─────────────────────────────────────
   DATE & TIME PILL
───────────────────────────────────── */
.datetime-pill {
    position: relative;
    z-index: 10;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 50px;
    padding: 6px 14px;
    margin-top: 14px;
    font-size: 12px;
    font-weight: 600;
    color: rgba(255,255,255,0.95);
}
.datetime-pill ion-icon { font-size: 14px; }

/* ─────────────────────────────────────
   ABSEN CARD (floating overlap)
───────────────────────────────────── */
.page-body {
    padding: 0 16px 100px;
    max-width: 520px;
    margin: 0 auto;
    position: relative;
    top: -44px;
}

.absen-card {
    background: #fff;
    border-radius: 20px;
    padding: 18px;
    box-shadow: 0 8px 32px rgba(59,111,240,0.12);
    border: 1px solid rgba(99,130,220,0.1);
    margin-bottom: 16px;
}
.absen-card-title {
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.absen-card-title ion-icon { font-size: 13px; color: #3b6ff0; }

.absen-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.absen-box {
    border-radius: 14px;
    padding: 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: transform .2s;
    position: relative;
    overflow: hidden;
}
.absen-box:hover { transform: scale(1.02); }
.absen-box.masuk  { background: linear-gradient(135deg, #dcfce7, #bbf7d0); }
.absen-box.pulang { background: linear-gradient(135deg, #fee2e2, #fecaca); }
.absen-box-thumb {
    width: 48px; height: 48px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
    background: rgba(255,255,255,0.6);
    display: flex;
    align-items: center;
    justify-content: center;
}
.absen-box-thumb img { width: 100%; height: 100%; object-fit: cover; }
.absen-box-thumb ion-icon { font-size: 22px; color: #94a3b8; }
.absen-box-info { flex: 1; min-width: 0; }
.absen-box-label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
.absen-box-time  { font-size: 15px; font-weight: 800; color: #1e2a4a; margin-top: 2px; }
.absen-box-status {
    position: absolute;
    top: 8px; right: 10px;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 10px;
}
.absen-box.masuk  .absen-box-status { background: rgba(22,163,74,0.15); color: #16a34a; }
.absen-box.pulang .absen-box-status { background: rgba(220,38,38,0.15); color: #dc2626; }

/* ─────────────────────────────────────
   QUICK MENU
───────────────────────────────────── */
.qmenu-card {
    background: #fff;
    border-radius: 20px;
    padding: 18px;
    box-shadow: 0 4px 20px rgba(59,111,240,0.08);
    border: 1px solid rgba(99,130,220,0.1);
    margin-bottom: 16px;
}
.qmenu-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}
.qmenu-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    cursor: pointer;
}
.qmenu-icon {
    width: 52px; height: 52px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .25s;
}
.qmenu-icon ion-icon { font-size: 22px; color: #fff; }
.qmenu-item:hover .qmenu-icon { transform: scale(1.08); }
.qmenu-label { font-size: 11px; font-weight: 700; color: #475569; text-align: center; }

.qi-profile   { background: linear-gradient(135deg, #3b6ff0, #667eea); }
.qi-cuti      { background: linear-gradient(135deg, #22c55e, #16a34a); }
.qi-histori   { background: linear-gradient(135deg, #f59e0b, #d97706); }
.qi-kamera    { background: linear-gradient(135deg, #ec4899, #db2777); }
.qi-lembur    { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.qi-logout    { background: linear-gradient(135deg, #ef4444, #dc2626); }

/* extra row for 6 items → 3+3 */
.qmenu-grid-2 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid rgba(99,130,220,0.1);
}

/* ─────────────────────────────────────
   SECTION TITLE
───────────────────────────────────── */
.sec-hd {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.sec-hd-title { font-size: 14px; font-weight: 800; color: #1e2a4a; }
.sec-hd-link  { font-size: 12px; font-weight: 600; color: #3b6ff0; text-decoration: none; }

/* ─────────────────────────────────────
   REKAP STRIP
───────────────────────────────────── */
.rekap-strip {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 4px;
    margin-bottom: 16px;
    scrollbar-width: none;
}
.rekap-strip::-webkit-scrollbar { display: none; }

.rekap-chip {
    flex-shrink: 0;
    background: #fff;
    border-radius: 14px;
    padding: 12px 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    border: 1px solid rgba(99,130,220,0.12);
    box-shadow: 0 2px 10px rgba(59,111,240,0.06);
    min-width: 72px;
}
.rekap-chip-num {
    font-size: 22px;
    font-weight: 800;
    line-height: 1;
}
.rekap-chip-lbl {
    font-size: 10px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.rnum-hadir    { color: #2563eb; }
.rnum-izin     { color: #16a34a; }
.rnum-sakit    { color: #d97706; }
.rnum-cuti     { color: #7c3aed; }
.rnum-terlambat{ color: #dc2626; }

/* ─────────────────────────────────────
   TAB
───────────────────────────────────── */
.tab-bar {
    display: flex;
    background: #eef2ff;
    border-radius: 12px;
    padding: 4px;
    margin-bottom: 14px;
    gap: 4px;
}
.tab-btn {
    flex: 1;
    padding: 9px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    border: none;
    background: transparent;
    color: #64748b;
    cursor: pointer;
    transition: all .2s;
}
.tab-btn.active {
    background: #fff;
    color: #3b6ff0;
    box-shadow: 0 2px 8px rgba(59,111,240,0.12);
}

/* ─────────────────────────────────────
   HISTORY LIST
───────────────────────────────────── */
.hist-list { display: flex; flex-direction: column; gap: 10px; }

.hist-item {
    background: #fff;
    border-radius: 14px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    border: 1px solid rgba(99,130,220,0.1);
    box-shadow: 0 2px 8px rgba(59,111,240,0.05);
    cursor: pointer;
    transition: all .2s;
    animation: fadeSlide .3s ease both;
}
.hist-item:hover { transform: translateX(4px); box-shadow: 0 4px 16px rgba(59,111,240,0.1); }

@keyframes fadeSlide {
    from { opacity:0; transform:translateY(8px); }
    to   { opacity:1; transform:translateY(0); }
}
.hist-item:nth-child(1){animation-delay:.05s}
.hist-item:nth-child(2){animation-delay:.08s}
.hist-item:nth-child(3){animation-delay:.11s}
.hist-item:nth-child(4){animation-delay:.14s}
.hist-item:nth-child(5){animation-delay:.17s}

.hist-thumb {
    width: 52px; height: 52px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
    background: #f0f4ff;
}
.hist-thumb img { width: 100%; height: 100%; object-fit: cover; }

.hist-info { flex: 1; min-width: 0; }
.hist-date { font-size: 13px; font-weight: 700; color: #1e2a4a; margin-bottom: 4px; }
.hist-status { font-size: 11px; font-weight: 600; }
.hist-status.hadir    { color: #16a34a; }
.hist-status.terlambat{ color: #dc2626; }

.hist-times { display: flex; flex-direction: column; gap: 5px; align-items: flex-end; }
.time-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
.time-pill ion-icon { font-size: 11px; }
.pill-in  { background: #dcfce7; color: #16a34a; }
.pill-out { background: #fee2e2; color: #dc2626; }
.pill-pending { background: #fef9c3; color: #b45309; }

/* ─────────────────────────────────────
   LEADERBOARD
───────────────────────────────────── */
.lb-list { display: flex; flex-direction: column; gap: 10px; }
.lb-item {
    background: #fff;
    border-radius: 14px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    border: 1px solid rgba(99,130,220,0.1);
    box-shadow: 0 2px 8px rgba(59,111,240,0.05);
    animation: fadeSlide .3s ease both;
}
.lb-rank {
    width: 28px; height: 28px;
    border-radius: 50%;
    font-size: 13px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.lb-rank.r1 { background: #fef08a; color: #854d0e; }
.lb-rank.r2 { background: #e2e8f0; color: #475569; }
.lb-rank.r3 { background: #fed7aa; color: #9a3412; }
.lb-rank.rn { background: #f1f5f9; color: #94a3b8; font-size: 11px; }

.lb-avatar {
    width: 44px; height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(99,130,220,0.2);
    flex-shrink: 0;
}
.lb-info { flex: 1; min-width: 0; }
.lb-name { font-size: 13px; font-weight: 700; color: #1e2a4a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.lb-jabatan { font-size: 11px; color: #64748b; margin-top: 1px; }
.lb-time-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 10px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 800;
    flex-shrink: 0;
}

/* ─────────────────────────────────────
   LIGHTBOX
───────────────────────────────────── */
.lb-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(2,6,23,0.92);
    z-index: 9999;
    align-items: flex-end;
    justify-content: center;
    backdrop-filter: blur(6px);
}
.lb-overlay.open { display: flex; animation: overlayIn .25s ease; }
@keyframes overlayIn { from{opacity:0} to{opacity:1} }

.lb-sheet {
    background: #0f172a;
    border-radius: 24px 24px 0 0;
    padding: 0 20px 48px;
    width: 100%;
    max-width: 480px;
    animation: sheetUp .3s cubic-bezier(.32,.72,0,1);
}
@keyframes sheetUp { from{transform:translateY(100%)} to{transform:translateY(0)} }

.lb-sheet-handle {
    width: 36px; height: 4px;
    background: rgba(255,255,255,0.15);
    border-radius: 2px;
    margin: 14px auto 20px;
}
.lb-photo {
    width: 100%;
    border-radius: 16px;
    max-height: 260px;
    object-fit: cover;
    margin-bottom: 16px;
}
.lb-info-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 12px;
}
.lb-info-block {
    background: rgba(255,255,255,0.07);
    border-radius: 12px;
    padding: 14px;
    text-align: center;
}
.lb-info-block-lbl { font-size: 10px; font-weight: 700; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
.lb-info-block-val { font-size: 20px; font-weight: 800; color: #fff; }
.lb-title { font-size: 16px; font-weight: 800; color: #fff; text-align: center; margin-bottom: 4px; }
.lb-date-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(59,111,240,0.3);
    border: 1px solid rgba(59,111,240,0.4);
    border-radius: 20px;
    padding: 4px 14px;
    font-size: 12px;
    font-weight: 600;
    color: #93c5fd;
    margin: 0 auto 16px;
    display: block;
    text-align: center;
    width: fit-content;
}

/* ─────────────────────────────────────
   SOUNDS
───────────────────────────────────── */
</style>

<audio id="clickSound"  src="https://assets.mixkit.co/sfx/preview/mixkit-select-click-1109.mp3"   preload="none"></audio>
<audio id="logoutSound" src="https://assets.mixkit.co/sfx/preview/mixkit-door-lock-click-1126.mp3" preload="none"></audio>

{{-- ════════════════════════════════════
     LIGHTBOX SHEET
════════════════════════════════════ --}}
<div id="lbOverlay" class="lb-overlay" onclick="handleLbClose(event)">
    <div class="lb-sheet">
        <div class="lb-sheet-handle"></div>
        <img id="lbPhoto" src="" alt="" class="lb-photo">
        <div class="lb-title" id="lbTitle">-</div>
        <div class="lb-date-pill" id="lbDate">-</div>
        <div class="lb-info-row">
            <div class="lb-info-block">
                <div class="lb-info-block-lbl">Jam Masuk</div>
                <div class="lb-info-block-val" id="lbIn">-</div>
            </div>
            <div class="lb-info-block">
                <div class="lb-info-block-lbl">Jam Pulang</div>
                <div class="lb-info-block-val" id="lbOut">-</div>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════
     HERO HEADER
════════════════════════════════════ --}}
<div class="hero-header" id="heroHeader">
    <div class="hero-blob hero-blob-1"></div>
    <div class="hero-blob hero-blob-2"></div>
    <div class="hero-blob hero-blob-3"></div>

    <div class="hero-content">
        <div class="hero-avatar-wrap">
            @if(!empty(Auth::guard('karyawan')->user()->foto))
                @php $fotoPath = Storage::url('uploads/karyawan/' . Auth::guard('karyawan')->user()->foto); @endphp
                <img src="{{ url($fotoPath) }}" class="hero-avatar" alt="avatar">
            @else
                <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" class="hero-avatar" alt="avatar">
            @endif
            <div class="hero-avatar-dot"></div>
        </div>
        <div class="hero-text">
            <div class="hero-greeting" id="heroGreeting">Selamat datang 👋</div>
            <div class="hero-name">{{ Auth::guard('karyawan')->user()->nama_lengkap }}</div>
            <div class="hero-role">{{ Auth::guard('karyawan')->user()->jabatan }}</div>
        </div>
        <a href="/logout" class="hero-logout-btn" onclick="return confirmLogout(event)" title="Keluar">
            <ion-icon name="log-out-outline"></ion-icon>
        </a>
    </div>

    <div class="datetime-pill" id="datetimePill">
        <ion-icon name="calendar-outline"></ion-icon>
        <span id="dateText">-</span>
        <span style="opacity:.5">·</span>
        <ion-icon name="time-outline"></ion-icon>
        <span id="timeText">-</span>
    </div>
</div>

{{-- ════════════════════════════════════
     PAGE BODY
════════════════════════════════════ --}}
<div class="page-body">

    {{-- ── Absen Hari Ini ── --}}
    <div class="absen-card">
        <div class="absen-card-title">
            <ion-icon name="today-outline"></ion-icon>
            Absensi Hari Ini
        </div>
        <div class="absen-row">
            {{-- MASUK --}}
            <div class="absen-box masuk"
                @if($presensihariini != null)
                    onclick="openLb(
                        '{{ url(Storage::url('uploads/absensi/'.$presensihariini->foto_in)) }}',
                        'Foto Masuk',
                        '{{ date('d M Y', strtotime($presensihariini->tgl_presensi)) }}',
                        '{{ $presensihariini->jam_in }}',
                        '{{ $presensihariini->jam_out ?? '' }}'
                    )"
                @endif>
                <div class="absen-box-thumb">
                    @if($presensihariini != null)
                        <img src="{{ url(Storage::url('uploads/absensi/'.$presensihariini->foto_in)) }}" alt="foto masuk">
                    @else
                        <ion-icon name="camera-outline"></ion-icon>
                    @endif
                </div>
                <div class="absen-box-info">
                    <div class="absen-box-label">Masuk</div>
                    <div class="absen-box-time">{{ $presensihariini != null ? $presensihariini->jam_in : '-- : --' }}</div>
                </div>
                @if($presensihariini != null)
                    <div class="absen-box-status">✓ Hadir</div>
                @endif
            </div>

            {{-- PULANG --}}
            <div class="absen-box pulang"
                @if($presensihariini != null && $presensihariini->foto_out)
                    onclick="openLb(
                        '{{ url(Storage::url('uploads/absensi/'.$presensihariini->foto_out)) }}',
                        'Foto Pulang',
                        '{{ date('d M Y', strtotime($presensihariini->tgl_presensi)) }}',
                        '{{ $presensihariini->jam_in }}',
                        '{{ $presensihariini->jam_out }}'
                    )"
                @endif>
                <div class="absen-box-thumb">
                    @if($presensihariini != null && $presensihariini->foto_out)
                        <img src="{{ url(Storage::url('uploads/absensi/'.$presensihariini->foto_out)) }}" alt="foto pulang">
                    @else
                        <ion-icon name="camera-outline"></ion-icon>
                    @endif
                </div>
                <div class="absen-box-info">
                    <div class="absen-box-label">Pulang</div>
                    <div class="absen-box-time">
                        {{ ($presensihariini != null && $presensihariini->jam_out) ? $presensihariini->jam_out : '-- : --' }}
                    </div>
                </div>
                @if($presensihariini != null && $presensihariini->jam_out)
                    <div class="absen-box-status">✓ Pulang</div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Quick Menu ── --}}
    <div class="qmenu-card">
        <div class="qmenu-grid">
            <a href="/presensi/editprofile" class="qmenu-item">
                <div class="qmenu-icon qi-profile"><ion-icon name="person-outline"></ion-icon></div>
                <span class="qmenu-label">Profil</span>
            </a>
            <a href="/presensi/izin" class="qmenu-item">
                <div class="qmenu-icon qi-cuti"><ion-icon name="calendar-outline"></ion-icon></div>
                <span class="qmenu-label">Cuti</span>
            </a>
            <a href="/presensi/histori" class="qmenu-item">
                <div class="qmenu-icon qi-histori"><ion-icon name="document-text-outline"></ion-icon></div>
                <span class="qmenu-label">Histori</span>
            </a>
            <a href="/presensi/create" class="qmenu-item">
                <div class="qmenu-icon qi-kamera"><ion-icon name="camera-outline"></ion-icon></div>
                <span class="qmenu-label">Kamera</span>
            </a>
        </div>
        <div class="qmenu-grid-2">
            <a href="/lembur" class="qmenu-item">
                <div class="qmenu-icon qi-lembur"><ion-icon name="time-outline"></ion-icon></div>
                <span class="qmenu-label">Lembur</span>
            </a>
            <a href="#" class="qmenu-item" onclick="return confirmLogout(event)">
                <div class="qmenu-icon qi-logout"><ion-icon name="log-out-outline"></ion-icon></div>
                <span class="qmenu-label">Keluar</span>
            </a>
        </div>
    </div>

    {{-- ── Rekap Bulan Ini ── --}}
    <div class="sec-hd">
        <span class="sec-hd-title">Rekap — {{ $namabulan[(int)$bulanini] ?? '' }} {{ $tahunini }}</span>
    </div>
    <div class="rekap-strip">
        <div class="rekap-chip">
            <div class="rekap-chip-num rnum-hadir">{{ $rekappresensi->jmlhadir ?? 0 }}</div>
            <div class="rekap-chip-lbl">Hadir</div>
        </div>
        <div class="rekap-chip">
            <div class="rekap-chip-num rnum-terlambat">{{ $rekappresensi->jmlterlambat ?? 0 }}</div>
            <div class="rekap-chip-lbl">Terlambat</div>
        </div>
        <div class="rekap-chip">
            <div class="rekap-chip-num rnum-izin">{{ $rekapizin->jmlizin ?? 0 }}</div>
            <div class="rekap-chip-lbl">Izin</div>
        </div>
        <div class="rekap-chip">
            <div class="rekap-chip-num rnum-sakit">{{ $rekapizin->jmlsakit ?? 0 }}</div>
            <div class="rekap-chip-lbl">Sakit</div>
        </div>
        <div class="rekap-chip">
            <div class="rekap-chip-num rnum-cuti">{{ $rekapizin->jmlcuti ?? 0 }}</div>
            <div class="rekap-chip-lbl">Cuti</div>
        </div>
    </div>

    {{-- ── Tabs ── --}}
    <div class="tab-bar">
        <button class="tab-btn active" id="tabBtnHistory" onclick="switchTab('history')">
            Riwayat Bulan Ini
        </button>
        <button class="tab-btn" id="tabBtnLeaderboard" onclick="switchTab('leaderboard')">
            Leaderboard
        </button>
    </div>

    {{-- Tab: History --}}
    <div id="tabHistory">
        <div class="hist-list">
            @forelse($histroribulanini as $d)
                @php
                    $fotoPath = url(Storage::url('uploads/absensi/'.$d->foto_in));
                    $tgl      = date('d M Y', strtotime($d->tgl_presensi));
                    $hariNama = date('l', strtotime($d->tgl_presensi));
                    $hariMap  = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'];
                    $hariInd  = $hariMap[$hariNama] ?? $hariNama;
                    $jamOut   = $d->jam_out ?? '';
                @endphp
                <div class="hist-item"
                     onclick="openLb('{{ $fotoPath }}','Presensi {{ $tgl }}','{{ $tgl }}','{{ $d->jam_in }}','{{ $jamOut }}')">
                    <div class="hist-thumb">
                        <img src="{{ $fotoPath }}" alt="foto" loading="lazy">
                    </div>
                    <div class="hist-info">
                        <div class="hist-date">{{ $hariInd }}, {{ $tgl }}</div>
                        <div class="hist-status {{ strtolower($d->status ?? 'hadir') }}">
                            {{ ucfirst($d->status ?? 'Hadir') }}
                        </div>
                    </div>
                    <div class="hist-times">
                        <div class="time-pill pill-in">
                            <ion-icon name="log-in-outline"></ion-icon>
                            {{ $d->jam_in }}
                        </div>
                        @if($d->jam_out)
                            <div class="time-pill pill-out">
                                <ion-icon name="log-out-outline"></ion-icon>
                                {{ $d->jam_out }}
                            </div>
                        @else
                            <div class="time-pill pill-pending">
                                <ion-icon name="time-outline"></ion-icon>
                                Belum
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align:center;padding:40px 20px;color:#94a3b8;">
                    <ion-icon name="calendar-outline" style="font-size:40px;display:block;margin:0 auto 10px"></ion-icon>
                    <div style="font-size:14px;font-weight:600">Belum ada presensi bulan ini</div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Tab: Leaderboard --}}
    <div id="tabLeaderboard" style="display:none">
        <div class="lb-list">
            @foreach($leaderboard as $i => $d)
                @php
                    $rankClass = match($i) { 0=>'r1', 1=>'r2', 2=>'r3', default=>'rn' };
                    $isOnTime  = $d->jam_in < '07:30';
                    $lbFoto    = !empty($d->foto)
                        ? url(Storage::url('uploads/karyawan/'.$d->foto))
                        : asset('assets/img/sample/avatar/avatar1.jpg');
                @endphp
                <div class="lb-item" style="animation-delay:{{ $i * 0.06 }}s">
                    <div class="lb-rank {{ $rankClass }}">{{ $i + 1 }}</div>
                    <img src="{{ $lbFoto }}" class="lb-avatar" alt="foto">
                    <div class="lb-info">
                        <div class="lb-name">{{ $d->nama_lengkap }}</div>
                        <div class="lb-jabatan">{{ $d->jabatan }}</div>
                    </div>
                    <div class="lb-time-pill {{ $isOnTime ? 'pill-in' : 'pill-out' }}">
                        <ion-icon name="{{ $isOnTime ? 'checkmark-circle-outline' : 'alert-circle-outline' }}" style="font-size:13px"></ion-icon>
                        {{ $d->jam_in }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>{{-- /page-body --}}

<script>
/* ── Time-based hero ── */
(function () {
    const el     = document.getElementById('heroHeader');
    const greet  = document.getElementById('heroGreeting');
    const h      = new Date().getHours();

    el.classList.remove('time-morning','time-noon','time-afternoon','time-night');

    // Remove old weather
    ['hero-sun','hero-moon','hero-stars','hero-cloud'].forEach(cls => {
        el.querySelectorAll('.'+cls).forEach(n => n.remove());
    });

    if (h >= 5 && h < 11) {
        el.classList.add('time-morning');
        greet.textContent = 'Selamat Pagi 🌤️';
        el.insertAdjacentHTML('afterbegin',
            '<div class="hero-cloud c1"></div><div class="hero-cloud c2"></div>');
    } else if (h >= 11 && h < 15) {
        el.classList.add('time-noon');
        greet.textContent = 'Selamat Siang ☀️';
        const sun = document.createElement('div');
        sun.className = 'hero-sun';
        el.prepend(sun);
    } else if (h >= 15 && h < 18) {
        el.classList.add('time-afternoon');
        greet.textContent = 'Selamat Sore 🌅';
        el.insertAdjacentHTML('afterbegin','<div class="hero-cloud c1"></div>');
    } else {
        el.classList.add('time-night');
        greet.textContent = 'Selamat Malam 🌙';
        const moon = document.createElement('div');
        moon.className = 'hero-moon';
        el.prepend(moon);
        const stars = document.createElement('div');
        stars.className = 'hero-stars';
        for (let i = 0; i < 40; i++) {
            const s = document.createElement('div');
            s.className = 'hero-star';
            s.style.cssText = `left:${Math.random()*100}%;top:${Math.random()*100}%;animation-delay:${Math.random()*3}s`;
            stars.appendChild(s);
        }
        el.prepend(stars);
    }
})();

/* ── Live clock ── */
const HARI  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
const BULAN = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
function tick() {
    const now = new Date();
    document.getElementById('dateText').textContent =
        HARI[now.getDay()] + ', ' + now.getDate() + ' ' + BULAN[now.getMonth()] + ' ' + now.getFullYear();
    document.getElementById('timeText').textContent =
        String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');
}
tick();
setInterval(tick, 30000);

/* ── Lightbox ── */
function openLb(src, title, date, jamIn, jamOut) {
    document.getElementById('lbPhoto').src = src;
    document.getElementById('lbTitle').textContent = title;
    document.getElementById('lbDate').textContent  = date;
    document.getElementById('lbIn').textContent    = jamIn  || '-';
    document.getElementById('lbOut').textContent   = jamOut || 'Belum Absen';
    document.getElementById('lbOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function handleLbClose(e) {
    if (e.target === document.getElementById('lbOverlay')) closeLb();
}
function closeLb() {
    document.getElementById('lbOverlay').classList.remove('open');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLb(); });

/* ── Tab switch ── */
function switchTab(tab) {
    const isHist = tab === 'history';
    document.getElementById('tabHistory').style.display      = isHist ? 'block' : 'none';
    document.getElementById('tabLeaderboard').style.display  = isHist ? 'none'  : 'block';
    document.getElementById('tabBtnHistory').classList.toggle('active', isHist);
    document.getElementById('tabBtnLeaderboard').classList.toggle('active', !isHist);
}

/* ── Logout confirm ── */
function confirmLogout(event) {
    event.preventDefault();
    document.getElementById('clickSound').play();
    Swal.fire({
        title: 'Yakin ingin keluar?',
        text: 'Anda akan mengakhiri sesi ini',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3b6ff0',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Ya, Keluar!',
        cancelButtonText: 'Batal',
        showClass: { popup: 'animate__animated animate__zoomIn animate__faster' },
        hideClass: { popup: 'animate__animated animate__zoomOut animate__faster' }
    }).then(r => {
        if (r.isConfirmed) {
            document.getElementById('logoutSound').play();
            setTimeout(() => { window.location.href = '/logout'; }, 400);
        }
    });
    return false;
}
</script>

@endsection