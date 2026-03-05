@extends('layout.presensi')

@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="/dashboard" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Data Lembur</div>
    <div class="right"></div>
</div>

<style>
    .container {
        padding-top: 70px;
        padding-bottom: 100px;
        background: #f5f7fa;
    }

    /* Modern Card Design */
    .lembur-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e8ecef;
    }

    .lembur-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        transform: translateY(-4px);
    }

    /* Header dengan tanggal */
    .lembur-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f3f7;
    }

    .date-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
    }

    .date-day {
        font-size: 18px;
        line-height: 1;
    }

    .date-month {
        font-size: 10px;
        text-transform: uppercase;
        opacity: 0.9;
    }

    .date-info {
        flex: 1;
    }

    .date-full {
        font-weight: 600;
        font-size: 15px;
        color: #1a1f36;
        margin-bottom: 2px;
    }

    .date-day-name {
        font-size: 12px;
        color: #8492a6;
    }

    /* Time Info */
    .time-info {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f8f9fb;
        padding: 10px 14px;
        border-radius: 10px;
        margin-bottom: 12px;
    }

    .time-info ion-icon {
        font-size: 18px;
        color: #667eea;
    }

    .time-text {
        font-size: 14px;
        color: #3c4257;
        font-weight: 500;
    }

    /* Duration Badge */
    .duration-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 16px;
        border-radius: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .duration-badge ion-icon {
        font-size: 18px;
    }

    /* Keterangan Box */
    .keterangan-box {
        background: #f8f9fb;
        padding: 12px 14px;
        border-radius: 10px;
        border-left: 3px solid #667eea;
        margin-bottom: 12px;
    }

    .keterangan-label {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        color: #8492a6;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .keterangan-text {
        font-size: 14px;
        color: #3c4257;
        line-height: 1.6;
    }

    /* Photo Badge */
    .photo-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #e3f2fd;
        color: #1976d2;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .photo-badge ion-icon {
        font-size: 16px;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        margin-top: 16px;
    }

    .btn-action {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-detail {
        background: #2196f3;
        color: white;
        box-shadow: 0 2px 8px rgba(33, 150, 243, 0.3);
    }

    .btn-detail:hover {
        background: #1976d2;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.4);
    }

    .btn-edit {
        background: #ffc107;
        color: #333;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    }

    .btn-edit:hover {
        background: #ffb300;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
    }

    .btn-delete {
        background: #f44336;
        color: white;
        box-shadow: 0 2px 8px rgba(244, 67, 54, 0.3);
    }

    .btn-delete:hover {
        background: #d32f2f;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
    }

    .btn-action ion-icon {
        font-size: 16px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }

    .empty-state ion-icon {
        font-size: 100px;
        color: #cbd5e0;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 20px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 14px;
        color: #718096;
        margin-bottom: 24px;
    }

    /* Floating Action Button (FAB) */
    .fab-button {
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 20px rgba(33, 150, 243, 0.5);
        z-index: 999;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        text-decoration: none;
    }

    .fab-button:hover {
        transform: scale(1.1) rotate(90deg);
        box-shadow: 0 6px 28px rgba(33, 150, 243, 0.6);
    }

    .fab-button:active {
        transform: scale(0.95);
    }

    .fab-button ion-icon {
        font-size: 28px;
        color: white;
    }

    /* Alert Modern */
    .alert {
        border-radius: 12px;
        border: none;
        padding: 14px 16px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .lembur-card {
            padding: 16px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
        }

        .fab-button {
            bottom: 80px;
            right: 16px;
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <!-- Alert Messages -->
    @if (Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <ion-icon name="checkmark-circle-outline" style="font-size: 20px; vertical-align: middle;"></ion-icon>
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ion-icon name="close-circle-outline" style="font-size: 20px; vertical-align: middle;"></ion-icon>
            {{ Session::get('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($dataLembur->count() > 0)
        @foreach($dataLembur as $lembur)
        <div class="lembur-card">
            <!-- Header dengan Icon Tanggal -->
            <div class="lembur-header">
                <div class="date-icon">
                    <div class="date-day">{{ date('d', strtotime($lembur->tanggal_lembur)) }}</div>
                    <div class="date-month">{{ date('M', strtotime($lembur->tanggal_lembur)) }}</div>
                </div>
                <div class="date-info">
                    <div class="date-full">{{ date('d F Y', strtotime($lembur->tanggal_lembur)) }}</div>
                    <div class="date-day-name">{{ date('l', strtotime($lembur->tanggal_lembur)) }}</div>
                </div>
            </div>

            <!-- Time Info -->
            <div class="time-info">
                <ion-icon name="time-outline"></ion-icon>
                <span class="time-text">
                    {{ date('H:i', strtotime($lembur->jam_mulai)) }} - {{ date('H:i', strtotime($lembur->jam_selesai)) }} WIB
                </span>
            </div>

            <!-- Duration Badge -->
            <div class="duration-badge">
                <ion-icon name="hourglass-outline"></ion-icon>
                @php
                    $jam = floor($lembur->durasi_menit / 60);
                    $menit = $lembur->durasi_menit % 60;
                @endphp
                <span>Durasi: {{ $jam }} jam {{ $menit }} menit</span>
            </div>

            <!-- Keterangan -->
            <div class="keterangan-box">
                <div class="keterangan-label">Keterangan</div>
                <div class="keterangan-text">{{ $lembur->keterangan }}</div>
            </div>

            <!-- Photo Badge -->
            @if($lembur->bukti_foto)
            <div class="photo-badge">
                <ion-icon name="image-outline"></ion-icon>
                <span>Dengan bukti foto</span>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="/lembur/{{ $lembur->id }}" class="btn-action btn-detail">
                    <ion-icon name="eye-outline"></ion-icon>
                    <span>Detail</span>
                </a>
                
                <a href="/lembur/{{ $lembur->id }}/edit" class="btn-action btn-edit">
                    <ion-icon name="create-outline"></ion-icon>
                    <span>Edit</span>
                </a>
                
                <form action="/lembur/{{ $lembur->id }}/delete" method="POST" style="flex: 1;" onsubmit="return confirm('Yakin ingin menghapus data lembur ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-delete" style="width: 100%;">
                        <ion-icon name="trash-outline"></ion-icon>
                        <span>Hapus</span>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    @else
        <div class="empty-state">
            <ion-icon name="briefcase-outline"></ion-icon>
            <h3>Belum Ada Data Lembur</h3>
            <p>Klik tombol + di pojok kanan bawah untuk menambah data lembur</p>
        </div>
    @endif
</div>

<!-- Floating Action Button -->
<a href="/lembur/create" class="fab-button">
    <ion-icon name="add-outline"></ion-icon>
</a>
@endsection