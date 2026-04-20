@extends('layout.admin.template')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@500&display=swap" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
/* ─────────────────────────────────────────
   SCOPE: .db-page — tidak menyentuh template
   ───────────────────────────────────────── */
.db-page {
    font-family: 'DM Sans', sans-serif;
    background: #f4f6fb;
    min-height: 100vh;
    padding: 24px 20px 40px;
}
.db-page * { box-sizing: border-box; font-family: 'DM Sans', sans-serif; }

/* ─── HEADER ─── */
.db-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 24px;
}
.db-header-title {
    font-size: 22px;
    font-weight: 700;
    color: #0f1c3f;
    margin: 0 0 4px;
    letter-spacing: -0.3px;
}
.db-header-sub {
    font-size: 13px;
    color: #8492a6;
    font-weight: 500;
}
.db-header-date {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    border: 1px solid #e4e9f0;
    border-radius: 12px;
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 600;
    color: #0f1c3f;
    white-space: nowrap;
}
.db-header-date i { color: #3b6ff0; font-size: 15px; }

/* ─── STAT CARDS ─── */
.db-stats {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 14px;
    margin-bottom: 24px;
}
@media(max-width:1100px){ .db-stats { grid-template-columns: repeat(3,1fr); } }
@media(max-width:680px) { .db-stats { grid-template-columns: repeat(2,1fr); } }

.db-stat {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8edf5;
    padding: 18px 16px 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    transition: transform .2s, box-shadow .2s;
    animation: dbFadeUp .4s ease both;
}
.db-stat:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(59,111,240,0.1);
}
.db-stat:nth-child(1){ animation-delay:.05s }
.db-stat:nth-child(2){ animation-delay:.10s }
.db-stat:nth-child(3){ animation-delay:.15s }
.db-stat:nth-child(4){ animation-delay:.20s }
.db-stat:nth-child(5){ animation-delay:.25s }

@keyframes dbFadeUp {
    from { opacity:0; transform:translateY(12px); }
    to   { opacity:1; transform:translateY(0); }
}

.db-stat-icon {
    width: 40px; height: 40px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.dsi-green  { background: #e8faf0; color: #16a34a; }
.dsi-blue   { background: #e8f0fe; color: #2563eb; }
.dsi-amber  { background: #fef9e7; color: #d97706; }
.dsi-red    { background: #fef2f2; color: #dc2626; }
.dsi-slate  { background: #f1f5f9; color: #475569; }

.db-stat-num {
    font-size: 28px;
    font-weight: 700;
    color: #0f1c3f;
    line-height: 1;
    font-family: 'DM Mono', monospace;
    letter-spacing: -1px;
}
.db-stat-label {
    font-size: 12px;
    font-weight: 600;
    color: #8492a6;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

/* ─── BOTTOM GRID ─── */
.db-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
@media(max-width:768px){ .db-grid { grid-template-columns: 1fr; } }

/* ─── CARD BASE ─── */
.db-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e8edf5;
    overflow: hidden;
}

.db-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #f0f3f9;
}
.db-card-title {
    font-size: 14px;
    font-weight: 700;
    color: #0f1c3f;
    display: flex;
    align-items: center;
    gap: 8px;
}
.db-card-title i { color: #3b6ff0; font-size: 16px; }

/* ─── CLOCK CARD ─── */
.db-clock-body {
    padding: 28px 20px 32px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(160deg, #0f1c3f 0%, #1a3a6e 100%);
    position: relative;
    overflow: hidden;
    min-height: 200px;
}
.db-clock-body::before {
    content: '';
    position: absolute;
    width: 260px; height: 260px;
    background: rgba(59,111,240,0.12);
    border-radius: 50%;
    top: -80px; right: -80px;
    pointer-events: none;
}
.db-clock-body::after {
    content: '';
    position: absolute;
    width: 160px; height: 160px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
    bottom: -50px; left: 20px;
    pointer-events: none;
}
.db-clock-time {
    font-size: 52px;
    font-weight: 500;
    color: #fff;
    font-family: 'DM Mono', monospace;
    letter-spacing: 3px;
    line-height: 1;
    position: relative; z-index: 1;
    margin-bottom: 10px;
}
.db-clock-date {
    font-size: 13px;
    font-weight: 500;
    color: rgba(255,255,255,0.6);
    position: relative; z-index: 1;
    letter-spacing: 0.02em;
}

/* ─── CALENDAR CARD ─── */
.db-cal-body { padding: 16px 18px 18px; }

.db-cal-nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
}
.db-cal-month {
    font-size: 14px;
    font-weight: 700;
    color: #0f1c3f;
}
.db-cal-btn {
    width: 30px; height: 30px;
    border-radius: 8px;
    border: 1px solid #e4e9f0;
    background: #f8faff;
    color: #3b6ff0;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    font-size: 13px;
    transition: background .15s;
}
.db-cal-btn:hover { background: #e8f0fe; }

.db-cal-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}
.db-cal-table thead th {
    padding: 6px 0;
    text-align: center;
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.db-cal-table thead th:first-child { color: #ef4444; }
.db-cal-table thead th:last-child  { color: #3b6ff0; }

.db-cal-table tbody td {
    padding: 6px 0;
    text-align: center;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    color: #374151;
    transition: background .15s, color .15s;
}
.db-cal-table tbody td:hover:not(.dbc-other) {
    background: #f0f4ff;
    color: #3b6ff0;
}
.db-cal-table tbody td.dbc-today {
    background: #3b6ff0;
    color: #fff;
    font-weight: 700;
    border-radius: 8px;
}
.db-cal-table tbody td.dbc-selected {
    background: #16a34a;
    color: #fff;
    font-weight: 700;
    border-radius: 8px;
}
.db-cal-table tbody td.dbc-other {
    color: #d1d5db;
    cursor: default;
    pointer-events: none;
}

.db-reset-btn {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 8px;
    border: 1px solid #e4e9f0;
    background: #f8faff;
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: background .15s;
}
.db-reset-btn:hover { background: #e8edf5; }
.db-reset-btn i { font-size: 13px; }

/* ─── LOADING STATE ─── */
.db-page .db-loading {
    opacity: 0.45;
    pointer-events: none;
    transition: opacity .3s;
}
</style>

<div class="db-page">

    {{-- ─── HEADER ─── --}}
    <div class="db-header">
        <div>
            <div class="db-header-title">
                <i class="bi bi-speedometer2" style="color:#3b6ff0;margin-right:8px;"></i>Dashboard
            </div>
            <div class="db-header-sub">Ringkasan kehadiran karyawan hari ini</div>
        </div>
        <div class="db-header-date">
            <i class="bi bi-calendar3"></i>
            <span id="selectedDateDisplay">{{ date('d F Y') }}</span>
        </div>
    </div>

    {{-- ─── STAT CARDS ─── --}}
    <div class="db-stats" id="dbStats">

        <div class="db-stat">
            <div class="db-stat-icon dsi-green">
                <i class="bi bi-fingerprint"></i>
            </div>
            <div class="db-stat-num" id="jmlKaryawan">{{ $jmlkaryawan }}</div>
            <div class="db-stat-label">Jumlah Karyawan</div>
        </div>

        <div class="db-stat">
            <div class="db-stat-icon dsi-blue">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="db-stat-num" id="jmlHadir">{{ $rekappresensi->jmlhadir ?? 0 }}</div>
            <div class="db-stat-label">Karyawan Hadir</div>
        </div>

        <div class="db-stat">
            <div class="db-stat-icon dsi-amber">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="db-stat-num" id="jmlIzin">{{ $rekapizin->jmlizin ?? 0 }}</div>
            <div class="db-stat-label">Karyawan Izin</div>
        </div>

        <div class="db-stat">
            <div class="db-stat-icon dsi-red">
                <i class="bi bi-thermometer-half"></i>
            </div>
            <div class="db-stat-num" id="jmlSakit">{{ $rekapizin->jmlsakit ?? 0 }}</div>
            <div class="db-stat-label">Karyawan Sakit</div>
        </div>

        <div class="db-stat">
            <div class="db-stat-icon dsi-slate">
                <i class="bi bi-alarm"></i>
            </div>
            <div class="db-stat-num" id="jmlTerlambat">{{ $rekappresensi->jmlterlambat ?? 0 }}</div>
            <div class="db-stat-label">Terlambat</div>
        </div>

    </div>

    {{-- ─── CLOCK + CALENDAR ─── --}}
    <div class="db-grid">

        {{-- JAM ─── --}}
        <div class="db-card">
            <div class="db-card-head">
                <div class="db-card-title">
                    <i class="bi bi-clock"></i> Waktu Sekarang
                </div>
            </div>
            <div class="db-clock-body">
                <div class="db-clock-time" id="digitalClock">00:00:00</div>
                <div class="db-clock-date" id="digitalDate"></div>
            </div>
        </div>

        {{-- KALENDER ─── --}}
        <div class="db-card">
            <div class="db-card-head">
                <div class="db-card-title">
                    <i class="bi bi-calendar3"></i> Kalender
                </div>
                <button class="db-reset-btn" id="resetDate">
                    <i class="bi bi-arrow-clockwise"></i> Hari Ini
                </button>
            </div>
            <div class="db-cal-body">
                <div class="db-cal-nav">
                    <button class="db-cal-btn" id="prevMonth">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <div class="db-cal-month" id="currentMonth"></div>
                    <button class="db-cal-btn" id="nextMonth">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
                <table class="db-cal-table">
                    <thead>
                        <tr>
                            <th>Min</th><th>Sen</th><th>Sel</th><th>Rab</th>
                            <th>Kam</th><th>Jum</th><th>Sab</th>
                        </tr>
                    </thead>
                    <tbody id="calBody"></tbody>
                </table>
            </div>
        </div>

    </div>

</div>{{-- end .db-page --}}

<script>
(function () {
    'use strict';

    var currentDate  = new Date();
    var selectedDate = new Date();

    var MONTHS = ['Januari','Februari','Maret','April','Mei','Juni',
                  'Juli','Agustus','September','Oktober','November','Desember'];
    var DAYS   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

    /* ─── Kalender ─── */
    function renderCalendar() {
        var y = currentDate.getFullYear();
        var m = currentDate.getMonth();

        document.getElementById('currentMonth').textContent = MONTHS[m] + ' ' + y;

        var firstDay    = new Date(y, m, 1).getDay();
        var daysInMonth = new Date(y, m + 1, 0).getDate();
        var prevDays    = new Date(y, m, 0).getDate();

        var today = new Date();
        var html  = '';
        var day   = 1;
        var next  = 1;

        for (var row = 0; row < 6; row++) {
            html += '<tr>';
            for (var col = 0; col < 7; col++) {
                if (row === 0 && col < firstDay) {
                    html += '<td class="dbc-other">' + (prevDays - firstDay + col + 1) + '</td>';
                } else if (day > daysInMonth) {
                    html += '<td class="dbc-other">' + (next++) + '</td>';
                } else {
                    var isToday    = day === today.getDate() && m === today.getMonth() && y === today.getFullYear();
                    var isSelected = day === selectedDate.getDate() && m === selectedDate.getMonth() && y === selectedDate.getFullYear();
                    var cls = isSelected ? 'dbc-selected' : (isToday ? 'dbc-today' : '');
                    var dateStr = y + '-' + pad(m + 1) + '-' + pad(day);
                    html += '<td class="' + cls + '" data-date="' + dateStr + '">' + day + '</td>';
                    day++;
                }
            }
            html += '</tr>';
            if (day > daysInMonth && next > 7) break;
        }

        document.getElementById('calBody').innerHTML = html;

        document.querySelectorAll('#calBody td:not(.dbc-other)').forEach(function (td) {
            td.addEventListener('click', function () {
                var d = this.getAttribute('data-date');
                if (!d) return;
                selectedDate = new Date(d + 'T00:00:00');
                renderCalendar();
                updateDashboard(d);
            });
        });
    }

    /* ─── Clock ─── */
    function updateClock() {
        var now = new Date();
        document.getElementById('digitalClock').textContent =
            pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
        document.getElementById('digitalDate').textContent =
            DAYS[now.getDay()] + ', ' + now.getDate() + ' ' + MONTHS[now.getMonth()] + ' ' + now.getFullYear();
    }

    /* ─── AJAX update stats ─── */
    function updateDashboard(dateStr) {
        var stats = document.getElementById('dbStats');
        stats.classList.add('db-loading');

        /* Update label tanggal terpilih */
        var d = new Date(dateStr + 'T00:00:00');
        document.getElementById('selectedDateDisplay').textContent =
            DAYS[d.getDay()] + ', ' + d.getDate() + ' ' + MONTHS[d.getMonth()] + ' ' + d.getFullYear();

        fetch('/admin/dashboard/data?date=' + dateStr, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            document.getElementById('jmlKaryawan').textContent  = data.jmlkaryawan  || 0;
            document.getElementById('jmlHadir').textContent     = data.jmlhadir     || 0;
            document.getElementById('jmlIzin').textContent      = data.jmlizin      || 0;
            document.getElementById('jmlSakit').textContent     = data.jmlsakit     || 0;
            document.getElementById('jmlTerlambat').textContent = data.jmlterlambat || 0;
            stats.classList.remove('db-loading');
        })
        .catch(function () {
            stats.classList.remove('db-loading');
        });
    }

    function pad(n) { return String(n).padStart(2, '0'); }

    function todayStr() {
        var t = new Date();
        return t.getFullYear() + '-' + pad(t.getMonth() + 1) + '-' + pad(t.getDate());
    }

    /* ─── Event listeners ─── */
    document.getElementById('prevMonth').addEventListener('click', function () {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });
    document.getElementById('nextMonth').addEventListener('click', function () {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });
    document.getElementById('resetDate').addEventListener('click', function () {
        selectedDate = new Date();
        currentDate  = new Date();
        renderCalendar();
        updateDashboard(todayStr());
    });

    /* ─── Init ─── */
    renderCalendar();
    updateClock();
    setInterval(updateClock, 1000);

})();
</script>

@endsection