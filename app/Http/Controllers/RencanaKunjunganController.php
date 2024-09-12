<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\RencanaKunjungan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RencanaKunjunganController extends Controller
{
    public function index ()
    {

      $title = 'Rencana Kunjungan';      
      return view('sales.rencanakunjungan.index',compact('title'));

    }

    public function datatable ()
    {
        $kunjungan = RencanaKunjungan::with(['user','outlet'])->orderBy('id','desc')->where('user_id',auth()->user()->id);
        return DataTables::of($kunjungan)
                ->addIndexColumn()
                ->editColumn('tanggal', function (RencanaKunjungan $kj) {
                    return $kj->tanggal ? with(new Carbon($kj->tanggal))->format('d F Y') : '';
                })
                ->editColumn('tanggal', function (RencanaKunjungan $kj) {
                    return $kj->tanggal ? with(new Carbon($kj->tanggal))->format('d F Y') : '';
                })
                ->editColumn('user', function (RencanaKunjungan $kj) {
                    return $kj->user->name;
                })
                ->editColumn('outlet', function (RencanaKunjungan $kj) {
                    return $kj->outlet->nama;
                })
                ->addColumn('aktivitas', function (RencanaKunjungan $kj) {
                    return view('sales.rencanakunjungan.partial.text',[
                        'text' => $kj->aktivitas
                    ]);
                })
                ->editColumn('created_at',function (RencanaKunjungan $kj){
                    return $kj->jam_buat ? with(new Carbon($kj->jam_buat))->format('H:i') : with(new Carbon($kj->created_at))->format('H:i');
                })
                ->addColumn('action', function ($row) {    
                    $id = $row->id;        
                    $sales_id = $row->user_id;                                              
                    return view('sales.rencanakunjungan.partial._form-action',[
                        'id' => $id,
                        'sales_id' => $sales_id
                    ]);
                })
                ->make(true);
    }

    public function create ()
    {
        $title = 'Rencana Kunjungan';
        $outlet = Outlet::get();
        return view('sales.rencanakunjungan.create',compact('title','outlet'));
     
    }

    public function store (Request $request)
    {
       RencanaKunjungan::create([
            'outlet_id' => $request->outlet_id,
            'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
            'aktivitas' => $request->aktivitas,
            'jam_buat' =>  Carbon::parse(now())->format('H:i'),
            'user_id' => auth()->user()->id
       ]);

       return redirect()->route('rencanakunjungan.index')->with('success','Data Berhasil Ditambahkan');
    }

    public function edit ($id)
    {
        $title = 'Rencana Kunjungan';
        $rencanakunjungan = RencanaKunjungan::where('id',$id)->first();
        $outlet = Outlet::get();
        return view('sales.rencanakunjungan.edit',compact('title','outlet','rencanakunjungan'));        
    }

    public function update (Request $request , $id)
    {
         $rencanakunjungan = RencanaKunjungan::where('id',$id)->update([
            'outlet_id' => $request->outlet_id,
            'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
            'aktivitas' => $request->aktivitas,                        
         ]);

         return redirect()->route('rencanakunjungan.index')->with('success','Data Berhasil diUbah');
    }

    public function delete(Request $request)
    {
        RencanaKunjungan::where('id',$request->id)->delete();

        return response()->json('Data Berhasil Dihapus');
    }

}
