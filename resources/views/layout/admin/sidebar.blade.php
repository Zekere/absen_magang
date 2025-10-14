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

        <!-- Konfigurasi -->
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#konfigurasiMenu" role="button" aria-expanded="false" aria-controls="konfigurasiMenu">
            <i class="fas fa-cogs"></i>
            <p>Konfigurasi</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="konfigurasiMenu" data-bs-parent=".sidebar-content">
            <ul class="nav nav-collapse">
              <li><a href="/konfigurasi"><span class="sub-item">Lokasi Kantor</span></a></li>
              <li><a href="/presensi/izinsakit"><span class="sub-item">Data Izin & Sakit</span></a></li>
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
</style>
