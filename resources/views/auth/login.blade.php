<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login E-Presensi</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">

  <!-- Custom CSS -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon/puprlogo.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/icon/puprlogo.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
</head>
<body>
   <div class="login-container">
        <div class="login-card">
            <h2 class="login-title">E-Presensi PUPR</h2>
            <p class="text-center text-white mb-4">Silahkan Login Dengan Akun Anda</p>
            
            <!-- Alert Error -->
            @php
                $message = Session::get('error');
            @endphp
            @if (Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $message }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
<<<<<<< HEAD
            <form action="/prosesloginadmin" method="POST" id="loginForm">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label" id="usernameLabel">Email Anda</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="username" name="email" placeholder="Masukkan email" required>
=======
            <form action="/proseslogin" method="POST" id="loginForm">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label" id="usernameLabel">ID Anda</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="username" name="nik" placeholder="Masukkan ID" required>
>>>>>>> 0a88e76297e0ec59449dcf44a5f4c5c7a6e9d9cc
                        <i class="bi bi-person-fill input-icon"></i>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                        <i class="bi bi-lock-fill input-icon"></i>
                    </div>
                </div>
                
                <!-- Role Toggle Switch -->
                <div class="role-toggle-container">
                    <div class="toggle-wrapper">
                        <div class="toggle-switch">
                            <input type="radio" name="role" id="user" value="user" checked>
                            <label for="user" class="toggle-option">Login as User</label>
                            
                            <input type="radio" name="role" id="admin" value="admin">
                            <label for="admin" class="toggle-option">Login as Admin</label>
                            
                            <div class="toggle-slider"></div>
                        </div>
                    </div>
                </div>
                
                <div class="form-options">
                    <a href="page-forgot-password.html" class="forgot-password">Forgot Password?</a>
                </div>
                
                <button type="submit" class="btn btn-login">Login</button>
            </form>
        </div>
    </div>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JS -->
  <script src="{{ asset('assets/js/login.js') }}"></script>
</body>
</html>