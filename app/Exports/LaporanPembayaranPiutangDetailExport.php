<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanPembayaranPiutangDetailExport implements FromView
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        // tanggal (anggap selalu ada)
        $tgl1 = Carbon::parse($this->data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($this->data['tgl2'])->format('Y-m-d');

        // base query
        $query = DB::table('piutangs as p')                        
            ->join('pembayaran_piutangs as pps', 'pps.piutang_id', '=', 'p.id')
            ->join('banks as b', 'pps.bank_id', '=', 'b.id')
            ->join('faktur_penjualans as fp', 'p.faktur_penjualan_id', '=', 'fp.id')
            ->join('customers as c', 'fp.customer_id', '=', 'c.id')            
            ->join('sales as s', 'fp.sales_id', '=', 's.id')

            // 🔥 filter tanggal (pakai tanggal pembayaran)
            ->whereBetween('pps.tanggal', [$tgl1, $tgl2])

            ->whereNull('pps.deleted_at'); // kalau ada soft delete

        // 🔥 filter customer
        $query->when($this->data['customer'] !== 'all', function ($q) {
            $q->where('p.customer_id', $this->data['customer']);
        });

        // 🔥 filter no faktur
        $query->when(!empty($this->data['no_faktur']), function ($q) {
            $q->where('fp.kode', $this->data['no_faktur']);
        });

        // 🔥 filter sales
        $query->when($this->data['sales'] !== 'all', function ($q) {
            $q->where('fp.sales_id', $this->data['sales']);
        });

        // 🔥 ambil data
        $datafilter = $query->select(
            'c.nama as nama_customer',                        
            'fp.kode as kode_fp',
            'fp.no_perusahaan as no_perusahaan',
            'p.*',
            's.nama as nama_sales',
            'pps.nominal as nominal_pembayaran',
            'b.nama as nama_bank',
            'pps.keterangan',
            'pps.tanggal as tanggal_pembayaran'
        )->get();

        // 🔥 return
        return view('laporan.pembayaran.export.exportPembayaranPiutangDetail', [
            'hutang' => $datafilter,
        ]);
    }
}
