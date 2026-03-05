<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Lembur - {{ $karyawan->nama_lengkap }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            padding: 40px;
            background: #f8f9fa;
            color: #1e293b;
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 50px;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.1);
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 3px solid #667eea;
        }

        .company-name {
            font-size: 24px;
            font-weight: 800;
            color: #667eea;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }

        .company-address {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 25px;
        }

        .report-title {
            font-size: 28px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 8px;
            letter-spacing: -0.8px;
        }

        .report-period {
            font-size: 15px;
            color: #667eea;
            font-weight: 600;
        }

        /* Employee Info */
        .employee-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 25px 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            color: white;
        }

        .employee-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .employee-info td {
            padding: 8px 0;
            font-size: 14px;
        }

        .employee-info td:first-child {
            width: 150px;
            font-weight: 600;
            opacity: 0.9;
        }

        .employee-info td:nth-child(2) {
            width: 20px;
            opacity: 0.7;
        }

        .employee-info td:last-child {
            font-weight: 700;
        }

        /* Summary Cards */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
            text-align: center;
        }

        .summary-card.total { border-left-color: #10b981; }
        .summary-card.days { border-left-color: #f59e0b; }
        .summary-card.avg { border-left-color: #3b82f6; }

        .summary-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 28px;
            font-weight: 800;
            color: #1e293b;
        }

        .summary-unit {
            font-size: 14px;
            color: #64748b;
            font-weight: 600;
        }

        /* Table */
        .table-container {
            margin-bottom: 30px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        table.data-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        table.data-table th {
            padding: 14px 12px;
            text-align: left;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table.data-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }

        table.data-table tbody tr:hover {
            background: #f8fafc;
        }

        table.data-table tbody tr:last-child {
            border-bottom: 2px solid #cbd5e0;
        }

        table.data-table td {
            padding: 14px 12px;
            color: #475569;
        }

        table.data-table td:first-child {
            font-weight: 600;
            color: #1e293b;
        }

        .badge-duration {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
        }

        /* Total Section */
        .total-section {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 25px 30px;
            border-radius: 10px;
            color: white;
            margin-bottom: 30px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            font-size: 16px;
            font-weight: 600;
            opacity: 0.95;
        }

        .total-value {
            font-size: 32px;
            font-weight: 800;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .footer-left {
            font-size: 12px;
            color: #64748b;
        }

        .footer-right {
            text-align: center;
        }

        .signature-label {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 60px;
        }

        .signature-name {
            font-weight: 700;
            color: #1e293b;
            border-top: 2px solid #1e293b;
            padding-top: 8px;
            min-width: 200px;
        }

        .signature-title {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }

        /* Print Styles */
        @media print {
            body {
                padding: 0;
                background: white;
            }

            .container {
                box-shadow: none;
                padding: 20px;
            }

            .no-print {
                display: none !important;
            }

            @page {
                margin: 1.5cm;
                size: A4;
            }
        }

        /* Buttons */
        .action-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
        }

        .btn-print {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-download {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-close {
            background: #ef4444;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-close:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
        }

        .empty-state-icon {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state h3 {
            color: #475569;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Action Buttons (Hidden on print) -->
    <div class="action-buttons no-print">
        <button class="btn btn-print" onclick="window.print()">
            🖨️ Cetak
        </button>
        <a href="/panel/lembur/exportexcel?nik={{ $karyawan->nik }}&bulan={{ $bulan }}&tahun={{ $tahun }}" class="btn btn-download">
            📊 Download Excel
        </a>
        <button class="btn btn-close" onclick="window.close()">
            ✕ Tutup
        </button>
    </div>

    @php
        $namaHari = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];
    @endphp

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">PU Direktorat Jendral Cipta Karya</div>
            <div class="company-address">Jl. Petompon, Kec. Gajahmungkur, Kota Semarang, Jawa Tengah 50237</div>
            <div class="report-title">LAPORAN LEMBUR PEGAWAI</div>
            <div class="report-period">Periode: {{ $namabulan[$bulan] }} {{ $tahun }}</div>
        </div>

        <!-- Employee Info -->
        <div class="employee-info">
            <table>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td>{{ $karyawan->nik }}</td>
                </tr>
                <tr>
                    <td>Nama Lengkap</td>
                    <td>:</td>
                    <td>{{ $karyawan->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ $karyawan->jabatan ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Departemen</td>
                    <td>:</td>
                    <td>{{ $karyawan->nama_dept }}</td>
                </tr>
            </table>
        </div>

        @if($lembur->count() > 0)
            <!-- Summary Cards -->
            <div class="summary-cards">
                <div class="summary-card total">
                    <div class="summary-label">Total Jam</div>
                    <div class="summary-value">{{ $total_jam }}<span class="summary-unit">j</span> {{ $total_menit }}<span class="summary-unit">m</span></div>
                </div>
                <div class="summary-card days">
                    <div class="summary-label">Hari Lembur</div>
                    <div class="summary-value">{{ $lembur->count() }}<span class="summary-unit"> hari</span></div>
                </div>
                <div class="summary-card avg">
                    <div class="summary-label">Rata-rata</div>
                    <div class="summary-value">{{ number_format($lembur->avg('durasi_menit') / 60, 1) }}<span class="summary-unit"> jam</span></div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th width="100">Durasi</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lembur as $index => $d)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ date('d/m/Y', strtotime($d->tanggal_lembur)) }}</td>
                            <td>{{ $namaHari[date('l', strtotime($d->tanggal_lembur))] }}</td>
                            <td>{{ date('H:i', strtotime($d->jam_mulai)) }}</td>
                            <td>{{ date('H:i', strtotime($d->jam_selesai)) }}</td>
                            <td>
                                @php
                                    $jam = floor($d->durasi_menit / 60);
                                    $menit = $d->durasi_menit % 60;
                                @endphp
                                <span class="badge-duration">{{ $jam }}j {{ $menit }}m</span>
                            </td>
                            <td>{{ $d->keterangan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total Section -->
            <div class="total-section">
                <div class="total-row">
                    <div class="total-label">⏱️ Total Durasi Lembur</div>
                    <div class="total-value">{{ $total_jam }} Jam {{ $total_menit }} Menit</div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>Tidak Ada Data Lembur</h3>
                <p>Tidak ada data lembur untuk periode {{ $namabulan[$bulan] }} {{ $tahun }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="footer-left">
                <div>Dicetak pada: {{ date('d F Y, H:i') }} WIB</div>
                <div>Sistem Presensi & Lembur v1.0</div>
            </div>
            <div class="footer-right">
                <div class="signature-label">Mengetahui,</div>
                <div class="signature-name">Deka Abdullah</div>
                <div class="signature-title">Kepala Keuangan</div>
            </div>
        </div>
    </div>
</body>
</html>