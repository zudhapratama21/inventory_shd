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
        
        foreach ($stokexp as $key => $item) {
            $product = Product::where('status_exp',1)->where('id',$item->product_id)->first();
            if ($product) {
                if ((int)$item->qty !== (int)$product->stok) {
                    $data[] =[
                        'product_id' => $item->product_id,
                        'kode' => $product->kode,
                        'qty_exp' => $item->qty,
                        'stok' => $product->stok,
                        'nama_product' => $product->nama,
                        'satuan' => $product->satuan
                    ];
                }
              
            }
           
        }
       
        return view('laporan.stok.export.sync',compact('data'));

    }
}
