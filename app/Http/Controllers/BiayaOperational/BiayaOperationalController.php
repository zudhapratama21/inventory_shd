<?php

namespace App\Http\Controllers\BiayaOperational;

use App\Http\Controllers\Controller;
use App\Imports\BiayaOperationalImport;
use App\Models\Bank;
use App\Models\BiayaOperational;
use App\Models\HRD\Karyawan;
use App\Models\JenisBiaya;
use App\Models\Keuangan\SubBiaya;
use App\Models\Sales;
use App\Traits\CodeTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class BiayaOperationalController extends Controller
{
    use CodeTrait;
    function __construct()
    {
        $this->middleware('permission:biayaoperational-list');
        $this->middleware('permission:biayaoperational-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:biayaoperational-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:biayaoperational-delete', ['only' => ['destroy']]);
    }

   
    public function index()
    {
        $title = "Biaya Operational";

        $biayaoperational = BiayaOperational::with(['jenisbiaya','subbiaya','bank','karyawan'])->orderByDesc('id');        

        if (request()->ajax()) {
            return DataTables::of($biayaoperational)                
                ->editColumn('tanggal', function (BiayaOperational $pb) {                    
                    return $pb->tanggal ? with(new Carbon($pb->tanggal))->format('d/m/Y') : '';
                })
                ->editColumn('jenis_biaya', function (BiayaOperational $pb) {
                    return $pb->jenisbiaya->nama;
                })
                ->editColumn('sub_biaya', function (BiayaOperational $pb) {
                    return $pb->subbiaya->nama;
                })
                ->editColumn('nominal', function (BiayaOperational $pb) {
                    return  number_format($pb->nominal , 0, ',', '.');
                })
                ->editColumn('karyawan_id', function (BiayaOperational $pb) {
                    return $pb->karyawan->nama;
                })
                ->editColumn('sumberdana', function (BiayaOperational $pb) {
                    return $pb->bank->nama;
                })
                ->editColumn('keterangan', function (BiayaOperational $pb) {
                    return $pb->keterangan;
                })
                ->addColumn('action', function ($row) {                    
                    $editUrl = route('biayaoperational.edit', ['biayaoperational' => $row->id]);                    
                    $id = $row->id;
                    $status = $row->status_pb_id;
                    return view('biayaoperational._formAction', compact('editUrl','id'));
                })
                ->make(true);
        }
        
        return view('biayaoperational.index',compact('title'));
    }

   
    public function create()
    {
        $title = "Tambah Biaya Operational";
        $jenisbiaya = JenisBiaya::with('subjenisbiaya')->get();
        $bank = Bank::get();
        $karyawan = Karyawan::get();
        $count = 0;
        return view('biayaoperational.create',compact('jenisbiaya','bank','title','count','karyawan'));
    }

  
    public function store(Request $request)
    {                 
            $jenisbiaya = SubBiaya::where('id', $request->jenis_biaya_id)->first();                                   
            BiayaOperational::create([
                'tanggal' => $request->tanggal,
                'kode' => $request->kode,
                'jenis_biaya_id' => $jenisbiaya->jenisbiaya_id,
                'subjenis_biaya_id' => $request->jenis_biaya_id,
                'nominal' => $request->nominal,      
                'karyawan_id' => $request->karyawan_id,
                'bank_id' => $request->bank_id,
                'verified' => 'Diterima',
                'verified_by' => auth()->user()->id,
                'keterangan' => $request->keterangan
            ]);
                       
            return redirect()->route('biayaoperational.index')->with('status','Biaya Operational berhasil ditambahkan');                
    }

   
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        $title = 'Ubah Biaya Operational';
        $biayaoperational = BiayaOperational::with(['jenisbiaya','subbiaya','bank','karyawan'])->findOrFail($id);
        $jenisbiaya = JenisBiaya::with('subjenisbiaya')->get();
        $bank = Bank::get();
        $karyawan = Karyawan::get();

        return view('biayaoperational.edit',compact(
            'title',
            'biayaoperational',
            'jenisbiaya',
            'bank',
            'karyawan'
        ));
    }

   
    public function update(Request $request, $id)
    {
        DB::beginTransaction();        
        try {          
            $biaya = BiayaOperational::findOrFail($id);  
            $jenisbiaya = SubBiaya::where('id', $request->jenis_biaya_id)->first();                                  
            $biaya->update([
                'tanggal' => $request->tanggal,
                'kode' => $request->kode,
                'jenis_biaya_id' => $jenisbiaya->jenisbiaya_id,
                'subjenis_biaya_id' => $request->jenis_biaya_id,
                'nominal' => $request->nominal,      
                'karyawan_id' => $request->karyawan_id,
                'bank_id' => $request->bank_id,
                'verified' => 'Diterima',
                'verified_by' => auth()->user()->id,
                'keterangan' => $request->keterangan
            ]);

            DB::commit();
            return redirect()->route('biayaoperational.index')->with('status','Biaya Operational berhasil ditambahkan');

        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->route('biayaoperational.index')->with('gagal',$th->getMessage());            
        }
    }

    public function delete(Request $request)
    {
        $biaya = BiayaOperational::findOrFail($request->id);
        $biaya->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function import (Request $request)
    {
        Excel::import(new BiayaOperationalImport, $request->file('file_operational'));   

        return back();
    }
    
}
