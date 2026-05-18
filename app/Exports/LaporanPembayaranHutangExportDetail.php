<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanPembayaranHutangExportDetail implements FromView
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        // tanggal pasti ada
        $tgl1 = Carbon::parse($this->data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($this->data['tgl2'])->format('Y-m-d');

        // base query
        $query = DB::table('pembayaran_hutangs as ph')
            ->join('hutangs as h', 'h.id', '=', 'ph.hutang_id')
            ->join('pesanan_pembelians as pp', 'h.pesanan_pembelian_id', '=', 'pp.id')
            ->join('banks as b', 'ph.bank_id', '=', 'b.id')
            ->join('penerimaan_barangs as pb', 'h.penerimaan_barang_id', '=', 'pb.id')
            ->join('suppliers as s', 'h.supplier_id', '=', 's.id')
            ->join('faktur_pembelians as fb', 'h.faktur_pembelian_id', '=', 'fb.id')

            // 🔥 tanggal langsung pakai range
            ->whereBetween('ph.tanggal', [$tgl1, $tgl2])
            ->where('pp.deleted_at',null);;

        // 🔥 supplier
        if ($this->data['supplier'] !== 'all') {
            $query->where('s.id', $this->data['supplier']);
        }

        // 🔥 no faktur (opsional)
        if (!empty($this->data['no_faktur'])) {
            $query->where('fb.kode', $this->data['no_faktur']);
        }

        // 🔥 ambil data
        $datafilter = $query->select(
            's.nama as nama_supplier',
            'pp.kode as kode_pp',
            'pb.kode as kode_pb',
            'fb.kode as kode_fp',
            'pp.no_so',
            'pp.no_so_customer',
            'fb.no_faktur_supplier',
            'h.*',
            'b.nama as nama_bank',
            'ph.nominal as nominal_pembayaran',
            'ph.tanggal as tanggal_pembayaran',
            'ph.keterangan as keterangan_pembayaran'
        )->get();

        // dd($datafilter);

        // 🔥 return view export
        return view('laporan.pembayaran.export.exportPembayaranHutangDetail', [            
            'hutang' => $datafilter,
        ]);
    }
}
