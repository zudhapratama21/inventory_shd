<?php

namespace App\Exports;

use App\Models\PengirimanBarangDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SyncronisasiDataExport implements FromView
{
    
    public function view(): View
    {

    //  $pengirimanbarang = DB::table('pengiriman_barang_details as pbd')
    //                     ->join('pengiriman_barangs as pb','pbd.pengiriman_barang_id','=','pb.id')
    //                     ->join('stok_exp_details as sed','pbd.id','=','sed.id_sj_detail')
    //                     ->join('harga_non_expired_detail as hned','pbd.id','=','hned.id_sj_detail')
    //                     ->join('products as p','pbd.product_id','=','p.id')
    //                     ->join('customers as c','pb.customer_id','=','c.id')
    //                     ->join('faktur_penjualans as fp','fp.pengiriman_barang_id','=','pb.id')
    //                     ->where('pbd.deleted_at','=',null)
    //                     ->select('pb.kode as kode_pengiriman','pb.id as id_pengiriman','pbd.*','sed.harga_beli as harga_beli_exp',
    //                              'sed.diskon_persen_beli as diskon_persen_beli_exp','sed.diskon_rupiah_beli as diskon_rupiah_beli_exp','sed.id as id_exp_detail',
    //                              'c.nama as customer_supplier','c.id as id_customer','p.nama as nama_product','p.id as id_product','fp.no_kpa as no_kpa')
    //                     ->get();

    $pengirimanbarang = PengirimanBarangDetail::with('PengirimanBarangs.SO.customers','stokexpdetail','harganonexpireddetail','faktupenjualandetail','products')->get();
    
                        
    //  dd($pengirimanbarang[0]);
     return view();   

    }
}
