@extends('layout.presensi')

@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="/dashboard" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Data Lembur</div>
    <div class="right">
        <a href="{{ route('lembur.create') }}" class="headerButton">
            <ion-icon name="add-outline" style="font-size:22px"></ion-icon>
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
    font-weight: 700;
    font-size: 16px;
    color: #fff !important;
}

/* ─── Container ─── */
.lembur-container {
    padding: 76px 16px 110px; /* 76px = tinggi appHeader (~56px) + gap 20px */
    max-width: 520px;
    margin: 0 auto;
}

/* ─── Alert ─── */
.ep-alert {
    border-radius: 12px;
    padding: 12px 16px;
    margin-bottom: 14px;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    border: none;
    animation: fadeSlide .3s ease;
}
.ep-alert ion-icon { font-size: 18px; flex-shrink: 0; }
.ep-alert.success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.ep-alert.error   { background: #fff1f2; color: #dc2626; border: 1px solid #fecdd3; }
.ep-alert-close {
    margin-left: auto;
    background: none;
    border: none;
    cursor: pointer;
    color: inherit;
    opacity: 0.6;
    font-size: 16px;
    padding: 0;
}

/* ─── Summary Strip ─── */
.sum-strip {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 18px;
}
.sum-chip {
    background: #fff;
    border-radius: 14px;
    padding: 14px 10px;
    text-align: center;
    border: 1px solid rgba(99,130,220,0.15);
    box-shadow: 0 2px 10px rgba(59,111,240,0.06);
}
.sum-num {
    font-size: 22px;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 4px;
}
.sum-lbl {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
}
.sum-chip.total .sum-num { color: #3b6ff0; }
.sum-chip.hours .sum-num { color: #8b5cf6; }
.sum-chip.this-month .sum-num { color: #16a34a; }

/* ─── Section Header ─── */
.sec-hd {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.sec-title { font-size: 13px; font-weight: 700; color: #1e2a4a; }
.sec-count {
    font-size: 12px;
    color: #64748b;
    background: #fff;
    padding: 3px 10px;
    border-radius: 20px;
    border: 1px solid rgba(99,130,220,0.15);
}

/* ─── Lembur Card ─── */
.card-list { display: flex; flex-direction: column; gap: 12px; }

.lembur-card {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    border: 1px solid rgba(99,130,220,0.12);
    box-shadow: 0 2px 14px rgba(59,111,240,0.07);
    animation: fadeSlide .35s ease both;
}
.lembur-card:nth-child(1){animation-delay:.04s}
.lembur-card:nth-child(2){animation-delay:.08s}
.lembur-card:nth-child(3){animation-delay:.12s}
.lembur-card:nth-child(4){animation-delay:.16s}
.lembur-card:nth-child(5){animation-delay:.20s}

@keyframes fadeSlide {
    from { opacity:0; transform:translateY(10px); }
    to   { opacity:1; transform:translateY(0); }
}

/* accent bar */
.card-accent {
    height: 4px;
    background: linear-gradient(90deg, #3b6ff0, #8b5cf6);
}

.card-body { padding: 16px; }

/* ─── Card Top Row ─── */
.card-top {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
}
.date-badge {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: linear-gradient(135deg, #3b6ff0, #667eea);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(59,111,240,0.3);
}
.date-badge-day   { font-size: 20px; font-weight: 800; line-height: 1; }
.date-badge-month { font-size: 10px; font-weight: 700; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.5px; }

.date-info { flex: 1; min-width: 0; }
.date-full { font-size: 14px; font-weight: 700; color: #1e2a4a; }
.date-dayname { font-size: 12px; color: #64748b; margin-top: 2px; }

.durasi-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 20px;
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(139,92,246,0.3);
}
.durasi-pill ion-icon { font-size: 13px; }

/* ─── Info Rows ─── */
.info-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    background: #f8faff;
    border-radius: 11px;
    margin-bottom: 10px;
}
.info-row-icon {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.info-row-icon.blue   { background: #eff6ff; }
.info-row-icon.blue   ion-icon { color: #3b6ff0; font-size: 16px; }
.info-row-icon.purple { background: #f5f3ff; }
.info-row-icon.purple ion-icon { color: #7c3aed; font-size: 16px; }
.info-row-icon.green  { background: #f0fdf4; }
.info-row-icon.green  ion-icon { color: #16a34a; font-size: 16px; }

.info-row-label { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.4px; }
.info-row-val   { font-size: 13px; font-weight: 600; color: #1e2a4a; }

/* keterangan */
.keterangan-wrap {
    background: #f8faff;
    border-radius: 11px;
    padding: 12px;
    border-left: 3px solid #3b6ff0;
    margin-bottom: 12px;
}
.keterangan-lbl  { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 5px; }
.keterangan-text { font-size: 13px; color: #374151; line-height: 1.55; }

/* bukti */
.bukti-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 20px;
    background: #eff6ff;
    color: #1d4ed8;
    font-size: 11px;
    font-weight: 700;
    border: 1px solid #bfdbfe;
    margin-bottom: 12px;
}
.bukti-pill ion-icon { font-size: 13px; }

/* ─── Action Buttons ─── */
.action-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 8px;
    padding-top: 12px;
    border-top: 1px solid rgba(99,130,220,0.1);
}
.btn-act {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 9px 8px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    border: none;
    cursor: pointer;
    transition: all .2s;
    text-decoration: none;
    width: 100%;
}
.btn-act ion-icon { font-size: 14px; }
.btn-act:hover { transform: translateY(-1px); }

.btn-detail   { background: #eff6ff; color: #1d4ed8; }
.btn-detail:hover { background: #dbeafe; }
.btn-edit-act { background: #fefce8; color: #854d0e; }
.btn-edit-act:hover { background: #fef08a; }
.btn-del      { background: #fff1f2; color: #dc2626; }
.btn-del:hover { background: #fecdd3; }

/* ─── Empty State ─── */
.empty-state {
    background: #fff;
    border-radius: 20px;
    padding: 52px 24px;
    text-align: center;
    border: 1px solid rgba(99,130,220,0.12);
    box-shadow: 0 2px 14px rgba(59,111,240,0.06);
}
.empty-icon-wrap {
    width: 72px;
    height: 72px;
    background: #eff6ff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
}
.empty-icon-wrap ion-icon { font-size: 32px; color: #3b6ff0; }
.empty-title { font-size: 16px; font-weight: 800; color: #1e2a4a; margin-bottom: 6px; }
.empty-desc  { font-size: 13px; color: #64748b; line-height: 1.5; margin-bottom: 20px; }
.btn-empty-add {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 12px;
    background: linear-gradient(135deg, #3b6ff0, #667eea);
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 4px 16px rgba(59,111,240,0.3);
    transition: all .2s;
}
.btn-empty-add:hover { transform: translateY(-2px); box-shadow: 0 6px 22px rgba(59,111,240,0.4); color: #fff; }
.btn-empty-add ion-icon { font-size: 18px; }

/* ─── FAB ─── */
.fab-btn {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 54px;
    height: 54px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b6ff0, #667eea);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 20px rgba(59,111,240,0.4);
    text-decoration: none;
    z-index: 50;
    transition: all .25s;
}
.fab-btn:hover { transform: scale(1.08) rotate(90deg); box-shadow: 0 8px 28px rgba(59,111,240,0.5); }
.fab-btn ion-icon { font-size: 26px; }

/* ─── Delete modal ─── */
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
@keyframes overlayIn { from{opacity:0} to{opacity:1} }
.del-sheet {
    background: #fff;
    border-radius: 24px 24px 0 0;
    padding: 0 20px 48px;
    width: 100%;
    max-width: 480px;
    animation: sheetUp .3s cubic-bezier(.32,.72,0,1);
}
@keyframes sheetUp { from{transform:translateY(100%)} to{transform:translateY(0)} }
.del-handle { width: 36px; height: 4px; background: rgba(0,0,0,0.1); border-radius: 2px; margin: 14px auto 20px; }
.del-icon-wrap {
    width: 64px; height: 64px;
    border-radius: 50%;
    background: #fff1f2;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
}
.del-icon-wrap ion-icon { font-size: 28px; color: #dc2626; }
.del-title { font-size: 17px; font-weight: 800; color: #1e2a4a; text-align: center; margin-bottom: 6px; }
.del-desc  { font-size: 13px; color: #64748b; text-align: center; margin-bottom: 20px; line-height: 1.5; }
.del-btn-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.del-cancel {
    padding: 13px;
    border-radius: 12px;
    border: 1.5px solid rgba(99,130,220,0.2);
    background: #fff;
    font-size: 14px;
    font-weight: 700;
    color: #64748b;
    cursor: pointer;
    font-family: 'Plus Jakarta Sans', sans-serif;
    transition: all .2s;
}
.del-cancel:hover { background: #f8faff; }
.del-confirm {
    padding: 13px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    font-size: 14px;
    font-weight: 700;
    color: #fff;
    cursor: pointer;
    font-family: 'Plus Jakarta Sans', sans-serif;
    box-shadow: 0 4px 14px rgba(220,38,38,0.3);
    transition: all .2s;
}
.del-confirm:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(220,38,38,0.4); }
</style>
@endsection

@section('content')

{{-- Delete Confirm Sheet --}}
<div class="del-overlay" id="delOverlay" onclick="handleDelClose(event)">
    <div class="del-sheet">
        <div class="del-handle"></div>
        <div class="del-icon-wrap">
            <ion-icon name="trash-outline"></ion-icon>
        </div>
        <div class="del-title">Hapus Data Lembur?</div>
        <div class="del-desc">Data lembur ini akan dihapus permanen dan tidak dapat dikembalikan.</div>
        <div class="del-btn-row">
            <button class="del-cancel" onclick="closeDelModal()">Batal</button>
            <button class="del-confirm" id="delConfirmBtn" onclick="submitDelete()">Ya, Hapus</button>
        </div>
    </div>
</div>
<form id="delForm" method="POST" style="display:none">
    @csrf
    @method('DELETE')
</form>

<div class="lembur-container">

    {{-- ── Alerts ── --}}
    @if(Session::get('success'))
        <div class="ep-alert success" id="alertBox">
            <ion-icon name="checkmark-circle-outline"></ion-icon>
            <span>{{ Session::get('success') }}</span>
            <button class="ep-alert-close" onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif
    @if(Session::get('error'))
        <div class="ep-alert error" id="alertBox">
            <ion-icon name="alert-circle-outline"></ion-icon>
            <span>{{ Session::get('error') }}</span>
            <button class="ep-alert-close" onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    @if($dataLembur->count() > 0)

        {{-- ── Summary Strip ── --}}
        @php
            $totalEntri    = $dataLembur->count();
            $totalMenit    = $dataLembur->sum('durasi_menit');
            $totalJam      = floor($totalMenit / 60);
            $sisaMenit     = $totalMenit % 60;
            $bulanSekarang = date('m');
            $tahunSekarang = date('Y');
            $bulanIni      = $dataLembur->filter(function($l) use ($bulanSekarang, $tahunSekarang) {
                return date('m', strtotime($l->tanggal_lembur)) == $bulanSekarang
                    && date('Y', strtotime($l->tanggal_lembur)) == $tahunSekarang;
            })->count();
        @endphp
        <div class="sum-strip">
            <div class="sum-chip total">
                <div class="sum-num">{{ $totalEntri }}</div>
                <div class="sum-lbl">Total</div>
            </div>
            <div class="sum-chip hours">
                <div class="sum-num">{{ $totalJam }}j</div>
                <div class="sum-lbl">Durasi</div>
            </div>
            <div class="sum-chip this-month">
                <div class="sum-num">{{ $bulanIni }}</div>
                <div class="sum-lbl">Bulan ini</div>
            </div>
        </div>

        {{-- ── Section Header ── --}}
        <div class="sec-hd">
            <span class="sec-title">Riwayat Lembur</span>
            <span class="sec-count">{{ $totalEntri }} data</span>
        </div>

        {{-- ── Card List ── --}}
        <div class="card-list">
            @foreach($dataLembur as $lembur)
                @php
                    $jam   = floor($lembur->durasi_menit / 60);
                    $menit = $lembur->durasi_menit % 60;
                    $hariMap = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'];
                    $hariInd = $hariMap[date('l', strtotime($lembur->tanggal_lembur))] ?? date('l', strtotime($lembur->tanggal_lembur));
                    $bulanMap = ['January'=>'Jan','February'=>'Feb','March'=>'Mar','April'=>'Apr','May'=>'Mei','June'=>'Jun','July'=>'Jul','August'=>'Agu','September'=>'Sep','October'=>'Okt','November'=>'Nov','December'=>'Des'];
                    $bulanInd = $bulanMap[date('F', strtotime($lembur->tanggal_lembur))] ?? date('M', strtotime($lembur->tanggal_lembur));
                @endphp

                <div class="lembur-card">
                    <div class="card-accent"></div>
                    <div class="card-body">

                        {{-- Top: tanggal + durasi --}}
                        <div class="card-top">
                            <div class="date-badge">
                                <div class="date-badge-day">{{ date('d', strtotime($lembur->tanggal_lembur)) }}</div>
                                <div class="date-badge-month">{{ $bulanInd }}</div>
                            </div>
                            <div class="date-info">
                                <div class="date-full">{{ $hariInd }}, {{ date('d', strtotime($lembur->tanggal_lembur)) }} {{ $bulanInd }} {{ date('Y', strtotime($lembur->tanggal_lembur)) }}</div>
                                <div class="date-dayname">{{ date('Y', strtotime($lembur->tanggal_lembur)) }}</div>
                            </div>
                            <div class="durasi-pill">
                                <ion-icon name="hourglass-outline"></ion-icon>
                                {{ $jam }}j {{ $menit > 0 ? $menit.'m' : '' }}
                            </div>
                        </div>

                        {{-- Jam ── --}}
                        <div class="info-row">
                            <div class="info-row-icon blue">
                                <ion-icon name="time-outline"></ion-icon>
                            </div>
                            <div>
                                <div class="info-row-label">Jam Lembur</div>
                                <div class="info-row-val">
                                    {{ date('H:i', strtotime($lembur->jam_mulai)) }} — {{ date('H:i', strtotime($lembur->jam_selesai)) }} WIB
                                </div>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="keterangan-wrap">
                            <div class="keterangan-lbl">Keterangan</div>
                            <div class="keterangan-text">{{ $lembur->keterangan }}</div>
                        </div>

                        {{-- Bukti --}}
                        @if($lembur->bukti_foto)
                            <div class="bukti-pill">
                                <ion-icon name="image-outline"></ion-icon>
                                Dilampirkan bukti foto
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="action-row">
                            <a href="{{ route('lembur.show', $lembur->id) }}" class="btn-act btn-detail">
                                <ion-icon name="eye-outline"></ion-icon>
                                Detail
                            </a>
                            <a href="{{ route('lembur.edit', $lembur->id) }}" class="btn-act btn-edit-act">
                                <ion-icon name="create-outline"></ion-icon>
                                Edit
                            </a>
                            <button type="button"
                                    class="btn-act btn-del"
                                    onclick="openDelModal('{{ route('lembur.destroy', $lembur->id) }}')">
                                <ion-icon name="trash-outline"></ion-icon>
                                Hapus
                            </button>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

    @else
        {{-- ── Empty State ── --}}
        <div class="empty-state">
            <div class="empty-icon-wrap">
                <ion-icon name="briefcase-outline"></ion-icon>
            </div>
            <div class="empty-title">Belum Ada Data Lembur</div>
            <div class="empty-desc">Anda belum memiliki catatan lembur. Tambahkan data lembur pertama Anda.</div>
            <a href="{{ route('lembur.create') }}" class="btn-empty-add">
                <ion-icon name="add-circle-outline"></ion-icon>
                Tambah Lembur
            </a>
        </div>
    @endif

</div>

{{-- FAB --}}
<a href="{{ route('lembur.create') }}" class="fab-btn" title="Tambah Lembur">
    <ion-icon name="add-outline"></ion-icon>
</a>

<script>
let pendingDeleteUrl = null;

function openDelModal(url) {
    pendingDeleteUrl = url;
    document.getElementById('delOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeDelModal() {
    document.getElementById('delOverlay').classList.remove('open');
    document.body.style.overflow = '';
    pendingDeleteUrl = null;
}
function handleDelClose(e) {
    if (e.target === document.getElementById('delOverlay')) closeDelModal();
}
function submitDelete() {
    if (!pendingDeleteUrl) return;
    const form = document.getElementById('delForm');
    form.action = pendingDeleteUrl;
    form.submit();
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDelModal(); });

// Auto-hide alert
const alertBox = document.getElementById('alertBox');
if (alertBox) setTimeout(() => alertBox.remove(), 4000);
</script>

@endsection