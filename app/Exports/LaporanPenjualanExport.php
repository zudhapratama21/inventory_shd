<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanPenjualanExport implements FromView
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
        $penjualan = DB::table('faktur_penjualans as fp')                    
                    ->join('users as u','fp.created_by','=','u.id')                    
                    ->join('sales as s','fp.sales_id','=','s.id')
                    ->join('customers as cs','fp.customer_id','=','cs.id')            
                    ->where('fp.deleted_at',null);
                    
        
        if ($this->data['tgl1']) {            
            if (!$this->data['tgl2']) {
                $tanggalFilter=$penjualan->where('fp.tanggal','>=',$tgl1);
                                
            }else{
                $tanggalFilter=$penjualan->where('fp.tanggal','>=',$tgl1)
                                ->where('fp.tanggal','<=',$tgl2);
            }
        }elseif($this->data['tgl2']){
            if (!$this->data['tgl1']) {
                $tanggalFilter=$penjualan->where('fp.tanggal','<=',$tgl2);
            }else{
                $tanggalFilter=$penjualan->where('fp.tanggal','>=',$tgl1)
                                ->where('fp.tanggal','<=',$tgl2);
            }
        }else{
            $tanggalFilter = $penjualan;
        }


        if ($this->data['customer'] == 'all') {            
            $customerfilter = $tanggalFilter;                           
        }else{
            $customerfilter = $tanggalFilter->where('fp.customer_id','=',$this->data['customer']);
        }

        if ($this->data['sales'] == 'all') {
            $salesfilter = $customerfilter;                                          
        }else{
            $salesfilter = $customerfilter->where('fp.sales_id','=',$this->data['sales']);                
        }

        $dataFilter = $salesfilter->orderBy('fp.tanggal','desc')
        ->select('fp.*','s.nama as nama_sales','u.name as nama_pembuat','cs.nama as nama_customer')->get();
        
        return view('laporan.penjualan.export.exportpenjualan',[
            'penjualan' => $dataFilter
        ]);
    }
}
