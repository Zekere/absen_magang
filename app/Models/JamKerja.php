<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamKerja extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini
     */
    protected $table = 'jam_kerja';

    /**
     * Kolom yang dapat diisi secara mass assignment
     */
    protected $fillable = [
        'jam_masuk',
        'jam_pulang',
        'toleransi_keterlambatan',
        'batas_absen_masuk_awal',
        'batas_absen_masuk_akhir',
        'batas_absen_pulang_sebelum',
    ];

    /**
     * Mengambil konfigurasi jam kerja yang aktif
     * Karena hanya ada 1 row, kita ambil yang pertama
     * 
     * @return JamKerja|null
     */
    public static function getConfig()
    {
        return self::first();
    }

    /**
     * Helper: Cek apakah waktu sekarang dalam rentang absen masuk
     * 
     * @return bool
     */
    public function isWaktuAbsenMasuk()
    {
        $waktuSekarangMenit = (int)date('H') * 60 + (int)date('i');
        
        return $waktuSekarangMenit >= $this->batas_absen_masuk_awal 
            && $waktuSekarangMenit <= $this->batas_absen_masuk_akhir;
    }

    /**
     * Helper: Cek apakah waktu sekarang sudah boleh absen pulang
     * 
     * @return bool
     */
    public function isWaktuAbsenPulang()
    {
        $waktuSekarangMenit = (int)date('H') * 60 + (int)date('i');
        $jamPulangMenit = (int)date('H', strtotime($this->jam_pulang)) * 60 + (int)date('i', strtotime($this->jam_pulang));
        $batasAbsenPulang = $jamPulangMenit - $this->batas_absen_pulang_sebelum;
        
        return $waktuSekarangMenit >= $batasAbsenPulang;
    }

    /**
     * Helper: Mendapatkan waktu mulai boleh absen masuk (format HH:MM)
     * 
     * @return string
     */
    public function getWaktuMulaiAbsenMasuk()
    {
        return sprintf("%02d:%02d", 
            floor($this->batas_absen_masuk_awal / 60), 
            $this->batas_absen_masuk_awal % 60
        );
    }

    /**
     * Helper: Mendapatkan waktu akhir boleh absen masuk (format HH:MM)
     * 
     * @return string
     */
    public function getWaktuAkhirAbsenMasuk()
    {
        return sprintf("%02d:%02d", 
            floor($this->batas_absen_masuk_akhir / 60), 
            $this->batas_absen_masuk_akhir % 60
        );
    }

    /**
     * Helper: Mendapatkan waktu mulai boleh absen pulang (format HH:MM)
     * 
     * @return string
     */
    public function getWaktuMulaiAbsenPulang()
    {
        $jamPulangMenit = (int)date('H', strtotime($this->jam_pulang)) * 60 + (int)date('i', strtotime($this->jam_pulang));
        $batasAbsenPulang = $jamPulangMenit - $this->batas_absen_pulang_sebelum;
        
        return sprintf("%02d:%02d", 
            floor($batasAbsenPulang / 60), 
            $batasAbsenPulang % 60
        );
    }

    /**
     * Helper: Cek apakah karyawan terlambat
     * 
     * @param string $jam_masuk_real Format HH:MM:SS
     * @return bool
     */
    public function isTerlambat($jam_masuk_real)
    {
        $jamMasukReal = strtotime($jam_masuk_real);
        $jamMasukSeharusnya = strtotime($this->jam_masuk);
        
        return $jamMasukReal > $jamMasukSeharusnya;
    }

    /**
     * Helper: Hitung selisih keterlambatan dalam menit
     * 
     * @param string $jam_masuk_real Format HH:MM:SS
     * @return int Selisih dalam menit (0 jika tidak terlambat)
     */
    public function hitungKeterlambatan($jam_masuk_real)
    {
        if (!$this->isTerlambat($jam_masuk_real)) {
            return 0;
        }

        $jamMasukReal = strtotime($jam_masuk_real);
        $jamMasukSeharusnya = strtotime($this->jam_masuk);
        $selisihDetik = $jamMasukReal - $jamMasukSeharusnya;
        
        return floor($selisihDetik / 60);
    }
}