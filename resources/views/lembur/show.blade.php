@extends('layout.presensi')

@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="/lembur" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Detail Lembur</div>
    <div class="right"></div>
</div>

<style>
    .container {
        padding-top: 70px;
        padding-bottom: 80px;
    }

    .detail-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 15px;
    }

    .detail-header {
        text-align: center;
        padding: 20px;
        background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
        color: white;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .detail-header h3 {
        margin: 10px 0;
        font-size: 20px;
    }

    .detail-header .date {
        font-size: 16px;
        opacity: 0.9;
    }

    .detail-item {
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #666;
        font-size: 13px;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-value {
        font-size: 15px;
        color: #333;
        margin-top: 5px;
    }

    .duration-box {
        background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
        color: white;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        margin: 15px 0;
    }

    .duration-box .number {
        font-size: 32px;
        font-weight: 700;
    }

    .duration-box .label {
        font-size: 14px;
        opacity: 0.9;
    }

    .foto-bukti {
        text-align: center;
        margin: 20px 0;
    }

    .foto-bukti img {
        max-width: 100%;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        cursor: pointer;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-action {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .btn-edit {
        background: #ffc107;
        color: #333;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .keterangan-box {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #2196f3;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="detail-header">
        <ion-icon name="calendar-outline" style="font-size: 40px;"></ion-icon>
        <h3>{{ date('d F Y', strtotime($lembur->tanggal_lembur)) }}</h3>
        <div class="date">{{ date('l', strtotime($lembur->tanggal_lembur)) }}</div>
    </div>

    <div class="detail-card">
        <div class="detail-item">
            <div class="detail-label">
                <ion-icon name="time-outline"></ion-icon>
                Waktu Lembur
            </div>
            <div class="detail-value">
                {{ date('H:i', strtotime($lembur->jam_mulai)) }} - {{ date('H:i', strtotime($lembur->jam_selesai)) }} WIB
            </div>
        </div>

        <div class="duration-box">
            <div class="number">
                @php
                    $jam = floor($lembur->durasi_menit / 60);
                    $menit = $lembur->durasi_menit % 60;
                @endphp
                {{ $jam }} Jam {{ $menit }} Menit
            </div>
            <div class="label">Total Durasi Lembur</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">
                <ion-icon name="document-text-outline"></ion-icon>
                Keterangan
            </div>
            <div class="keterangan-box">
                {{ $lembur->keterangan }}
            </div>
        </div>

        @if($lembur->bukti_foto)
        <div class="detail-item">
            <div class="detail-label">
                <ion-icon name="image-outline"></ion-icon>
                Bukti Foto
            </div>
            <div class="foto-bukti">
                <img src="{{ asset('storage/uploads/lembur/' . $lembur->bukti_foto) }}" 
                     alt="Bukti Lembur"
                     onclick="window.open(this.src, '_blank')">
                <p class="text-muted mt-2"><small>Klik foto untuk memperbesar</small></p>
            </div>
        </div>
        @endif

        <div class="detail-item">
            <div class="detail-label">
                <ion-icon name="time-outline"></ion-icon>
                Dibuat Pada
            </div>
            <div class="detail-value">
                {{ date('d F Y, H:i', strtotime($lembur->created_at)) }} WIB
            </div>
        </div>
    </div>

    <div class="action-buttons">
        <a href="/lembur/{{ $lembur->id }}/edit" class="btn-action btn-edit">
            <ion-icon name="create-outline"></ion-icon>
            Edit
        </a>
        <form action="/lembur/{{ $lembur->id }}/delete" method="POST" style="flex: 1;" onsubmit="return confirm('Yakin ingin menghapus data lembur ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-action btn-delete" style="width: 100%;">
                <ion-icon name="trash-outline"></ion-icon>
                Hapus
            </button>
        </form>
    </div>
</div>
@endsection