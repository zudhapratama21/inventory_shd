<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanPenjualanDetailExport implements FromView
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $totHargaJual = 0;
        $totDiskon = 0;
        $totCN = 0;
        $totTotal=0;
        $totHargaBersih = 0;
        $totSubtotal = 0;

                   
        $tgl1 = Carbon::parse($this->data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($this->data['tgl2'])->format('Y-m-d');                
        $penjualan = DB::table('faktur_penjualans as fp')
                    ->join('pengiriman_barangs as pb','fp.pengiriman_barang_id','=','pb.id')
                    ->join('faktur_penjualan_details as fpb','fpb.faktur_penjualan_id','=','fp.id')
                    ->join('users as u','fp.created_by','=','u.id')
                    ->join('pesanan_penjualans as pp','fp.pesanan_penjualan_id','=','pp.id')
                    ->join('sales as s','pp.sales_id','=','s.id')
                    ->join('kategoripesanans as kp','pp.kategoripesanan_id','=','kp.id')
                    ->join('komoditas as km','pp.komoditas_id','=','km.id')
                    ->join('customers as cs','fp.customer_id','=','cs.id')
                    ->join('customer_categories as cc','cs.kategori_id','=','cc.id')
                    ->join('products as p','p.id','=','fpb.product_id')
                    ->join('merks as m','p.merk_id','=','m.id')
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
                                        ->where('fp.tanggal_top','<=',$tgl2);
                    }
                }else{
                    $tanggalFilter = $penjualan;
                }
                                    

                if ($this->data['customer'] == 'all') {            
                         $customerfilter = $tanggalFilter;                                  
                }else{
                         $customerfilter = $penjualan->where('fp.customer_id','=',$this->data['customer']);     
                }

                if ($this->data['sales'] == 'all') {
                          $salesfilter = $customerfilter;                                          
                }else{
                          $salesfilter = $customerfilter->where('pp.sales_id','=',$this->data['sales']);                
                }
                
                if ($this->data['kategori_pesanan'] !== 'all') {
                        $salesfilter->where('pp.kategoripesanan_id',$this->data['kategori_pesanan']);               
                }
        
                if ($this->data['komoditas'] !== 'all') {
                    $salesfilter->where('pp.komoditas_id',$this->data['komoditas']);
                }

                if ($this->data['kategori_customer'] !== 'all') {
                    $salesfilter->where('cs.kategori_id',$this->data['kategori_customer']);
                }

                if ($this->data['produk'] == 'all') {
                    $produkfilter = $salesfilter;            
                } else {
                    $produkfilter = $salesfilter->where('p.id','=',$this->data['produk']);
                }


                if ($this->data['merk'] == 'all') {
                       $merkfilter  = $produkfilter;
                } else {
                       $merkfilter  = $produkfilter->where('m.id','=',$this->data['merk']);
                }

                $filter = $merkfilter
                ->orderBy('fp.tanggal','desc')
                ->orderBy('fpb.id','asc')
                ->orderBy('fp.kode','desc')                
                ->select('fp.*','pb.id as id_pengiriman','p.id as id_product','fpb.qty as qty_det','fpb.satuan as satuan_det','fpb.hargajual as hargajual_det'
                ,'fpb.diskon_persen as dikson_persen_det','fpb.diskon_rp as diskon_rp_det','fpb.subtotal as subtotal_det'
                ,'fpb.total as total_det','fpb.total_diskon as total_diskon_det','fpb.ongkir as ongkir_det','fpb.keterangan as keterangan_det','fpb.cn_persen as cn_persen',
                'fpb.cn_total as cn_total'
                ,'pb.kode as kode_SJ','pp.kode as kode_SP'
                ,'s.nama as nama_sales','u.name as nama_pembuat'
                ,'cs.nama as nama_customer','p.nama as nama_produk'
                ,'m.nama as nama_merk','p.kode as kode_produk'
                ,'km.nama as nama_komoditas','kp.nama as nama_kategori_pesanan','cc.nama as nama_kategori_customer','p.status_exp as status_exp'
                )->get();     


                
                foreach ($filter as $key ) {
                    $totHargaJual += $key->hargajual_det;
                    $totCN += $key->cn_total;
                    $totDiskon+=$key->total_diskon_det;
                    $totTotal += $key->total_det;
                    $totSubtotal += $key->subtotal_det;
                }                           

                $totHargaBersih = $totTotal  -  $totCN;
                                            
        // dd($filter);
            return view('laporan.penjualan.export.exportpenjualandetail',[
                'penjualan' => $filter,      
                'totHargaJual' => $totHargaJual,
                'totCN' => $totCN,
                'totDiskon' => $totDiskon,
                'totTotal' => $totTotal,
                'totHargaBersih' => $totHargaBersih,
                'totSubtotal' => $totSubtotal
            ]);            
        
    }
}
