<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanPiutangExport implements FromView
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        // tanggal
        $tgl1 = Carbon::parse($this->data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($this->data['tgl2'])->format('Y-m-d');    

        // base query
        $query = DB::table('piutangs as p')            
            ->join('faktur_penjualans as fp', 'p.faktur_penjualan_id', '=', 'fp.id')
            ->join('customers as c', 'p.customer_id', '=', 'c.id')
            ->join('sales as s', 'fp.sales_id', '=', 's.id')            
            ->whereBetween('fp.tanggal', [$tgl1, $tgl2]);

        // 🔥 filter customer
        $query->when($this->data['customer'] !== 'all', function ($q) {
            $q->where('c.id', $this->data['customer']);
        });

        // 🔥 filter no faktur
        $query->when(!empty($this->data['no_faktur']), function ($q) {
            $q->where('fp.no_perusahaan', $this->data['no_faktur']);
        });

        // 🔥 filter sales
        $query->when($this->data['sales'] !== 'all', function ($q) {
            $q->where('pp.sales_id', $this->data['sales']);
        });

        // 🔥 filter status
        $query->when($this->data['status'] !== 'all', function ($q) {
            $q->where('p.status', $this->data['status']);
        });

        // 🔥 ambil data
        $datafilter = $query->select(
            'c.nama as nama_customer',            
            'fp.kode as kode_fp',
            'fp.no_perusahaan',
            'fp.tanggal as tanggal_faktur',
            's.nama as nama_sales',
            'p.*'
        )
            ->orderBy('c.nama')
            ->get();       

        // 🔥 return view
        return view('laporan.hutangpiutang.export.piutang', [
            'hutang' => $datafilter            
        ]);
    }
}
