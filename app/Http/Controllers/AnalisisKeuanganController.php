<?php

namespace App\Http\Controllers;

use App\Exports\CashAdvanceExport;
use App\Exports\LaporanBiayaOperasional;
use App\Models\HRD\Divisi;
use App\Models\HRD\Karyawan;
use App\Models\JenisBiaya;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xls\RC4;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class AnalisisKeuanganController extends Controller
{
    public function index()
    {
        $title = 'Analisis Keuangan';
        $divisi = Divisi::get();
        $karyawan = Karyawan::get();
        $jenisbiaya = JenisBiaya::with('subjenisbiaya')->get();
        return view('keuangan.analisis.index', compact('title', 'divisi', 'karyawan', 'jenisbiaya'));
    }

    public function grafikdivisi(Request $request)
    {
        $results = DB::table('biaya_operationals as bo')
            ->join('karyawan as k', 'k.id', '=', 'bo.karyawan_id')
            ->join('posisi as p', 'p.id', '=', 'k.posisi_id')
            ->join('divisi as d', 'd.id', '=', 'p.divisi_id')
            ->where('deleted_at', null)
            ->groupBy('d.nama')
            ->select('d.nama as divisi', DB::raw('SUM(bo.nominal) as total_biaya'));

        if ($request->tahun) {
            $results->whereYear('bo.tanggal', $request->tahun);
        }

        if ($request->jenisbiaya_id !== 'all') {
            $results->where('bo.jenis_biaya_id', $request->jenisbiaya_id);
        }

        $result = $results->get();

        $divisi[0] = null;
        $total_biaya[0] = null;
        $grand_total = 0;

        for ($i = 0; $i < count($result); $i++) {
            $divisi[$i + 1] = $result[$i]->divisi;
            $total_biaya[$i + 1] = $result[$i]->total_biaya;
            $grand_total += $result[$i]->total_biaya;
        }

        return response()->json([
            'divisi' => $divisi,
            'total_biaya' => $total_biaya,
            'grand_total' => 'Rp.' . number_format($grand_total, 0, ',', '.'),
        ]);
    }

    public function datatable(Request $request)
    {
        $query = DB::table('biaya_operationals as bo')
            ->join('karyawan as k', 'k.id', '=', 'bo.karyawan_id')
            ->join('posisi as p', 'p.id', '=', 'k.posisi_id')
            ->join('divisi as d', 'd.id', '=', 'p.divisi_id')
            ->join('jenis_biayas as jb', 'jb.id', '=', 'bo.jenis_biaya_id')
            ->join('sub_biaya as sb', 'sb.id', '=', 'bo.subjenis_biaya_id')
            ->where('deleted_at', null)
            ->groupBy('sb.nama')
            ->select(
                'jb.nama as jenis_biaya',                
                'sb.nama as sub_jenis_biaya',
                'sb.id as id',
                DB::raw('SUM(bo.nominal) as total_biaya')
            )
            ->orderByDesc(DB::raw('SUM(bo.nominal)'));

        if ($request->tahun) {
            $query->whereYear('bo.tanggal', $request->tahun);
        }

        if ($request->divisi_id) {
            $query->where('d.id', $request->divisi_id);
        }

        if ($request->karyawan_id !== 'all') {
            $query->where('k.id', $request->karyawan_id);
        }

        if ($request->jenisbiaya_id !== 'all') {
            $query->where('bo.jenis_biaya_id', $request->jenisbiaya_id);
        }

        $results = $query->get();



        return DataTables::of($results)
            ->addIndexColumn()
            ->editColumn('jenis_biaya', function ($row) {
                return $row->jenis_biaya;
            }) 
              ->editColumn('sub_jenis_biaya', function ($row) {
                return $row->sub_jenis_biaya;
            })          
            ->editColumn('total_biaya', function ($row) {
                return 'Rp. ' . number_format($row->total_biaya, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                return $row->id;
            })
            ->make(true);
    }


    public function historycash(Request $request)
    {
        return view('keuangan.analisis.modal');
    }

    public function datatablehistorycash(Request $request)
    {
        $query = DB::table('biaya_operationals as bo')
            ->join('karyawan as k', 'k.id', '=', 'bo.karyawan_id')
            ->join('posisi as p', 'p.id', '=', 'k.posisi_id')
            ->join('divisi as d', 'd.id', '=', 'p.divisi_id')
            ->join('jenis_biayas as jb', 'jb.id', '=', 'bo.jenis_biaya_id')
            ->join('sub_biaya as sb', 'sb.id', '=', 'bo.subjenis_biaya_id')
            ->where('bo.deleted_at', null)
            ->select(
                'bo.tanggal as tanggal',
                'k.nama as karyawan',
                'd.nama as divisi',
                'bo.nominal as total_biaya',
                'bo.keterangan',
            )
            ->orderByDesc('bo.nominal')
            ->where('bo.subjenis_biaya_id', $request->subbiaya_id);

        if ($request->tahun) {
            $query->whereYear('bo.tanggal', $request->tahun);
        }

        if ($request->divisi_id) {
            $query->where('d.id', $request->divisi_id);
        }

        if ($request->karyawan_id !== 'all') {
            $query->where('k.id', $request->karyawan_id);
        }

        if ($request->jenisbiaya_id !== 'all') {
            $query->where('bo.jenis_biaya_id', $request->jenisbiaya_id);
        }

        $results = $query->get();

        return DataTables::of($results)
            ->addIndexColumn()
            ->editColumn('total_biaya', function ($row) {
                return 'Rp. ' . number_format($row->total_biaya, 0, ',', '.');
            })
            ->make(true);
    }

    public function download(Request $request)
    {
        $data = $request->all();
        if ($request->jenis_laporan == 'biaya_operational') {
            return Excel::download(new LaporanBiayaOperasional($data), 'laporanbiayaoperasional-.xlsx');
        } else {
            return Excel::download(new CashAdvanceExport($data), 'laporancashadvance-.xlsx');
        }
    }
}
