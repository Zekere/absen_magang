{{-- 
<div class="appBottomMenu">
    <a href="/dashboard" class="item {{ Request()->is('dashboard') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="home-outline"></ion-icon>
            <strong>Home</strong>
        </div>
    </a>

    <a href="/presensi/izin" class="item {{ Request()->is('presensi/izin') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="calendar-outline"></ion-icon>
            <strong>Izin</strong>
        </div>
    </a>

    

    <a href="/presensi/create" class="item {{ Request()->is('presensi/create') ? 'active' : '' }}">
        <div class="col">
            <div class="action-button large">
                <ion-icon name="camera"></ion-icon>
            </div>
        </div>
    </a>

    <a href="/presensi/histori" class="item {{ Request()->is('presensi/histori') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="document-text-outline"></ion-icon>
            <strong>Histori</strong>
        </div>
    </a>

    <a href="/editprofile" class="item {{ Request()->is('editprofile') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="people-outline"></ion-icon>
            <strong>Profile</strong>
        </div>
    </a>
</div>
--}}

<div class="appBottomMenu">
    <a href="/dashboard" class="item {{ Request()->is('dashboard') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="home-outline"></ion-icon>
            <strong>Home</strong>
        </div>
    </a>

    <a href="/presensi/izin" class="item {{ Request()->is('presensi/izin') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="calendar-outline"></ion-icon>
            <strong>Izin</strong>
        </div>
    </a>

    @php
        $currentHour = date('H');
        $currentMinute = date('i');
        $currentTime = ($currentHour * 60) + $currentMinute; // Konversi ke menit
        
        $startTime = (5 * 60); // 05:00 = 300 menit
        $endTime = (17 * 60); // 17:00 = 1020 menit
        
        $isAbsenActive = ($currentTime >= $startTime && $currentTime <= $endTime);
    @endphp

    @if($isAbsenActive)
        <a href="/presensi/create" class="item {{ Request()->is('presensi/create') ? 'active' : '' }}">
            <div class="col">
                <div class="action-button large">
                    <ion-icon name="camera"></ion-icon>
                </div>
            </div>
        </a>
    @else
        <a href="javascript:void(0)" class="item disabled" onclick="showAbsenAlert()">
            <div class="col">
                <div class="action-button large disabled">
                    <ion-icon name="camera-outline"></ion-icon>
                </div>
            </div>
        </a>
    @endif

    <a href="/presensi/histori" class="item {{ Request()->is('presensi/histori') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="document-text-outline"></ion-icon>
            <strong>Histori</strong>
        </div>
    </a>

    <a href="/editprofile" class="item {{ Request()->is('editprofile') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="people-outline"></ion-icon>
            <strong>Profile</strong>
        </div>
    </a>
</div>

<script>
function showAbsenAlert() {
    const currentHour = new Date().getHours();
    const currentMinute = new Date().getMinutes();
    const currentTime = `${String(currentHour).padStart(2, '0')}:${String(currentMinute).padStart(2, '0')}`;
    
    let message = '';
    if (currentHour < 5) {
        message = `Absensi belum dibuka. Silakan absen mulai jam 05:00 pagi.<br>Waktu sekarang: ${currentTime}`;
    } else if (currentHour >= 17) {
        message = `Waktu absensi telah berakhir. Absensi tersedia jam 05:00 - 17:00.<br>Waktu sekarang: ${currentTime}`;
    }
    
    Swal.fire({
        icon: 'warning',
        title: 'Absensi Tidak Tersedia',
        html: message,
        confirmButtonColor: '#6571FF',
        confirmButtonText: 'OK'
    });
}
</script>

<style>
/* Style untuk button disabled */
.appBottomMenu .item.disabled {
    pointer-events: auto !important;
    cursor: not-allowed;
}

.appBottomMenu .item.disabled .action-button {
    background: #cccccc !important;
    opacity: 0.5;
    cursor: not-allowed;
}

.appBottomMenu .item.disabled .action-button ion-icon {
    color: #666666 !important;
}

.appBottomMenu .item.disabled:hover .action-button {
    transform: none !important;
    box-shadow: none !important;
}
</style>