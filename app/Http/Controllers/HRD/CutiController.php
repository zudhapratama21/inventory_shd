<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\HRD\Cuti;
use App\Models\HRD\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CutiController extends Controller
{
    public function index()
    {
        $title = 'Cuti';
        return view('hrd.cuti.index', compact('title'));
    }

    public function datatable(Request  $request)
    {
        $cuti = Cuti::with('karyawan')->get();

        return DataTables::of($cuti)
            ->addIndexColumn()
            ->editColumn('nama', function (Cuti $k) {
                return $k->karyawan->nama;
            })  
            ->editColumn('tanggal', function (Cuti $k) {
                return Carbon::parse($k->tanggal)->format('d/m/Y');
            })        
            ->editColumn('status', function (Cuti $k) {
                return $k->status;
            })        
            ->addColumn('action', function ($row) {
                $id = $row->id;
                return view('hrd.cuti.partial.action', compact('id'));
            })
            ->make(true);
    }

    public function create()
    {
        $title =  'Cuti';
        $karyawan = Karyawan::get();

        return  view('hrd.cuti.create', compact('karyawan', 'title'));
    }

    public function store(Request $request)
    {
        $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');
        $bulan = Carbon::parse($request->tanggal)->format('m');
        $tahun = Carbon::parse($request->tanggal)->format('Y');

        Cuti::create([
            'karyawan_id' => $request->karyawan_id,
            'tanggal' => $tanggal,
            'alasan' => $request->alasan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'status' => 'DiSetujui'
        ]);

        return redirect()->route('cuti.index')->with('success', 'Data  Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        $title = 'Cuti';
        $karyawan = Karyawan::get();
        $cuti = Cuti::where('id',$id)->first();
        return view('hrd.cuti.edit', compact('karyawan','cuti','title'));

    }

    public function update(Request $request, $id)
    {
        $cuti = Cuti::where('id', $id)->first();
        $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');
        $bulan = Carbon::parse($request->tanggal)->format('m');
        $tahun = Carbon::parse($request->tanggal)->format('Y');

        $cuti->update([
            'karyawan_id' => $request->karyawan_id,
            'tanggal' => $tanggal,
            'alasan' => $request->alasan,
            'bulan' => $bulan,
            'tahun' => $tahun,            
        ]);

        return redirect()->route('cuti.index')->with('success','Data Berhasil Diubah');
    }

    public function delete (Request $request)
    {
        Cuti::where('id',$request->id)->delete();

        return response()->json('Data Berhasil di Hapus');
    }


}
