<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Dashboard Admin')</title>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css"/>
        <link rel="icon" type="image/png" href="{{ asset('assets/img/icon/puprlogo.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/icon/puprlogo.png') }}">
  </head>

  <body>
    <div class="wrapper">
      {{-- Sidebar --}}
      @include('layout.admin.sidebar')

      <div class="main-panel">
        {{-- Navbar --}}
        @include('layout.admin.navbar')
        
        {{-- Content --}}
        <div class="container">
          <div class="page-wrapper">
            @yield('content')
          </div>
        </div>
        {{-- End Content --}}

        {{-- Footer --}}
        @include('layout.admin.footer')
      </div>
      {{-- End Main Panel --}}
    </div>
    {{-- End Wrapper --}}

    {{-- JS --}}
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Logout Confirmation Script --}}
    <script>
      document.getElementById("logout").addEventListener("click", function(e) {
        e.preventDefault();

        Swal.fire({
          title: 'Yakin mau logout?',
          text: "Kamu akan keluar dari akun ini.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, Logout',
          cancelButtonText: 'Batal',
          showClass: {
            popup: 'animate_animated animate_zoomIn'
          },
          hideClass: {
            popup: 'animate_animated animate_zoomOut'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "/logoutadmin";
          }
        });
      });
    </script>
  </body>
</html>