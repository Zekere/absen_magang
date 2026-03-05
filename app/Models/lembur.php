<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembur extends Model
{
    use HasFactory;

    protected $table = 'lembur';

    protected $fillable = [
        'nik',
        'tanggal_lembur',
        'jam_mulai',
        'jam_selesai',
        'durasi_menit',
        'keterangan',
        'bukti_foto',
    ];

    /**
     * Relasi ke tabel karyawan
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }

    /**
     * Format durasi ke jam dan menit
     * 
     * @return string
     */
    public function getDurasiFormatted()
    {
        $jam = floor($this->durasi_menit / 60);
        $menit = $this->durasi_menit % 60;
        
        if ($jam > 0 && $menit > 0) {
            return $jam . ' jam ' . $menit . ' menit';
        } elseif ($jam > 0) {
            return $jam . ' jam';
        } else {
            return $menit . ' menit';
        }
    }

    /**
     * Hitung durasi lembur dari jam mulai dan selesai
     * 
     * @param string $jam_mulai
     * @param string $jam_selesai
     * @return int durasi dalam menit
     */
    public static function hitungDurasi($jam_mulai, $jam_selesai)
    {
        $start = strtotime($jam_mulai);
        $end = strtotime($jam_selesai);
        
        $diff = $end - $start;
        return floor($diff / 60);
    }

    /**
     * Scope untuk filter by bulan
     */
    public function scopeByBulan($query, $bulan, $tahun)
    {
        return $query->whereMonth('tanggal_lembur', $bulan)
                     ->whereYear('tanggal_lembur', $tahun);
    }

    /**
     * Scope untuk filter by NIK
     */
    public function scopeByNik($query, $nik)
    {
        return $query->where('nik', $nik);
    }
}