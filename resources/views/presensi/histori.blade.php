@extends('layout.presensi')

@section('header')
<div class="appHeader bg-primary text-light shadow-sm">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle fw-semibold">Histori Presensi</div>
    <div class="right"></div>
</div>
@endsection

@section('content')

<div class="hp-wrap">

    {{-- ─── CLOCK CARD ─── --}}
    <div class="hp-clock">
        <div class="hp-clock-left">
            <div class="hp-clock-time" id="today-time">00:00:00</div>
            <div class="hp-clock-date" id="today-date">Memuat...</div>
        </div>
        <div class="hp-clock-icon">
            <ion-icon name="time-outline"></ion-icon>
        </div>
    </div>

    {{-- ─── FILTER PERIODE ─── --}}
    <div class="hp-section-label">
        <ion-icon name="calendar-outline"></ion-icon>
        Filter Periode
    </div>

    <div class="hp-filter-card">
        <div class="hp-filter-row">
            <div class="hp-filter-field">
                <div class="hp-field-label">Bulan</div>
                <select name="bulan" id="bulan" class="hp-select">
                    <option value="">Pilih Bulan</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ (int)date('m') == $i ? 'selected' : '' }}>
                            {{ $namabulan[$i] }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="hp-filter-field hp-filter-field-year">
                <div class="hp-field-label">Tahun</div>
                <select name="tahun" id="tahun" class="hp-select">
                    <option value="">Tahun</option>
                    @php $cy = date('Y'); @endphp
                    @for ($y = 2023; $y <= $cy; $y++)
                        <option value="{{ $y }}" {{ $y == $cy ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="hp-filter-btns">
            <button class="hp-btn-search" id="get">
                <ion-icon name="search-outline"></ion-icon>
                Tampilkan
            </button>
            <button class="hp-btn-reset" id="reset">
                <ion-icon name="refresh-outline"></ion-icon>
            </button>
        </div>
    </div>

    {{-- ─── LOADING ─── --}}
    <div class="hp-loading d-none" id="loading">
        <div class="hp-loading-spinner"></div>
        <div class="hp-loading-text">Memuat data histori...</div>
    </div>

    {{-- ─── HASIL HISTORI ─── --}}
    <div id="showhistori"></div>

</div>

{{-- ─── LIGHTBOX MODAL ─── --}}
<div id="lightboxModal" class="hp-lb-bg" onclick="hpCloseLightbox(event)">
    <div class="hp-lb-sheet">
        <div class="hp-lb-handle"></div>
        <button class="hp-lb-close" onclick="hpCloseLightboxDirect()">
            <ion-icon name="close-outline"></ion-icon>
        </button>

        <img id="lightboxImage" src="" alt="Foto Presensi" class="hp-lb-img">

        <div class="hp-lb-info">
            <div class="hp-lb-title" id="lightboxTitle">Foto Presensi</div>
            <div class="hp-lb-date-badge" id="lightboxDate"></div>
        </div>

        <div class="hp-lb-times">
            <div class="hp-lb-time-block hp-time-in">
                <div class="hp-lb-time-label">Jam Masuk</div>
                <div class="hp-lb-time-val" id="lightboxJamIn">-</div>
            </div>
            <div class="hp-lb-time-block hp-time-out">
                <div class="hp-lb-time-label">Jam Pulang</div>
                <div class="hp-lb-time-val" id="lightboxJamOut">-</div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ STYLES ═══════════════════════════════════ --}}
<style>
/* ─── BASE ─── */
.hp-wrap {
    padding: 72px 14px 100px;
    max-width: 500px;
    margin: 0 auto;
    font-family: -apple-system, 'Segoe UI', sans-serif;
}

/* ─── CLOCK ─── */
.hp-clock {
    background: #185FA5;
    border-radius: 20px;
    padding: 20px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    position: relative;
    overflow: hidden;
}

.hp-clock::after {
    content: '';
    position: absolute;
    right: -20px;
    top: -20px;
    width: 120px;
    height: 120px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
}

.hp-clock-time {
    font-size: 32px;
    font-weight: 700;
    color: #fff;
    letter-spacing: 2px;
    font-variant-numeric: tabular-nums;
    line-height: 1;
    margin-bottom: 6px;
}

.hp-clock-date {
    font-size: 12px;
    color: rgba(255,255,255,0.75);
    font-weight: 500;
    line-height: 1.4;
}

.hp-clock-icon {
    width: 56px;
    height: 56px;
    background: rgba(255,255,255,0.14);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    z-index: 1;
}
.hp-clock-icon ion-icon { font-size: 28px; color: rgba(255,255,255,0.9); }

/* ─── SECTION LABEL ─── */
.hp-section-label {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 13px;
    font-weight: 700;
    color: #555;
    margin-bottom: 8px;
    padding: 0 2px;
}
.hp-section-label ion-icon { font-size: 16px; color: #185FA5; }

/* ─── FILTER CARD ─── */
.hp-filter-card {
    background: #fff;
    border-radius: 18px;
    border: 0.5px solid rgba(0,0,0,0.07);
    padding: 16px 16px 14px;
    margin-bottom: 16px;
}

.hp-filter-row {
    display: flex;
    gap: 10px;
    margin-bottom: 12px;
}

.hp-filter-field       { flex: 1; }
.hp-filter-field-year  { flex: 0 0 105px; }

.hp-field-label {
    font-size: 11px;
    font-weight: 700;
    color: #aaa;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 6px;
}

.hp-select {
    width: 100%;
    padding: 11px 12px;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    color: #111;
    background: #f8f9fa;
    font-family: inherit;
    outline: none;
    appearance: none;
    -webkit-appearance: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.hp-select:focus {
    border-color: #185FA5;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(24,95,165,0.1);
}

/* ─── FILTER BUTTONS ─── */
.hp-filter-btns {
    display: flex;
    gap: 10px;
}

.hp-btn-search {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 13px;
    background: #185FA5;
    color: #fff;
    border: none;
    border-radius: 14px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: opacity 0.15s, transform 0.15s;
    box-shadow: 0 3px 12px rgba(24,95,165,0.28);
}
.hp-btn-search ion-icon { font-size: 18px; }
.hp-btn-search:active   { opacity: 0.85; transform: scale(0.98); }

.hp-btn-reset {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    border: 1.5px solid #e5e7eb;
    border-radius: 14px;
    cursor: pointer;
    font-family: inherit;
    transition: background 0.15s;
    flex-shrink: 0;
}
.hp-btn-reset ion-icon { font-size: 20px; color: #555; }
.hp-btn-reset:active   { background: #e5e7eb; }

/* ─── LOADING ─── */
.hp-loading {
    text-align: center;
    padding: 40px 0;
}

.hp-loading-spinner {
    width: 36px;
    height: 36px;
    border: 3px solid #e5e7eb;
    border-top-color: #185FA5;
    border-radius: 50%;
    margin: 0 auto 12px;
    animation: hpSpin 0.7s linear infinite;
}

@keyframes hpSpin {
    to { transform: rotate(360deg); }
}

.hp-loading-text {
    font-size: 13px;
    color: #aaa;
    font-weight: 500;
}

/* ─── HASIL HISTORI (render dari AJAX) ─── */
#showhistori { animation: hpFadeUp 0.35s ease; }

@keyframes hpFadeUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ─── LIGHTBOX BOTTOM SHEET ─── */
.hp-lb-bg {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    z-index: 9999;
    align-items: flex-end;
    justify-content: center;
}
.hp-lb-bg.open { display: flex; }

.hp-lb-sheet {
    background: #fff;
    border-radius: 24px 24px 0 0;
    width: 100%;
    max-width: 500px;
    padding: 16px 18px 36px;
    animation: hpSlideUp 0.3s cubic-bezier(0.4,0,0.2,1);
    position: relative;
    max-height: 92vh;
    overflow-y: auto;
}

@keyframes hpSlideUp {
    from { transform: translateY(100%); }
    to   { transform: translateY(0); }
}

.hp-lb-handle {
    width: 38px;
    height: 4px;
    border-radius: 2px;
    background: #e0e0e0;
    margin: 0 auto 16px;
}

.hp-lb-close {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 34px;
    height: 34px;
    background: #FCEBEB;
    border: none;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #A32D2D;
}
.hp-lb-close ion-icon { font-size: 20px; }

.hp-lb-img {
    width: 100%;
    border-radius: 16px;
    object-fit: cover;
    max-height: 320px;
    display: block;
    margin-bottom: 14px;
    background: #f3f4f6;
}

.hp-lb-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
    gap: 10px;
}

.hp-lb-title {
    font-size: 15px;
    font-weight: 700;
    color: #111;
}

.hp-lb-date-badge {
    background: #E6F1FB;
    color: #0C447C;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
    white-space: nowrap;
}

.hp-lb-times {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.hp-lb-time-block {
    background: #f8f9fa;
    border-radius: 14px;
    padding: 14px 14px;
    border-left: 3px solid transparent;
}

.hp-time-in  { border-left-color: #3B6D11; }
.hp-time-out { border-left-color: #A32D2D; }

.hp-lb-time-label {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 700;
    color: #aaa;
    margin-bottom: 6px;
}

.hp-lb-time-val {
    font-size: 20px;
    font-weight: 700;
    color: #111;
    font-variant-numeric: tabular-nums;
}

/* ─── RESPONSIVE ─── */
@media (max-width: 380px) {
    .hp-clock-time { font-size: 26px; }
    .hp-lb-times   { grid-template-columns: 1fr; }
}
</style>

@endsection

@push('scripts')
<script>
/* ─── LIGHTBOX ─── */
function openLightbox(imageSrc, tanggal, jamIn, jamOut, type) {
    document.getElementById('lightboxImage').src = imageSrc;
    document.getElementById('lightboxTitle').textContent =
        type === 'in' ? 'Foto Masuk' : type === 'out' ? 'Foto Pulang' : 'Detail Presensi';
    document.getElementById('lightboxDate').textContent = tanggal;
    document.getElementById('lightboxJamIn').innerHTML  = jamIn  || '<span style="font-size:13px;color:#aaa">Belum absen</span>';
    document.getElementById('lightboxJamOut').innerHTML = jamOut || '<span style="font-size:13px;color:#aaa">Belum absen</span>';

    document.getElementById('lightboxModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function hpCloseLightbox(e) {
    if (e.target === document.getElementById('lightboxModal')) hpCloseLightboxDirect();
}

function hpCloseLightboxDirect() {
    document.getElementById('lightboxModal').classList.remove('open');
    document.body.style.overflow = '';
    document.getElementById('lightboxImage').src = '';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') hpCloseLightboxDirect();
});

/* ─── JQUERY ─── */
$(function () {

    /* AJAX Tampilkan Histori */
    $('#get').on('click', function (e) {
        e.preventDefault();

        const bulan = $('#bulan').val();
        const tahun = $('#tahun').val();

        if (!bulan || !tahun) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Periode',
                text: 'Silakan pilih bulan dan tahun terlebih dahulu.',
                confirmButtonColor: '#185FA5'
            });
            return;
        }

        $('#loading').removeClass('d-none');
        $('#showhistori').html('');

        $.ajax({
            type: 'POST',
            url: '/gethistori',
            data: {
                _token: '{{ csrf_token() }}',
                bulan: bulan,
                tahun: tahun
            },
            cache: false,
            success: function (respond) {
                $('#loading').addClass('d-none');
                $('#showhistori').html(respond);
            },
            error: function () {
                $('#loading').addClass('d-none');
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memuat',
                    text: 'Tidak dapat memuat data histori. Silakan coba lagi.',
                    confirmButtonColor: '#185FA5'
                });
            }
        });
    });

    /* Reset Filter */
    $('#reset').on('click', function (e) {
        e.preventDefault();
        $('#bulan').val('');
        $('#tahun').val('{{ date("Y") }}');
        $('#showhistori').html('');
    });

    /* Real-time Clock */
    function updateClock() {
        const now  = new Date();
        const h    = String(now.getHours()).padStart(2, '0');
        const m    = String(now.getMinutes()).padStart(2, '0');
        const s    = String(now.getSeconds()).padStart(2, '0');
        const date = now.toLocaleDateString('id-ID', {
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
        });

        $('#today-time').text(`${h}:${m}:${s}`);
        $('#today-date').text(date);
    }

    setInterval(updateClock, 1000);
    updateClock();

    /* Auto load saat halaman dibuka */
    setTimeout(() => $('#get').trigger('click'), 400);
});
</script>
@endpush