<?php

namespace App\Http\Controllers;

use App\Models\HRD\Divisi;
use App\Models\HRD\Karyawan;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xls\RC4;
use Yajra\DataTables\Facades\DataTables;

class AnalisisKeuanganController extends Controller
{
    public function index()
    {
        $title = 'Analisis Keuangan';
        $divisi = Divisi::get();
        $karyawan = Karyawan::get();
        return view('keuangan.analisis.index', compact('title','divisi','karyawan'));
    }

    public function grafikdivisi(Request $request)
    {
        $results = DB::table('biaya_operationals as bo')
            ->join('karyawan as k', 'k.id', '=', 'bo.karyawan_id')
            ->join('posisi as p', 'p.id', '=', 'k.posisi_id')
            ->join('divisi as d', 'd.id', '=', 'p.divisi_id')
            ->where('deleted_at', null)
            ->whereYear('bo.tanggal', 2025)
            ->groupBy('d.nama')
            ->select('d.nama as divisi', DB::raw('SUM(bo.nominal) as total_biaya'));
            
        if ($request->tahun) {
            $results->whereYear('bo.tanggal', $request->tahun);
        }

        $result = $results->get();

        $divisi [0]= null;
        $total_biaya [0] = null;
         $grand_total = 0;

        for ($i = 0; $i < count($result); $i++) {
            $divisi[$i + 1] = $result[$i]->divisi;
            $total_biaya[$i + 1] = $result[$i]->total_biaya;
            $grand_total += $result[$i]->total_biaya;

        }

        return response()->json([
            'divisi' => $divisi,
            'total_biaya' => $total_biaya,
            'grand_total' =>'Rp.' . number_format($grand_total, 0, ',', '.'), 
        ]);
    }

    public function datatable (Request $request)
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
               'sb.nama as sub_biaya', 'sb.id as id',
                DB::raw('SUM(bo.nominal) as total_biaya')
            )
             ->orderByDesc(DB::raw('SUM(bo.nominal)'));             

        if ($request->tahun) {
            $query->whereYear('bo.tanggal', $request->tahun);
        }

        if ($request->divisi_id) {
            $query->where('d.nama', $request->divisi_id);
        }

        if ($request->karyawan_id) {
            $query->where('k.id', $request->karyawan_id);
        }
        $results = $query->get();


        
        return DataTables::of($results)
            ->addIndexColumn()
            ->editColumn('sub_biaya', function ($row) {
                return $row->sub_biaya;
            })
            ->editColumn('total_biaya', function ($row) {
                return 'Rp. ' . number_format($row->total_biaya, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {                            
                return $row->id;
            })
            ->make(true);
        
     
    }
}
