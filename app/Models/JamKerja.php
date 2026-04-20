<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamKerja extends Model
{
    use HasFactory;

    protected $table = 'jam_kerja';

    protected $fillable = [
        'jam_masuk',
        'jam_pulang',
        'toleransi_keterlambatan',
        'batas_absen_masuk_awal',
        'batas_absen_masuk_akhir',
        'batas_absen_pulang_sebelum',
    ];

    /**
     * Paksa kolom time disimpan & dibaca sebagai string "HH:MM:SS"
     * agar tidak otomatis dikonversi ke Carbon oleh Laravel.
     */
    protected $casts = [
        'jam_masuk'  => 'string',
        'jam_pulang' => 'string',
    ];

    // ─────────────────────────────────────────────────────────
    //  HELPER INTERNAL: parse string / Carbon → total menit
    // ─────────────────────────────────────────────────────────

    /**
     * Konversi nilai waktu (string "HH:MM:SS" atau Carbon) ke total menit.
     * Ini adalah satu-satunya tempat parsing terjadi — semua method lain
     * memanggil helper ini agar tidak ada duplikasi logika.
     */
    private function toMenit($waktu): int
    {
        // Jika Carbon / object dengan method format()
        if (is_object($waktu) && method_exists($waktu, 'format')) {
            $waktu = $waktu->format('H:i:s');
        }

        // Pastikan string
        $waktu = (string) $waktu;

        // Ambil hanya "HH:MM" dari "HH:MM:SS" atau "HH:MM"
        $parts = explode(':', substr(trim($waktu), 0, 5));

        return (int)($parts[0] ?? 0) * 60 + (int)($parts[1] ?? 0);
    }

    /**
     * Format total menit → string "HH:MM"
     */
    private function menitKeJam(int $menit): string
    {
        return sprintf('%02d:%02d', floor($menit / 60), $menit % 60);
    }

    // ─────────────────────────────────────────────────────────
    //  PUBLIC STATIC
    // ─────────────────────────────────────────────────────────

    /**
     * Ambil konfigurasi jam kerja (hanya 1 row).
     */
    public static function getConfig(): ?self
    {
        return self::first();
    }

    // ─────────────────────────────────────────────────────────
    //  GETTER WAKTU (format HH:MM)
    // ─────────────────────────────────────────────────────────

    /**
     * Waktu paling awal bisa absen masuk (format HH:MM).
     * Contoh: batas_absen_masuk_awal = 360 → "06:00"
     */
    public function getWaktuMulaiAbsenMasuk(): string
    {
        return $this->menitKeJam((int) $this->batas_absen_masuk_awal);
    }

    /**
     * Waktu paling akhir bisa absen masuk (format HH:MM).
     * Contoh: batas_absen_masuk_akhir = 1020 → "17:00"
     */
    public function getWaktuAkhirAbsenMasuk(): string
    {
        return $this->menitKeJam((int) $this->batas_absen_masuk_akhir);
    }

    /**
     * Waktu mulai boleh absen pulang (format HH:MM).
     * Contoh: jam_pulang = "15:00", batas_absen_pulang_sebelum = 60 → "14:00"
     */
    public function getWaktuMulaiAbsenPulang(): string
    {
        $jamPulangMenit   = $this->toMenit($this->jam_pulang);
        $batasAbsenPulang = $jamPulangMenit - (int) $this->batas_absen_pulang_sebelum;

        return $this->menitKeJam($batasAbsenPulang);
    }

    // ─────────────────────────────────────────────────────────
    //  CEK WAKTU
    // ─────────────────────────────────────────────────────────

    /**
     * Apakah sekarang masih dalam rentang waktu absen masuk?
     */
    public function isWaktuAbsenMasuk(): bool
    {
        $sekarang = (int) date('H') * 60 + (int) date('i');

        return $sekarang >= (int) $this->batas_absen_masuk_awal
            && $sekarang <= (int) $this->batas_absen_masuk_akhir;
    }

    /**
     * Apakah sekarang sudah boleh absen pulang?
     */
    public function isWaktuAbsenPulang(): bool
    {
        $sekarang         = (int) date('H') * 60 + (int) date('i');
        $jamPulangMenit   = $this->toMenit($this->jam_pulang);
        $batasAbsenPulang = $jamPulangMenit - (int) $this->batas_absen_pulang_sebelum;

        return $sekarang >= $batasAbsenPulang;
    }

    // ─────────────────────────────────────────────────────────
    //  KETERLAMBATAN
    // ─────────────────────────────────────────────────────────

    /**
     * Apakah jam masuk karyawan melewati batas toleransi?
     *
     * @param string $jam_masuk_real Format "HH:MM:SS" atau "HH:MM"
     */
    public function isTerlambat(string $jam_masuk_real): bool
    {
        $realMenit      = $this->toMenit($jam_masuk_real);
        $seharusnyaMenit = $this->toMenit($this->jam_masuk);
        $batasToleransi  = $seharusnyaMenit + (int) $this->toleransi_keterlambatan;

        return $realMenit > $batasToleransi;
    }

    /**
     * Hitung keterlambatan dalam menit (0 jika tidak terlambat).
     *
     * @param string $jam_masuk_real Format "HH:MM:SS" atau "HH:MM"
     */
    public function hitungKeterlambatan(string $jam_masuk_real): int
    {
        $realMenit       = $this->toMenit($jam_masuk_real);
        $seharusnyaMenit = $this->toMenit($this->jam_masuk);

        $selisih = $realMenit - $seharusnyaMenit;

        return max(0, $selisih);
    }
}