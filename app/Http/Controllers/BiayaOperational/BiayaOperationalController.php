<?php

namespace App\Http\Controllers\BiayaOperational;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BiayaOperational;
use App\Models\JenisBiaya;
use App\Models\Keuangan\SubBiaya;
use App\Models\Sales;
use App\Traits\CodeTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $biayaoperational = BiayaOperational::with(['jenisbiaya','bank','sales'])->orderByDesc('id');

        if (request()->ajax()) {
            return DataTables::of($biayaoperational)
                ->addIndexColumn()
                ->editColumn('tanggal', function (BiayaOperational $pb) {                    
                    return $pb->tanggal ? with(new Carbon($pb->tanggal))->format('d/m/Y') : '';
                })
                ->addColumn('jenis_biaya', function (BiayaOperational $pb) {
                    return $pb->jenisbiaya->nama;
                })
                ->editColumn('nominal', function (BiayaOperational $pb) {
                    return  number_format($pb->nominal , 0, ',', '.');
                })
                ->addColumn('sales_id', function (BiayaOperational $pb) {
                    return $pb->sales->nama;
                })
                ->addColumn('sumberdana', function (BiayaOperational $pb) {
                    return $pb->bank->nama;
                })
                ->addColumn('keterangan', function (BiayaOperational $pb) {
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
        $sales = Sales::get();
        $count = 0;
        return view('biayaoperational.create',compact('jenisbiaya','bank','title','count','sales'));
    }

  
    public function store(Request $request)
    {        
        $jenisbiaya = SubBiaya::where('jenisbiaya_id', $request->jenis_biaya_id)->first();
            $data = $request->all();            
            BiayaOperational::create([
                'tanggal' => $request->tanggal,
                'kode' => $request->kode,
                'jenis_biaya_id' => $jenisbiaya->jenisbiaya_id,
                'subjenis_biaya_id' => $request->jenis_biaya_id,
                'nominal' => $request->nominal,      
                'sales_id' => $data['sales_id'],
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
        $biayaoperational = BiayaOperational::with(['jenisbiaya','bank','sales'])->findOrFail($id);
        $jenisbiaya = JenisBiaya::get();
        $bank = Bank::get();
        $sales = Sales::get();

        return view('biayaoperational.edit',compact(
            'title',
            'biayaoperational',
            'jenisbiaya',
            'bank',
            'sales'
        ));
    }

   
    public function update(Request $request, $id)
    {

        DB::beginTransaction();
        
        try {          
            $biaya = BiayaOperational::findOrFail($id);
                        
            $biaya->update([
                'tanggal' => $request->tanggal,
                'jenis_biaya_id' => $request->jenis_biaya_id,
                'nominal' => $request->nominal,        
                'request' => $request->request,
                'bank_id' => $request->bank_id,
                'verifikasi' => 'Diterima'
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
        $data = BiayaOperational::where('id', '=', $request->id)->first();
        $id = $request->id;        

        return view('biayaoperational._confirmDelete', compact('id'));
    }
    
   
    public function destroy(Request $request)
    {
        $biaya = BiayaOperational::findOrFail($request->id);
        $biaya->delete();

        return redirect()->route('biayaoperational.index')->with('status','Biaya Operational berhasil dhapus');
    }
}
