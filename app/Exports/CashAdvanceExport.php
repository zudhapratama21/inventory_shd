<?php

namespace App\Exports;

use App\Models\CashAdvance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CashAdvanceExport implements FromView
{
    protected $data;

    public function __construct($data)
    {        
        $this->data = $data;
    }
    public function view(): View
    {
        $tgl1 = $this->data['tanggal_mulai'];
        $tgl2 = $this->data['tanggal_akhir'];
        $cashAdvance = CashAdvance::with('biayaoperational.subbiaya','karyawan.posisi.divisi')
            ->where('deleted_at', null)            
            ->whereBetween('tanggal', [$tgl1, $tgl2])
            ->get();
        
        
        return view('keuangan.analisis.export.cashadvance', [
            'data' => $cashAdvance
        ]);
    }
}
