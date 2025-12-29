<?php

namespace App\Imports;

use App\Models\HRD\Karyawan;
use App\Models\HRD\Lembur;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use DateTime;

class LemburImport implements ToModel
{
    protected $no = 0;
    public function model(array $row)
    {        
        if ($this->no > 0) {
            $karyawan = Karyawan::where('no_emp', $row[0])->first();
            if ($karyawan) {                
                $timetanggal = DateTime::createFromFormat('d/m/Y', $row[2])->format('Y-m-d');

                
                
                $hari = DateTime::createFromFormat('d/m/Y', $row[2])->format('D');

                if ($hari == 'Sat') {
                    $nominalLembur = $this->hitungLemburSabtu($row[4], $karyawan->gaji_pokok);
                } elseif ($hari == 'Sun') {
                    $nominalLembur = $this->hitungLemburMinggu($row[4], $karyawan->gaji_pokok);
                } else {
                    $nominalLembur = $this->hitungLemburWeekDays($row[4], $karyawan->gaji_pokok);
                }

                // save gaji
                Lembur::create([
                    'karyawan_id' => $karyawan->id,
                    'penanggungjawab_id' => 20,
                    'tugas' => $row[3],
                    'tanggal' => $timetanggal,
                    'nominal_gaji' => $nominalLembur,
                    'jumlah_jam' => $row[4]
                ]);
            }
        }

        $this->no++;
        return;
    }

    public function hitungLemburSabtu($jam, $gapok)
    {
        $totalJam = $jam;
        $gajiLembur1 = 0;
        $gajiLembur2 = 0;
        $gajiLembur3 = 0;

        while ($totalJam > 0) {
            if ($totalJam > 9) {
                $nilaiJam = $totalJam - 9;
                $totalJam = 9;
                $gajiLembur1 = 1 / 173 * 4 * $nilaiJam * $gapok;
            } elseif ($totalJam > 8) {
                $nilaiJam = $totalJam - 8;
                $totalJam = 8;
                $gajiLembur2 = 1 / 173 * 3 * $nilaiJam * $gapok;
            } else {
                $jam = $totalJam;
                $totalJam = 0;
                $gajiLembur3 = 1 / 173 * 2 * $jam * $gapok;
            }
        }

        $totalLembur = $gajiLembur1 + $gajiLembur2 + $gajiLembur3;
        return $totalLembur;
    }

    public function hitungLemburMinggu($jam, $gapok)
    {
        $totalJam = $jam;
        $gajiLembur1 = 0;
        $gajiLembur2 = 0;
        $gajiLembur3 = 0;

        while ($totalJam > 0) {
            if ($totalJam > 7) {
                $nilaiJam = $totalJam - 7;
                $totalJam = 7;
                $gajiLembur1 = 1 / 173 * 4 * $nilaiJam * $gapok;
            } elseif ($totalJam > 6) {
                $nilaiJam = $totalJam - 6;
                $totalJam = 6;
                $gajiLembur2 = 1 / 173 * 3 * $nilaiJam * $gapok;
            } else {
                $jam = $totalJam;
                $totalJam = 0;
                $gajiLembur3 = 1 / 173 * 2 * $jam * $gapok;
            }
        }

        $totalLembur = $gajiLembur1 + $gajiLembur2 + $gajiLembur3;
        return $totalLembur;
    }

    public function hitungLemburWeekDays($jam, $gapok)
    {
        $totalJam = $jam;
        $gajiLembur1 = 0;
        $gajiLembur2 = 0;

        while ($totalJam > 0) {
            if ($totalJam > 1) {
                $nilaiJam = $totalJam - 1;
                $totalJam = 1;
                $gajiLembur1 = 1 / 173 * 2 * $nilaiJam * $gapok;
            } else {
                $jam = $totalJam;
                $totalJam = 0;
                $gajiLembur2 = 1 / 173 * 1.5 * $jam * $gapok;
            }
        }

        $totalLembur = $gajiLembur1 + $gajiLembur2;
        return $totalLembur;
    }
}
