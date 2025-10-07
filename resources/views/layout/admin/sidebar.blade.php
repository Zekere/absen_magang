<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
  <div class="sidebar-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
      <a href="index.html" class="logo">
        <img
          src="{{ asset ('assets/img/LogoPuprText.png')}}"
          alt="navbar brand"
          class="navbar-brand"
          height="45"
        />
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
    <!-- End Logo Header -->
  </div>
  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <ul class="nav nav-secondary">
       <li class="nav-item active">
          <a href="/panel/dashboardadmin">
            <i class="fas fa-home"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-section">
          <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
          </span>
          <h4 class="text-section">Components</h4>
        </li>
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#base">
            <i class="fas fa-layer-group"></i>
            <p>Data Master</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="base">
            <ul class="nav nav-collapse">
              <li>
                <a href="/karyawan">
                  <span class="sub-item">Data Karyawan</span>
                </a>
              </li>
              <li>
                <a href="components/buttons.html">
                  <span class="sub-item">Buttons</span>
                </a>
<<<<<<< HEAD
                <div class="collapse" id="base">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="/karyawan">
                        <span class="sub-item">Data Karyawan</span>
                      </a>
                    </li>
                    <li>
                      <a href="/departemen">
                        <span class="sub-item">Departemen</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ asset('components/gridsystem.html') }}">
                        <span class="sub-item">Grid System</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ asset('components/panels.html') }}">
                        <span class="sub-item">Panels</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ asset('components/notifications.html') }}">
                        <span class="sub-item">Notifications</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ asset('components/sweetalert.html') }}">
                        <span class="sub-item">Sweet Alert</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ asset('components/font-awesome-icons.html') }}">
                        <span class="sub-item">Font Awesome Icons</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ asset('components/simple-line-icons.html') }}">
                        <span class="sub-item">Simple Line Icons</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ asset('components/typography.html') }}">
                        <span class="sub-item">Typography</span>
                      </a>
                    </li>
                  </ul>
                </div>
=======
>>>>>>> 66b9b460b1e6f77cb70fbf7c4f533e003fce493b
              </li>
              <li>
                <a href="components/gridsystem.html">
                  <span class="sub-item">Grid System</span>
                </a>
              </li>
              <li>
                <a href="components/panels.html">
                  <span class="sub-item">Panels</span>
                </a>
              </li>
              <li>
                <a href="components/notifications.html">
                  <span class="sub-item">Notifications</span>
                </a>
              </li>
              <li>
                <a href="components/sweetalert.html">
                  <span class="sub-item">Sweet Alert</span>
                </a>
              </li>
              <li>
                <a href="components/font-awesome-icons.html">
                  <span class="sub-item">Font Awesome Icons</span>
                </a>
              </li>
              <li>
                <a href="components/simple-line-icons.html">
                  <span class="sub-item">Simple Line Icons</span>
                </a>
              </li>
              <li>
                <a href="components/typography.html">
                  <span class="sub-item">Typography</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>
<!-- End Sidebar -->