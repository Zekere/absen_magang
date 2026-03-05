<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\Lembur;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LemburController extends Controller
{
    // ===== KARYAWAN SECTION ===== (Methods sebelumnya tetap sama)
    
    public function index()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $dataLembur = Lembur::where('nik', $nik)
            ->orderBy('tanggal_lembur', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('lembur.index', compact('dataLembur'));
    }

    public function create()
    {
        return view('lembur.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_lembur' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'keterangan' => 'required|min:10',
            'bukti_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $nik = Auth::guard('karyawan')->user()->nik;

        if ($request->jam_selesai <= $request->jam_mulai) {
            return Redirect::back()->with(['error' => 'Jam selesai harus lebih besar dari jam mulai!']);
        }

        $cek = Lembur::where('nik', $nik)
            ->where('tanggal_lembur', $request->tanggal_lembur)
            ->first();

        if ($cek) {
            return Redirect::back()->with(['error' => 'Anda sudah menginput lembur pada tanggal tersebut!']);
        }

        $durasi = Lembur::hitungDurasi($request->jam_mulai, $request->jam_selesai);

        $bukti_foto = null;
        if ($request->hasFile('bukti_foto')) {
            $file = $request->file('bukti_foto');
            $ext = $file->getClientOriginalExtension();
            $filename = $nik . '-' . date('YmdHis') . '.' . $ext;
            $path = public_path('storage/uploads/lembur');
            
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            
            $file->move($path, $filename);
            $bukti_foto = $filename;
        }

        $lembur = Lembur::create([
            'nik' => $nik,
            'tanggal_lembur' => $request->tanggal_lembur,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'durasi_menit' => $durasi,
            'keterangan' => $request->keterangan,
            'bukti_foto' => $bukti_foto,
        ]);

        if ($lembur) {
            return Redirect::route('lembur.index')->with(['success' => 'Data lembur berhasil disimpan']);
        } else {
            return Redirect::back()->with(['error' => 'Gagal menyimpan data lembur']);
        }
    }

    public function show($id)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $lembur = Lembur::where('id', $id)->where('nik', $nik)->first();

        if (!$lembur) {
            return Redirect::back()->with(['error' => 'Data tidak ditemukan']);
        }

        return view('lembur.show', compact('lembur'));
    }

    public function edit($id)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $lembur = Lembur::where('id', $id)->where('nik', $nik)->first();

        if (!$lembur) {
            return Redirect::back()->with(['error' => 'Data tidak ditemukan']);
        }

        return view('lembur.edit', compact('lembur'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_lembur' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'keterangan' => 'required|min:10',
            'bukti_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $nik = Auth::guard('karyawan')->user()->nik;
        $lembur = Lembur::where('id', $id)->where('nik', $nik)->first();

        if (!$lembur) {
            return Redirect::back()->with(['error' => 'Data tidak ditemukan']);
        }

        if ($request->jam_selesai <= $request->jam_mulai) {
            return Redirect::back()->with(['error' => 'Jam selesai harus lebih besar dari jam mulai!']);
        }

        $cek = Lembur::where('nik', $nik)
            ->where('tanggal_lembur', $request->tanggal_lembur)
            ->where('id', '!=', $id)
            ->first();

        if ($cek) {
            return Redirect::back()->with(['error' => 'Anda sudah memiliki data lembur pada tanggal tersebut!']);
        }

        $durasi = Lembur::hitungDurasi($request->jam_mulai, $request->jam_selesai);

        $bukti_foto = $lembur->bukti_foto;
        if ($request->hasFile('bukti_foto')) {
            if ($bukti_foto) {
                $path_old = public_path('storage/uploads/lembur/' . $bukti_foto);
                if (file_exists($path_old)) {
                    unlink($path_old);
                }
            }

            $file = $request->file('bukti_foto');
            $ext = $file->getClientOriginalExtension();
            $filename = $nik . '-' . date('YmdHis') . '.' . $ext;
            $path = public_path('storage/uploads/lembur');
            
            $file->move($path, $filename);
            $bukti_foto = $filename;
        }

        $update = $lembur->update([
            'tanggal_lembur' => $request->tanggal_lembur,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'durasi_menit' => $durasi,
            'keterangan' => $request->keterangan,
            'bukti_foto' => $bukti_foto,
        ]);

        if ($update) {
            return Redirect::route('lembur.index')->with(['success' => 'Data lembur berhasil diupdate']);
        } else {
            return Redirect::back()->with(['error' => 'Gagal mengupdate data lembur']);
        }
    }

    public function destroy($id)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $lembur = Lembur::where('id', $id)->where('nik', $nik)->first();

        if (!$lembur) {
            return Redirect::back()->with(['error' => 'Data tidak ditemukan']);
        }

        if ($lembur->bukti_foto) {
            $path = public_path('storage/uploads/lembur/' . $lembur->bukti_foto);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $lembur->delete();

        return Redirect::back()->with(['success' => 'Data lembur berhasil dihapus']);
    }

    public function lihatBukti($id)
    {
        $lembur = Lembur::findOrFail($id);
        
        if (!$lembur->bukti_foto) {
            abort(404, 'Foto tidak ditemukan');
        }
        
        $path = public_path('storage/uploads/lembur/' . $lembur->bukti_foto);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($path);
    }

    // ===== ADMIN SECTION =====

    public function dataLembur()
    {
        $lembur = DB::table('lembur')
            ->join('karyawan', 'lembur.nik', '=', 'karyawan.nik')
            ->leftJoin('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->select(
                'lembur.*', 
                'karyawan.nama_lengkap',
                'karyawan.jabatan',
                DB::raw('COALESCE(departemen.nama_dept, "-") as nama_dept')
            )
            ->orderBy('lembur.tanggal_lembur', 'desc')
            ->get();

        return view('lembur.data', compact('lembur'));
    }

    public function laporan()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        
        $karyawan = DB::table('karyawan')
            ->orderBy('nama_lengkap')
            ->get();
        
        return view('lembur.laporan', compact('namabulan', 'karyawan'));
    }

    /**
     * Cetak laporan PDF - Preview di browser
     */
    public function cetakLaporan(Request $request)
    {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        
        $karyawan = DB::table('karyawan')
            ->leftJoin('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->select(
                'karyawan.*',
                DB::raw('COALESCE(departemen.nama_dept, "-") as nama_dept')
            )
            ->where('karyawan.nik', $nik)
            ->first();

        if (!$karyawan) {
            return Redirect::back()->with(['error' => 'Data karyawan tidak ditemukan']);
        }

        $lembur = Lembur::where('nik', $nik)
            ->whereMonth('tanggal_lembur', $bulan)
            ->whereYear('tanggal_lembur', $tahun)
            ->orderBy('tanggal_lembur')
            ->get();

        $total_durasi = $lembur->sum('durasi_menit');
        $total_jam = floor($total_durasi / 60);
        $total_menit = $total_durasi % 60;

        return view('lembur.cetaklaporan', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'lembur', 'total_jam', 'total_menit'));
    }

    /**
     * Export Excel - Download langsung
     */
    public function exportExcel(Request $request)
    {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        
        // Get karyawan data
        $karyawan = DB::table('karyawan')
            ->leftJoin('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->select(
                'karyawan.*',
                DB::raw('COALESCE(departemen.nama_dept, "-") as nama_dept')
            )
            ->where('karyawan.nik', $nik)
            ->first();

        if (!$karyawan) {
            return Redirect::back()->with(['error' => 'Data karyawan tidak ditemukan']);
        }

        // Get lembur data
        $lembur = Lembur::where('nik', $nik)
            ->whereMonth('tanggal_lembur', $bulan)
            ->whereYear('tanggal_lembur', $tahun)
            ->orderBy('tanggal_lembur')
            ->get();

        $total_durasi = $lembur->sum('durasi_menit');
        $total_jam = floor($total_durasi / 60);
        $total_menit = $total_durasi % 60;

        // Create Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(40);

        $row = 1;

        // Title
        $sheet->setCellValue('A' . $row, 'LAPORAN LEMBUR KARYAWAN');
        $sheet->mergeCells('A' . $row . ':G' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setSize(16)->setBold(true);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;

        // Period
        $sheet->setCellValue('A' . $row, 'Periode: ' . $namabulan[$bulan] . ' ' . $tahun);
        $sheet->mergeCells('A' . $row . ':G' . $row);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row += 2;

        // Employee Info
        $sheet->setCellValue('A' . $row, 'NIK');
        $sheet->setCellValue('B' . $row, ': ' . $karyawan->nik);
        $sheet->mergeCells('B' . $row . ':C' . $row);
        $row++;

        $sheet->setCellValue('A' . $row, 'Nama');
        $sheet->setCellValue('B' . $row, ': ' . $karyawan->nama_lengkap);
        $sheet->mergeCells('B' . $row . ':C' . $row);
        $row++;

        $sheet->setCellValue('A' . $row, 'Jabatan');
        $sheet->setCellValue('B' . $row, ': ' . ($karyawan->jabatan ?? '-'));
        $sheet->mergeCells('B' . $row . ':C' . $row);
        $row++;

        $sheet->setCellValue('A' . $row, 'Departemen');
        $sheet->setCellValue('B' . $row, ': ' . $karyawan->nama_dept);
        $sheet->mergeCells('B' . $row . ':C' . $row);
        $row += 2;

        // Table Header
        $headerRow = $row;
        $sheet->setCellValue('A' . $row, 'No');
        $sheet->setCellValue('B' . $row, 'Tanggal');
        $sheet->setCellValue('C' . $row, 'Hari');
        $sheet->setCellValue('D' . $row, 'Jam Mulai');
        $sheet->setCellValue('E' . $row, 'Jam Selesai');
        $sheet->setCellValue('F' . $row, 'Durasi');
        $sheet->setCellValue('G' . $row, 'Keterangan');

        // Style header
        $sheet->getStyle('A' . $row . ':G' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':G' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF667EEA');
        $sheet->getStyle('A' . $row . ':G' . $row)->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A' . $row . ':G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;

        // Data
        $no = 1;
        foreach ($lembur as $d) {
            $jam = floor($d->durasi_menit / 60);
            $menit = $d->durasi_menit % 60;

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($d->tanggal_lembur)));
            $sheet->setCellValue('C' . $row, date('l', strtotime($d->tanggal_lembur)));
            $sheet->setCellValue('D' . $row, date('H:i', strtotime($d->jam_mulai)));
            $sheet->setCellValue('E' . $row, date('H:i', strtotime($d->jam_selesai)));
            $sheet->setCellValue('F' . $row, $jam . 'j ' . $menit . 'm');
            $sheet->setCellValue('G' . $row, $d->keterangan);

            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $row . ':F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;
        }

        // Total
        $row++;
        $sheet->setCellValue('E' . $row, 'TOTAL');
        $sheet->setCellValue('F' . $row, $total_jam . ' Jam ' . $total_menit . ' Menit');
        $sheet->mergeCells('F' . $row . ':G' . $row);
        $sheet->getStyle('E' . $row . ':G' . $row)->getFont()->setBold(true);
        $sheet->getStyle('E' . $row . ':G' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF10B981');
        $sheet->getStyle('E' . $row . ':G' . $row)->getFont()->getColor()->setARGB('FFFFFFFF');

        // Border all data
        $sheet->getStyle('A' . $headerRow . ':G' . ($row))->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Generate filename
        $filename = 'Laporan_Lembur_' . $karyawan->nama_lengkap . '_' . $namabulan[$bulan] . '_' . $tahun . '.xlsx';

        // Save and download
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}