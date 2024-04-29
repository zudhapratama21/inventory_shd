<?php

namespace App\Http\Controllers\HRD;

use App\Exports\AbsensiExport;
use App\Http\Controllers\Controller;
use App\Imports\AbsensiImport;
use App\Models\HRD\Absensi;
use App\Models\HRD\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class AbsensiController extends Controller
{
    public function index ()
    {        
        $title = 'Absensi';
        $bulan =  [];
        for ($i = 1; $i <=12; $i++) {
            $databulan = '1-'.$i.'-2023';
            $bulan[] = [
                'nama' => Carbon::parse($databulan)->format('F') ,
                'id' => $i
            ];         
        }

        return view('hrd.absensi.index',compact('title','bulan'));
    }

    public function import (Request $request)
    {
        Excel::import(new AbsensiImport, $request->file('file_import'));    
        return back();
    }

    public function datatable (Request $request)
    {
        $absensi = DB::table('absensi as ab')
                ->join('karyawan as k','ab.karyawan_id','=','k.id')                
                ->where('ab.deleted_at','=',null)
                ->select('ab.id','ab.tanggal','k.nama','ab.clock_in','ab.clock_out','ab.work_time','ab.status')->get();                       
        
        return DataTables::of($absensi)
            ->addIndexColumn()
            ->editColumn('nama', function ($absensi) {
                return $absensi->nama;
            })  
            ->editColumn('tanggal', function ($absensi) {
                return Carbon::parse($absensi->tanggal)->format('d/m/Y');
            }) 
            ->editColumn('clock_in', function ($absensi) {
                return Carbon::parse($absensi->clock_in)->format('H:i');
            })        
            ->editColumn('clock_out', function ($absensi) {
                return Carbon::parse($absensi->clock_out)->format('H:i');
            }) 
            ->editColumn('work_time', function ($absensi) {
                return Carbon::parse($absensi->work_time)->format('H:i');
            }) 
            ->editColumn('status', function ($absensi) {
                $status = $absensi->status;
                return view('hrd.absensi.partial.status' ,compact('status')) ;
            })        
            ->addColumn('action', function ($row) {
                $id = $row->id;
                return view('hrd.absensi.partial.action', compact('id'));
            })
            ->make(true);
    }

    public function export (Request $request)
    {
        $data = $request->all();        
        return Excel::download(new AbsensiExport($data), 'absensi.xlsx');
    }

    public function create ()
    {        
        $karyawan = Karyawan::get();        
        $title = 'Absensi';
        return view('hrd.absensi.create',compact('karyawan','title'));
    }

    public function store (Request $request)
    {
        $absensi = Absensi::create([
            'karyawan_id' => $request->karyawan_id,
            'clock_in' => Carbon::parse($request->clock_in)->format('H:i:s'),
            'clock_out' => Carbon::parse($request->clock_out)->format('H:i:s'),
            'work_time' => Carbon::parse($request->work_time)->format('H:i:s'),
            'tanggal' => Carbon::parse($request->tanggak)->format('Y-m-d'),
            'status' => $request->status,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('absensi.index')->with('status','Data Berhasil Ditambahkan');
    }


    public function edit ($id)
    {        
        $absensi = Absensi::where('id',$id)->with('karyawan')->first();        
        $title = 'Absensi';

        // dd($absensi);

        return view('hrd.absensi.edit',compact('absensi','title'));
    }

    public function update (Request $request , $id)
    {
       Absensi::where('id',$id)->update([            
            'clock_in' => Carbon::parse($request->clock_in)->format('H:i') ,
            'clock_out' => Carbon::parse($request->clock_out)->format('H:i') ,
            'work_time' => Carbon::parse($request->clock_out)->format('H:i') ,
            'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d') ,
            'status' => $request->status,
            'keterangan' => $request->keterangan
       ]);

       return redirect()->route('absensi.index');
    }

    public function delete (Request $request)
    {
        Absensi::where('id',$request->id)->delete();

        return response()->json('Data Berhasil Dihapus');
    }
    
    
}
