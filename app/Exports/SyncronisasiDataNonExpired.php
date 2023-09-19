<?php

namespace App\Exports;

use App\Models\PengirimanBarangDetail;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SyncronisasiDataNonExpired implements FromView
{
    
    public function view(): View
    {
     $stoknonexp = Product::with('harganonexpired.supplier')->where('stok','>',0)->where('status_exp',0)->get();
                        
    //  dd($stoknonexp);
     return view('laporan.stok.export.stoknonexp',compact('stoknonexp'));   

    }
}
