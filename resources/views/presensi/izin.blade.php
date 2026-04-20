@extends('layout.presensi')

@section('header')
<div class="appHeader bg-primary text-light shadow-sm">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle fw-semibold">Izin &amp; Cuti</div>
</div>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- ─── NOTIFIKASI SESSION ─── --}}
@if(Session::get('success'))
<div class="isc-toast isc-toast-success" id="iscToast">
    <ion-icon name="checkmark-circle-outline"></ion-icon>
    <span>{{ Session::get('success') }}</span>
    <button onclick="document.getElementById('iscToast').remove()">
        <ion-icon name="close-outline"></ion-icon>
    </button>
</div>
@endif

@if(Session::get('error'))
<div class="isc-toast isc-toast-error" id="iscToast">
    <ion-icon name="close-circle-outline"></ion-icon>
    <span>{{ Session::get('error') }}</span>
    <button onclick="document.getElementById('iscToast').remove()">
        <ion-icon name="close-outline"></ion-icon>
    </button>
</div>
@endif

<div class="isc-wrap">

    {{-- ─── STAT CARDS ─── --}}
    <div class="isc-stats">
        @php
            $total    = count($dataizin);
            $pending  = $dataizin->where('status_approved', 0)->count();
            $approved = $dataizin->where('status_approved', 1)->count();
            $rejected = $dataizin->where('status_approved', 2)->count();
        @endphp
        <div class="isc-stat">
            <div class="isc-stat-num">{{ $total }}</div>
            <div class="isc-stat-label">Total</div>
        </div>
        <div class="isc-stat">
            <div class="isc-stat-num isc-num-pending">{{ $pending }}</div>
            <div class="isc-stat-label">Pending</div>
        </div>
        <div class="isc-stat">
            <div class="isc-stat-num isc-num-approved">{{ $approved }}</div>
            <div class="isc-stat-label">Disetujui</div>
        </div>
        <div class="isc-stat">
            <div class="isc-stat-num isc-num-rejected">{{ $rejected }}</div>
            <div class="isc-stat-label">Ditolak</div>
        </div>
    </div>

    {{-- ─── FILTER PERIODE ─── --}}
    <div class="isc-period-bar">
        <div class="isc-period-icon">
            <ion-icon name="calendar-outline"></ion-icon>
        </div>
        <select id="monthFilter" class="isc-select">
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
        <select id="yearFilter" class="isc-select isc-select-year">
            <option value="">Semua Tahun</option>
            @php $cy = date('Y'); @endphp
            @for($y = $cy; $y >= $cy - 3; $y--)
                <option value="{{ $y }}" {{ $y == $cy ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </div>

    {{-- ─── TAB KATEGORI ─── --}}
    <div class="isc-tabs">
        <button class="isc-tab active" data-filter="all">
            <ion-icon name="apps-outline"></ion-icon>
            Semua
        </button>
        <button class="isc-tab" data-filter="1">
            <ion-icon name="calendar-outline"></ion-icon>
            Izin
        </button>
        <button class="isc-tab" data-filter="2">
            <ion-icon name="medkit-outline"></ion-icon>
            Sakit
        </button>
        <button class="isc-tab" data-filter="3">
            <ion-icon name="airplane-outline"></ion-icon>
            Cuti
        </button>
    </div>

    {{-- ─── INFO ROW ─── --}}
    <div class="isc-info-row">
        <span class="isc-info-label" id="sectionLabel">Semua pengajuan</span>
        <span class="isc-info-count" id="visibleCount">0 data</span>
    </div>

    {{-- ─── CARD LIST ─── --}}
    <div class="isc-list" id="iscList">

        @php
            $sorted = $dataizin->sortBy(fn($i) => $i->status_approved == 0 ? 0 : 1);
        @endphp

        @forelse ($sorted as $d)
        @php
            $palettes = ['pal-blue','pal-teal','pal-amber','pal-coral'];
            $pal = $palettes[$loop->index % 4];
            $initials = collect(explode(' ', $d->nama_lengkap ?? auth()->user()->nama_lengkap ?? 'U'))
                ->take(2)->map(fn($w) => strtoupper(substr($w,0,1)))->join('');
        @endphp

        <div class="isc-card {{ $d->status_approved == 0 ? 'isc-card-pending' : '' }}"
             data-status="{{ $d->status }}"
             data-approved="{{ $d->status_approved }}"
             data-month="{{ date('m', strtotime($d->tgl_izin)) }}"
             data-year="{{ date('Y', strtotime($d->tgl_izin)) }}">

            {{-- TOP ROW --}}
            <div class="isc-card-top">
                <div class="isc-left">
                    <div class="isc-avatar {{ $pal }}">{{ $initials }}</div>
                    <div class="isc-card-meta">
                        <div class="isc-card-date">{{ date('d F Y', strtotime($d->tgl_izin)) }}</div>
                        @if($d->status == 1)
                            <span class="isc-type-badge type-izin">
                                <ion-icon name="calendar-outline"></ion-icon> Izin
                            </span>
                        @elseif($d->status == 2)
                            <span class="isc-type-badge type-sakit">
                                <ion-icon name="medkit-outline"></ion-icon> Sakit
                            </span>
                        @elseif($d->status == 3)
                            <span class="isc-type-badge type-cuti">
                                <ion-icon name="airplane-outline"></ion-icon> Cuti
                            </span>
                        @endif
                    </div>
                </div>

                {{-- APPROVAL BADGE --}}
                @if($d->status_approved == 0)
                    <span class="isc-appr appr-pending">
                        <span class="isc-dot dot-pending"></span>Pending
                    </span>
                @elseif($d->status_approved == 1)
                    <span class="isc-appr appr-approved">
                        <span class="isc-dot dot-approved"></span>Disetujui
                    </span>
                @else
                    <span class="isc-appr appr-rejected">
                        <span class="isc-dot dot-rejected"></span>Ditolak
                    </span>
                @endif
            </div>

            {{-- KETERANGAN --}}
            <div class="isc-card-desc">
                <ion-icon name="document-text-outline"></ion-icon>
                {{ $d->keterangan }}
            </div>

            {{-- BUKTI SURAT --}}
            @if(!empty($d->bukti_surat))
            <div class="isc-card-footer">
                <button class="isc-bukti-btn"
                    onclick="iscViewAttachment(
                        '{{ asset('storage/uploads/izin/' . $d->bukti_surat) }}',
                        '{{ $d->bukti_surat }}'
                    )">
                    <ion-icon name="attach-outline"></ion-icon>
                    Lihat Bukti Surat
                    <ion-icon name="eye-outline" class="isc-eye"></ion-icon>
                </button>
            </div>
            @endif

        </div>
        @empty

        {{-- EMPTY STATE DATA KOSONG --}}
        <div class="isc-empty" id="emptyData">
            <div class="isc-empty-icon">
                <ion-icon name="document-text-outline"></ion-icon>
            </div>
            <div class="isc-empty-title">Belum Ada Pengajuan</div>
            <div class="isc-empty-sub">Kamu belum pernah mengajukan izin, sakit, atau cuti</div>
            <a href="/presensi/buatizin" class="isc-empty-btn">
                <ion-icon name="add-circle-outline"></ion-icon>
                Buat Pengajuan
            </a>
        </div>

        @endforelse
    </div>

    {{-- EMPTY STATE FILTER --}}
    <div class="isc-empty" id="emptyFilter" style="display:none;">
        <div class="isc-empty-icon" style="background:#f0f4ff;">
            <ion-icon name="search-outline" style="color:#185FA5;"></ion-icon>
        </div>
        <div class="isc-empty-title">Tidak Ada Data</div>
        <div class="isc-empty-sub">Tidak ada pengajuan di periode yang dipilih</div>
    </div>

</div>

{{-- ─── FAB ─── --}}
<a href="/presensi/buatizin" class="isc-fab" title="Buat pengajuan baru">
    <ion-icon name="add-outline"></ion-icon>
</a>

{{-- ─── MODAL VIEWER BUKTI SURAT ─── --}}
<div class="isc-modal-bg" id="iscModalBg" onclick="iscCloseModal(event)">
    <div class="isc-modal">
        <div class="isc-modal-head">
            <span class="isc-modal-title" id="iscModalTitle">Bukti Surat</span>
            <button class="isc-modal-close" onclick="iscCloseModalDirect()">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>
        <div class="isc-modal-body" id="iscModalBody">
            <img id="iscModalImg" src="" alt="Bukti" style="display:none;">
            <iframe id="iscModalPdf" src="" style="display:none;"></iframe>
            <div id="iscModalDoc" style="display:none;">
                <div class="isc-doc-placeholder">
                    <ion-icon name="document-text-outline"></ion-icon>
                    <p>File tidak dapat ditampilkan di browser</p>
                    <a id="iscDownloadLink" href="" download class="isc-dl-btn">
                        <ion-icon name="download-outline"></ion-icon>
                        Download File
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ STYLES ══════════════════════════════════ --}}
<style>
/* ─── BASE ─── */
.isc-wrap {
    padding: 70px 14px 100px;
    max-width: 500px;
    margin: 0 auto;
    font-family: -apple-system, 'Segoe UI', sans-serif;
}

/* ─── TOAST ─── */
.isc-toast {
    position: fixed;
    top: 70px;
    left: 14px;
    right: 14px;
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 14px;
    border-radius: 14px;
    font-size: 14px;
    font-weight: 500;
    color: #fff;
    animation: iscSlideDown 0.35s ease;
    box-shadow: 0 4px 18px rgba(0,0,0,0.15);
}

.isc-toast ion-icon { font-size: 22px; flex-shrink: 0; }
.isc-toast span { flex: 1; }
.isc-toast button { background: rgba(255,255,255,0.2); border: none; border-radius: 8px; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #fff; }
.isc-toast button ion-icon { font-size: 18px; }
.isc-toast-success { background: linear-gradient(135deg, #16a34a, #15803d); }
.isc-toast-error   { background: linear-gradient(135deg, #dc2626, #b91c1c); }

@keyframes iscSlideDown {
    from { opacity: 0; transform: translateY(-14px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ─── STATS ─── */
.isc-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 8px;
    margin-bottom: 14px;
}

.isc-stat {
    background: #fff;
    border-radius: 14px;
    border: 0.5px solid rgba(0,0,0,0.07);
    padding: 12px 8px;
    text-align: center;
}

.isc-stat-num   { font-size: 22px; font-weight: 700; color: #111; line-height: 1.1; }
.isc-stat-label { font-size: 10px; color: #999; text-transform: uppercase; letter-spacing: 0.04em; margin-top: 3px; }

.isc-num-pending  { color: #854F0B; }
.isc-num-approved { color: #3B6D11; }
.isc-num-rejected { color: #A32D2D; }

/* ─── PERIOD BAR ─── */
.isc-period-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    border-radius: 14px;
    border: 0.5px solid rgba(0,0,0,0.07);
    padding: 11px 14px;
    margin-bottom: 12px;
}

.isc-period-icon {
    width: 34px;
    height: 34px;
    background: #E6F1FB;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.isc-period-icon ion-icon { font-size: 18px; color: #185FA5; }

.isc-select {
    flex: 1;
    border: 0.5px solid rgba(0,0,0,0.12);
    border-radius: 10px;
    padding: 8px 10px;
    font-size: 13px;
    font-weight: 600;
    color: #111;
    background: #f8f9fa;
    outline: none;
    font-family: inherit;
    appearance: none;
    -webkit-appearance: none;
    min-width: 0;
}

.isc-select-year { flex: 0 0 90px; max-width: 90px; }

/* ─── TABS ─── */
.isc-tabs {
    display: flex;
    gap: 7px;
    margin-bottom: 12px;
    overflow-x: auto;
    padding-bottom: 2px;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}
.isc-tabs::-webkit-scrollbar { display: none; }

.isc-tab {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 9px 16px;
    border-radius: 22px;
    border: 1.5px solid rgba(0,0,0,0.1);
    background: #fff;
    font-size: 13px;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    white-space: nowrap;
    font-family: inherit;
    transition: background 0.2s, color 0.2s, border-color 0.2s, transform 0.15s;
    flex-shrink: 0;
}

.isc-tab ion-icon { font-size: 15px; }

.isc-tab.active {
    background: #185FA5;
    color: #fff;
    border-color: #185FA5;
    transform: scale(1.04);
    box-shadow: 0 3px 12px rgba(24,95,165,0.28);
}

/* ─── INFO ROW ─── */
.isc-info-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 2px;
    margin-bottom: 10px;
}

.isc-info-label { font-size: 13px; font-weight: 600; color: #555; }
.isc-info-count {
    font-size: 12px;
    font-weight: 600;
    color: #185FA5;
    background: #E6F1FB;
    padding: 3px 10px;
    border-radius: 20px;
}

/* ─── CARD ─── */
.isc-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.isc-card {
    background: #fff;
    border-radius: 18px;
    border: 0.5px solid rgba(0,0,0,0.07);
    padding: 14px 14px 12px;
    transition: transform 0.15s;
    animation: iscFadeUp 0.3s ease both;
}

.isc-card:active { transform: scale(0.985); }

.isc-card-pending {
    border-color: #EF9F27;
    border-width: 1.5px;
    background: linear-gradient(135deg, #fffbf2 0%, #fff 100%);
}

@keyframes iscFadeUp {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ─── CARD TOP ─── */
.isc-card-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 10px;
}

.isc-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.isc-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
    flex-shrink: 0;
}

.pal-blue   { background: #E6F1FB; color: #0C447C; }
.pal-teal   { background: #E1F5EE; color: #085041; }
.pal-amber  { background: #FAEEDA; color: #633806; }
.pal-coral  { background: #FAECE7; color: #712B13; }

.isc-card-date { font-size: 14px; font-weight: 700; color: #111; margin-bottom: 4px; }

.isc-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}
.isc-type-badge ion-icon { font-size: 12px; }

.type-izin  { background: #E6F1FB; color: #0C447C; }
.type-sakit { background: #FAEEDA; color: #633806; }
.type-cuti  { background: #EAF3DE; color: #27500A; }

/* ─── APPROVAL BADGE ─── */
.isc-appr {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 11px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
    white-space: nowrap;
}

.appr-pending  { background: #FAEEDA; color: #854F0B; }
.appr-approved { background: #EAF3DE; color: #3B6D11; }
.appr-rejected { background: #FCEBEB; color: #A32D2D; }

.isc-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
}
.dot-pending  { background: #EF9F27; animation: iscPulse 1.8s ease-in-out infinite; }
.dot-approved { background: #639922; }
.dot-rejected { background: #E24B4A; }

@keyframes iscPulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: 0.55; transform: scale(1.3); }
}

/* ─── CARD DESC ─── */
.isc-card-desc {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    font-size: 13px;
    color: #666;
    line-height: 1.55;
    padding-bottom: 2px;
}
.isc-card-desc ion-icon { font-size: 15px; flex-shrink: 0; margin-top: 1px; color: #aaa; }

/* ─── CARD FOOTER ─── */
.isc-card-footer {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 0.5px solid rgba(0,0,0,0.06);
}

.isc-bukti-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    background: #f5f6f8;
    border: 0.5px solid rgba(0,0,0,0.09);
    border-radius: 10px;
    font-size: 12px;
    font-weight: 600;
    color: #333;
    cursor: pointer;
    font-family: inherit;
    transition: background 0.15s;
}
.isc-bukti-btn:active { background: #ececec; }
.isc-bukti-btn ion-icon { font-size: 15px; }
.isc-bukti-btn .isc-eye { color: #185FA5; }

/* ─── EMPTY STATE ─── */
.isc-empty {
    text-align: center;
    padding: 44px 20px;
}

.isc-empty-icon {
    width: 64px;
    height: 64px;
    background: #f0f4ff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
}
.isc-empty-icon ion-icon { font-size: 30px; color: #185FA5; }

.isc-empty-title { font-size: 15px; font-weight: 700; color: #222; margin-bottom: 6px; }
.isc-empty-sub   { font-size: 13px; color: #999; line-height: 1.5; margin-bottom: 20px; }

.isc-empty-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 11px 24px;
    background: #185FA5;
    color: #fff;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    transition: opacity 0.15s;
}
.isc-empty-btn:active { opacity: 0.85; }
.isc-empty-btn ion-icon { font-size: 18px; }

/* ─── FAB ─── */
.isc-fab {
    position: fixed;
    bottom: 80px;
    right: 20px;
    width: 56px;
    height: 56px;
    background: #185FA5;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 26px;
    box-shadow: 0 4px 16px rgba(24,95,165,0.38);
    z-index: 100;
    text-decoration: none;
    transition: transform 0.2s, background 0.15s;
}
.isc-fab:hover  { background: #1450a0; transform: rotate(90deg); }
.isc-fab:active { transform: scale(0.9); }
.isc-fab ion-icon { pointer-events: none; }

/* ─── MODAL BUKTI SURAT ─── */
.isc-modal-bg {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.72);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 16px;
    animation: iscFade 0.25s ease;
}
.isc-modal-bg.open { display: flex; }

@keyframes iscFade {
    from { opacity: 0; }
    to   { opacity: 1; }
}

.isc-modal {
    background: #fff;
    border-radius: 20px;
    width: 100%;
    max-width: 540px;
    max-height: 88vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    animation: iscSlideUp 0.28s ease;
}

@keyframes iscSlideUp {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
}

.isc-modal-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 18px;
    border-bottom: 0.5px solid rgba(0,0,0,0.08);
    flex-shrink: 0;
}

.isc-modal-title { font-size: 15px; font-weight: 700; color: #111; }

.isc-modal-close {
    width: 34px;
    height: 34px;
    background: #fee2e2;
    border: none;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #dc2626;
}
.isc-modal-close ion-icon { font-size: 20px; }

.isc-modal-body {
    overflow: auto;
    flex: 1;
    background: #f3f4f6;
}

.isc-modal-body img  { width: 100%; height: auto; display: block; }
.isc-modal-body iframe { width: 100%; height: 75vh; border: none; }

.isc-doc-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 50px 20px;
    text-align: center;
}
.isc-doc-placeholder ion-icon { font-size: 64px; color: #bbb; margin-bottom: 14px; }
.isc-doc-placeholder p { font-size: 13px; color: #888; margin-bottom: 18px; }

.isc-dl-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 12px 24px;
    background: #185FA5;
    color: #fff;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
}
.isc-dl-btn ion-icon { font-size: 18px; }

/* ─── RESPONSIVE ─── */
@media (max-width: 380px) {
    .isc-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .isc-tab   { padding: 8px 13px; font-size: 12px; }
}
</style>

{{-- ═══ SCRIPT ══════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs        = document.querySelectorAll('.isc-tab');
    const cards       = Array.from(document.querySelectorAll('.isc-card'));
    const monthSel    = document.getElementById('monthFilter');
    const yearSel     = document.getElementById('yearFilter');
    const sectionLbl  = document.getElementById('sectionLabel');
    const countLbl    = document.getElementById('visibleCount');
    const listEl      = document.getElementById('iscList');
    const emptyFilter = document.getElementById('emptyFilter');

    let activeTab   = 'all';
    let activeMonth = '';
    let activeYear  = yearSel ? yearSel.value : '';

    /* Set bulan saat ini sebagai default */
    if (monthSel) {
        const cm = (new Date().getMonth() + 1).toString().padStart(2, '0');
        monthSel.value = cm;
        activeMonth = cm;
    }

    const tabLabels = { all:'Semua pengajuan', '1':'Izin', '2':'Sakit', '3':'Cuti' };

    function applyFilters() {
        let visible = cards.filter(c => {
            const matchTab   = activeTab === 'all' || c.dataset.status === activeTab;
            const matchMonth = !activeMonth || c.dataset.month === activeMonth;
            const matchYear  = !activeYear  || c.dataset.year  === activeYear;
            return matchTab && matchMonth && matchYear;
        });

        /* Sort: pending duluan */
        visible.sort((a, b) => {
            const pa = parseInt(a.dataset.approved), pb = parseInt(b.dataset.approved);
            if (pa === 0 && pb !== 0) return -1;
            if (pa !== 0 && pb === 0) return 1;
            return 0;
        });

        /* Hide semua */
        cards.forEach(c => { c.style.display = 'none'; c.style.animationDelay = ''; });

        if (visible.length === 0) {
            if (emptyFilter) emptyFilter.style.display = 'block';
        } else {
            if (emptyFilter) emptyFilter.style.display = 'none';
            visible.forEach((c, i) => {
                c.style.animationDelay = (i * 40) + 'ms';
                c.style.display = '';
                listEl.appendChild(c);
            });
        }

        sectionLbl.textContent = tabLabels[activeTab] || 'Semua';
        countLbl.textContent   = visible.length + ' data';
    }

    tabs.forEach(btn => {
        btn.addEventListener('click', function () {
            tabs.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            activeTab = this.dataset.filter;
            applyFilters();
        });
    });

    if (monthSel) monthSel.addEventListener('change', function () { activeMonth = this.value; applyFilters(); });
    if (yearSel)  yearSel.addEventListener('change',  function () { activeYear  = this.value; applyFilters(); });

    applyFilters();

    /* Auto-dismiss toast */
    const toast = document.getElementById('iscToast');
    if (toast) setTimeout(() => toast.remove(), 5000);
});

/* ─── MODAL BUKTI SURAT ─── */
function iscViewAttachment(filePath, fileName) {
    const bg  = document.getElementById('iscModalBg');
    const img = document.getElementById('iscModalImg');
    const pdf = document.getElementById('iscModalPdf');
    const doc = document.getElementById('iscModalDoc');
    const ttl = document.getElementById('iscModalTitle');
    const dl  = document.getElementById('iscDownloadLink');

    img.style.display = pdf.style.display = doc.style.display = 'none';
    img.src = ''; pdf.src = '';
    ttl.textContent = fileName;

    const ext = fileName.split('.').pop().toLowerCase();

    if (['jpg','jpeg','png','gif','bmp','webp'].includes(ext)) {
        img.src = filePath;
        img.style.display = 'block';
    } else if (ext === 'pdf') {
        pdf.src = filePath;
        pdf.style.display = 'block';
    } else {
        dl.href = filePath;
        dl.download = fileName;
        doc.style.display = 'flex';
    }

    bg.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function iscCloseModal(e) {
    if (e && e.target !== document.getElementById('iscModalBg')) return;
    iscCloseModalDirect();
}

function iscCloseModalDirect() {
    const bg = document.getElementById('iscModalBg');
    bg.classList.remove('open');
    document.body.style.overflow = '';
    document.getElementById('iscModalImg').src = '';
    document.getElementById('iscModalPdf').src = '';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') iscCloseModalDirect();
});
</script>

@endsection