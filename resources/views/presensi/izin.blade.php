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
        @php
            // Sorting: Pending (0) dulu, baru Approved (1) & Declined (2)
            $sortedData = $dataizin->sortBy(function($item) {
                // Pending = 0, akan muncul paling atas
                // Approved & Declined akan di bawah
                return $item->status_approved == 0 ? 0 : 1;
            });
        @endphp

        @forelse ($sortedData as $d)
        <div class="col-12 izin-card" data-status="{{ $d->status }}" data-approved="{{ $d->status_approved }}">
            <div class="card border-0 shadow-sm hover-card rounded-4 {{ $d->status_approved == 0 ? 'pending-highlight' : '' }}">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start gap-3">

                        {{-- 🧩 Icon --}}
                        <div class="icon-wrapper {{ $d->status_approved == 0 ? 'icon-pulse' : '' }}">
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

                            <p class="text-muted small mb-2">
                                <ion-icon name="document-text-outline" class="me-1"></ion-icon>
                                {{ $d->keterangan }}
                            </p>

                            {{-- 📎 Bukti Surat --}}
                            @if(!empty($d->bukti_surat))
                            <div class="attachment-container">
                                <button class="attachment-btn" onclick="viewAttachment('{{ asset('storage/uploads/izin/' . $d->bukti_surat) }}', '{{ $d->bukti_surat }}')">
                                    <ion-icon name="attach-outline"></ion-icon>
                                    <span>Lihat Bukti Surat</span>
                                    <ion-icon name="eye-outline" class="view-icon"></ion-icon>
                                </button>
                            </div>
                            @endif
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

{{-- 📎 Modal Attachment Viewer --}}
<div id="attachmentModal" class="attachment-modal" onclick="closeAttachment(event)">
    <div class="attachment-modal-content">
        <div class="attachment-modal-header">
            <h5 id="attachmentTitle">Bukti Surat</h5>
            <button class="attachment-close" onclick="closeAttachment(event)">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>
        <div class="attachment-modal-body" id="attachmentBody">
            <img id="attachmentImage" src="" alt="Bukti Surat" style="display: none;">
            <iframe id="attachmentPDF" src="" style="display: none;"></iframe>
            <div id="attachmentDoc" style="display: none;">
                <div class="doc-placeholder">
                    <ion-icon name="document-text-outline"></ion-icon>
                    <p>File dokumen tidak dapat ditampilkan di browser</p>
                    <a id="downloadLink" href="" download class="btn-download">
                        <ion-icon name="download-outline"></ion-icon>
                        Download File
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 💫 Script Filter Dinamis dengan Sorting --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const buttons = document.querySelectorAll(".filter-btn");
    const dataList = document.getElementById("dataList");
    const cards = Array.from(document.querySelectorAll(".izin-card"));

    // Fungsi untuk sorting cards: pending dulu
    function sortCards(cardsToSort) {
        return cardsToSort.sort((a, b) => {
            const statusA = parseInt(a.dataset.approved);
            const statusB = parseInt(b.dataset.approved);
            
            // Pending (0) akan di atas, sisanya di bawah
            if (statusA === 0 && statusB !== 0) return -1;
            if (statusA !== 0 && statusB === 0) return 1;
            return 0;
        });
    }

    buttons.forEach(btn => {
        btn.addEventListener("click", function() {
            buttons.forEach(b => b.classList.remove("active"));
            this.classList.add("active");
            const filter = this.getAttribute("data-filter");

            // Filter cards berdasarkan status
            let visibleCards = cards.filter(card => {
                if (filter === "all" || card.dataset.status === filter) {
                    return true;
                }
                return false;
            });

            // Sort cards yang visible (pending dulu)
            visibleCards = sortCards(visibleCards);

            // Hide semua cards dulu
            cards.forEach(card => {
                card.style.display = "none";
                card.classList.remove("fadeIn");
            });

            // Append cards yang sudah disort ke container
            visibleCards.forEach(card => {
                card.style.display = "block";
                card.classList.add("fadeIn");
                dataList.appendChild(card);
            });
        });
    });

    // Initial sort saat page load
    const sortedCards = sortCards(cards);
    sortedCards.forEach(card => {
        dataList.appendChild(card);
    });
});

// Fungsi untuk melihat attachment
function viewAttachment(filePath, fileName) {
    const modal = document.getElementById('attachmentModal');
    const imageViewer = document.getElementById('attachmentImage');
    const pdfViewer = document.getElementById('attachmentPDF');
    const docViewer = document.getElementById('attachmentDoc');
    const downloadLink = document.getElementById('downloadLink');
    const title = document.getElementById('attachmentTitle');
    
    // Hide all viewers
    imageViewer.style.display = 'none';
    pdfViewer.style.display = 'none';
    docViewer.style.display = 'none';
    
    // Set title
    title.textContent = fileName;
    
    // Detect file type
    const extension = fileName.split('.').pop().toLowerCase();
    
    if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(extension)) {
        // Image
        imageViewer.src = filePath;
        imageViewer.style.display = 'block';
    } else if (extension === 'pdf') {
        // PDF
        pdfViewer.src = filePath;
        pdfViewer.style.display = 'block';
    } else {
        // Doc/Docx or other
        downloadLink.href = filePath;
        downloadLink.download = fileName;
        docViewer.style.display = 'flex';
    }
    
    // Show modal
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// Fungsi untuk menutup modal
function closeAttachment(event) {
    if (event.target.id === 'attachmentModal' || event.target.closest('.attachment-close')) {
        const modal = document.getElementById('attachmentModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        // Reset viewers
        document.getElementById('attachmentImage').src = '';
        document.getElementById('attachmentPDF').src = '';
    }
}

// Close with ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('attachmentModal');
        if (modal.style.display === 'flex') {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('attachmentImage').src = '';
            document.getElementById('attachmentPDF').src = '';
        }
    }
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

/* ===== Highlight Pending Card ===== */
.pending-highlight {
    border: 2px solid #fbbf24 !important;
    background: linear-gradient(135deg, #fffbeb 0%, #ffffff 100%);
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

/* Pulse animation untuk pending */
.icon-pulse {
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
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
.badge-pending { 
    background: #fef3c7; 
    color: #92400e;
    animation: badgePulse 2s ease-in-out infinite;
}

@keyframes badgePulse {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.4);
    }
    50% {
        box-shadow: 0 0 0 6px rgba(251, 191, 36, 0);
    }
}

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

/* ===== Attachment Button ===== */
.attachment-container {
    margin-top: 8px;
}

.attachment-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    cursor: pointer;
    transition: all 0.3s ease;
}

.attachment-btn:hover {
    background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
    border-color: #9ca3af;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.attachment-btn ion-icon {
    font-size: 16px;
}

.attachment-btn .view-icon {
    color: #3b82f6;
}

/* ===== Attachment Modal ===== */
.attachment-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    animation: modalFadeIn 0.3s ease;
}

@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.attachment-modal-content {
    background: white;
    border-radius: 16px;
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: modalSlideUp 0.3s ease;
}

@keyframes modalSlideUp {
    from { 
        opacity: 0;
        transform: translateY(50px);
    }
    to { 
        opacity: 1;
        transform: translateY(0);
    }
}

.attachment-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
}

.attachment-modal-header h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
}

.attachment-close {
    width: 36px;
    height: 36px;
    border: none;
    background: #fee2e2;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.attachment-close:hover {
    background: #fecaca;
    transform: scale(1.1);
}

.attachment-close ion-icon {
    font-size: 24px;
    color: #ef4444;
}

.attachment-modal-body {
    padding: 0;
    max-height: calc(90vh - 70px);
    overflow: auto;
    background: #f3f4f6;
}

.attachment-modal-body img {
    width: 100%;
    height: auto;
    display: block;
}

.attachment-modal-body iframe {
    width: 100%;
    height: 80vh;
    border: none;
}

.doc-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    text-align: center;
}

.doc-placeholder ion-icon {
    font-size: 80px;
    color: #9ca3af;
    margin-bottom: 16px;
}

.doc-placeholder p {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 20px;
}

.btn-download {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-download:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
}

.btn-download ion-icon {
    font-size: 20px;
}

/* ===== Responsive ===== */
@media (max-width: 576px) {
    .attachment-modal-content {
        width: 95%;
        max-height: 95vh;
    }
    
    .attachment-modal-header {
        padding: 12px 16px;
    }
    
    .attachment-modal-header h5 {
        font-size: 14px;
    }
    
    .attachment-modal-body iframe {
        height: 70vh;
    }
}
</style>
@endsection