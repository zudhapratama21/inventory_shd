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
        $totalpiutang = 0;

        $title = 'Laporan Pembayaran Hutang';

        $tgl1 = Carbon::parse($this->data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($this->data['tgl2'])->format('Y-m-d');

        $pembayaran = DB::table('piutangs as p')
            ->join('pesanan_penjualans as pp', 'p.pesanan_penjualan_id', '=', 'pp.id')
            ->join('pengiriman_barangs as pb', 'p.pengiriman_barang_id', '=', 'pb.id')
            ->join('faktur_penjualans as fp', 'p.faktur_penjualan_id', '=', 'fp.id')
            ->join('customers as c', 'p.customer_id', '=', 'c.id');


        if ($this->data['tgl1']) {
            if (!$this->data['tgl2']) {
                $tanggalFilter = $pembayaran->where('fp.tanggal', '>=', $tgl1);
            } else {
                $tanggalFilter = $pembayaran->where('fp.tanggal', '>=', $tgl1)
                    ->where('fp.tanggal', '<=', $tgl2);
            }
        } elseif ($this->data['tgl2']) {
            if (!$this->data['tgl1']) {
                $tanggalFilter = $pembayaran->where('fp.tanggal', '<=', $tgl2);
            } else {
                $tanggalFilter = $pembayaran->where('fp.tanggal', '>=', $tgl1)
                    ->where('fp.tanggal', '<=', $tgl2);
            }
        } else {
            $tanggalFilter = $pembayaran;
        }

        if ($this->data['customer'] == 'all') {

            $customerfilter = $tanggalFilter;

            if ($this->data['no_faktur'] <> null) {
                $filter =  $customerfilter->where('fb.kode', '=', $this->data['no_faktur']);

                if ($this->data['sales'] == 'all') {
                    $salesfilter = $filter->join('sales as s', 'pp.sales_id', '=', 's.id');
                } else {
                    $salesfilter = $filter->join('sales as s', 'pp.sales_id', '=', 's.id')
                        ->where('pp.sales_id', '=', $this->data['sales']);
                }
            } else {
                $filter =  $customerfilter;

                if ($this->data['sales'] == 'all') {
                    $salesfilter = $filter->join('sales as s', 'pp.sales_id', '=', 's.id');
                } else {
                    $salesfilter = $filter->join('sales as s', 'pp.sales_id', '=', 's.id')
                        ->where('pp.sales_id', '=', $this->data['sales']);
                }
            }
        } else {
            $customerfilter = $pembayaran->where('c.id', '=', $this->data['customer']);

            if ($this->data['no_faktur'] <> null) {
                $filter =  $customerfilter->where('fb.kode', '=', $this->data['no_faktur']);

                if ($this->data['sales'] == 'all') {
                    $salesfilter = $filter->join('sales as s', 'pp.sales_id', '=', 's.id');
                } else {
                    $salesfilter = $filter->join('sales as s', 'pp.sales_id', '=', 's.id')
                        ->where('pp.sales_id', '=', $this->data['sales']);
                }
            } else {
                $filter =  $customerfilter;

                if ($this->data['sales'] == 'all') {
                    $salesfilter = $filter->join('sales as s', 'pp.sales_id', '=', 's.id');
                } else {
                    $salesfilter = $filter->join('sales as s', 'pp.sales_id', '=', 's.id')
                        ->where('pp.sales_id', '=', $this->data['sales']);
                }
            }
        }


        $statusFilter = $salesfilter->where('status', '=', $this->data['status']);

        $datafilter = $statusFilter->select(
            'c.nama as nama_customer',
            'pp.kode as kode_pp',
            'pb.kode as kode_pb',
            'fp.kode as kode_fp',
            'fp.no_kpa as no_kpa',
            'fp.tanggal as tanggal_faktur',
            'p.*',
            's.nama as nama_sales',            
        )
            ->orderBy('c.nama')
            ->get();

        // dd($datafilter);
        foreach ($datafilter as $key) {
            $totalpiutang = $totalpiutang + $key->total - $key->dibayar;
        }

        return view('laporan.hutangpiutang.export.piutang', [
            'hutang' => $datafilter,
            'totalpiutang' => $totalpiutang
        ]);
    }
}
