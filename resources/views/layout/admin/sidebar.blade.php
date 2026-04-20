<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
  <div class="sidebar-logo">
    <div class="logo-header" data-background-color="dark">
      <a href="/panel/dashboardadmin" class="logo">
        <img src="{{ asset('assets/img/LogoPuprText.png') }}" alt="navbar brand" class="navbar-brand" height="45" />
      </a>
      <div class="nav-toggle">
        <button class="btn btn-toggle toggle-sidebar">
          <i class="gg-menu-right"></i>
        </button>
        <button class="btn btn-toggle sidenav-toggler">
          <i class="gg-menu-left"></i>
        </button>
      </div>
      <button class="topbar-toggler more">
        <i class="gg-more-vertical-alt"></i>
      </button>
    </div>
  </div>

  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <ul class="nav nav-secondary">

        <!-- Dashboard -->
        <li class="nav-item active">
          <a href="/panel/dashboardadmin">
            <i class="bi bi-dash-square"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-section">
          <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
          <h4 class="text-section">Components</h4>
        </li>

        <!-- Data Master -->
        <li class="nav-item sdb-item">
          <a href="javascript:void(0)" class="sdb-trigger">
            <i class="fas fa-database"></i>
            <p>Data Master</p>
            <span class="caret"></span>
          </a>
          <div class="sdb-sub">
            <ul class="nav nav-collapse">
              <li><a href="/admin"><span class="sub-item">Data Admin</span></a></li>
              <li><a href="/karyawan"><span class="sub-item">Data Karyawan</span></a></li>
              <li><a href="/departemen"><span class="sub-item">Departemen</span></a></li>
            </ul>
          </div>
        </li>

        <!-- Monitoring -->
        <li class="nav-item sdb-item">
          <a href="javascript:void(0)" class="sdb-trigger">
            <i class="fas fa-eye"></i>
            <p>Monitoring</p>
            @php
              $pendingCount = DB::table('pengajuan_izin')
                  ->where('status_approved', '0')
                  ->count();
            @endphp
            <span class="sdb-badge" id="mainNotificationBadge"
                  style="{{ $pendingCount > 0 ? '' : 'display:none;' }}">{{ $pendingCount }}</span>
            <span class="caret"></span>
          </a>
          <div class="sdb-sub">
            <ul class="nav nav-collapse">
              <li>
                <a href="/presensi/monitoring">
                  <span class="sub-item">Monitoring Presensi</span>
                </a>
              </li>
              <li>
                <a href="/presensi/izinsakit">
                  <span class="sub-item">Data Izin &amp; Sakit</span>
                  <span class="sdb-sub-badge" id="subNotificationBadge"
                        style="{{ $pendingCount > 0 ? '' : 'display:none;' }}">{{ $pendingCount }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <!-- Konfigurasi -->
        <li class="nav-item sdb-item">
          <a href="javascript:void(0)" class="sdb-trigger">
            <i class="fas fa-cogs"></i>
            <p>Konfigurasi</p>
            <span class="caret"></span>
          </a>
          <div class="sdb-sub">
            <ul class="nav nav-collapse">
              <li><a href="/konfigurasi"><span class="sub-item">Lokasi Kantor</span></a></li>
              <li><a href="/jamkerja"><span class="sub-item">Jam Kerja</span></a></li>
            </ul>
          </div>
        </li>

        <!-- Lembur -->
        <li class="nav-item sdb-item">
          <a href="javascript:void(0)" class="sdb-trigger">
            <i class="fas fa-business-time"></i>
            <p>Lembur</p>
            <span class="caret"></span>
          </a>
          <div class="sdb-sub">
            <ul class="nav nav-collapse">
              <li><a href="/admin/lembur/data"><span class="sub-item">Data Lembur</span></a></li>
              <li><a href="/admin/lembur/laporan"><span class="sub-item">Laporan Lembur</span></a></li>
            </ul>
          </div>
        </li>

        <!-- Laporan -->
        <li class="nav-item sdb-item">
          <a href="javascript:void(0)" class="sdb-trigger">
            <i class="fas fa-file-alt"></i>
            <p>Laporan</p>
            <span class="caret"></span>
          </a>
          <div class="sdb-sub">
            <ul class="nav nav-collapse">
              <li><a href="/presensi/laporan"><span class="sub-item">Laporan Presensi</span></a></li>
              <li><a href="/presensi/rekap"><span class="sub-item">Rekap Presensi</span></a></li>
            </ul>
          </div>
        </li>

      </ul>
    </div>
  </div>
</div>

<style>
/* Semua scoped ke .sidebar agar tidak merembet ke template */

.sidebar .sdb-sub {
  display: none;
  overflow: hidden;
}

.sidebar .sdb-item.sdb-open > .sdb-sub {
  display: block;
}

.sidebar .sdb-trigger .caret {
  display: inline-block;
  transition: transform 0.25s ease;
}
.sidebar .sdb-item.sdb-open > .sdb-trigger .caret {
  transform: rotate(180deg);
}

/* Badge utama */
.sidebar .sdb-badge {
  position: absolute;
  right: 45px;
  top: 50%;
  transform: translateY(-50%);
  background: linear-gradient(135deg, #ef4444, #dc2626);
  color: #fff;
  font-size: 11px;
  font-weight: 700;
  min-width: 20px;
  height: 20px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 6px;
  z-index: 10;
  animation: sdbPulse 2s ease-in-out infinite;
}
@keyframes sdbPulse {
  0%,100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.6); }
  50%      { box-shadow: 0 0 0 7px rgba(239,68,68,0); }
}

/* Sub-badge */
.sidebar .sdb-sub-badge {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  color: #fff;
  font-size: 10px;
  font-weight: 700;
  min-width: 18px;
  height: 18px;
  border-radius: 9px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0 5px;
  margin-left: 8px;
  animation: sdbSubPulse 2s ease-in-out infinite;
}
@keyframes sdbSubPulse {
  0%,100% { transform: scale(1); }
  50%     { transform: scale(1.12); }
}

.sidebar .nav-collapse li a {
  position: relative;
  display: flex;
  align-items: center;
}
.sidebar .nav-collapse li a .sub-item { flex: 1; }

@media (max-width: 991px) {
  .sidebar .sdb-badge { right: 35px; }
}
</style>

<script>
(function () {
  'use strict';

  function initSidebar() {

    /* ── Toggle murni JS — tidak pakai Bootstrap collapse ── */
    document.querySelectorAll('.sidebar .sdb-trigger').forEach(function (trigger) {
      /* Klon trigger untuk buang semua event listener lama dari template */
      var fresh = trigger.cloneNode(true);
      trigger.parentNode.replaceChild(fresh, trigger);

      fresh.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var li = this.closest('.sdb-item');
        if (!li) return;

        var isOpen = li.classList.contains('sdb-open');
        var sub    = li.querySelector('.sdb-sub');

        if (isOpen) {
          li.classList.remove('sdb-open');
          sub.style.display = 'none';
        } else {
          li.classList.add('sdb-open');
          sub.style.display = 'block';
        }
      });
    });

    /* ── Auto-buka submenu halaman aktif ── */
    var path = window.location.pathname;
    document.querySelectorAll('.sidebar .sdb-sub a').forEach(function (link) {
      if (link.getAttribute('href') === path) {
        var li  = link.closest('.sdb-item');
        var sub = link.closest('.sdb-sub');
        if (li && sub) {
          li.classList.add('sdb-open');
          sub.style.display = 'block';
        }
      }
    });

    /* ── Polling notifikasi ── */
    var lastCount = parseInt(document.getElementById('mainNotificationBadge')
                    ? document.getElementById('mainNotificationBadge').textContent : '0') || 0;

    function updateBadge() {
      fetch('/api/pending-izin-count', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (!data.success) return;
        var count = parseInt(data.count) || 0;
        var main  = document.getElementById('mainNotificationBadge');
        var sub   = document.getElementById('subNotificationBadge');
        if (!main || !sub) return;

        if (count > 0) {
          main.textContent   = count;
          sub.textContent    = count;
          main.style.display = 'flex';
          sub.style.display  = 'inline-flex';
        } else {
          main.style.display = 'none';
          sub.style.display  = 'none';
        }
        lastCount = count;
      })
      .catch(function () {});
    }

    setInterval(updateBadge, 30000);
    document.addEventListener('visibilitychange', function () {
      if (!document.hidden) updateBadge();
    });
    setTimeout(updateBadge, 1000);
  }

  /* Jalankan setelah DOM siap */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSidebar);
  } else {
    initSidebar();
  }

})();
</script>