<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\PlanMarketing;
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

    public function datatable (Request $request)
    {
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');
        $rencanakunjungan = RencanaKunjungan::with(['user','outlet'])
                            ->where('tanggal','>=',$start)
                            ->where('tanggal','<=',$end)
                            ->orderBy('id','desc')->where('user_id',auth()->user()->id)
                            ->get()
                            ->map(fn($item) => [
                                'id' => $item->id,
                                'start' => $item->tanggal,
                                'title' => $item->outlet->nama,
                                'description' => $item->aktivitas,
                                'className' => 'fc-event-primary fc-event-solid-success'                        
                            ]);
        return response()->json($rencanakunjungan);
       
    }

    public function create (Request $request)
    {        
        $planmarketing = PlanMarketing::with('outlet')->where('tanggal',$request->start_date)->where('user_id',auth()->user()->id)->get();
        $outlet = Outlet::get();

        $terlambat = 0;
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        if ($start_date < now()->format('Y-m-d')) {
            $terlambat = 1;
        }

        $terlambat=0;
        

        return view('sales.rencanakunjungan.partial.modal',compact('outlet','request','planmarketing','terlambat'));
     
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

       return response()->json('Data Berhasil Ditambahkan');
    }

    public function edit ($id)
    {        
        $rencanakunjungan = RencanaKunjungan::where('id',$id)->first();
        $planmarketing = PlanMarketing::where('tanggal',$rencanakunjungan->tanggal)->where('user_id',auth()->user()->id)->get();
        $outlet = Outlet::get();

        $terlambat = 0;
        $start_date = Carbon::parse($rencanakunjungan->tanggal)->format('Y-m-d');
        if ($start_date < now()->format('Y-m-d')) {
            $terlambat = 1;
        }

        $terlambat=0;

        return view('sales.rencanakunjungan.partial.modaledit',compact('planmarketing','outlet','rencanakunjungan','terlambat'));        
    }

    public function update (Request $request)
    {
         $rencanakunjungan = RencanaKunjungan::where('id',$request->data_id)->update([
            'outlet_id' => $request->outlet_id,            
            'aktivitas' => $request->aktivitas,                        
         ]);

         return response()->json('Data Berhasil Ditambahkan');
    }

    public function delete(Request $request)
    {
        RencanaKunjungan::where('id',$request->data_id)->delete();

        return response()->json('Data Berhasil Dihapus');
    }

}
