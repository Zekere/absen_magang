@extends('layout.presensi')

@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">E-Presensi</div>
    <div class="right"></div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --bg: #f0f4ff;
        --surface: #ffffff;
        --border: rgba(99,130,220,0.15);
        --accent: #3b6ff0;
        --text: #1e2a4a;
        --text-muted: #64748b;
        --radius: 16px;
        --radius-sm: 10px;
        --success: #16a34a; --success-bg: #f0fdf4; --success-border: #bbf7d0;
        --warning: #b45309; --warning-bg: #fffbeb; --warning-border: #fde68a;
        --danger:  #dc2626; --danger-bg:  #fff1f2; --danger-border:  #fecdd3;
        --info:    #1d4ed8; --info-bg:    #eff6ff; --info-border:    #bfdbfe;
    }

    * { box-sizing: border-box; }
    body, .wrapper { background: var(--bg) !important; font-family: 'Plus Jakarta Sans', sans-serif; }

    .appHeader {
        background: linear-gradient(135deg, #3b6ff0, #667eea) !important;
        border-bottom: none;
        box-shadow: 0 2px 20px rgba(59,111,240,0.3) !important;
    }
    .appHeader .pageTitle { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 16px; color: #ffffff !important; }

    .container { padding-top: 75px; padding-bottom: 100px; max-width: 520px; margin: 0 auto; }

    /* ─── Leaflet controls ─────────────────────── */
    .leaflet-control-zoom, .leaflet-control-attribution, .leaflet-control { display: none !important; }

    /* ─── Webcam wrap ──────────────────────────── */
    .webcam-wrap {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        background: var(--surface);
        border: 2px solid rgba(59,111,240,0.2);
        box-shadow: 0 8px 40px rgba(59,111,240,0.15);
        aspect-ratio: 4/3;
    }
    .webcam-wrap video, .webcam-wrap canvas {
        display: block; width: 100% !important; height: 100% !important;
        object-fit: cover; border-radius: 20px;
    }
    #face-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 10; border-radius: 20px; }
    #face-status {
        position: absolute; top: 12px; left: 50%; transform: translateX(-50%);
        display: flex; align-items: center; gap: 7px;
        padding: 8px 16px; border-radius: 100px;
        font-size: 12px; font-weight: 600;
        z-index: 20; white-space: nowrap; backdrop-filter: blur(12px);
        transition: all .3s; border: 1px solid rgba(255,255,255,0.12);
    }
    .face-loading      { background: rgba(255,255,255,0.92); color: #64748b; }
    .face-detecting    { background: rgba(59,111,240,0.1);   color: #3b6ff0; border-color: rgba(59,111,240,0.35); }
    .face-verified     { background: rgba(22,163,74,0.1);    color: #16a34a; border-color: rgba(22,163,74,0.35); }
    .face-not-verified { background: rgba(220,38,38,0.1);    color: #dc2626; border-color: rgba(220,38,38,0.35); }
    .webcam-wrap::after {
        content: ''; position: absolute; top: -100%; left: 0; width: 100%; height: 2px;
        background: linear-gradient(90deg, transparent, rgba(59,111,240,0.6), transparent);
        animation: scanline 3s ease-in-out infinite; z-index: 5; opacity: 0.7;
    }
    @keyframes scanline { 0% { top: -2px; } 100% { top: 100%; } }

    /* ─── Info Panel Jam Kerja ─────────────────── */
    .jk-wrap { margin: 16px 0; }

    .jk-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        margin-bottom: 10px;
        box-shadow: 0 2px 12px rgba(59,111,240,0.06);
    }

    .jk-head {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 16px;
        border-bottom: 1px solid var(--border);
    }
    .jk-head-icon {
        width: 32px; height: 32px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: rgba(59,111,240,0.1);
    }
    .jk-head-title { font-size: 14px; font-weight: 700; color: var(--text); }
    .jk-head-sub   { font-size: 11px; color: var(--text-muted); margin-top: 1px; }

    .jk-badge {
        display: inline-flex; align-items: center;
        padding: 3px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 700; margin-left: auto; flex-shrink: 0;
    }
    .jk-badge-ok     { background: var(--success-bg); color: var(--success); border: 1px solid var(--success-border); }
    .jk-badge-warn   { background: var(--warning-bg); color: var(--warning); border: 1px solid var(--warning-border); }
    .jk-badge-danger { background: var(--danger-bg);  color: var(--danger);  border: 1px solid var(--danger-border);  }
    .jk-badge-info   { background: var(--info-bg);    color: var(--info);    border: 1px solid var(--info-border);    }

    .jk-body { padding: 0 16px; }
    .jk-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 9px 0; border-bottom: 1px solid rgba(99,130,220,0.08);
    }
    .jk-row:last-child { border-bottom: none; }
    .jk-lbl { font-size: 12px; color: var(--text-muted); }
    .jk-val { font-size: 12px; font-weight: 700; color: var(--text); text-align: right; max-width: 60%; }

    .jk-status-bar { display: flex; align-items: flex-start; gap: 10px; padding: 13px 16px; }
    .jk-sdot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; margin-top: 3px; }
    .jk-smain { font-size: 13px; font-weight: 700; color: var(--text); }
    .jk-sdesc { font-size: 12px; color: var(--text-muted); margin-top: 2px; line-height: 1.5; }

    .jk-tl { padding: 10px 16px 14px; }
    .jk-tl-lbl { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--text-muted); margin-bottom: 8px; }
    .jk-track { height: 5px; background: rgba(99,130,220,0.15); border-radius: 3px; margin-bottom: 6px; }
    .jk-fill  { height: 100%; border-radius: 3px; }
    .jk-tl-labels { display: flex; justify-content: space-between; }
    .jk-tl-labels span { font-size: 10px; color: var(--text-muted); font-variant-numeric: tabular-nums; }

    .jk-ticks { display: flex; flex-wrap: wrap; gap: 6px 14px; padding: 8px 16px 14px; }
    .jk-tick  { display: flex; align-items: center; gap: 5px; font-size: 11px; color: var(--text-muted); }
    .jk-tdot  { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
    .dot-blue  { background: #378ADD; }
    .dot-green { background: #639922; }
    .dot-amber { background: #BA7517; }
    .dot-red   { background: #E24B4A; }

    /* ─── Location status ──────────────────────── */
    #location-status {
        display: none; align-items: center; gap: 8px;
        padding: 11px 16px; border-radius: var(--radius-sm);
        font-size: 13px; font-weight: 600; margin: 12px 0;
    }
    #location-status.status-dalam-kantor { background: var(--success-bg); color: var(--success); border: 1px solid var(--success-border); }
    #location-status.status-luar-kantor  { background: var(--warning-bg); color: var(--warning); border: 1px solid var(--warning-border); }

    /* ─── Absen button ─────────────────────────── */
    .absen-btn-wrap { margin: 20px 0 16px; }
    #takeabsen {
        width: 100%; padding: 15px 24px; border-radius: var(--radius);
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; font-weight: 700;
        border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        transition: all .2s;
    }
    #takeabsen.btn-success { background: linear-gradient(135deg, #22c55e, #16a34a); color: white; box-shadow: 0 4px 20px rgba(34,197,94,0.3); }
    #takeabsen.btn-danger  { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; box-shadow: 0 4px 20px rgba(239,68,68,0.3); }
    #takeabsen:not(:disabled):hover { transform: translateY(-1px); filter: brightness(1.1); }
    #takeabsen:disabled { opacity: .45; cursor: not-allowed; }

    #button-hint { text-align: center; font-size: 12px; color: var(--text-muted); margin-top: 8px; font-weight: 500; }
    .done-badge {
        width: 100%; padding: 15px 24px; border-radius: var(--radius);
        background: #f8faff; color: var(--text-muted); font-size: 14px; font-weight: 600;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        border: 1px solid var(--border);
    }

    /* ─── Map ──────────────────────────────────── */
    #map {
        width: 100%; height: 240px; border-radius: var(--radius);
        overflow: hidden; border: 1px solid var(--border);
        margin-top: 8px; box-shadow: 0 4px 20px rgba(59,111,240,0.1);
    }

    .section-label { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin: 20px 0 8px; }
    .spinner { display: inline-block; width: 15px; height: 15px; border: 2px solid rgba(255,255,255,0.25); border-radius: 50%; border-top-color: white; animation: spin .7s linear infinite; flex-shrink: 0; }
    @keyframes spin { to { transform: rotate(360deg); } }

    #reference-face { display: none; }

    /* ─── Setup Wajah Screen ───────────────────── */
    .setup-screen { display: flex; flex-direction: column; align-items: center; gap: 0; }
    .setup-alert { width: 100%; background: #fffbeb; border: 1.5px solid #fde68a; border-radius: var(--radius); padding: 16px 18px; display: flex; gap: 13px; align-items: flex-start; margin-bottom: 22px; }
    .setup-alert-icon { font-size: 26px; flex-shrink: 0; margin-top: 2px; }
    .setup-alert-title { font-size: 14px; font-weight: 800; color: #92400e; margin-bottom: 4px; }
    .setup-alert-desc  { font-size: 13px; color: #78350f; line-height: 1.5; }
    .setup-face-preview { position: relative; width: 220px; height: 220px; border-radius: 50%; overflow: hidden; background: #0f172a; border: 4px solid #667eea; box-shadow: 0 4px 24px rgba(102,126,234,0.35); margin-bottom: 18px; }
    .setup-face-preview video, .setup-face-preview canvas { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); min-width: 100%; min-height: 100%; width: auto; height: auto; object-fit: cover; }
    .setup-face-preview canvas { display: none; }
    .face-ring { position: absolute; inset: 0; border-radius: 50%; border: 3px solid rgba(102,126,234,0.5); pointer-events: none; animation: pulse-ring 2s ease-in-out infinite; }
    @keyframes pulse-ring { 0%,100% { box-shadow: 0 0 0 0 rgba(102,126,234,0.4); } 50% { box-shadow: 0 0 0 12px rgba(102,126,234,0); } }
    .setup-face-status { padding: 9px 18px; border-radius: 50px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 7px; margin-bottom: 18px; }
    .setup-face-status.pending { background: #fff3cd; color: #856404; border: 1px solid #ffc107; }
    .setup-face-status.success { background: #d1e7dd; color: #0a3622; border: 1px solid #198754; }
    .setup-btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 26px; border-radius: 10px; font-size: 14px; font-weight: 700; border: none; cursor: pointer; transition: all .25s; font-family: 'Plus Jakarta Sans', sans-serif; margin: 4px; }
    .setup-btn-cam    { background: linear-gradient(135deg,#667eea,#764ba2); color: #fff; box-shadow: 0 4px 14px rgba(102,126,234,.35); }
    .setup-btn-retake { background: #f59e0b; color: #fff; }
    .setup-btn-save { width: 100%; padding: 15px; border-radius: var(--radius); background: linear-gradient(135deg,#22c55e,#16a34a); color: #fff; font-size: 15px; font-weight: 800; border: none; cursor: pointer; margin-top: 12px; display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 4px 20px rgba(34,197,94,.3); transition: all .25s; font-family: 'Plus Jakarta Sans', sans-serif; }
    .setup-btn-save:disabled { opacity: .45; cursor: not-allowed; }

    @media(max-width: 480px) {
        .container { padding-top: 68px; padding-bottom: 110px; }
        .setup-face-preview { width: 190px; height: 190px; }
    }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
@endsection

@section('content')
<div class="container">

@if(!$hasFaceData)
{{-- ═══════════════════════════════════════════ --}}
{{-- SCREEN 1 — SETUP WAJAH                     --}}
{{-- ═══════════════════════════════════════════ --}}
<div class="setup-screen" id="setup-screen">
    <div class="setup-alert">
        <span class="setup-alert-icon">⚠️</span>
        <div>
            <div class="setup-alert-title">Data Wajah Belum Tersimpan</div>
            <div class="setup-alert-desc">
                Akun Anda didaftarkan oleh admin. Sebelum bisa absen, Anda perlu merekam data wajah sekali saja untuk verifikasi identitas.
            </div>
        </div>
    </div>
    <div class="setup-face-preview">
        <video id="setup-video" autoplay playsinline></video>
        <canvas id="setup-canvas"></canvas>
        <div class="face-ring"></div>
    </div>
    <div class="setup-face-status pending" id="setup-status">
        <i>⚪</i> Belum diverifikasi
    </div>
    <div style="text-align:center;margin-bottom:6px;">
        <button type="button" class="setup-btn setup-btn-cam" id="setup-startCam">📷 Nyalakan Kamera</button>
        <button type="button" class="setup-btn setup-btn-cam" id="setup-captureBtn" style="display:none;">📸 Ambil Foto Wajah</button>
        <button type="button" class="setup-btn setup-btn-retake" id="setup-retakeBtn" style="display:none;">🔄 Foto Ulang</button>
    </div>
    <button type="button" class="setup-btn-save" id="setup-saveBtn" disabled>
        ✅ Simpan &amp; Mulai Absen
    </button>
</div>

@else
{{-- ═══════════════════════════════════════════ --}}
{{-- SCREEN 2 — ABSEN NORMAL                    --}}
{{-- ═══════════════════════════════════════════ --}}

    <img id="reference-face" crossorigin="anonymous"
         src="{{ asset('storage/uploads/faces/' . Auth::guard('karyawan')->user()->nik . '_face.jpg') }}"
         alt="Reference Face">

    <div class="webcam-wrap">
        <input type="hidden" id="lokasi">
        <video id="webcam" autoplay playsinline></video>
        <canvas id="face-overlay"></canvas>
        <div id="face-status" class="face-loading" style="display:none;">
            <span class="spinner"></span>
            <span>Memuat model...</span>
        </div>
    </div>

    {{-- ══ INFO PANEL JAM KERJA ══════════════════════════════════════════ --}}
    @if($jamKerja)
    @php
        /* ── Konversi semua nilai ke menit ──────────────────────────────── */
        $nowMenit        = (int)date('H') * 60 + (int)date('i');
        $masukMenit      = (int)date('H', strtotime($jamKerja->jam_masuk))  * 60
                         + (int)date('i', strtotime($jamKerja->jam_masuk));
        $pulangMenit     = (int)date('H', strtotime($jamKerja->jam_pulang)) * 60
                         + (int)date('i', strtotime($jamKerja->jam_pulang));
        $batasMasukAwal  = (int)$jamKerja->batas_absen_masuk_awal;
        $batasMasukAkhir = (int)$jamKerja->batas_absen_masuk_akhir;
        $batasTerlambat  = $masukMenit + (int)$jamKerja->toleransi_keterlambatan;
        $batasPulang     = $pulangMenit - (int)$jamKerja->batas_absen_pulang_sebelum;

        /* ── Utility closure ─────────────────────────────────────────────── */
        $fmt = fn(int $m) => sprintf('%02d:%02d', floor($m / 60), $m % 60);

        /* ── Tentukan konteks: masuk atau pulang ──────────────────────────── */
        if ($cek === 0) {
            /* ── STATUS ABSEN MASUK ─────────────────── */
            if ($nowMenit < $batasMasukAwal) {
                $statusLabel = 'Belum waktunya absen masuk';
                $statusDesc  = 'Absen masuk dibuka mulai pukul ' . $fmt($batasMasukAwal);
                $statusColor = '#888780';
                $badgeClass  = 'jk-badge-info';
                $badgeLabel  = 'Menunggu';
            } elseif ($nowMenit <= $masukMenit) {
                $statusLabel = 'Tepat waktu';
                $statusDesc  = 'Silakan absen masuk sekarang';
                $statusColor = '#639922';
                $badgeClass  = 'jk-badge-ok';
                $badgeLabel  = 'Bisa absen';
            } elseif ($nowMenit <= $batasTerlambat) {
                $statusLabel = 'Toleransi keterlambatan';
                $statusDesc  = 'Masih dalam toleransi ' . $jamKerja->toleransi_keterlambatan
                             . ' menit (s.d. ' . $fmt($batasTerlambat) . ')';
                $statusColor = '#EF9F27';
                $badgeClass  = 'jk-badge-warn';
                $badgeLabel  = 'Toleransi';
            } elseif ($nowMenit <= $batasMasukAkhir) {
                $mntTelat    = $nowMenit - $masukMenit;
                $statusLabel = 'Terlambat ' . $mntTelat . ' menit';
                $statusDesc  = 'Masih bisa absen masuk hingga ' . $fmt($batasMasukAkhir);
                $statusColor = '#E24B4A';
                $badgeClass  = 'jk-badge-danger';
                $badgeLabel  = 'Terlambat';
            } else {
                $statusLabel = 'Waktu absen masuk berakhir';
                $statusDesc  = 'Batas akhir absen masuk pukul ' . $fmt($batasMasukAkhir) . ' telah lewat';
                $statusColor = '#E24B4A';
                $badgeClass  = 'jk-badge-danger';
                $badgeLabel  = 'Berakhir';
            }

            $cardTitle = 'Ketentuan absen masuk';
            $rows = [
                ['Jam masuk',              $fmt($masukMenit)],
                ['Mulai bisa absen',        $fmt($batasMasukAwal)],
                ['Toleransi keterlambatan', $jamKerja->toleransi_keterlambatan . ' menit (s.d. ' . $fmt($batasTerlambat) . ')'],
                ['Batas akhir absen',       $fmt($batasMasukAkhir)],
            ];
            $range     = max(1, $batasMasukAkhir - $batasMasukAwal);
            $pct       = min(100, max(0, (int)round(($nowMenit - $batasMasukAwal) / $range * 100)));
            $tlColor   = $nowMenit <= $masukMenit  ? '#378ADD'
                       : ($nowMenit <= $batasTerlambat ? '#639922'
                       : ($nowMenit <= $batasMasukAkhir ? '#BA7517' : '#E24B4A'));
            $tlLabels  = [$fmt($batasMasukAwal), $fmt($masukMenit), $fmt($batasMasukAkhir)];
            $ticks = [
                ['dot-blue',  'Mulai bisa absen: '    . $fmt($batasMasukAwal)],
                ['dot-green', 'Tepat waktu s.d.: '    . $fmt($masukMenit)],
                ['dot-amber', 'Batas toleransi: '     . $fmt($batasTerlambat)],
                ['dot-red',   'Batas akhir absen: '   . $fmt($batasMasukAkhir)],
            ];

        } else {
            /* ── STATUS ABSEN PULANG ─────────────────── */
            if ($nowMenit < $batasPulang) {
                $mntSisa     = $batasPulang - $nowMenit;
                $statusLabel = 'Belum bisa absen pulang';
                $statusDesc  = 'Absen pulang dibuka pukul ' . $fmt($batasPulang)
                             . ' — ' . $mntSisa . ' menit lagi';
                $statusColor = '#BA7517';
                $badgeClass  = 'jk-badge-warn';
                $badgeLabel  = 'Menunggu';
            } else {
                $statusLabel = 'Bisa absen pulang';
                $statusDesc  = 'Silakan absen pulang — sudah melewati pukul ' . $fmt($batasPulang);
                $statusColor = '#639922';
                $badgeClass  = 'jk-badge-ok';
                $badgeLabel  = 'Bisa pulang';
            }

            $cardTitle = 'Ketentuan absen pulang';
            $rows = [
                ['Jam pulang',            $fmt($pulangMenit)],
                ['Absen pulang dibuka',   $fmt($batasPulang)],
                ['Lebih awal dari jam pulang', $jamKerja->batas_absen_pulang_sebelum . ' menit'],
            ];
            $pct      = min(100, max(0, (int)round(($nowMenit - $batasPulang) / max(1, $pulangMenit - $batasPulang) * 100)));
            $tlColor  = $pct > 0 ? '#639922' : '#888780';
            $tlLabels = [$fmt($batasPulang), $fmt($pulangMenit), ''];
            $ticks    = [
                ['dot-amber', 'Absen pulang dibuka: ' . $fmt($batasPulang)],
                ['dot-green', 'Jam pulang: '          . $fmt($pulangMenit)],
            ];
        }
    @endphp

    <div class="jk-wrap">

        {{-- Kartu detail --}}
        <div class="jk-card">
            <div class="jk-head">
                <div class="jk-head-icon">
                    <ion-icon name="time-outline" style="font-size:16px;color:#3b6ff0"></ion-icon>
                </div>
                <div>
                    <div class="jk-head-title">{{ $cardTitle }}</div>
                    <div class="jk-head-sub">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }} · {{ now()->format('H:i') }}</div>
                </div>
                <span class="jk-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
            </div>
            <div class="jk-body">
                @foreach($rows as [$label, $nilai])
                <div class="jk-row">
                    <span class="jk-lbl">{{ $label }}</span>
                    <span class="jk-val">{{ $nilai }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Kartu status + timeline --}}
        <div class="jk-card">
            <div class="jk-status-bar">
                <div class="jk-sdot" style="background:{{ $statusColor }}"></div>
                <div>
                    <div class="jk-smain">{{ $statusLabel }}</div>
                    <div class="jk-sdesc">{{ $statusDesc }}</div>
                </div>
            </div>
            <div class="jk-tl">
                <div class="jk-tl-lbl">Progress hari ini</div>
                <div class="jk-track">
                    <div class="jk-fill" style="width:{{ $pct }}%;background:{{ $tlColor }}"></div>
                </div>
                <div class="jk-tl-labels">
                    @foreach($tlLabels as $lbl)
                        <span>{{ $lbl }}</span>
                    @endforeach
                </div>
            </div>
            <div class="jk-ticks">
                @foreach($ticks as [$dotClass, $tickLabel])
                    <div class="jk-tick">
                        <div class="jk-tdot {{ $dotClass }}"></div>
                        <span>{{ $tickLabel }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
    @endif
    {{-- ══ END INFO PANEL ═══════════════════════════════════════════════ --}}

    <div id="location-status"></div>

    {{-- ── Tombol Absen ─────────────────────────────────────────────── --}}
    <div class="absen-btn-wrap">
        @if($cek >= 2)
            <div class="done-badge">
                <ion-icon name="checkmark-done-circle"></ion-icon>
                Absen hari ini sudah selesai
            </div>

        @elseif($cek == 1)
            {{-- Sudah absen masuk — cek apakah sudah waktunya pulang --}}
            @php
                $bisaPulang      = true;
                $waktuMulaiPulang = '--:--';
                if ($jamKerja) {
                    $bisaPulang       = $nowMenit >= $batasPulang;
                    $waktuMulaiPulang = $fmt($batasPulang);
                }
            @endphp

            @if(!$bisaPulang)
                <div class="done-badge" style="background:#fffbeb;color:#b45309;border:1px solid #fde68a;flex-direction:column;gap:6px;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <ion-icon name="time-outline"></ion-icon>
                        <span>Absen pulang belum tersedia</span>
                    </div>
                    <span style="font-size:12px;font-weight:500;color:#92400e;">
                        Bisa absen pulang mulai pukul <strong>{{ $waktuMulaiPulang }}</strong>
                    </span>
                </div>
            @else
                <button id="takeabsen" class="btn-danger" disabled>
                    <ion-icon name="log-out-outline"></ion-icon> Absen Pulang
                </button>
                <p id="button-hint">Tunggu verifikasi wajah...</p>
            @endif

        @else
            {{-- Belum absen masuk --}}
            @php
                $bisaMasuk = !$jamKerja || ($nowMenit >= $batasMasukAwal && $nowMenit <= $batasMasukAkhir);
            @endphp
            @if(!$bisaMasuk && $jamKerja)
                @if($nowMenit < $batasMasukAwal)
                    <div class="done-badge" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;flex-direction:column;gap:6px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <ion-icon name="time-outline"></ion-icon>
                            <span>Belum waktunya absen masuk</span>
                        </div>
                        <span style="font-size:12px;font-weight:500;color:#1e40af;">
                            Dibuka mulai pukul <strong>{{ $fmt($batasMasukAwal) }}</strong>
                        </span>
                    </div>
                @else
                    <div class="done-badge" style="background:#fff1f2;color:#dc2626;border:1px solid #fecdd3;flex-direction:column;gap:6px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <ion-icon name="close-circle-outline"></ion-icon>
                            <span>Waktu absen masuk telah berakhir</span>
                        </div>
                        <span style="font-size:12px;font-weight:500;color:#991b1b;">
                            Batas akhir pukul <strong>{{ $fmt($batasMasukAkhir) }}</strong>
                        </span>
                    </div>
                @endif
            @else
                <button id="takeabsen" class="btn-success" disabled>
                    <ion-icon name="log-in-outline"></ion-icon> Absen Masuk
                </button>
                <p id="button-hint">Tunggu verifikasi wajah...</p>
            @endif
        @endif
    </div>

    <p class="section-label">Lokasi Anda</p>
    <div id="map"></div>

@endif
</div>
@endsection

@push('scripts')
<script>
const HAS_FACE_DATA = @json($hasFaceData);
const CSRF_TOKEN    = '{{ csrf_token() }}';
const userNik       = '{{ Auth::guard("karyawan")->user()->nik }}';

/* ══════════════════════════════════════════════════════
   SETUP WAJAH
══════════════════════════════════════════════════════ */
if (!HAS_FACE_DATA) {
    let setupStream   = null;
    let setupFaceData = null;

    const setupVideo     = document.getElementById('setup-video');
    const setupCanvas    = document.getElementById('setup-canvas');
    const setupStatus    = document.getElementById('setup-status');
    const setupStartBtn  = document.getElementById('setup-startCam');
    const setupCapBtn    = document.getElementById('setup-captureBtn');
    const setupRetakeBtn = document.getElementById('setup-retakeBtn');
    const setupSaveBtn   = document.getElementById('setup-saveBtn');

    setupStartBtn.addEventListener('click', async function () {
        try {
            setupStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } }
            });
            setupVideo.srcObject      = setupStream;
            setupVideo.style.display  = 'block';
            setupCanvas.style.display = 'none';
            setupStartBtn.style.display  = 'none';
            setupCapBtn.style.display    = 'inline-flex';
            setupRetakeBtn.style.display = 'none';
        } catch (err) {
            alert('Tidak dapat membuka kamera. Pastikan izin kamera diaktifkan.');
        }
    });

    setupCapBtn.addEventListener('click', function () {
        setupCanvas.width  = setupVideo.videoWidth;
        setupCanvas.height = setupVideo.videoHeight;
        setupCanvas.getContext('2d').drawImage(setupVideo, 0, 0);
        setupFaceData = setupCanvas.toDataURL('image/jpeg', 0.8);

        setupVideo.style.display     = 'none';
        setupCanvas.style.display    = 'block';
        setupCapBtn.style.display    = 'none';
        setupRetakeBtn.style.display = 'inline-flex';

        setupStatus.className = 'setup-face-status success';
        setupStatus.innerHTML = '✅ Wajah berhasil diambil';
        setupSaveBtn.disabled = false;

        if (setupStream) { setupStream.getTracks().forEach(t => t.stop()); setupStream = null; }
    });

    setupRetakeBtn.addEventListener('click', function () {
        setupFaceData = null;
        setupSaveBtn.disabled = true;
        setupStatus.className = 'setup-face-status pending';
        setupStatus.innerHTML = '⚪ Belum diverifikasi';
        setupCanvas.style.display    = 'none';
        setupRetakeBtn.style.display = 'none';
        setupStartBtn.style.display  = 'inline-flex';
        setupStartBtn.click();
    });

    setupSaveBtn.addEventListener('click', async function () {
        if (!setupFaceData) { alert('Harap ambil foto wajah terlebih dahulu!'); return; }
        setupSaveBtn.disabled = true;
        setupSaveBtn.innerHTML = '⏳ Menyimpan...';
        try {
            const formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            formData.append('face_data', setupFaceData);
            const response = await fetch('/presensi/setupwajah', { method: 'POST', body: formData });
            const result   = await response.json();
            if (result.success) {
                setupSaveBtn.innerHTML = '✅ Tersimpan! Memuat ulang...';
                setTimeout(() => location.reload(), 1200);
            } else {
                alert('Gagal menyimpan: ' + result.message);
                setupSaveBtn.disabled  = false;
                setupSaveBtn.innerHTML = '✅ Simpan & Mulai Absen';
            }
        } catch (err) {
            alert('Terjadi kesalahan. Silakan coba lagi.');
            setupSaveBtn.disabled  = false;
            setupSaveBtn.innerHTML = '✅ Simpan & Mulai Absen';
        }
    });
}

/* ══════════════════════════════════════════════════════
   ABSEN NORMAL
══════════════════════════════════════════════════════ */
if (HAS_FACE_DATA) {
    let faceVerified = false;
    let isInRadius   = false;
    let userLatitude, userLongitude;
    let video, canvas, faceStatus, takeAbsenBtn, referenceImage, lokasiInput;
    let faceDetectionInterval;
    let modelsLoaded  = false;
    let referenceFaceDescriptor = null;

    const SIMILARITY_THRESHOLD = 0.45;

    document.addEventListener('DOMContentLoaded', async function () {
        video          = document.getElementById('webcam');
        canvas         = document.getElementById('face-overlay');
        faceStatus     = document.getElementById('face-status');
        takeAbsenBtn   = document.getElementById('takeabsen');
        referenceImage = document.getElementById('reference-face');
        lokasiInput    = document.getElementById('lokasi');

        setupButtonHandler();

        try {
            await Promise.all([setupWebcam(), loadFaceModels(), setupLocation()]);
            await loadReferenceFace();
            startFaceDetection();
        } catch (error) {
            console.error('Init error:', error);
        }
    });

    function setupButtonHandler() {
        if (!takeAbsenBtn) return;
        takeAbsenBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (!faceVerified) {
                Swal.fire({ title: 'Peringatan!', text: 'Wajah Anda tidak cocok!', icon: 'warning' });
                return;
            }
            const tempCanvas = document.createElement('canvas');
            tempCanvas.width  = video.videoWidth;
            tempCanvas.height = video.videoHeight;
            tempCanvas.getContext('2d').drawImage(video, 0, 0);
            const image  = tempCanvas.toDataURL('image/jpeg', 0.9);
            const lokasi = lokasiInput.value;
            if (!lokasi) {
                Swal.fire({ title: 'Error!', text: 'Lokasi tidak terbaca!', icon: 'error' });
                return;
            }
            takeAbsenBtn.disabled  = true;
            const origHTML = takeAbsenBtn.innerHTML;
            takeAbsenBtn.innerHTML = '<span class="spinner"></span> Memproses...';

            fetch('/presensi/store', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify({ lokasi, image, is_in_radius: isInRadius ? 1 : 0, face_verified: faceVerified ? 1 : 0 })
            })
            .then(r => r.text())
            .then(respond => {
                const status = respond.split('|');
                if (status[0] === 'success') {
                    Swal.fire({ title: 'Berhasil!', text: status[1], icon: 'success' })
                        .then(() => { window.location.href = '/dashboard'; });
                } else {
                    Swal.fire({ title: 'Gagal!', text: status[1], icon: 'error' });
                    takeAbsenBtn.disabled  = false;
                    takeAbsenBtn.innerHTML = origHTML;
                }
            })
            .catch(() => {
                Swal.fire({ title: 'Error!', text: 'Terjadi kesalahan jaringan', icon: 'error' });
                takeAbsenBtn.disabled  = false;
                takeAbsenBtn.innerHTML = origHTML;
            });
        });
    }

    async function setupWebcam() {
        faceStatus.style.display = 'flex';
        faceStatus.className     = 'face-loading';
        faceStatus.innerHTML     = '<span class="spinner"></span><span>Mengaktifkan kamera...</span>';
        const stream = await navigator.mediaDevices.getUserMedia({
            video: { width: { ideal: 640, max: 1280 }, height: { ideal: 480, max: 720 }, facingMode: 'user', frameRate: { ideal: 30, max: 30 } },
            audio: false
        });
        video.srcObject = stream;
        await video.play();
        canvas.width  = video.videoWidth;
        canvas.height = video.videoHeight;
        faceStatus.innerHTML = '<span class="spinner"></span><span>Memuat model AI...</span>';
    }

    async function loadFaceModels() {
        const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/';
        await Promise.all([
            faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL),
            faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
            faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
        ]);
        modelsLoaded = true;
    }

    async function loadReferenceFace() {
        faceStatus.innerHTML = '<span class="spinner"></span><span>Memuat foto referensi...</span>';
        if (!referenceImage.complete) {
            await new Promise((res, rej) => {
                referenceImage.onload  = res;
                referenceImage.onerror = () => rej(new Error('Gagal memuat foto referensi'));
                setTimeout(() => rej(new Error('Timeout')), 5000);
            });
        }
        const detection = await faceapi.detectSingleFace(referenceImage).withFaceLandmarks().withFaceDescriptor();
        if (!detection) throw new Error('Tidak ada wajah di foto referensi');
        referenceFaceDescriptor = detection.descriptor;
        faceStatus.innerHTML    = '<span class="spinner"></span><span>Mendeteksi wajah...</span>';
    }

    function startFaceDetection() {
        if (!modelsLoaded || !referenceFaceDescriptor) { setTimeout(startFaceDetection, 300); return; }
        faceDetectionInterval = setInterval(async () => { await detectAndVerifyFace(); }, 800);
    }

    async function detectAndVerifyFace() {
        if (!video || video.readyState !== 4 || !referenceFaceDescriptor) return;
        try {
            const detections = await faceapi
                .detectAllFaces(video, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.5, maxResults: 2 }))
                .withFaceLandmarks().withFaceDescriptors();

            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            const buttonHint = document.getElementById('button-hint');
            const cek        = @json($cek);

            if (detections.length === 0) {
                faceVerified             = false;
                faceStatus.className     = 'face-detecting';
                faceStatus.innerHTML     = '<ion-icon name="scan-outline"></ion-icon><span>Tidak ada wajah</span>';
                faceStatus.style.display = 'flex';
                if (takeAbsenBtn && cek < 2) { takeAbsenBtn.disabled = true; if (buttonHint) buttonHint.textContent = 'Posisikan wajah di depan kamera'; }
            } else if (detections.length > 1) {
                faceVerified             = false;
                faceStatus.className     = 'face-not-verified';
                faceStatus.innerHTML     = '<ion-icon name="warning"></ion-icon><span>' + detections.length + ' wajah terdeteksi</span>';
                faceStatus.style.display = 'flex';
                if (takeAbsenBtn && cek < 2) { takeAbsenBtn.disabled = true; if (buttonHint) buttonHint.textContent = 'Hanya 1 orang diizinkan'; }
                detections.forEach(d => drawFaceBox(d.detection.box, '#ef4444'));
            } else {
                const detection  = detections[0];
                const distance   = faceapi.euclideanDistance(referenceFaceDescriptor, detection.descriptor);
                const similarity = Math.round((1 - distance) * 100);

                if (distance < SIMILARITY_THRESHOLD) {
                    faceVerified             = true;
                    faceStatus.className     = 'face-verified';
                    faceStatus.innerHTML     = '<ion-icon name="checkmark-circle"></ion-icon><span>Wajah Cocok — ' + similarity + '%</span>';
                    faceStatus.style.display = 'flex';
                    if (takeAbsenBtn && cek < 2) { takeAbsenBtn.disabled = false; if (buttonHint) buttonHint.textContent = '✅ Klik tombol untuk absen'; }
                    drawFaceBox(detection.detection.box, '#22c55e');
                } else {
                    faceVerified             = false;
                    faceStatus.className     = 'face-not-verified';
                    faceStatus.innerHTML     = '<ion-icon name="close-circle"></ion-icon><span>Tidak Cocok — ' + similarity + '%</span>';
                    faceStatus.style.display = 'flex';
                    if (takeAbsenBtn && cek < 2) { takeAbsenBtn.disabled = true; if (buttonHint) buttonHint.textContent = '❌ Wajah tidak dikenali'; }
                    drawFaceBox(detection.detection.box, '#ef4444');
                }
            }
        } catch (error) { /* silent */ }
    }

    function drawFaceBox(box, color) {
        const ctx = canvas.getContext('2d');
        const r = 8, cl = 22;
        ctx.strokeStyle = color;
        ctx.lineWidth   = 2;
        ctx.beginPath();
        ctx.roundRect(box.x, box.y, box.width, box.height, r);
        ctx.stroke();
        ctx.lineWidth = 3.5;
        const dc = (x, y, dx, dy) => {
            ctx.beginPath();
            ctx.moveTo(x + dx * r, y); ctx.lineTo(x + dx * cl, y);
            ctx.moveTo(x, y + dy * r); ctx.lineTo(x, y + dy * cl);
            ctx.stroke();
        };
        dc(box.x, box.y, 1, 1);
        dc(box.x + box.width, box.y, -1, 1);
        dc(box.x, box.y + box.height, 1, -1);
        dc(box.x + box.width, box.y + box.height, -1, -1);
    }

    async function setupLocation() {
        return new Promise((resolve) => {
            if (!navigator.geolocation) { resolve(); return; }
            navigator.geolocation.getCurrentPosition(
                (pos) => { successCallback(pos); resolve(); },
                (err) => { errorCallback(err);   resolve(); },
                { enableHighAccuracy: false, timeout: 10000, maximumAge: 30000 }
            );
        });
    }

    function successCallback(position) {
        userLatitude  = position.coords.latitude;
        userLongitude = position.coords.longitude;
        lokasiInput.value = userLatitude + ',' + userLongitude;

        const lok_kantor  = "{{ $lok_kantor->lokasi_kantor }}".split(',');
        const lat_kantor  = parseFloat(lok_kantor[0]);
        const lng_kantor  = parseFloat(lok_kantor[1]);
        const radius      = parseFloat("{{ $lok_kantor->radius }}");
        const jarak       = hitungJarak(userLatitude, userLongitude, lat_kantor, lng_kantor);
        isInRadius        = jarak <= radius;

        const statusDiv = document.getElementById('location-status');
        statusDiv.style.display = 'flex';
        if (isInRadius) {
            statusDiv.className = 'status-dalam-kantor';
            statusDiv.innerHTML = '<ion-icon name="checkmark-circle-outline"></ion-icon> Anda di Lingkungan Kantor';
        } else {
            statusDiv.className = 'status-luar-kantor';
            statusDiv.innerHTML = '<ion-icon name="warning-outline"></ion-icon> Di Luar Kantor — Jarak: ' + Math.round(jarak) + ' m';
        }

        const map = L.map('map').setView([userLatitude, userLongitude], 15);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 19, attribution: '© OpenStreetMap © CartoDB'
        }).addTo(map);

        const blueIcon = L.icon({ iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png', shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png', iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41] });
        const redIcon  = L.icon({ iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png', iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41] });

        const userMarker   = L.marker([userLatitude, userLongitude], { icon: blueIcon }).addTo(map);
        const kantorMarker = L.marker([lat_kantor, lng_kantor], { icon: redIcon }).addTo(map);
        const circle       = L.circle([lat_kantor, lng_kantor], { color: isInRadius ? '#22c55e' : '#ef4444', fillColor: isInRadius ? '#22c55e' : '#ef4444', fillOpacity: 0.12, radius }).addTo(map);

        userMarker.bindPopup('<b>Lokasi Anda</b><br>Jarak: ' + Math.round(jarak) + ' m').openPopup();
        kantorMarker.bindPopup('<b>Kantor</b>');
        map.fitBounds(L.featureGroup([userMarker, kantorMarker, circle]).getBounds().pad(0.15));
    }

    function hitungJarak(lat1, lon1, lat2, lon2) {
        const R  = 6371e3;
        const φ1 = lat1 * Math.PI / 180, φ2 = lat2 * Math.PI / 180;
        const Δφ = (lat2 - lat1) * Math.PI / 180;
        const Δλ = (lon2 - lon1) * Math.PI / 180;
        const a  = Math.sin(Δφ / 2) ** 2 + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) ** 2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    }

    function errorCallback() {
        Swal.fire({ title: 'Error!', text: 'Tidak bisa mendapatkan lokasi! Pastikan GPS aktif.', icon: 'error' });
    }

    window.addEventListener('beforeunload', () => {
        if (faceDetectionInterval) clearInterval(faceDetectionInterval);
        if (video?.srcObject) video.srcObject.getTracks().forEach(t => t.stop());
    });
}
</script>
@endpush