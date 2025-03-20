<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\StokExp;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class SyncExport implements FromView
{
   

    public function view(): View
    {  
        $stokexp = StokExp::select('product_id', DB::raw('SUM(qty) as qty'))
            ->groupBy('product_id')
            ->get();

        $product = Product::where('status_exp',1)->where('stok',0)->get();
        
        foreach ($product as $key => $item) {
            
            $stok = $stokexp->where('product_id',$item->id)->update([
                'qty' => $item->stok
            ]);                       
        }
       
        return view('laporan.stok.export.sync',compact('data'));

    }
}
