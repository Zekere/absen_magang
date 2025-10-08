@extends('layout.presensi')

@section('header')
<div class="appHeader bg-primary text-light shadow-sm">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle fw-semibold">Data Izin / Sakit</div>
</div>
@endsection

@section('content')
<div class="container-fluid" style="margin-top: 70px; margin-bottom: 100px;">

    {{-- 🔔 Notifikasi --}}
    @if(Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-3">
        <ion-icon name="checkmark-circle" class="me-2" style="font-size: 22px;"></ion-icon>
        {{ Session::get('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(Session::get('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-3">
        <ion-icon name="close-circle" class="me-2" style="font-size: 22px;"></ion-icon>
        {{ Session::get('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- 🔍 Filter Tabs --}}
    <div class="filter-tabs mb-3 text-center">
        <div class="d-flex justify-content-between gap-2 overflow-auto pb-2">
            <button class="filter-btn active" data-filter="all">
                <ion-icon name="apps-outline"></ion-icon>
                <span>Semua</span>
            </button>
            <button class="filter-btn" data-filter="1">
                <ion-icon name="calendar-outline"></ion-icon>
                <span>Izin</span>
            </button>
            <button class="filter-btn" data-filter="2">
                <ion-icon name="medkit-outline"></ion-icon>
                <span>Sakit</span>
            </button>
            <button class="filter-btn" data-filter="3">
                <ion-icon name="airplane-outline"></ion-icon>
                <span>Cuti</span>
            </button>
        </div>
    </div>

    {{-- 📄 List Data --}}
    <div class="row g-3" id="dataList">
        @forelse ($dataizin as $d)
        <div class="col-12 izin-card" data-status="{{ $d->status }}">
            <div class="card border-0 shadow-sm hover-card rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start gap-3">

                        {{-- 🧩 Icon --}}
                        <div class="icon-wrapper">
                            @if($d->status == 1)
                                <ion-icon name="calendar-outline" class="icon-izin"></ion-icon>
                            @elseif($d->status == 2)
                                <ion-icon name="medkit-outline" class="icon-sakit"></ion-icon>
                            @elseif($d->status == 3)
                                <ion-icon name="airplane-outline" class="icon-cuti"></ion-icon>
                            @else
                                <ion-icon name="help-circle-outline" class="icon-default"></ion-icon>
                            @endif
                        </div>

                        {{-- 📄 Isi --}}
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">
                                        {{ date('d F Y', strtotime($d->tgl_izin)) }}
                                    </h6>
                                    <span class="badge-type 
                                        @if($d->status == 1) badge-type-izin
                                        @elseif($d->status == 2) badge-type-sakit
                                        @elseif($d->status == 3) badge-type-cuti
                                        @else badge-type-default
                                        @endif">
                                        {{ $d->status == 1 ? 'Izin' : ($d->status == 2 ? 'Sakit' : ($d->status == 3 ? 'Cuti' : 'Lainnya')) }}
                                    </span>
                                </div>

                                {{-- 🟢 Status Persetujuan --}}
                                @if($d->status_approved == 0)
                                    <span class="badge-status badge-pending">
                                        <ion-icon name="time-outline"></ion-icon> Pending
                                    </span>
                                @elseif($d->status_approved == 1)
                                    <span class="badge-status badge-approved">
                                        <ion-icon name="checkmark-circle"></ion-icon> Approved
                                    </span>
                                @elseif($d->status_approved == 2)
                                    <span class="badge-status badge-declined">
                                        <ion-icon name="close-circle"></ion-icon> Declined
                                    </span>
                                @endif
                            </div>

                            <p class="text-muted small mb-0">
                                <ion-icon name="document-text-outline" class="me-1"></ion-icon>
                                {{ $d->keterangan }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @empty
        {{-- 🚫 Empty State --}}
        <div class="col-12">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <ion-icon name="document-text-outline"></ion-icon>
                </div>
                <h5 class="fw-bold mb-2">Belum Ada Data</h5>
                <p class="text-muted">Anda belum pernah mengajukan izin, sakit, atau cuti.</p>
                <a href="/presensi/buatizin" class="btn btn-primary btn-sm px-4">
                    <ion-icon name="add-circle-outline" class="me-1"></ion-icon>
                    Buat Izin Baru
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- ➕ Tombol Tambah --}}
<div class="fab-button animate-fab">
    <a href="/presensi/buatizin" class="fab shadow-lg">
        <ion-icon name="add-outline"></ion-icon>
    </a>
</div>

{{-- 💫 Script Filter Dinamis --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const buttons = document.querySelectorAll(".filter-btn");
    const cards = document.querySelectorAll(".izin-card");

    buttons.forEach(btn => {
        btn.addEventListener("click", function() {
            buttons.forEach(b => b.classList.remove("active"));
            this.classList.add("active");
            const filter = this.getAttribute("data-filter");

            cards.forEach(card => {
                if (filter === "all" || card.dataset.status === filter) {
                    card.style.display = "block";
                    card.classList.add("fadeIn");
                } else {
                    card.style.display = "none";
                    card.classList.remove("fadeIn");
                }
            });
        });
    });
});
</script>

{{-- 🎨 CSS Modern --}}
<style>
/* ===== Filter Buttons ===== */
.filter-btn {
    border: none;
    background: #f3f4f6;
    color: #4b5563;
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.25s ease;
}

.filter-btn.active {
    background: #3b82f6;
    color: #fff;
    box-shadow: 0 4px 10px rgba(59,130,246,0.3);
    transform: scale(1.05);
}

/* ===== Icon Wrapper ===== */
.icon-wrapper {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    transition: 0.3s;
}

.icon-izin { color: #3b82f6; background: #dbeafe; }
.icon-sakit { color: #ef4444; background: #fee2e2; }
.icon-cuti { color: #8b5cf6; background: #ede9fe; }
.icon-default { color: #6b7280; background: #f3f4f6; }

/* ===== Badge ===== */
.badge-type {
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 600;
}
.badge-type-izin { background: #dbeafe; color: #1e40af; }
.badge-type-sakit { background: #fee2e2; color: #991b1b; }
.badge-type-cuti { background: #ede9fe; color: #5b21b6; }

.badge-status {
    font-size: 12px;
    padding: 6px 10px;
    border-radius: 8px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}
.badge-pending { background: #fef3c7; color: #92400e; }
.badge-approved { background: #d1fae5; color: #065f46; }
.badge-declined { background: #fee2e2; color: #991b1b; }

/* ===== Hover Card ===== */
.hover-card {
    transition: all 0.25s ease;
}
.hover-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.08);
}

/* ===== Floating Button ===== */
.fab-button {
    position: fixed;
    bottom: 80px;
    right: 20px;
    z-index: 999;
}
.fab {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-radius: 50%;
    color: #fff;
    font-size: 26px;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: 0.3s;
}
.fab:hover { transform: scale(1.1) rotate(90deg); }

/* ===== Empty State ===== */
.empty-state {
    background: #f9fafb;
    border-radius: 20px;
    padding: 2.5rem 2rem;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}
.empty-state-icon {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: #e0f2fe;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: auto;
}
.empty-state-icon ion-icon { font-size: 45px; color: #3b82f6; }

/* ===== Animation ===== */
.fadeIn {
    animation: fadeIn 0.4s ease;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection
