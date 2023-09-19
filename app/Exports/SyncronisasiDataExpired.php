<?php

namespace App\Exports;

use App\Models\PengirimanBarangDetail;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SyncronisasiDataExpired implements FromView
{
    
    public function view(): View
    {

        $stokexp = Product::with('stokExp.supplier')->where('stok','>',0)->where('status_exp',1)->get();     
        return view('laporan.stok.export.stokexp',compact('stokexp'));   

    }
}
