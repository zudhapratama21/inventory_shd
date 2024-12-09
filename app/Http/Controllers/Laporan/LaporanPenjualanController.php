<?php

namespace App\Http\Controllers\Laporan;

use App\Exports\LaporanPenjualanCNExport;
use App\Exports\LaporanPenjualanDetailExport;
use App\Exports\LaporanPenjualanExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Customer_category;
use App\Models\FakturPenjualan;
use App\Models\Kategoripesanan;
use App\Models\Komoditas;
use App\Models\Merk;
use App\Models\Product;
use App\Models\Sales;
use App\Traits\CodeTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPenjualanController extends Controller
{
    use CodeTrait;

    function __construct()
    {
        $this->middleware('permission:laporanpenjualan-list');
        $this->middleware('permission:laporanpenjualan-list', ['only' => ['filterPenjualan', 'stokprodukresult', 'expstokproduk']]);
        $this->middleware('permission:laporanstokkartu-list', ['only' => ['edit', 'update']]);
        $this->middleware('permission:laporanstokexp-list', ['only' => ['destroy']]);
    }


    public function index()
    {        
        $title = "Laporan Penjualan";
        return view('laporan.penjualan.index', compact('title'));
        
    }

    public function filterPenjualan()
    {
            
        $title = "Laporan Penjualan";
        $customer = Customer::select('id','nama')->get();
        $sales = Sales::select('id','nama')->get();                  
        

        return view('laporan.penjualan.filterPenjualan', [
            'customer' => $customer ,
            'sales' => $sales,
            'title' => $title,
                    
        ]);

    }

    public function filterPenjualanDetail()
    {
        
        $title = "Laporan Penjualan";
        $customer = Customer::select('id','nama')->get();
        $sales = Sales::select('id','nama')->get();
        $merk = Merk::select('id','nama')->get();      
        $produk = Product::select('id','nama')->get(); 
        $kategory = Kategoripesanan::select('id','nama')->get();
        $komoditas = Komoditas::select('id','nama')->get();
        $kategoryCustomer = Customer_category::select('id','nama')->get();
        
        return view('laporan.penjualan.filterPenjualanDetail', [
            'customer' => $customer,
            'sales' => $sales,
            'title' => $title,
            'merk' => $merk,
            'produk' => $produk,
            'komoditas' => $komoditas,
            'kategory' => $kategory,
            'kategoryCustomer' => $kategoryCustomer
        ]);
        
    }

    public function filterPenjualanCN()
    {

        $title = "Laporan Penjualan";
        $customer = Customer::select('id','nama')->get();
        $sales = Sales::select('id','nama')->get();
        $merk = Merk::select('id','nama')->get();      
        $produk = Product::select('id','nama')->get(); 
        
        return view('laporan.penjualan.filterPenjualanCN', compact('title','customer','sales','merk','produk'));
        
    }

    public function filterDataPenjualan(Request $request)
    {
        $title = 'Laporan Penjualan';
        $data = $request->all();
        
        $tgl1 = Carbon::parse($data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($data['tgl2'])->format('Y-m-d');                
        $penjualan = DB::table('faktur_penjualans as fp')
                    ->join('pengiriman_barangs as pb','fp.pengiriman_barang_id','=','pb.id')
                    ->join('users as u','fp.created_by','=','u.id')
                    ->join('customers as cs','fp.customer_id','=','cs.id')
                    ->join('pesanan_penjualans as pp','fp.pesanan_penjualan_id','=','pp.id')
                    ->join('sales as s','pp.sales_id','=','s.id')
                    ->where('fp.deleted_at','=',null);                    

                    

        if ($data['tgl1']) {            
            if (!$data['tgl2']) {
                $tanggalFilter=$penjualan->where('fp.tanggal','>=',$tgl1);
                                
            }else{
                $tanggalFilter=$penjualan->where('fp.tanggal','>=',$tgl1)
                                ->where('fp.tanggal','<=',$tgl2);
            }
        }elseif($data['tgl2']){
            if (!$data['tgl1']) {
                $tanggalFilter=$penjualan->where('fp.tanggal','<=',$tgl2);
            }else{
                $tanggalFilter=$penjualan->where('fp.tanggal','>=',$tgl1)
                                ->where('fp.tanggal_top','<=',$tgl2);
            }
        }else{
            $tanggalFilter = $penjualan;
        }
                        

        if ($data['customer'] == 'all') {            

            $customerfilter = $tanggalFilter;                     
            
        }else{
            $customerfilter = $penjualan->where('fp.customer_id','=',$data['customer']);
        }

        if ($data['sales'] == 'all') {
            $salesfilter = $customerfilter;
                          
        }else{
            $salesfilter = $customerfilter->where('pp.sales_id','=',$data['sales']);                
        }      


        $filter = $salesfilter->orderBy('fp.tanggal','desc')->select('fp.*','pb.kode as kode_SJ','pp.kode as kode_SP','s.nama as nama_sales'
                                ,'u.name as nama_pembuat','cs.nama as nama_customer'                                
                                )->get();     
                
        if (count($filter) <= 0) {
            return redirect()->back()->with('status_danger', 'Data tidak ditemukan');
        }        

        return view('laporan.penjualan.filterPenjualanResult',[
            'penjualan' => $filter,
            'title' => $title,
            'form' => $data
        ]);            
        
    }

    public function exportPenjualan(Request $request)
    {
        $data = $request->all();        
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LaporanPenjualanExport($data), 'laporanpenjualan-'.$now.'.xlsx');
    }

    public function filterDataPenjualanDetail(Request $request)
    {
        // dd($request->all());
        $title = 'Laporan Penjualan';
        $data = $request->all();        
        $tgl1 = Carbon::parse($data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($data['tgl2'])->format('Y-m-d');                
        $penjualan = DB::table('faktur_penjualans as fp')
                    ->join('pengiriman_barangs as pb','fp.pengiriman_barang_id','=','pb.id')
                    ->join('faktur_penjualan_details as fpb','fpb.faktur_penjualan_id','=','fp.id')
                    ->join('pesanan_penjualans as pp','fp.pesanan_penjualan_id','=','pp.id')
                    ->join('sales as s','pp.sales_id','=','s.id')
                    ->join('kategoripesanans as kp','pp.kategoripesanan_id','=','kp.id')
                    ->join('komoditas as km','pp.komoditas_id','=','km.id')
                    ->join('customers as cs','fp.customer_id','=','cs.id')
                    ->join('customer_categories as cc','cs.kategori_id','=','cc.id')
                    ->join('products as p','p.id','=','fpb.product_id')
                    ->join('merks as m','p.merk_id','=','m.id')
                    ->join('users as u','fp.created_by','=','u.id');                    
                                                      
        if ($data['tgl1']) {            
            if (!$data['tgl2']) {
                $tanggalFilter=$penjualan->where('fp.tanggal','>=',$tgl1);
                                
            }else{
                $tanggalFilter=$penjualan->where('fp.tanggal','>=',$tgl1)
                                ->where('fp.tanggal','<=',$tgl2);
            }
        }elseif($data['tgl2']){
            if (!$data['tgl1']) {
                $tanggalFilter=$penjualan->where('fp.tanggal','<=',$tgl2);
            }else{
                $tanggalFilter=$penjualan->where('fp.tanggal','>=',$tgl1)
                                ->where('fp.tanggal_top','<=',$tgl2);
            }
        }else{
            $tanggalFilter = $penjualan;
        }

        if ($data['customer'] == 'all') {            
            $customerfilter = $tanggalFilter;                                  
        }else{
            $customerfilter = $tanggalFilter->where('fp.customer_id','=',$data['customer']);     
        }

        if ($data['sales'] == 'all') {
            $salesfilter = $customerfilter;                                          
        }else{
            $salesfilter = $customerfilter->where('pp.sales_id','=',$data['sales']);                
        }

        if ($data['kategori_pesanan'] !== 'all') {
            $salesfilter->where('pp.kategoripesanan_id',$data['kategori_pesanan']);               
        }

        if ($data['kategori_customer'] !== 'all') {
            $salesfilter->where('cs.kategori_id',$data['kategori_customer']);               
        }

        if ($data['komoditas'] !== 'all') {
            $salesfilter->where('pp.komoditas_id',$data['komoditas']);
        }
        if ($data['produk'] == 'all') {
            $produkfilter = $salesfilter;            
        } else {
            $produkfilter = $salesfilter->where('p.id','=',$data['produk']);
        }
        

        if ($data['merk'] == 'all') {
            $merkfilter  = $produkfilter;
        } else {
            $merkfilter  = $produkfilter->where('m.id','=',$data['merk']);
        }

    
        $filter = $merkfilter->orderBy('fp.tanggal','desc')->select('fp.*','fpb.qty as qty_det','fpb.satuan as satuan_det','fpb.hargajual as hargajual_det'
                                        ,'fpb.diskon_persen as dikson_persen_det','fpb.diskon_rp as diskon_rp_det','fpb.subtotal as subtotal_det'
                                        ,'fpb.total as total_det','fpb.total_diskon as total_diskon_det','fpb.ongkir as ongkir_det','fpb.keterangan as keterangan_det' 
                                        ,'pb.kode as kode_SJ','pp.kode as kode_SP'
                                        ,'s.nama as nama_sales','u.name as nama_pembuat'
                                        ,'cs.nama as nama_customer','p.nama as nama_produk'
                                        ,'m.nama as nama_merk','p.kode as kode_produk'
                                        ,'km.nama as nama_komoditas','kp.nama as nama_kategori_pesanan','cc.nama as nama_category_customer'
                                        )->get();                                        

        
        // dd($filter[0]);            
        if (count($filter) <= 0) {
            return redirect()->back()->with('status_danger', 'Data tidak ditemukan');
        }        
                
        // dd($filter);
        return view('laporan.penjualan.filterPenjualanResultDetail',[
            'penjualan' => $filter,
            'title' => $title,
            'form' => $data
        ]);            
        
    }
    public function exportPenjualanDetail(Request $request)
    {
        $data = $request->all();        
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LaporanPenjualanDetailExport($data), 'laporanpenjualandetail-'.$now.'.xlsx');
    }


    public function filterDataPenjualanCN(Request $request)
    {
        $title = 'Laporan Penjualan';
        $data = $request->all();        
        $tgl1 = Carbon::parse($data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($data['tgl2'])->format('Y-m-d');                
        $penjualan = DB::table('faktur_penjualans as fp')
                    ->join('pengiriman_barangs as pb','fp.pengiriman_barang_id','=','pb.id')
                    ->join('faktur_penjualan_details as fpb','fpb.faktur_penjualan_id','=','fp.id')
                    ->join('users as u','fp.created_by','=','u.id')
                    ->join('no_faktur_pajaks as nfp' ,'fp.pajak_id','=','nfp.id');                    
        
                                 
        if ($data['tgl1']) {            
            if (!$data['tgl2']) {
                $tanggalFilter=$penjualan->where('fp.tanggal','>=',$tgl1);
                                
            }else{
                $tanggalFilter=$penjualan->where('fp.tanggal','>=',$tgl1)
                                ->where('fp.tanggal','<=',$tgl2);
            }
        }elseif($data['tgl2']){
            if (!$data['tgl1']) {
                $tanggalFilter=$penjualan->where('fp.tanggal','<=',$tgl2);
            }else{
                $tanggalFilter=$penjualan->where('fp.tanggal','>=',$tgl1)
                                ->where('fp.tanggal_top','<=',$tgl2);
            }
        }else{
            $tanggalFilter = $penjualan;
        }
                                                                        

        if ($data['customer'] == 'all') {            
            $customerfilter = $tanggalFilter->join('customers as cs','fp.customer_id','=','cs.id');                                  
        }else{
            $customerfilter = $tanggalFilter->join('customers as cs','fp.customer_id','=','cs.id')
                              ->where('fp.customer_id','=',$data['customer']);            
        }

        if ($data['sales'] == 'all') {
            $salesfilter = $customerfilter->join('pesanan_penjualans as pp','fp.pesanan_penjualan_id','=','pp.id')
                          ->join('sales as s','pp.sales_id','=','s.id');                

        }else{
            $salesfilter = $customerfilter->join('pesanan_penjualans as pp','fp.pesanan_penjualan_id','=','pp.id')
                          ->join('sales as s','pp.sales_id','=','s.id')
                          ->where('pp.sales_id','=',$data['sales']);                
        }


        if ($data['produk'] == 'all') {
            $produkfilter = $salesfilter ->join('products as p','p.id','=','fpb.product_id');            
        } else {
            $produkfilter = $salesfilter ->join('products as p','p.id','=','fpb.product_id')
                                         ->where('p.id','=',$data['produk']);
        }
        

        if ($data['merk'] == 'all') {
            $merkfilter  = $produkfilter->join('merks as m','p.merk_id','=','m.id');
        } else {
            $merkfilter  = $produkfilter->join('merks as m','p.merk_id','=','m.id')
                            ->where('m.id','=',$data['merk']);
        }
    
        $filter = $merkfilter->select('fp.*','fpb.qty as qty_det','fpb.satuan as satuan_det','fpb.hargajual as hargajual_det'
                                        ,'fpb.diskon_persen as dikson_persen_det','fpb.diskon_rp as diskon_rp_det','fpb.subtotal as subtotal_det'
                                        ,'fpb.total as total_det','fpb.total_diskon as total_diskon_det','fpb.ongkir as ongkir_det','fpb.cn_persen','fpb.cn_rupiah','fpb.cn_total','fpb.keterangan as keterangan_det' 
                                        ,'pb.kode as kode_SJ','pp.kode as kode_SP'
                                        ,'s.nama as nama_sales','u.name as nama_pembuat'
                                        ,'cs.nama as nama_customer','p.nama as nama_produk','p.kode as kode_produk','m.nama as nama_merk',
                                        'pp.ppn as no_ppn'
                                        )->get(); 
                
        if (count($filter) <= 0) {
            return redirect()->back()->with('status_danger', 'Data tidak ditemukan');
        }
        

                
        // dd($filter);
        return view('laporan.penjualan.filterPenjualanResultCN',[
            'penjualan' => $filter,
            'title' => $title,
            'form' => $data
        ]);            
        
    }
    
    public function exportPenjualanCN(Request $request)
    {
        $data = $request->all();        
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LaporanPenjualanCNExport($data), 'laporanpenjualanCN-'.$now.'.xlsx');
    }

}
