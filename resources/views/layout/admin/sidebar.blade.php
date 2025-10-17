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
            <i class="fas fa-home"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Section Title -->
        <li class="nav-section">
          <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
          <h4 class="text-section">Components</h4>
        </li>

        <!-- Data Master -->
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#dataMasterMenu" role="button" aria-expanded="false" aria-controls="dataMasterMenu">
            <i class="fas fa-database"></i>
            <p>Data Master</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="dataMasterMenu" data-bs-parent=".sidebar-content">
            <ul class="nav nav-collapse">
              <li><a href="/admin"><span class="sub-item">Data Admin</span></a>
              <li><a href="/karyawan"><span class="sub-item">Data Karyawan</span></a></li>
              <li><a href="/departemen"><span class="sub-item">Departemen</span></a></li>
            </ul>
          </div>
        </li>

        <!-- Monitoring -->
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#monitoringMenu" role="button" aria-expanded="false" aria-controls="monitoringMenu">
            <i class="fas fa-eye"></i>
            <p>Monitoring</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="monitoringMenu" data-bs-parent=".sidebar-content">
            <ul class="nav nav-collapse">
              <li><a href="/presensi/monitoring"><span class="sub-item">Monitoring Presensi</span></a></li>
            </ul>
          </div>
        </li>

        <!-- Konfigurasi dengan Notifikasi -->
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#konfigurasiMenu" role="button" aria-expanded="false" aria-controls="konfigurasiMenu">
            <i class="fas fa-cogs"></i>
            <p>Konfigurasi</p>
            @php
              $pendingCount = DB::table('pengajuan_izin')
                  ->where('status_approved', '0')
                  ->count();
            @endphp
            @if($pendingCount > 0)
              <span class="notification-badge pulse">{{ $pendingCount }}</span>
            @endif
            <span class="caret"></span>
          </a>
          <div class="collapse" id="konfigurasiMenu" data-bs-parent=".sidebar-content">
            <ul class="nav nav-collapse">
              <li><a href="/konfigurasi"><span class="sub-item">Lokasi Kantor</span></a></li>
              <li>
                <a href="/presensi/izinsakit">
                  <span class="sub-item">Data Izin & Sakit</span>
                  @if($pendingCount > 0)
                    <span class="sub-notification-badge">{{ $pendingCount }}</span>
                    <span class="notification-dot"></span>
                  @endif
                </a>
              </li>
            </ul>
          </div>
        </li>

        <!-- Laporan -->
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#laporanMenu" role="button" aria-expanded="false" aria-controls="laporanMenu">
            <i class="fas fa-file-alt"></i>
            <p>Laporan</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="laporanMenu" data-bs-parent=".sidebar-content">
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
/* ===== Notification Badge Styles ===== */

/* Badge pada menu utama (Konfigurasi) */
.notification-badge {
  position: absolute;
  right: 45px;
  top: 50%;
  transform: translateY(-50%);
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
  color: white;
  font-size: 11px;
  font-weight: 700;
  min-width: 20px;
  height: 20px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 6px;
  box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
  z-index: 10;
}

/* Pulse animation untuk badge */
.notification-badge.pulse {
  animation: badgePulseAnimation 2s ease-in-out infinite;
}

@keyframes badgePulseAnimation {
  0%, 100% {
    box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
  }
  50% {
    box-shadow: 0 0 0 8px rgba(239, 68, 68, 0);
  }
}

/* Badge pada submenu (Data Izin & Sakit) */
.sub-notification-badge {
  background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
  color: white;
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
  box-shadow: 0 2px 6px rgba(245, 158, 11, 0.4);
  animation: subBadgePulse 2s ease-in-out infinite;
}

@keyframes subBadgePulse {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
}

/* Dot indicator untuk submenu */
.notification-dot {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  width: 8px;
  height: 8px;
  background: #ef4444;
  border-radius: 50%;
  animation: dotBlink 1.5s ease-in-out infinite;
}

@keyframes dotBlink {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.3;
  }
}

/* Submenu styling */
.nav-collapse li a {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.nav-collapse li a .sub-item {
  flex: 1;
}

/* ===== Existing Hover Effects ===== */
.sidebar .nav-item a {
  transition: all 0.3s ease;
  position: relative;
}

.sidebar .nav-item a:hover {
  background-color: rgba(255, 255, 255, 0.08);
  padding-left: 20px;
  transform: translateX(5px);
}

.sidebar .nav-item a:hover i {
  color: #1572e8;
  transform: scale(1.1);
}

.sidebar .nav-collapse li a:hover {
  background-color: rgba(255, 255, 255, 0.06);
  padding-left: 25px;
}

.sidebar .nav-collapse li a:hover .sub-item {
  color: #1572e8;
  font-weight: 500;
}

/* Active state */
.sidebar .nav-item.active > a {
  background-color: rgba(21, 114, 232, 0.2);
  border-left: 3px solid #1572e8;
}

/* Caret animation */
.nav-item a .caret {
  transition: transform 0.3s ease;
}
.nav-item a[aria-expanded="true"] .caret {
  transform: rotate(180deg);
}

/* Hover effect untuk notification badge */
.nav-item:hover .notification-badge {
  transform: translateY(-50%) scale(1.1);
}

.nav-collapse li a:hover .sub-notification-badge {
  transform: scale(1.15);
}

/* Responsive adjustment */
@media (max-width: 991px) {
  .notification-badge {
    right: 35px;
  }
}
</style>

<script>
// Auto refresh notification count setiap 30 detik (opsional)
setInterval(function() {
  // Anda bisa menambahkan AJAX call untuk update badge count tanpa reload
  // fetch('/api/pending-izin-count')
  //   .then(response => response.json())
  //   .then(data => {
  //     document.querySelector('.notification-badge').textContent = data.count;
  //   });
}, 30000);
</script>