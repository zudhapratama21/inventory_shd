<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;


class TopProductExport implements FromView
{

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $results = DB::table('faktur_penjualans as fp')
            ->join('faktur_penjualan_details as fdp', 'fdp.faktur_penjualan_id', '=', 'fp.id')
            ->join('pesanan_penjualans as pp', 'fp.pesanan_penjualan_id', '=', 'pp.id')
            ->join('products as p', 'fdp.product_id', '=', 'p.id')
            ->join('customers as c', 'fp.customer_id', '=', 'c.id')
            ->where('fp.deleted_at', '=', null);

        if ($this->data['tahun']) {
            $res = $results->whereYear('fp.tanggal', $this->data['tahun']);
        } else {
            $res = $results;
        }

        if ($this->data['bulan_product'] !== 'all') {
            $bulan = $res->whereMonth('fp.tanggal', $this->data['bulan_product'])
                ->groupBy(DB::raw("DATE_FORMAT(fp.tanggal, '%m-%Y')"));
        } else {
            $bulan = $res;
        }

        if ($this->data['kategori_product'] !== 'all') {
            $kategori = $bulan->where('pp.kategoripesanan_id', $this->data['kategori_product']);
        } else {
            $kategori = $bulan;
        }

        $hasil = $kategori
            // ->groupBy('fp.customer_id')
            ->select(
                'p.nama',
                'p.id',
                'c.nama as nama_customer',
                'fp.tanggal as tanggal_penjualan',
                'fdp.qty as stok_produk',
                'fdp.total as total_penjualan',
                'fdp.cn_total as total_cn'
            )
            ->get();

        $count = count($hasil);

        $tmp = null;

        if ($count > 0) {
            for ($i = 0; $i < $count - 1; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {

                    $awal = $hasil[$i]->total_penjualan - ($hasil[$i]->total_cn ? $hasil[$i]->total_cn : 0);
                    $akhir = $hasil[$j]->total_penjualan - ($hasil[$j]->total_cn ? $hasil[$j]->total_cn : 0);

                    if ($awal < $akhir) {
                        $tmp = $hasil[$i];
                        $hasil[$i] = $hasil[$j];
                        $hasil[$j] = $tmp;
                    }
                }
            }
        }

        $data = $hasil;
        return view('laporan.laporantop.produk', [
            'data' => $data
        ]);
    }
}
