<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Customer_category;
use App\Models\Kategoripesanan;
use App\Models\Merk;
use App\Models\Sales;
use App\Models\TargetSales;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PerformaSalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:performasales-list');
    }

    public function index()
    {
        $title = 'Performa Sales';
        $kategori = Kategoripesanan::get();
        $sales = Sales::get();

        $bulan =  [];
        for ($i = 1; $i <=12; $i++) {
            $databulan = '1-'.$i.'-2023';
            $bulan[] = [
                'nama' => Carbon::parse($databulan)->format('F') ,
                'id' => $i
            ];         
        }


        return view('sales.performasales.index',compact('sales','title','kategori','bulan'));
    }

    public function dataperformasales(Request $request)
    {
       $results = DB::table('faktur_penjualans as fp')
                    ->join('pesanan_penjualans as pp','fp.pesanan_penjualan_id','=','pp.id')
                    ->join('kategoripesanans as kp','pp.kategoripesanan_id','=','kp.id')
                    ->join('sales as s','pp.sales_id','=','s.id')
                    ->where('fp.deleted_at','=',null);

        if ($request->year) {
            $res=$results->whereYear('fp.tanggal',$request->year);       
        }else{
            $res=$results;
        }

        if ($request->kategori == 'All') {
            $kategori = $res;
        }else{
            $kategori = $res->where('pp.kategoripesanan_id',$request->kategori);
        }

        if ($request->month) {
            $data=$kategori->whereMonth('fp.tanggal',$request->month);       
        }else{
            $data=$kategori;
        }

        $hasil = $data->groupBy('pp.sales_id')
        ->select(
            DB::raw("DATE_FORMAT(fp.tanggal,'%M') as tanggal"), 
           's.nama','s.id','s.hp',
            DB::raw("sum(fp.grandtotal) as grandtotal_penjualan"),
            DB::raw("sum(fp.ppn) as total_ppn"),
            DB::raw("sum(fp.total_cn) as total_cn"),
            DB::raw("sum(fp.ongkir) as total_ongkir"),
        )->get();


        $sales = Sales::with('user')->get();
        $dataSales = [];
        $persen = 0;

        foreach ($sales as $value) {
            
            foreach ($hasil as $res) {
                $dataOmset = $res->grandtotal_penjualan - $res->total_cn - $res->total_ppn - $res->total_ongkir;
                if ($request->kategori == 2) {
                    $targetSales = TargetSales::where('sales_id',$value->id)->where('tahun',$request->year)->where('bulan',$request->month)->first();
                    if ($targetSales) {
                        $persen = $dataOmset/$targetSales->nominal * 100;    
                    }
                    
                }
                 if ($value->id == $res->id) {
                    $dataSales[] = [
                        'id' => $value->id,
                        'bulan' =>$res->tanggal,
                        'user' => $value->user,
                        'hp' => $res->hp,
                        'nama' => $res->nama,
                        'laba' => number_format($dataOmset,0, ',', '.'),
                        'persen' => (int) $persen,
                    ]; 
                 }
            }    
        }

        return response()->json([ 
            'sales' => $dataSales
        ]);
    }

    public function grafikPerformaSales(Request $request)
    {        
        $results = DB::table('faktur_penjualans as fp')
                  ->join('pesanan_penjualans as pp','fp.pesanan_penjualan_id','=','pp.id')
                  ->join('kategoripesanans as kp','pp.kategoripesanan_id','=','kp.id')
                  ->join('sales as s','pp.sales_id','=','s.id')
                  ->where('fp.deleted_at','=',null);
        
        if ($request->year) {
            $res=$results->whereYear('fp.tanggal',$request->year);       
        }else{
            $res=$results;
        }

        if ($request->kategori == 'All') {
            $kategori = $res;
        }else{
            $kategori = $res->where('pp.kategoripesanan_id',$request->kategori);
        }

        if ($request->bulan == 'All') {
            $bulan = $kategori;
        }else{
            $bulan = $kategori->whereMonth('fp.tanggal',$request->bulan)
                              ->groupBy(DB::raw("DATE_FORMAT(fp.tanggal, '%m-%Y')"));
        }
        
        $hasil= $bulan->groupBy('pp.sales_id')                 
                            ->select(
                                    's.id','s.nama as nama_sales','kp.nama as nama_kategori',
                                    DB::raw("sum(fp.grandtotal) as grandtotal_penjualan"),
                                    DB::raw("sum(fp.ppn) as total_ppn"),
                                    DB::raw("sum(fp.total_cn) as total_cn"),
                                    DB::raw("sum(fp.ongkir) as total_ongkir"),
                            )->get();
                             
        $sales = [];
        $penjualan = [];

        $count = count($hasil);

        if ($count > 0) {
            foreach ($hasil as $key => $value) {
                $sales[] =  $value->nama_sales ;
                $penjualan []  = $value->grandtotal_penjualan - $value->total_ppn - $value->total_cn - $value->total_ongkir;
            }
        }

        return response()->json([
            'sales' => $sales,
            'penjualan' => $penjualan
        ]);

        
    }

    // =====================================================================================================================================
    // GRAFIK UNTUK DETAIL SALES 
    // =====================================================================================================================================


    public function performasalesdetail($id,$month,$kategori)
    {
        $title = 'Detail Performa Sales';
        $category = Kategoripesanan::get();
        $categorycustomer = Customer_category::get();
        $merk = Merk::get();

        for ($i = 1; $i <=12; $i++) {
            $databulan = '1-'.$i.'-2023';
            $months[] = [
                'nama' => Carbon::parse($databulan)->format('F') ,
                'id' => $i
            ];         
        }

        $sales = Sales::where('id',$id)->first();
        

        return view('sales.performasales.detailperforma.index',[
            'sales_id' => $id,
            'title' => $title,
            'kategori' => $category,
            'bulan_id' => $month,
            'kategori_id' => $kategori,
            'bulan' => $months,
            'sales' => $sales,
            'categorycustomer' => $categorycustomer,
            'merk' => $merk
        ]);


    }


    public function grafikperformasalesdetail(Request $request)
    {
         $results = DB::table('faktur_penjualans as fp')
                    ->join('pesanan_penjualans as pp','fp.pesanan_penjualan_id','=','pp.id')
                    ->where('fp.deleted_at','=',null)
                    ->orderBy('fp.tanggal')
                    ->where('pp.sales_id',$request->id);                                 
        
        if ($request->year) {
            $res=$results->whereYear('fp.tanggal',$request->year);       
        }else{
            $res=$results;
        }

        $targetSales = [];
        if ($request->kategori !== 'All') {            
            $kategori=$res->where('pp.kategoripesanan_id',$request->kategori); 
            
        }else{
            $kategori=$res;
            
        }
        if ($request->kategori == 2) {
            $targetSales = TargetSales::where('sales_id',$request->id)->where('tahun',$request->year)->orderBy('bulan')->get();
        }

        $bulan = $kategori;

        $tipe = $bulan->groupBy(DB::raw("DATE_FORMAT(fp.tanggal, '%m-%Y')"))
                ->select(
                    DB::raw("DATE_FORMAT(fp.tanggal, '%m') as tanggal_penjualan"),
                    DB::raw("sum(fp.grandtotal) as grandtotal_penjualan"),
                    DB::raw("sum(fp.ppn) as total_ppn"),
                    DB::raw("sum(fp.total_cn) as total_cn"),
                    DB::raw("sum(fp.ongkir) as total_ongkir"),
                );         
        
        $hasil= $tipe->get();                        
        $laba = array();  
        $data=[]; 
                        
        foreach ($hasil as $key => $value) {
            $data[(int)$value->tanggal_penjualan] = [
                'grandtotal' => (int) ( $value->grandtotal_penjualan - $value->total_ppn - $value->total_cn - $value->total_ongkir)
            ];
        }
        
        
        for ($i=0; $i <= 12; $i++) { 
            if ($i==0) {
                $laba[] = 0;
            }else{
                if (!empty($data[$i])) {
                    $laba[] = $data[$i]['grandtotal'];
                }else{
                    $laba[] = 0;
                }
            }
            
        }

        for ($i = 0; $i <= 12; $i++) {
            $databulan = '1-'.$i.'-2023';
            if ($i==0) {
                $months[]= [0];
            }else{
                $months[] = [
                    Carbon::parse($databulan)->format('F') 
                ]; 
            }
                   
        }
        $dataTargetSales = [];

        if (count($targetSales) > 0) {
            for ($i=-1; $i <= 11; $i++) {
                if ($i==-1) {
                    $dataTargetSales[] = $targetSales[0]->nominal;    
                } else{
                    $dataTargetSales[] = $targetSales[$i]->nominal;
                }
                
            }
           
        }
       

        return response()->json([
            'laba' => $laba,
            'bulan' => $months,
            'targetsales' => $dataTargetSales
        ]);
    }


    public function datatableCustomer(Request $request)
    {
          $results = DB::table('faktur_penjualans as fp')
                    ->join('faktur_penjualan_details as fdp','fdp.faktur_penjualan_id','=','fp.id')                    
                    ->join('pesanan_penjualans as pp','fp.pesanan_penjualan_id','=','pp.id')        
                    ->join('customers as c','fp.customer_id','=','c.id')           
                    ->where('fp.deleted_at','=',null)
                    ->where('pp.sales_id',$request->sales_id);

        if ($request->year) {
            $res=$results->whereYear('fp.tanggal',$request->year);       
        }else{
            $res=$results;
        }

        if ($request->bulan !== 'All') {
            $bulan = $res->whereMonth('fp.tanggal',$request->bulan)
                    ->groupBy(DB::raw("DATE_FORMAT(fp.tanggal, '%m-%Y')"));
        }else{
            $bulan = $res;
        }

        if ($request->kategori !== 'All') {
            $kategori = $bulan->where('pp.kategoripesanan_id',$request->kategori);
        }else{
            $kategori = $bulan;
        }

        if ($request->kategoricustomer !== 'All') {
            $customercategori = $kategori->where('c.kategori_id',$request->kategoricustomer);
        }else{
            $customercategori = $kategori;
        }


        
        $hasil = $customercategori
                ->groupBy('pp.customer_id')             
                ->select(     
                    'c.nama','c.id',              
                    DB::raw("DATE_FORMAT(fp.tanggal, '%m') as tanggal_penjualan"),
                    DB::raw("DATE_FORMAT(fp.tanggal, '%Y') as tahun_penjualan"),
                    DB::raw("sum(fdp.qty) as stok_produk"),
                    DB::raw("sum(fdp.total) as total_penjualan"),                    
                    DB::raw("sum(fdp.cn_total) as total_cn") 
                )                  
                ->get(); 

        $count = count($hasil);
        $tmp = null;
        
        if ($count > 0) {            
            for ($i=0; $i < $count-1 ; $i++) { 
                for ($j=$i+1; $j < $count ; $j++) { 
                    $awal = $hasil[$i]->total_penjualan - ($hasil[$i]->total_cn ? $hasil[$i]->total_cn : 0) ;
                    $akhir = $hasil[$j]->total_penjualan - ($hasil[$j]->total_cn ? $hasil[$j]->total_cn : 0);     

                    if ($awal < $akhir) {
                        $tmp = $hasil[$i];
                        $hasil[$i] = $hasil[$j];
                        $hasil[$j] = $tmp;
                    }
                }
            }       
        }

        $data = $hasil;

        return DataTables::of($data)
                        ->addIndexColumn() 
                        ->editColumn('tanggal', function ($data) {
                           return $data->tanggal_penjualan . '-'. $data->tahun_penjualan; 
                        })                        
                        ->editColumn('total', function ($data) {
                            return 'Rp.' . number_format($data->total_penjualan - $data->total_cn, 0, ',', '.');
                        })        
                        ->addColumn('action', function ($data) {
                            $customer_id =  $data->id;
                            return view('sales.performasales.detailperforma.partial.button',compact('customer_id'));
                        })      
                        ->make(true);         
    }

    public function datatableProduk(Request $request)
    {
        $results = DB::table('faktur_penjualans as fp')
                    ->join('faktur_penjualan_details as fdp','fdp.faktur_penjualan_id','=','fp.id')                    
                    ->join('pesanan_penjualans as pp','fp.pesanan_penjualan_id','=','pp.id')        
                    ->join('products as p','fdp.product_id','=','p.id')           
                    ->where('fp.deleted_at','=',null)
                    ->where('pp.sales_id',$request->sales_id)
                    ->where('fp.customer_id',$request->customer_id);

        if ($request->year) {
            $res=$results->whereYear('fp.tanggal',$request->year);       
        }else{
            $res=$results;
        }

        if ($request->bulan !== 'All') {
             $bulan = $res->whereMonth('fp.tanggal',$request->bulan)
                      ->groupBy(DB::raw("DATE_FORMAT(fp.tanggal, '%m-%Y')"));
        }else{
             $bulan = $res;
        }

        if ($request->kategori !== 'All') {
             $kategori = $bulan->where('pp.kategoripesanan_id',$request->kategori);
        }else{
            $kategori = $bulan;
        }

        $hasil = $kategori
            ->groupBy('fdp.product_id')             
            ->select(     
                'p.nama','p.id',              
                DB::raw("DATE_FORMAT(fp.tanggal, '%m') as tanggal_penjualan"),
                DB::raw("DATE_FORMAT(fp.tanggal, '%Y') as tahun_penjualan"),
                DB::raw("sum(fdp.qty) as stok_produk"),
                DB::raw("sum(fdp.total) as total_penjualan"),
                DB::raw("sum(fdp.cn_total) as total_cn")
            )                  
            ->get(); 

        $count = count($hasil);
        $tmp = null;

        if ($count > 0) {            
        for ($i=0; $i < $count-1 ; $i++) { 
            for ($j=$i+1; $j < $count ; $j++) { 
                $awal = $hasil[$i]->total_penjualan - ($hasil[$i]->total_cn ? $hasil[$i]->total_cn : 0) ;
                $akhir = $hasil[$j]->total_penjualan - ($hasil[$j]->total_cn ? $hasil[$j]->total_cn : 0);     

                if ($awal < $akhir) {
                    $tmp = $hasil[$i];
                    $hasil[$i] = $hasil[$j];
                    $hasil[$j] = $tmp;
                }
            }
        }       
        }

        $data = $hasil;

        return DataTables::of($data)
                    ->addIndexColumn() 
                    ->editColumn('tanggal', function ($data) {
                    return $data->tanggal_penjualan . '-'. $data->tahun_penjualan; 
                    })                        
                    ->editColumn('total', function ($data) {
                        return 'Rp.' . number_format($data->total_penjualan -  $data->total_cn, 0, ',', '.');
                    })        
                    ->addColumn('action', function ($data) {
                        $customer_id =  $data->id;
                        return view('sales.performasales.detailperforma.partial.button',compact('customer_id'));
                    })      
                    ->make(true);  
    }


    // =================================================== DATATABLE DATA PRODUCT ========================================================================

    public function dataProduct(Request $request)
    {
        $results = DB::table('faktur_penjualans as fp')
                    ->join('faktur_penjualan_details as fdp','fdp.faktur_penjualan_id','=','fp.id')                    
                    ->join('pesanan_penjualans as pp','fp.pesanan_penjualan_id','=','pp.id')        
                    ->join('products as p','fdp.product_id','=','p.id')           
                    ->where('fp.deleted_at','=',null)
                    ->where('pp.sales_id',$request->sales_id);                    

        if ($request->year) {
            $res=$results->whereYear('fp.tanggal',$request->year);       
        }else{
            $res=$results;
        }

        if ($request->bulan !== 'All') {
             $bulan = $res->whereMonth('fp.tanggal',$request->bulan)
                      ->groupBy(DB::raw("DATE_FORMAT(fp.tanggal, '%m-%Y')"));
        }else{
             $bulan = $res;
        }

        if ($request->kategori !== 'All') {
             $kategori = $bulan->where('pp.kategoripesanan_id',$request->kategori);
        }else{
            $kategori = $bulan;
        }

        if ($request->merk !== 'All') {
            $merk = $kategori->where('p.merk_id',$request->merk);
        }else{
            $merk = $kategori;
        }

        $hasil = $merk
            ->groupBy('fdp.product_id')             
            ->select(     
                'p.nama','p.id',              
                DB::raw("DATE_FORMAT(fp.tanggal, '%m') as tanggal_penjualan"),
                DB::raw("DATE_FORMAT(fp.tanggal, '%Y') as tahun_penjualan"),
                DB::raw("sum(fdp.qty) as stok_produk"),
                DB::raw("sum(fdp.total) as total_penjualan"),
                DB::raw("sum(fdp.cn_total) as total_cn")
            )                  
            ->get(); 

        $count = count($hasil);
        $tmp = null;

        if ($count > 0) {            
        for ($i=0; $i < $count-1 ; $i++) { 
            for ($j=$i+1; $j < $count ; $j++) { 
                $awal = $hasil[$i]->total_penjualan - ($hasil[$i]->total_cn ? $hasil[$i]->total_cn : 0) ;
                $akhir = $hasil[$j]->total_penjualan - ($hasil[$j]->total_cn ? $hasil[$j]->total_cn : 0);     

                if ($awal < $akhir) {
                    $tmp = $hasil[$i];
                    $hasil[$i] = $hasil[$j];
                    $hasil[$j] = $tmp;
                }
            }
        }       
        }

        $data = $hasil;

        return DataTables::of($data)
                    ->addIndexColumn() 
                    ->editColumn('tanggal', function ($data) {
                    return $data->tanggal_penjualan . '-'. $data->tahun_penjualan; 
                    })                        
                    ->editColumn('total', function ($data) {
                        return 'Rp.' . number_format($data->total_penjualan -  $data->total_cn, 0, ',', '.');
                    })        
                    ->addColumn('action', function ($data) {
                        $customer_id =  $data->id;
                        return view('sales.performasales.detailperforma.partial.button',compact('customer_id'));
                    })      
                    ->make(true);  
    }


}
