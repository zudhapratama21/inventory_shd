<?php

namespace App\Http\Controllers\Laporan;

use App\Exports\LaporanPembelianDetailExport;
use App\Exports\LaporanPembelianExport;
use App\Http\Controllers\Controller;
use App\Models\Merk;
use App\Models\Product;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPembelianController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:laporanpembelian-list');        
        // $this->middleware('permission:laporanstokkartu-list', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:laporanstokexp-list', ['only' => ['destroy']]);
    }


    public function index()
    {        
        $title = "Laporan Pembelian";
        return view('laporan.pembelian.index', compact('title'));
        
    }

    public function filterPembelian()
    {                    
        $title = "Laporan Pembelian";
        $supplier = Supplier::with('namakota')->select('id','nama','kota')->get();                

        return view('laporan.pembelian.laporanpembelian.filterPembelian', [
            'supplier' => $supplier,        
            'title' => $title
        ]);

    }

    public function filterPembelianDetail()
    {
        $title = "Laporan Pembelian Detail";
        $supplier = Supplier::with('namakota')->select('id','nama','kota')->get();  
        $produk = Product::select('id','nama')->get();
        $merk = Merk::select('id','nama')->get();

        
        return view('laporan.pembelian.laporanpembeliandetail.filterPembelian', [
            'supplier' => $supplier,        
            'title' => $title,
            'produk' => $produk,
            'merk' => $merk
        ]);
        
    }

    public function filterDataPembelian(Request $request)
    {
        
        $title = 'Laporan Pembelian';
        $data = $request->all();
        $tgl1 = Carbon::parse($data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($data['tgl2'])->format('Y-m-d');                
        $penjualan = DB::table('faktur_pembelians as fp')        
                    ->join('penerimaan_barangs as pb','fp.penerimaan_barang_id','=','pb.id')
                    ->join('pesanan_pembelians as pp','fp.pesanan_pembelian_id','=','pp.id')
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
    
        // dd($penjualan->get());

        if ($data['supplier'] == 'all') {            

            $customerfilter = $tanggalFilter->join('suppliers as s','fp.supplier_id','=','s.id');                           
        }else{
            $customerfilter = $tanggalFilter->join('supplier as s','fp.supplier_id','=','s.id')
                              ->where('fp.supplier_id','=',$data['supplier']);                 

        }
        
        $filter = $customerfilter->select('fp.*','pb.kode as kode_SJ','pp.no_so as no_pesanan','pp.kode as kode_SP','pp.no_so as no_pesanan','s.nama as nama_supplier','u.name as nama_pembuat')->get();                                        
                
        if (count($filter) <= 0) {
            return redirect()->back()->with('status_danger', 'Data tidak ditemukan');
        }     
        
        dd($filter[0]);

        return view('laporan.pembelian.laporanpembelian.filterPembelianResult',[
            'pembelian' => $filter,
            'title' => $title,
            'form' => $data
        ]);            


    }
    public function exportPembelian(Request $request)
    {
        $data = $request->all();        
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LaporanPembelianExport($data), 'laporanpembelian-'.$now.'.xlsx');
    }

    public function filterDataPembelianDetail(Request $request)
    {
        $title = 'Laporan Pembelian Detail';
        $data = $request->all();
        $tgl1 = Carbon::parse($data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($data['tgl2'])->format('Y-m-d');                
        $penjualan = DB::table('faktur_pembelians as fp')        
                    ->join('penerimaan_barangs as pb','fp.penerimaan_barang_id','=','pb.id')
                    ->join('pesanan_pembelians as pp','fp.pesanan_pembelian_id','=','pp.id')
                    ->join('faktur_pembelian_details as fpb','fpb.faktur_pembelian_id','=','fp.id')
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
                
                    
            
                    if ($data['supplier'] == 'all') {            
            
                        $customerfilter = $tanggalFilter->join('suppliers as s','fp.supplier_id','=','s.id');                           
                    }else{
                        $customerfilter = $tanggalFilter->join('suppliers as s','fp.supplier_id','=','s.id')
                                          ->where('fp.supplier_id','=',$data['supplier']);                 
            
                    }
            
                    if ($data['produk'] == 'all') {
                        $produkfilter = $customerfilter ->join('products as p','p.id','=','fpb.product_id');            
                    } else {
                        $produkfilter = $customerfilter ->join('products as p','p.id','=','fpb.product_id')
                                                     ->where('p.id','=',$data['produk']);
                    }
                    
            
                    if ($data['merk'] == 'all') {
                        $merkfilter  = $produkfilter->join('merks as m','p.merk_id','=','m.id');
                    } else {
                        $merkfilter  = $produkfilter->join('merks as m','p.merk_id','=','m.id')
                                        ->where('m.id','=',$data['merk']);
                    }

        $filter = $merkfilter->select('fp.*','pb.kode as kode_SJ'
                                        ,'pp.kode as kode_SP'
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
                
        if (count($filter) <= 0) {
            return redirect()->back()->with('status_danger', 'Data tidak ditemukan');
        }    
    
            
        return view('laporan.pembelian.laporanpembeliandetail.filterPembelianResult',[
            'pembelian' => $filter,
            'title' => $title,
            'form' => $data
        ]);            
    }

    public function exportPembelianDetail(Request $request)
    {
        $data = $request->all();        
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LaporanPembelianDetailExport($data), 'laporanpembeliandetail-'.$now.'.xlsx');
    }



}
