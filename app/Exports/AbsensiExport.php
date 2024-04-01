<?php

namespace App\Exports;

use App\Models\HRD\Divisi;
use App\Models\HRD\Lembur;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class AbsensiExport implements FromView
{

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function view(): View
    {

        $tanggal_awal = Carbon::parse($this->data['tanggal_awal'])->format('Y-m-d');
        $tanggal_akhir = Carbon::parse($this->data['tanggal_akhir'])->format('Y-m-d');

        $absensi = DB::table('absensi as ab')
            ->join('karyawan as k', 'ab.karyawan_id', '=', 'k.id')
            ->join('posisi as p', 'k.posisi_id', '=', 'p.id')
            ->join('divisi as d', 'p.divisi_id', '=', 'd.id');

        if ($this->data['tipe_export'] == 'rekap_mingguan') {
            if ($this->data['tanggal_awal']) {
                if (!$this->data['tanggal_akhir']) {
                    $tanggalFilter = $absensi->where('ab.tanggal', '>=', $tanggal_awal);
                } else {
                    $tanggalFilter = $absensi->where('ab.tanggal', '>=', $tanggal_awal)
                        ->where('ab.tanggal', '<=', $tanggal_akhir);
                }
            } elseif ($this->data['tanggal_akhir']) {
                if (!$this->data['tanggal_awal']) {
                    $tanggalFilter = $absensi->where('ab.tanggal', '<=', $tanggal_akhir);
                } else {
                    $tanggalFilter = $absensi->where('ab.tanggal', '>=', $tanggal_awal)
                        ->where('ab.tanggal_top', '<=', $tanggal_akhir);
                }
            } else {
                $tanggalFilter = $absensi;
            }

            $result = $tanggalFilter->where('ab.deleted_at',null)->select('k.nama as nama_karyawan','k.id as id_karyawan', 'd.nama as nama_divisi', 'ab.clock_in as clock_in', 'ab.clock_out as clock_out', 'ab.work_time as work_time', 'ab.tanggal as tanggal_absensi', 'ab.status as status')->get();
        } else {
            $bulanawal = $this->data['bulan']-1;
            $tanggalawal = $this->data['tahun'] .'-'.$bulanawal.'-'.'28';
            $tanggalakhir = $this->data['tahun'] .'-'.$this->data['bulan'].'-'.'27';
            $filteryear = $absensi->whereYear('ab.tanggal', $this->data['tahun']);
            $filtertanggalawal = $filteryear->where('ab.tanggal','>=',$tanggalawal);
            $filtertanggalakhir = $filtertanggalawal->where('ab.tanggal','<=',$tanggalakhir);

            $result = $filtertanggalakhir->where('ab.deleted_at',null)->select('k.nama as nama_karyawan', 'k.id as id_karyawan', 'd.nama as nama_divisi', 'ab.clock_in as clock_in', 'ab.clock_out as clock_out', 'ab.work_time as work_time', 'ab.tanggal as tanggal_absensi', 'ab.status as status')->get();
            $group = $filtertanggalakhir->where('ab.deleted_at',null)->groupBy('k.nama')->select('k.nama as nama_karyawan', 'k.id as id_karyawan', 'd.nama as nama_divisi', 'ab.clock_in as clock_in', 'ab.clock_out as clock_out', 'ab.work_time as work_time', 'ab.tanggal as tanggal_absensi', 'ab.status as status')->get();

            $lembur = DB::table('lembur as lb')->whereYear('lb.tanggal', $this->data['tahun'])
                        ->where('lb.tanggal','>=',$tanggalawal)
                        ->where('lb.tanggal','<=',$tanggalakhir)                        
                        ->select('lb.*')
                        ->get();
        }

        $divisi = Divisi::get();

        if ($this->data['tipe_export'] == 'rekap_mingguan') {
            foreach ($divisi as $asset) {
                foreach ($result as $item) {
                    if ($asset->nama == $item->nama_divisi) {
                        $data[$asset->nama][] = [
                            'nama' => $item->nama_karyawan,
                            'id_karyawan' => $item->id_karyawan,
                            'clock_in' => $item->clock_in,
                            'clock_out' => $item->clock_out,
                            'work_time' => $item->work_time,
                            'tanggal' => $item->tanggal_absensi,
                            'status' => $item->status
                        ];
                    }
                }
            }
        } else {
            $ontime = 0;
            $ijin = 0;
            $terlambat = 0;
            $jumlah_jam = 0;
            $tidak_hadir = 0;
            $nominalLembur=0;
            $error = 0;
            $pengurangan = 0;
            foreach ($group as $item) {
                foreach ($result as $asset) {
                    if ($asset->nama_karyawan == $item->nama_karyawan) {
                        if ($asset->status == 'ontime') {
                            $ontime += 1;
                        } elseif ($asset->status == 'ijin') {
                            $ijin += 1;
                        } elseif ($asset->status == 'terlambat') {
                            $terlambat += 1;
                        } elseif ($asset->status == 'tidak hadir') {
                            $tidak_hadir += 1;
                        }elseif ($asset->status == 'error') {
                            $error += 1;
                        }
                    }
                }

                foreach ($lembur as $value) {
                    if ($item->id_karyawan == $value->karyawan_id) {
                        $hari = Carbon::parse($value->tanggal)->format('D');

                        if ($hari == 'Sat') {
                            $nominalLembur = $this->hitungLemburSabtu($value->jumlah_jam);
                        } elseif ($hari == 'Sun') {
                            $nominalLembur = $this->hitungLemburMinggu($value->jumlah_jam);
                        } else {
                            $nominalLembur = $this->hitungLemburWeekDays($value->jumlah_jam);
                        }
                        
                        $jumlah_jam += $nominalLembur;
                    }
                }

                if ($terlambat > 3) {
                    $hasil = $terlambat/4;
                    $pengurangan = intval($hasil);
                }

                $array[] = [
                    'nama' => $item->nama_karyawan,
                    'nama_divisi' => $item->nama_divisi,
                    'ontime' => $ontime,
                    'ijin' => $ijin,
                    'terlambat' => $terlambat,
                    'lembur' => $jumlah_jam,
                    'tidak_hadir' => $tidak_hadir,
                    'error' => $error , 
                    'pengurangan' =>$pengurangan
                ];

                $ontime = 0;
                $ijin = 0;
                $terlambat = 0;
                $jumlah_jam = 0;
                $tidak_hadir = 0;
                $error = 0;
                $pengurangan=0;
            }

            foreach ($divisi as $asset) {
                foreach ($array as $item) {
                    if ($asset->nama == $item['nama_divisi']) {
                        $data[$asset->nama][] = [
                            'nama' => $item['nama'],
                            'ontime' => $item['ontime'],
                            'ijin' => $item['ijin'],
                            'terlambat' => $item['terlambat'],
                            'lembur' => $item['lembur'],
                            'tidak_hadir' => $item['tidak_hadir'],
                            'error' => $item['error'],
                            'pengurangan' => $item['pengurangan'],
                        ];
                    }
                }
            }
        }       

        

        if ($this->data['tipe_export'] == 'rekap_mingguan') {
            return view('hrd.absensi.export._export', [
                'data' => $data
            ]);
        }else{
            return view('hrd.absensi.export._exportbulanan', [
                'data' => $data
            ]);
        }
        
      
    }

    public function hitungLemburSabtu($jam)
    {
        $totalJam = $jam;
        $gajiLembur1 = 0;
        $gajiLembur2 = 0;
        $gajiLembur3 = 0;

        while ($totalJam > 0) {
            if ($totalJam > 9) {
                $nilaiJam = $totalJam - 9;
                $totalJam = 9;
                $gajiLembur1 =  4 * $nilaiJam ;
            } elseif ($totalJam > 8) {
                $nilaiJam = $totalJam - 8;
                $totalJam = 8;
                $gajiLembur2 =  3 * $nilaiJam ;
            } else {
                $jam = $totalJam;
                $totalJam = 0;
                $gajiLembur3 =  2 * $jam ;
            }
        }

        $totalLembur = $gajiLembur1 + $gajiLembur2 + $gajiLembur3;
        return $totalLembur;
    }

    public function hitungLemburMinggu($jam)
    {
        $totalJam = $jam;
        $gajiLembur1 = 0;
        $gajiLembur2 = 0;
        $gajiLembur3 = 0;

        while ($totalJam > 0) {
            if ($totalJam > 7) {
                $nilaiJam = $totalJam - 7;
                $totalJam = 7;
                $gajiLembur1 =  4 * $nilaiJam ;
            } elseif ($totalJam > 6) {
                $nilaiJam = $totalJam - 6;
                $totalJam = 6;
                $gajiLembur2 =  3 * $nilaiJam ;
            } else {
                $jam = $totalJam;
                $totalJam = 0;
                $gajiLembur3 =  2 * $jam ;
            }
        }

        $totalLembur = $gajiLembur1 + $gajiLembur2 + $gajiLembur3;
        return $totalLembur;
    }

    public function hitungLemburWeekDays($jam)
    {
        $totalJam = $jam;
        $gajiLembur1 = 0;
        $gajiLembur2 = 0;

        while ($totalJam > 0) {
            if ($totalJam > 1) {
                $nilaiJam = $totalJam - 1;
                $totalJam = 1;
                $gajiLembur1 = 2 * $nilaiJam ;
            } else {
                $jam = $totalJam;
                $totalJam = 0;
                $gajiLembur2 =  1.5 * $jam ;
            }
        }

        $totalLembur = $gajiLembur1 + $gajiLembur2;
        return $totalLembur;
    }
}
