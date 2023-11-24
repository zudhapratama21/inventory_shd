<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanPembayaranPiutangDetailExport implements FromView
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    { 
        $title = 'Laporan Pembayaran Piutang Detail';        
        
        $tgl1 = Carbon::parse($this->data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($this->data['tgl2'])->format('Y-m-d');                

        $tgl1 = Carbon::parse($this->data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($this->data['tgl2'])->format('Y-m-d');                

        $pembayaran = DB::table('piutangs as p')
                            ->join('pesanan_penjualans as pp','p.pesanan_penjualan_id','=','pp.id')                    
                            ->join('pengiriman_barangs as pb','p.pengiriman_barang_id','=','pb.id')                   
                            ->join('pembayaran_piutangs as pps','pps.piutang_id','=','p.id')                   
                            ->join('banks as b','pps.bank_id','=','b.id');

        if ($this->data['tgl1']) {            
            if (!$this->data['tgl2']) {
                $tanggalFilter=$pembayaran->where('fp.tanggal','>=',$tgl1);
                                
            }else{
                $tanggalFilter=$pembayaran->where('fp.tanggal','>=',$tgl1)
                                ->where('fp.tanggal','<=',$tgl2);
            }
        }elseif($this->data['tgl2']){                        
            if (!$this->data['tgl1']) {
                $tanggalFilter=$pembayaran->where('fp.tanggal','<=',$tgl2);
            }else{
                $tanggalFilter=$pembayaran->where('fp.tanggal','>=',$tgl1)
                                ->where('fp.tanggal_top','<=',$tgl2);
            }

        }else{
            $tanggalFilter = $pembayaran;
        }
                    
        
        if ($this->data['customer'] == 'all') {  

            $customerfilter = $tanggalFilter->join('customers as c','p.customer_id','=','c.id');

            if ($this->data['no_faktur'] <> null) {                
                $filter =  $customerfilter->join('faktur_penjualans as fp','p.faktur_penjualan_id','=','fp.id')
                                            ->where('fb.kode','=',$this->data['no_faktur']);

                    if ($this->data['sales'] == 'all') {
                        $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id');                
                                        
                    }else{
                        $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id')
                                        ->where('pp.sales_id','=',$this->data['sales']);                
                    }
                                    
            }else{                
                $filter =  $customerfilter->join('faktur_penjualans as fp','p.faktur_penjualan_id','=','fp.id');

                if ($this->data['sales'] == 'all') {
                    $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id');                
                                    
                }else{
                    $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id')
                                    ->where('pp.sales_id','=',$this->data['sales']);                
                }
                                          
            }
        }else{
            $customerfilter = $pembayaran->join('customers as c','p.customer_id','=','c.id')
                                         ->where('c.id','=',$this->data['customer']);

            if ($this->data['no_faktur'] <> null) {
                $filter =  $customerfilter->join('faktur_penjualans as fp','p.faktur_penjualan_id','=','fp.id')
                                        ->where('fb.kode','=',$this->data['no_faktur']); 

                    if ($this->data['sales'] == 'all') {
                        $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id');                
                                        
                    }else{
                        $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id')
                                        ->where('pp.sales_id','=',$this->data['sales']);                
                    }
            }else{
                $filter =  $customerfilter->join('faktur_penjualans as fp','p.faktur_penjualan_id','=','fp.id');

                        if ($this->data['sales'] == 'all') {
                            $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id');                
                                            
                        }else{
                            $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id')
                                            ->where('pp.sales_id','=',$this->data['sales']);                
                        }                                                
            }
        }

        $datafilter = $salesfilter->select('c.nama as nama_customer'
                                            ,'pp.kode as kode_pp','pb.kode as kode_pb','fp.kode as kode_fp'
                                            ,'p.*','s.nama as nama_sales','pps.nominal as nominal_pembayaran'
                                            ,'b.nama as nama_bank','pps.keterangan','pp.tanggal as tanggal_pembayaran',
                                            'pp.keterangan as keterangan_pembayaran'
                                            )
                                ->get();

        if (count($datafilter) <= 0) {
                return redirect()->back()->with('status_danger', 'Data tidak ditemukan atau belum melakukan pembayaran');
        }
        
        return view('laporan.pembayaran.export.exportPembayaranPiutangDetail',[
            'title' => $title,
            'hutang' => $datafilter,  
        ]);
    }


}
