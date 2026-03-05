@extends('layout.admin.template')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    * { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root {
        --primary: #5b4fcf;
        --primary-end: #764ba2;
        --accent: #22c55e;
        --surface: #f1f4f9;
        --card: #ffffff;
        --border: #e4e9f0;
        --text: #1e293b;
        --muted: #64748b;
    }

    body, .page-wrapper { background: var(--surface); min-height: 100vh; }

    /* Hero */
    .page-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 28px 36px;
        margin-bottom: 28px;
    }

    .hero-title {
        color: #fff;
        font-size: 24px;
        font-weight: 800;
        margin: 0 0 4px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .hero-sub {
        color: rgba(255,255,255,0.8);
        font-size: 14px;
        margin: 0;
    }

    /* Layout */
    .main-layout {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 24px;
        padding: 0 28px 40px;
        max-width: 1100px;
        margin: 0 auto;
    }

    /* Form Card */
    .form-card {
        background: var(--card);
        border-radius: 16px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.07);
        padding: 36px 36px 28px;
        animation: fadeUp 0.35s ease both;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Fields */
    .field-wrap { margin-bottom: 22px; }

    .field-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
    }

    .field-label i { color: var(--primary); font-size: 16px; }

    .custom-select {
        width: 100%;
        padding: 12px 42px 12px 16px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text);
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") no-repeat right 14px center;
        appearance: none;
        -webkit-appearance: none;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }

    .custom-select:hover { border-color: #a5b4fc; }
    .custom-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(91,79,207,0.12); }

    .field-hint {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: var(--muted);
        margin-top: 6px;
    }

    .divider { border: none; border-top: 1.5px dashed var(--border); margin: 24px 0; }

    /* Buttons */
    .btn-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .btn-pdf, .btn-excel {
        width: 100%;
        padding: 14px 20px;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: all 0.25s ease;
    }

    .btn-pdf {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 4px 14px rgba(102,126,234,0.35);
    }

    .btn-pdf:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(102,126,234,0.45); }

    .btn-excel {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        box-shadow: 0 4px 14px rgba(34,197,94,0.35);
    }

    .btn-excel:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(34,197,94,0.45); }

    .form-footer-note {
        text-align: center;
        font-size: 12px;
        color: var(--muted);
        margin-top: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    /* Info Panel */
    .info-panel { animation: fadeUp 0.35s ease 0.1s both; }

    .info-card {
        background: var(--card);
        border-radius: 16px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.07);
        overflow: hidden;
    }

    .info-header {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
    }

    .info-body { padding: 4px 0; }

    .info-item {
        padding: 18px 20px;
        border-bottom: 1px solid var(--border);
        transition: background 0.2s;
    }

    .info-item:last-child { border-bottom: none; }
    .info-item:hover { background: #f8fafc; }

    .info-item-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .info-item-title.pdf   { color: #5b4fcf; }
    .info-item-title.excel { color: #16a34a; }
    .info-item-title.period{ color: #d97706; }

    .info-item-desc {
        font-size: 13px;
        color: var(--muted);
        line-height: 1.55;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .main-layout { grid-template-columns: 1fr; padding: 0 16px 32px; }
        .page-hero { padding: 20px; }
        .form-card { padding: 24px 20px; }
        .btn-row { grid-template-columns: 1fr; }
    }
</style>

<!-- Hero -->
<div class="page-hero">
    <h1 class="hero-title">
        <i class="bi bi-file-bar-graph-fill"></i> Laporan Lembur
    </h1>
    <p class="hero-sub">Cetak atau export laporan lembur karyawan</p>
</div>

<!-- Layout -->
<div class="main-layout">

    <!-- Form Card -->
    <div class="form-card">

        <!-- Bulan -->
        <div class="field-wrap">
            <label class="field-label" for="bulan">
                <i class="bi bi-calendar-month-fill"></i> Bulan
            </label>
            <select id="bulan" class="custom-select">
                <option value="">— Pilih Bulan —</option>
                @foreach($namabulan as $index => $bulan)
                    @if($index > 0)
                    <option value="{{ $index }}" {{ date('n') == $index ? 'selected' : '' }}>{{ $bulan }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <!-- Tahun -->
        <div class="field-wrap">
            <label class="field-label" for="tahun">
                <i class="bi bi-calendar-fill"></i> Tahun
            </label>
            <select id="tahun" class="custom-select">
                <option value="">— Pilih Tahun —</option>
                @for($i = date('Y'); $i >= date('Y')-5; $i--)
                <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>

        <!-- Karyawan -->
        <div class="field-wrap">
            <label class="field-label" for="nik">
                <i class="bi bi-person-fill"></i> Karyawan
            </label>
            <select id="nik" class="custom-select">
                <option value="">— Pilih Karyawan —</option>
                @foreach($karyawan as $k)
                <option value="{{ $k->nik }}">{{ $k->nik }} - {{ $k->nama_lengkap }}</option>
                @endforeach
            </select>
            <div class="field-hint">
                <i class="bi bi-info-circle"></i>
                Pilih karyawan yang akan dicetak laporannya
            </div>
        </div>

        <hr class="divider">

        <!-- Buttons -->
        <div class="btn-row">
            <button type="button" class="btn-pdf" onclick="submitForm('PDF')">
                <i class="bi bi-printer-fill"></i> Cetak PDF
            </button>
            <button type="button" class="btn-excel" onclick="submitForm('Excel')">
                <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
            </button>
        </div>

        <div class="form-footer-note">
            <i class="bi bi-shield-check"></i>
            Data laporan akan dibuat berdasarkan filter yang Anda pilih
        </div>
    </div>

    <!-- Info Panel -->
    <div class="info-panel">
        <div class="info-card">
            <div class="info-header">
                <i class="bi bi-info-circle-fill"></i> Informasi
            </div>
            <div class="info-body">
                <div class="info-item">
                    <div class="info-item-title pdf">
                        <i class="bi bi-file-pdf-fill"></i> Format PDF
                    </div>
                    <div class="info-item-desc">
                        Laporan akan dibuat dalam format PDF yang siap untuk dicetak atau disimpan.
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-item-title excel">
                        <i class="bi bi-file-earmark-excel-fill"></i> Format Excel
                    </div>
                    <div class="info-item-desc">
                        Laporan dalam format Excel dapat diedit dan dianalisis lebih lanjut.
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-item-title period">
                        <i class="bi bi-clock-history"></i> Periode Data
                    </div>
                    <div class="info-item-desc">
                        Pilih bulan dan tahun untuk menampilkan data lembur pada periode tersebut.
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function submitForm(type) {
        const nik   = document.getElementById('nik').value;
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;

        if (!nik || !bulan || !tahun) {
            alert('⚠️ Harap lengkapi semua pilihan terlebih dahulu.');
            return;
        }

        // PERBAIKAN: Pakai prefix /panel/ untuk routes admin
        if (type === 'PDF') {
            // Cetak PDF - buka di tab baru
            window.open(`/panel/lembur/cetaklaporan?nik=${nik}&bulan=${bulan}&tahun=${tahun}`, '_blank');
        } else {
            // Export Excel - download langsung
            window.location.href = `/panel/lembur/exportexcel?nik=${nik}&bulan=${bulan}&tahun=${tahun}`;
        }
    }
</script>

@endsection