<?php

namespace App\Exports;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanBiayaOperasional implements FromView
{

    protected $data;

    public function __construct($data)
    {        
        $this->data = $data;
    }
    public function view(): View
    {        
        $tgl1 = Carbon::parse($this->data['tanggal_mulai'])->format('Y-m-d');
        $tgl2 = Carbon::parse($this->data['tanggal_akhir'])->format('Y-m-d');
        $biaya = DB::table('biaya_operationals as bo')
            ->join('karyawan as k', 'k.id', '=', 'bo.karyawan_id')
            ->join('posisi as p', 'p.id', '=', 'k.posisi_id')
            ->join('divisi as d', 'd.id', '=', 'p.divisi_id')
            ->join('jenis_biayas as jb', 'jb.id', '=', 'bo.jenis_biaya_id')
            ->join('banks as b', 'b.id', '=', 'bo.bank_id')
            ->join('sub_biaya as sb', 'sb.id', '=', 'bo.subjenis_biaya_id')
            ->where('bo.deleted_at', null)
            ->whereBetween(
                'bo.tanggal',
                [$tgl1, $tgl2]
            );                            

        if ($this->data['jenisbiaya_id'] !== 'all') { 
                $biaya->where('sb.id', $this->data['jenisbiaya_id']);            
        }

        if ($this->data['karyawan_id'] !== 'all') {
                $biaya->where('k.id', $this->data['karyawan_id']);            
        }

        $results = $biaya->select(
                'bo.tanggal as tanggal',
                'k.nama as karyawan',
                'd.nama as divisi',
                'bo.nominal as total_biaya',
                'bo.keterangan',
                'jb.nama as jenis_biaya',
                'sb.nama as subjenis_biaya',
                'b.nama as bank',
            )
            ->orderByDesc('bo.id')->get();
                        
        return view('laporan.biayaoperational.export.export', [
            'data' => $results
        ]);
    }
}
