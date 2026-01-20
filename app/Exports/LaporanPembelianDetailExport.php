<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanPembelianDetailExport implements FromView
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
        $penjualan = DB::table('faktur_pembelians as fp')        
                    ->join('penerimaan_barangs as pb','fp.penerimaan_barang_id','=','pb.id')
                    ->join('pesanan_pembelians as pp','fp.pesanan_pembelian_id','=','pp.id')
                    ->join('faktur_pembelian_details as fpb','fpb.faktur_pembelian_id','=','fp.id')
                    ->join('users as u','fp.created_by','=','u.id')
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
                
                    
            
                    if ($this->data['supplier'] == 'all') {            
            
                        $customerfilter = $tanggalFilter->join('suppliers as s','fp.supplier_id','=','s.id');                           
                    }else{
                        $customerfilter = $tanggalFilter->join('suppliers as s','fp.supplier_id','=','s.id')
                                          ->where('fp.supplier_id','=',$this->data['supplier']);                 
            
                    }
            
                    if ($this->data['produk'] == 'all') {
                        $produkfilter = $customerfilter ->join('products as p','p.id','=','fpb.product_id');            
                    } else {
                        $produkfilter = $customerfilter ->join('products as p','p.id','=','fpb.product_id')
                                                     ->where('p.id','=',$this->data['produk']);
                    }
                    
            
                    if ($this->data['merk'] == 'all') {
                        $merkfilter  = $produkfilter->join('merks as m','p.merk_id','=','m.id');
                    } else {
                        $merkfilter  = $produkfilter->join('merks as m','p.merk_id','=','m.id')
                                        ->where('m.id','=',$this->data['merk']);
                    }

        $filter = $merkfilter->orderBy('fp.tanggal','desc')->select('fp.*','pb.kode as kode_SJ'
                                        ,'pp.kode as kode_SP'
                                        ,'pp.no_so as no_pesanan'
                                        ,'s.nama as nama_supplier'
                                        ,'u.name as nama_pembuat',
                                        'fpb.qty as qty_produk','fpb.satuan as satuan_produk'
                                        ,'fpb.hargabeli as hargabeli_produk','fpb.diskon_persen as diskon_persen_produk',
                                        'fpb.diskon_rp as diskon_rp_produk','fpb.subtotal as subtotal_produk',
                                        'fpb.total_diskon as total_diskon_produk',
                                        'fpb.total as total_produk','fpb.ongkir as ongkir_produk',
                                        'fpb.keterangan as keterangan_produk','p.nama as nama_produk', 'p.kode as kode_produk',
                                        'm.nama as nama_merk'
                                        )->get();            
                            
        return view('laporan.pembelian.export.exportPembeliandetail',[
            'pembelian' => $filter,                        
        ]);            


    }
}
