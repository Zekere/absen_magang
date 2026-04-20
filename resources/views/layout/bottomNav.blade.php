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

<style>
.appBottomMenu {
    display: flex;
    align-items: center;
    justify-content: space-around;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    height: 64px;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-top: 1px solid rgba(0, 0, 0, 0.06);
    padding: 0 8px;
    z-index: 999;
}

.appBottomMenu .item {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 1;
    text-decoration: none;
    color: #aaa;
    transition: color 0.2s ease;
}

.appBottomMenu .item .col {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 3px;
}

.appBottomMenu .item ion-icon {
    font-size: 22px;
    transition: transform 0.2s ease, color 0.2s ease;
}

.appBottomMenu .item strong {
    font-size: 10px;
    font-weight: 500;
    letter-spacing: 0.3px;
}

.appBottomMenu .item.active {
    color: #6571FF;
}

.appBottomMenu .item.active ion-icon {
    transform: translateY(-1px);
}

.appBottomMenu .item:active ion-icon {
    transform: scale(0.88);
}

/* Tombol kamera tengah */
.appBottomMenu .action-button.large {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6571FF, #8B95FF);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 14px rgba(101, 113, 255, 0.45);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.appBottomMenu .item:active .action-button.large {
    transform: scale(0.92);
    box-shadow: 0 2px 8px rgba(101, 113, 255, 0.3);
}

.appBottomMenu .item.active .action-button.large {
    background: linear-gradient(135deg, #4f5de0, #6571FF);
}

.appBottomMenu .action-button.large ion-icon {
    font-size: 22px;
    color: #ffffff !important;
    transform: none !important;
}
</style>