<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanHutangExport implements FromView
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $totalhutang = 0;
        $title = 'Laporan Pembayaran Hutang';

        // tanggal
        $tgl1 = Carbon::parse($this->data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($this->data['tgl2'])->format('Y-m-d');            

        // base query
        $query = DB::table('hutangs as h')
            ->join('pesanan_pembelians as pp', 'h.pesanan_pembelian_id', '=', 'pp.id')
            ->join('penerimaan_barangs as pb', 'h.penerimaan_barang_id', '=', 'pb.id')
            ->join('suppliers as s', 'h.supplier_id', '=', 's.id')
            ->join('faktur_pembelians as fb', 'h.faktur_pembelian_id', '=', 'fb.id')

            // filter tanggal
            ->whereBetween('h.tanggal_top', [$tgl1, $tgl2]);

        // 🔥 filter supplier
        $query->when($this->data['supplier'] !== 'all', function ($q) {
            $q->where('s.id', $this->data['supplier']);
        });

        // 🔥 filter no faktur
        $query->when(!empty($this->data['no_faktur']), function ($q) {
            $q->where('fb.kode', $this->data['no_faktur']);
        });

        // 🔥 filter status
        $query->when($this->data['status'] !== 'all', function ($q) {
            $q->where('h.status', $this->data['status']);
        });

        // 🔥 ambil data
        $datafilter = $query->select(
            's.nama as nama_supplier',
            'pp.kode as kode_pp',
            'pb.kode as kode_pb',
            'fb.kode as kode_fp',
            'fb.no_faktur_supplier',
            'pp.no_so_customer',
            'pp.no_so',
            'h.*'
        )->get();      

        return view('laporan.hutangpiutang.export.hutang', [
            'title' => $title,
            'hutang' => $datafilter,
        ]);
    }
}
