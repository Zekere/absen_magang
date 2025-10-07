  <div class="wrapper">
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <div class="logo-header" data-background-color="dark">
            <a href="{{ asset('panel/dashboardadmin') }}" class="logo">
              <img
                src=" {{ asset('assets/img/icon/puprlogo.png') }}"
                alt="navbar brand"
                class="navbar-brand"
                height="20"
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
          </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-item active">
                
                  
                  <a href="/panel/dashboardadmin">
                  <p>Dashboard</p>
                
              
                </a>
                <div class="collapse" id="dashboard">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="{{ asset('Template_admin/demo1/index.html') }}">
                        <span class="sub-item">Dashboard </span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
             
              <li class="nav-item ">
                <a data-bs-toggle="collapse" href="#base">
                  
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
              </li>
                   
          </div>
        </div>
      </div>