<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Imports\LemburImport;
use App\Models\HRD\Karyawan;
use App\Models\HRD\Lembur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class LemburController extends Controller
{


    public function index()
    {
        $title = 'Lembur Karyawan';
        $lembur = Lembur::with(['karyawan', 'penanggungjawab'])->get();


        return view('hrd.lembur.index', compact('title'));
    }

    public function datatable(Request $request)
    {
        $cuti = Lembur::with(['karyawan', 'penanggungjawab'])->get();

        return DataTables::of($cuti)
            ->addIndexColumn()
            ->editColumn('nama', function (Lembur $k) {
                return $k->karyawan->nama;
            })
            ->editColumn('penanggungjawab', function (Lembur $k) {
                return $k->penanggungjawab->nama;
            })
            ->editColumn('tanggal', function (Lembur $k) {
                return Carbon::parse($k->tanggal)->format('d/m/Y');
            })
            ->editColumn('nominal_gaji', function (Lembur $k) {
                return number_format($k->nominal_gaji, 2, ',', '.');
            })
            ->addColumn('action', function ($row) {
                $id = $row->id;
                return view('hrd.lembur.partial.action', compact('id'));
            })
            ->make(true);
    }

    public function create()
    {
        $title = 'Lembur Karyawan';
        $karyawan = Karyawan::get();

        return view('hrd.lembur.create', compact('karyawan', 'title'));
    }

    public function store(Request $request)
    {
        // cek gaji pokok karyawan
        $karyawan = Karyawan::where('id', $request->karyawan_id)->first();

        // penghitungan gaji
        $hari = Carbon::parse($request->tanggal)->format('D');

        if ($hari == 'Sat') {
            $nominalLembur = $this->hitungLemburSabtu($request->jumlah_jam, $karyawan->gaji_pokok);
        } elseif ($hari == 'Sun') {
            $nominalLembur = $this->hitungLemburMinggu($request->jumlah_jam, $karyawan->gaji_pokok);
        } else {
            $nominalLembur = $this->hitungLemburWeekDays($request->jumlah_jam, $karyawan->gaji_pokok);
        }

        // save gaji
        Lembur::create([
            'karyawan_id' => $request->karyawan_id,
            'penanggungjawab_id' => $request->penanggungjawab_id,
            'tugas' => $request->tugas,
            'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
            'nominal_gaji' => $nominalLembur,
            'jumlah_jam' => $request->jumlah_jam
        ]);

        return redirect()->route('lembur.index')->with('success-create', 'Berhasil Membuat Daftar Lembur');
    }

    public function edit($id)
    {
        $title = 'Lembur Karyawan';
        $karyawan = Karyawan::get();
        $lembur = Lembur::where('id', $id)->first();

        return view('hrd.lembur.edit', compact('karyawan', 'title', 'lembur'));
    }

    public function update(Request $request, $id)
    {
        $lembur = Lembur::where('id', $id)->first();
        // cek gaji pokok karyawan
        $karyawan = Karyawan::where('id', $request->karyawan_id)->first();

        // penghitungan gaji
        $hari = Carbon::parse($request->tanggal)->format('D');

        if ($hari == 'Sat') {
            $nominalLembur = $this->hitungLemburSabtu($request->jumlah_jam, $karyawan->gaji_pokok);
        } elseif ($hari == 'Sun') {
            $nominalLembur = $this->hitungLemburMinggu($request->jumlah_jam, $karyawan->gaji_pokok);
        } else {
            $nominalLembur = $this->hitungLemburWeekDays($request->jumlah_jam, $karyawan->gaji_pokok);
        }

        // save gaji
        $lembur->update([
            'karyawan_id' => $request->karyawan_id,
            'penanggungjawab_id' => $request->penanggungjawab_id,
            'tugas' => $request->tugas,
            'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
            'nominal_gaji' => $nominalLembur,
            'jumlah_jam' => $request->jumlah_jam
        ]);

        return redirect()->route('lembur.index')->with('success-create', 'Berhasil Membuat Daftar Lembur');
    }


    public function delete(Request $request)
    {
        Lembur::where('id', $request->id)->delete();
        return response()->json('Data Beerhasil DiHapus');
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

    public function import(Request $request)
    {
        $file = $request->file;        
        Excel::import(new LemburImport, $file);
        return back();
    }
}
