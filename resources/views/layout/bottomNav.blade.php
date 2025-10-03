<div class="appBottomMenu">
    <a href="/dashboard" class="item {{ Request()->is('dashboard') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="home-outline"></ion-icon>
            <strong>Home</strong>
        </div>
    </a>

    <a href="/presensi/histori" class="item {{ Request()->is('presensi/histori') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="document-text-outline"></ion-icon>
            <strong>Histori</strong>
        </div>
    </a>

    <a href="/presensi/create" class="item {{ Request()->is('presensi/create') ? 'active' : '' }}">
        <div class="col">
            <div class="action-button large">
                <ion-icon name="camera"></ion-icon>
            </div>
        </div>
    </a>

    <a href="/presensi/izin" class="item {{ Request()->is('presensi/izin') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="calendar-outline"></ion-icon>
            <strong>Izin</strong>
        </div>
    </a>

    <a href="/editprofile" class="item {{ Request()->is('editprofile') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="people-outline"></ion-icon>
            <strong>Profile</strong>
        </div>
    </a>
</div>
