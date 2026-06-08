<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanStokExp implements FromView
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function view(): View
    {
        $tgl1 = Carbon::parse($this->data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($this->data['tgl2'])->format('Y-m-d');                
        $penjualan = DB::table('stok_exps as se')    
                    ->join('products  as p','se.product_id','=','p.id')    
                    ->where('se.deleted_at',null)
                    ->where('p.status_exp', 1)
                    ->where('qty','<>',0);
                    
        
        if ($this->data['tgl1']) {            
            if (!$this->data['tgl2']) {
                $tanggalFilter=$penjualan->where('se.tanggal','>=',$tgl1);
                                
            }else{
                $tanggalFilter=$penjualan->where('se.tanggal','>=',$tgl1)
                                ->where('se.tanggal','<=',$tgl2);
            }
        }elseif($this->data['tgl2']){
            if (!$this->data['tgl1']) {
                $tanggalFilter=$penjualan->where('se.tanggal','<=',$tgl2);
            }else{
                $tanggalFilter=$penjualan->where('se.tanggal','>=',$tgl1)
                                ->where('se.tanggal','<=',$tgl2);
            }
        }else{
            $tanggalFilter = $penjualan;
        }

        $result = $tanggalFilter->orderBy('se.tanggal','asc')
                                ->select('p.nama as nama_produk','p.kode_item','se.lot','se.tanggal','se.qty','p.status_exp')->get();
        
        return view('laporan.stok.export.stokexp',[
            'stok' => $result
        ]);
    }
}
