<?php

namespace App\Http\Controllers\Laporan;

use App\Exports\LaporanPembayaranHutangExport;
use App\Exports\LaporanPembayaranHutangExportDetail;
use App\Exports\LaporanPembayaranPiutangDetailExport;
use App\Exports\LaporanPembayaranPiutangExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\LogToleransi;
use App\Models\Sales;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPembayaranController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:pembayaran-list');        
        // $this->middleware('permission:laporanstokkartu-list', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:laporanstokexp-list', ['only' => ['destroy']]);
    }

    
    public function index()
    {
        return view('laporan.pembayaran.index');
    }
    public function filterHutang()
    {
        $title = "Laporan Pembayaran Hutang";
        $supplier = Supplier::with('namakota')->select('id','nama','kota')->get();

        // dd($supplier[0]);
        return view('laporan.pembayaran.pembayaranHutang.filterHutang',[
            'suppliers' =>$supplier,
            'title' => $title
        ]);
    }

    public function filterHutangDetail()
    {
        $title = "Laporan Pembayaran Hutang Detail";
        $supplier = Supplier::with('namakota')->select('id','nama','kota')->get();

        return view('laporan.pembayaran.pembayaranHutangDetail.filterHutangDetail',[
            'suppliers' =>$supplier,
            'title' => $title
        ]);
    }

    public function filterPiutang()
    {
        $title = "Laporan Pembayaran Piutang";
        $customer = Customer::with('namakota')->select('id','nama','kota')->get();
        $sales = Sales::select('id','nama')->get();

        return view('laporan.pembayaran.pembayaranPiutang.filterPiutang',[
            'customer' => $customer,
            'sales' => $sales,
            'title' => $title
        ]);
    }

    public function filterPiutangDetail()
    {
        $title = "Laporan Pembayaran Piutang Detail";
        $customer = Customer::with('namakota')->select('id','nama','kota')->get();
        $sales = Sales::select('id','nama')->get();

        return view('laporan.pembayaran.pembayaranPiutangDetail.filterPiutangDetail',[
            'customer' => $customer,
            'sales' => $sales,
            'title' => $title
        ]);
    }

    public function filterDataHutang(Request $request)
    {
        $title = 'Laporan Pembayaran Hutang';
        $data = $request->all();        
        
        $tgl1 = Carbon::parse($data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($data['tgl2'])->format('Y-m-d');                

        $pembayaran = DB::table('hutangs as h')
                    ->join('pesanan_pembelians as pp','h.pesanan_pembelian_id','=','pp.id')                    
                    ->join('penerimaan_barangs as pb','h.penerimaan_barang_id','=','pb.id');

        if ($data['tgl1']) {            
            if (!$data['tgl2']) {
                $tanggalFilter=$pembayaran->where('p.tanggal','>=',$tgl1);
                                
            }else{
                $tanggalFilter=$pembayaran->where('p.tanggal','>=',$tgl1)
                                ->where('p.tanggal','<=',$tgl2);
            }
        }elseif($data['tgl2']){
            if (!$data['tgl1']) {
                $tanggalFilter=$pembayaran->where('p.tanggal','<=',$tgl2);
            }else{
                $tanggalFilter=$pembayaran->where('p.tanggal','>=',$tgl1)
                                ->where('p.tanggal','<=',$tgl2);
            }
        }else{
            $tanggalFilter = $pembayaran;
        }                    
                    
        
        if ($request->supplier == 'all') {  

            $customerfilter = $tanggalFilter->join('suppliers as s','h.supplier_id','=','s.id');

            if ($request->no_faktur <> null) {                
                $filter =  $customerfilter->join('faktur_pembelians as fb','h.faktur_pembelian_id','=','fb.id')
                                            ->where('fb.kode','=',$request->no_faktur);
            }else{                
                $filter =  $customerfilter->join('faktur_pembelians as fb','h.faktur_pembelian_id','=','fb.id');
                                          
            }
        }else{
            $customerfilter = $pembayaran->join('suppliers as s','h.supplier_id','=','s.id')
                                         ->where('s.id','=',$request->supplier);

            if ($request->no_faktur <> null) {
                $filter =  $customerfilter->join('faktur_pembelians as fb','h.faktur_pembelian_id','=','fb.id')
                                        ->where('fb.kode','=',$request->no_faktur); 
            }else{
                $filter =  $customerfilter->join('faktur_pembelians as fb','h.faktur_pembelian_id','=','fb.id');
                                          
            }
        }

        $datafilter = $filter->select('s.nama as nama_supplier','pp.kode as kode_pp','pb.kode as kode_pb','fb.kode as kode_fp'
                                ,'h.*')->get();

        if (count($datafilter) <= 0) {
                return redirect()->back()->with('status_danger', 'Data tidak ditemukan atau belum melakukan pembayaran');
        }

        return view('laporan.pembayaran.pembayaranHutang.filterPembayaranResult',[
            'title' => $title,
            'hutang' => $datafilter,
            'form' => $data
        ]);

    }

    public function exportPembayaranHutang(Request $request)
    {
        $data = $request->all();        
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LaporanPembayaranHutangExportDetail($data), 'laporanpembayaranhutang-'.$now.'.xlsx');
    }
    
    public function filterDataHutangDetail(Request $request)
    {
        $title = 'Laporan Pembayaran Hutang Detail';
        $data = $request->all();        
        
        $tgl1 = Carbon::parse($data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($data['tgl2'])->format('Y-m-d');                

        $pembayaran = DB::table('hutangs as h')
                    ->join('pembayaran_hutangs as ph','h.id','=','ph.hutang_id')  
                    ->join('pesanan_pembelians as pp','h.pesanan_pembelian_id','=','pp.id') 
                    ->join('banks as b','ph.bank_id','=','b.id')                   
                    ->join('penerimaan_barangs as pb','h.penerimaan_barang_id','=','pb.id');

        if ($data['tgl1']) {            
            if (!$data['tgl2']) {
                $tanggalFilter=$pembayaran->where('h.tanggal','>=',$tgl1);
                                
            }else{
                $tanggalFilter=$pembayaran->where('h.tanggal','>=',$tgl1)
                                ->where('h.tanggal','<=',$tgl2);
            }
        }elseif($data['tgl2']){
            if (!$data['tgl1']) {
                $tanggalFilter=$pembayaran->where('h.tanggal','<=',$tgl2);
            }else{
                $tanggalFilter=$pembayaran->where('h.tanggal','>=',$tgl1)
                                ->where('h.tanggal','<=',$tgl2);
            }
        }else{
            $tanggalFilter = $pembayaran;
        }                    
                    
        
        if ($request->supplier == 'all') {  

            $customerfilter = $tanggalFilter->join('suppliers as s','h.supplier_id','=','s.id');

            if ($request->no_faktur <> null) {
                
                $filter =  $customerfilter->join('faktur_pembelians as fb','h.faktur_pembelian_id','=','fb.id')
                                            ->where('fb.kode','=',$request->no_faktur);
            }else{                
                $filter =  $customerfilter->join('faktur_pembelians as fb','h.faktur_pembelian_id','=','fb.id');
                                          
            }
        }else{
            $customerfilter = $pembayaran->join('suppliers as s','h.supplier_id','=','s.id')
                                         ->where('s.id','=',$request->supplier);

            if ($request->no_faktur <> null) {
                $filter =  $customerfilter->join('faktur_pembelians as fb','h.faktur_pembelian_id','=','fb.id')
                                        ->where('fb.kode','=',$request->no_faktur); 
            }else{
                $filter =  $customerfilter->join('faktur_pembelians as fb','h.faktur_pembelian_id','=','fb.id');
                                          
            }
        }

        $datafilter = $filter->select('s.nama as nama_supplier',
                                      'pp.kode as kode_pp','pb.kode as kode_pb','fb.kode as kode_fp'
                                     ,'h.*','b.nama as nama_bank','ph.nominal as nominal_pembayaran')->get();

        if (count($datafilter) <= 0) {
                return redirect()->back()->with('status_danger', 'Data tidak ditemukan atau belum melakukan pembayaran');
        }

        return view('laporan.pembayaran.pembayaranHutangDetail.filterPembayaranResult',[
            'title' => $title,
            'hutang' => $datafilter,
            'form' => $data
        ]);
    }

    public function exportPembayaranHutangDetail(Request $request)
    {
        $data = $request->all();        
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LaporanPembayaranHutangExportDetail($data), 'laporanpembayaranhutangdetail-'.$now.'.xlsx');
    }

    public function filterDataPiutang(Request $request)
    {
        $title = 'Laporan Pembayaran Piutang';
        $data = $request->all();        
        
        $tgl1 = Carbon::parse($data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($data['tgl2'])->format('Y-m-d');                

        $pembayaran = DB::table('piutangs as p')
                    ->join('pesanan_penjualans as pp','p.pesanan_penjualan_id','=','pp.id')                    
                    ->join('pengiriman_barangs as pb','p.pengiriman_barang_id','=','pb.id');

        if ($data['tgl1']) {            
            if (!$data['tgl2']) {
                $tanggalFilter=$pembayaran->where('p.tanggal','>=',$tgl1);
                                
            }else{
                $tanggalFilter=$pembayaran->where('p.tanggal','>=',$tgl1)
                                ->where('p.tanggal','<=',$tgl2);
            }
        }elseif($data['tgl2']){
            if (!$data['tgl1']) {
                $tanggalFilter=$pembayaran->where('p.tanggal','<=',$tgl2);
            }else{
                $tanggalFilter=$pembayaran->where('p.tanggal','>=',$tgl1)
                                ->where('p.tanggal','<=',$tgl2);
            }
        }else{
            $tanggalFilter = $pembayaran;
        }
                    
        
        if ($data['customer'] == 'all') {  

            $customerfilter = $tanggalFilter->join('customers as c','p.customer_id','=','c.id');

            if ($data['no_faktur'] <> null) {                
                $filter =  $customerfilter->join('faktur_penjualans as fp','p.faktur_penjualan_id','=','fp.id')
                                            ->where('fb.kode','=',$data['no_faktur']);

                    if ($data['sales'] == 'all') {
                        $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id');                
                                        
                    }else{
                        $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id')
                                        ->where('pp.sales_id','=',$data['sales']);                
                    }
                                    
            }else{                
                $filter =  $customerfilter->join('faktur_penjualans as fp','p.faktur_penjualan_id','=','fp.id');

                if ($data['sales'] == 'all') {
                    $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id');                
                                    
                }else{
                    $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id')
                                    ->where('pp.sales_id','=',$data['sales']);                
                }
                                          
            }
        }else{
            $customerfilter = $pembayaran->join('customers as c','p.customer_id','=','c.id')
                                         ->where('c.id','=',$data['customer']);

            if ($data['no_faktur'] <> null) {
                $filter =  $customerfilter->join('faktur_penjualans as fp','p.faktur_penjualan_id','=','fp.id')
                                        ->where('fb.kode','=',$data['no_faktur']); 

                    if ($data['sales'] == 'all') {
                        $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id');                
                                        
                    }else{
                        $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id')
                                        ->where('pp.sales_id','=',$data['sales']);                
                    }
            }else{
                $filter =  $customerfilter->join('faktur_penjualans as fp','p.faktur_penjualan_id','=','fp.id');

                        if ($data['sales'] == 'all') {
                            $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id');                
                                            
                        }else{
                            $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id')
                                            ->where('pp.sales_id','=',$data['sales']);                
                        }
                                                
            }
        }

        $datafilter = $salesfilter->select('c.nama as nama_customer','pp.kode as kode_pp','pb.kode as kode_pb','fp.kode as kode_fp','p.*','s.nama as nama_sales')->get();

        if (count($datafilter) <= 0) {
                return redirect()->back()->with('status_danger', 'Data tidak ditemukan atau belum melakukan pembayaran');
        }
        

        return view('laporan.pembayaran.pembayaranPiutang.filterPembayaranResult',[
            'title' => $title,
            'hutang' => $datafilter,
            'form' => $data
        ]);
    }

    public function exportPembayaranPiutang(Request $request)
    {
        $data = $request->all();        
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LaporanPembayaranPiutangDetailExport($data), 'laporanpembayaranpiutang-'.$now.'.xlsx');
    }

    public function filterPembayaranPiutangDetail(Request $request)
    {
        $title = 'Laporan Pembayaran Piutang';
        $data = $request->all();        
        
        $tgl1 = Carbon::parse($data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($data['tgl2'])->format('Y-m-d');                

        $pembayaran = DB::table('piutangs as p')
                    ->join('pesanan_penjualans as pp','p.pesanan_penjualan_id','=','pp.id')                    
                    ->join('pengiriman_barangs as pb','p.pengiriman_barang_id','=','pb.id')                   
                    ->join('pembayaran_piutangs as pps','pps.piutang_id','=','p.id')                   
                    ->join('banks as b','pps.bank_id','=','b.id');

        if ($data['tgl1']) {            
            if (!$data['tgl2']) {
                $tanggalFilter=$pembayaran->where('p.tanggal','>=',$tgl1);
                                
            }else{
                $tanggalFilter=$pembayaran->where('p.tanggal','>=',$tgl1)
                                ->where('p.tanggal','<=',$tgl2);
            }
        }elseif($data['tgl2']){
            if (!$data['tgl1']) {
                $tanggalFilter=$pembayaran->where('p.tanggal','<=',$tgl2);
            }else{
                $tanggalFilter=$pembayaran->where('p.tanggal','>=',$tgl1)
                                ->where('p.tanggal','<=',$tgl2);
            }
        }else{
            $tanggalFilter = $pembayaran;
                    }
                    
        
        if ($data['customer'] == 'all') {  

            $customerfilter = $tanggalFilter->join('customers as c','p.customer_id','=','c.id');

            if ($data['no_faktur'] <> null) {                
                $filter =  $customerfilter->join('faktur_penjualans as fp','p.faktur_penjualan_id','=','fp.id')
                                            ->where('fb.kode','=',$data['no_faktur']);

                    if ($data['sales'] == 'all') {
                        $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id');                
                                        
                    }else{
                        $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id')
                                        ->where('pp.sales_id','=',$data['sales']);                
                    }
                                    
            }else{                
                $filter =  $customerfilter->join('faktur_penjualans as fp','p.faktur_penjualan_id','=','fp.id');

                if ($data['sales'] == 'all') {
                    $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id');                
                                    
                }else{
                    $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id')
                                    ->where('pp.sales_id','=',$data['sales']);                
                }
                                          
            }
        }else{
            $customerfilter = $pembayaran->join('customers as c','p.customer_id','=','c.id')
                                         ->where('c.id','=',$data['customer']);

            if ($data['no_faktur'] <> null) {
                $filter =  $customerfilter->join('faktur_penjualans as fp','p.faktur_penjualan_id','=','fp.id')
                                        ->where('fb.kode','=',$data['no_faktur']); 

                    if ($data['sales'] == 'all') {
                        $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id');                
                                        
                    }else{
                        $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id')
                                        ->where('pp.sales_id','=',$data['sales']);                
                    }
            }else{
                $filter =  $customerfilter->join('faktur_penjualans as fp','p.faktur_penjualan_id','=','fp.id');

                        if ($data['sales'] == 'all') {
                            $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id');                
                                            
                        }else{
                            $salesfilter = $filter->join('sales as s','pp.sales_id','=','s.id')
                                            ->where('pp.sales_id','=',$data['sales']);                
                        }
                                                
            }
        }

        $datafilter = $salesfilter->select('c.nama as nama_customer','pp.kode as kode_pp','pb.kode as kode_pb','fp.kode as kode_fp','p.*','s.nama as nama_sales','pps.nominal as nominal_pembayaran','b.nama as nama_bank','pps.keterangan')->get();

        if (count($datafilter) <= 0) {
                return redirect()->back()->with('status_danger', 'Data tidak ditemukan atau belum melakukan pembayaran');
        }
        

        return view('laporan.pembayaran.pembayaranPiutangDetail.filterPembayaranResult',[
            'title' => $title,
            'hutang' => $datafilter,
            'form' => $data
        ]);
    }

    public function exportPembayaranPiutangDetail(Request $request)
    {        
        $data = $request->all();        
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LaporanPembayaranPiutangDetailExport($data), 'laporanpembayaranpiutangdetail-'.$now.'.xlsx');

    }
    
    public function logToleransi()
    {
        $title = 'Laporan Log Toleransi';
        return view('laporan.pembayaran.logToleransi.filterlogtoleransi',[
            'title' => $title
        ]);
    }
    
    public function filterLogToleransi(Request $request)
    {
        $title = 'Laporan Log Toleransi';
        $data = $request->all();        
        
        $tgl1 = Carbon::parse($data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($data['tgl2'])->format('Y-m-d');                
        
        $pembayaran = DB::table('log_toleransis as lt');

        if ($data['tgl1']) {            
            if (!$data['tgl2']) {
                $tanggalFilter=$pembayaran->where('p.tanggal','>=',$tgl1);
                                
            }else{
                $tanggalFilter=$pembayaran->where('p.tanggal','>=',$tgl1)
                                ->where('p.tanggal','<=',$tgl2);
            }
        }elseif($data['tgl2']){
            if (!$data['tgl1']) {
                $tanggalFilter=$pembayaran->where('p.tanggal','<=',$tgl2);
            }else{
                $tanggalFilter=$pembayaran->where('p.tanggal','>=',$tgl1)
                                ->where('p.tanggal','<=',$tgl2);
            }
        }else{
            $tanggalFilter = $pembayaran;
        }

        if ($data['jenis'] == 'hutang') {
            $jenis=$tanggalFilter->where('jenis','=','hutang');
        }elseif ($data['jenis'] == 'piutang') {
            $jenis=$tanggalFilter->where('jenis','=','piutang');
        }        

        $dataLog = $jenis->get();
               
        return view('laporan.pembayaran.logToleransi.filterlogtoleransiresult', [
            'datalog' => $dataLog,
            'title' => $title
        ]);                    
                    
    }






}
