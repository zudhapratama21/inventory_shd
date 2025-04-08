<?php

namespace App\Exports;

use App\Models\KunjunganTeknisi;
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
        $sales = $this->data['sales'];

        $biaya = KunjunganTeknisi::with('outlet')
                 ->where('tanggal','>=',$tgl1)
                 ->where('tanggal','<=',$tgl2)
                 ->whereHas('user', function($query) use($sales) {
                    if ($sales !== 'All') {
                        $query->where('users.id', '=', $sales);             
                    }                            
                 })->get();                    
       

        return view('laporan.teknisi.export.kunjunganteknisi',[
            'data' => $biaya
        ]);        
    }
}
