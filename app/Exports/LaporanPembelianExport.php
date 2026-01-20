<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanPembelianExport implements FromView
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
                ->join('users as u','fp.created_by','=','u.id')
                ->where('fp.deleted_at',null);;  

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
        
    
        // dd($penjualan->get());

        if ($this->data['supplier'] == 'all') {            

            $customerfilter = $tanggalFilter->join('suppliers as s','fp.supplier_id','=','s.id');                           
        }else{
            $customerfilter = $penjualan->join('supplier as s','fp.supplier_id','=','s.id')
                              ->where('fp.supplier_id','=',$this->data['supplier']);                 

        }

        $filter = $customerfilter->orderBy('fp.tanggal','desc')->select('fp.*','pb.kode as kode_SJ','pp.kode as kode_SP','s.nama as nama_supplier','u.name as nama_pembuat','pp.no_so as no_pesanan')->get();                                        
                
        if (count($filter) <= 0) {
            return redirect()->back()->with('status_danger', 'Data tidak ditemukan');
        }        

        return view('laporan.pembelian.export.exportPembelian',[
            'pembelian' => $filter,                        
        ]);            


    }
}
