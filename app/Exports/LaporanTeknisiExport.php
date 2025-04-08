<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class LaporanTeknisiExport implements FromView
{
   
    protected $data ;

    public function __construct($data)
    {
        $this->data = $data;

    }

    public function view(): View
    {
        $tgl1 = Carbon::parse($this->data['tanggal_mulai'])->format('Y-m-d');
        $tgl2 = Carbon::parse($this->data['tanggal_selesai'])->format('Y-m-d');   
        $biaya = DB::table('kunjungan_teknisi as kj')
                    ->join('users as u','kj.user_id','=','u.id');
        
        if ($this->data['tanggal_mulai']) {            
            if (!$this->data['tanggal_selesai']) {
               $biaya->where('kj.tanggal','>=',$tgl1);
                                
            }else{
               $biaya->where('kj.tanggal','>=',$tgl1)
                     ->where('kj.tanggal','<=',$tgl2);
            }
        }elseif($this->data['tanggal_selesai']){
            if (!$this->data['tanggal_mulai']) {
                $biaya->where('kj.tanggal','<=',$tgl2);
            }else{
                $biaya->where('kj.tanggal','>=',$tgl1)
                     ->where('kj.tanggal','<=',$tgl2);
            }
        }else{
             $biaya;
        }

        if ($this->data['sales'] !== 'All' ) {
           $biaya->where('kj.user_id','=',$this->data['sales']);                    
        }else{
           $biaya;
        }

        $data= $biaya->select('kj.*','u.name as nama_sales')->get();       
        
        return view('laporan.teknisi.export.kunjunganteknisi',[
            'data' => $data
        ]);        
    }
}
