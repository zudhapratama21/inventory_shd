<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\BiayaLain;
use App\Models\JenisBiaya;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BiayaLainController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:biayalain-list');
        $this->middleware('permission:biayalain-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:biayalain-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:biayalain-delete', ['only' => ['destroy']]);
    }

    public function index($id)
    {
        $jenisbiaya = JenisBiaya::get();
        $title = 'Biaya Lain-Lain';
        $fakturId = $id;
        $biayalain = BiayaLain::with('jenisbiaya')->where('fakturpenjualan_id',$id)->get();
        // dd($biayalain);

        return view('penjualan.fakturpenjualan.biayaLain.index',compact('title','fakturId','jenisbiaya','biayalain'));
    }

    public function datatables(Request $request)
    {
        // $biayalain = BiayaLain::with('jenisbiaya');
        // return DataTables::of($biayalain)
        //     ->addIndexColumn()             
        //     ->addColumn('jenis_biaya', function (BiayaLain $pb) {
        //         return $pb->jenisbiaya->nama;
        //     })               
        //     ->addColumn('nominal', function (BiayaLain $pb) {
        //         return  number_format($pb->nominal , 0, ',', '.');
        //     })                            
        //     ->addColumn('keterangan', function (BiayaLain $pb) {
        //         return $pb->keterangan;
        //     })
        //     ->addColumn('action', function ($row) {                                                    
        //         $id = $row->id;                   
        //         return view('penjualan.fakturpenjualan.biayaLain.partial._formAction', compact('id'));
        //     })
        //     ->make(true);

        return response()->json('sukses');
        
    }

    public function store(Request $request)
    {
        $biayalain = BiayaLain::create([
            'jenisbiaya_id' => $request->jenisbiaya_id ,
            'fakturpenjualan_id' => $request->fakturpenjualan_id,
            'pengurangan_cn' => $request->pengurangan_cn,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]); 
        
        return response()->json("data berhasil di proses"); 

        // return back();
    }

    public function edit(Request $request)
    {
       $biayalain = BiayaLain::where('id',$request->id)->with('jenisbiaya')->first();
       $jenisbiaya = JenisBiaya::get();

       return view('penjualan.fakturpenjualan.biayaLain.modal._form-control-edit',compact('biayalain','jenisbiaya'));
    }

    public function update(Request $request)
    {        
        $biayalain = BiayaLain::where('id',$request->id)->update([
               'jenisbiaya_id' => $request->jenisbiaya_id ,
                'fakturpenjualan_id' => $request->fakturpenjualan_id,
                'pengurangan_cn' => $request->pengurangan_cn,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,            
        ]); 
        
        return response()->json('tes'); 
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        BiayaLain::where('id',$id)->delete();
        return response()->json('anda sukses menghapus');
    }
}
