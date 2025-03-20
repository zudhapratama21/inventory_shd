<?php

namespace App\Http\Controllers\Laporan;

use App\Exports\LaporanAdjustmentStok;
use App\Exports\SyncExport;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanAdjustmentStokController extends Controller
{
    public function filter()
    {
        $title = 'Filter Adjustment Stok';

        return view('laporan.stok.adjustmentstok.filter',compact('title'));
    }

    public function result(Request $request)
    {
        $form = $request->all();
        $title = 'Adjustment Stok';
        

        $adjustment = DB::table('adjustment_stoks as as')
                    ->join('products as p','as.product_id','=','p.id');
                    
        if ($request->adjustment == 'all') {
            $filter = $adjustment;
        }else{
            $filter = $adjustment->where('as.jenis',$request->adjustment);
        }

        $data = $filter->select('as.*','p.*','as.kode as kode_adjustment','as.qty as qty_adjustment','as.created_at as tanggal_adjustment')->get();
        

        if (count($data) < 0) {
            return redirect()->back()->with('status_danger', 'Data tidak ditemukan');
        }        

        return view('laporan.stok.adjustmentstok.result',compact('title','data','form'));
    }

    public function export(Request $request)
    {
        $data = $request->all();        
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LaporanAdjustmentStok($data), 'laporanadjustmentstok-'.$now.'.xlsx');
    }

    public function sync()
    {    
        return Excel::download(new SyncExport(), 'Syncronisasi.xlsx');       
    }
}
