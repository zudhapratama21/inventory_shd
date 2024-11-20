<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class TopCustomerExport implements FromView
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {

        // dd($this->data);
        $results = DB::table('faktur_penjualans as fp')
            ->join('faktur_penjualan_details as fdp', 'fdp.faktur_penjualan_id', '=', 'fp.id')
            ->join('pesanan_penjualans as pp', 'fp.pesanan_penjualan_id', '=', 'pp.id')
            ->join('products as p', 'fdp.product_id', '=', 'p.id')
            ->join('customers as c', 'fp.customer_id', '=', 'c.id')
            ->where('fp.deleted_at', '=', null);

        if ($this->data['tahun_customer']) {
            $res = $results->whereYear('fp.tanggal', $this->data['tahun_customer']);
        } else {
            $res = $results;
        }

        if ($this->data['bulan_customer'] !== 'all') {
            $bulan = $res->whereMonth('fp.tanggal', $this->data['bulan_customer'])
                ->groupBy(DB::raw("DATE_FORMAT(fp.tanggal, '%m-%Y')"));
        } else {
            $bulan = $res;
        }

        if ($this->data['kategori_customer'] !== 'all') {
            $kategori = $bulan->where('pp.kategoripesanan_id', $this->data['kategori_customer']);
        } else {
            $kategori = $bulan;
        }

        $hasil = $kategori            
            ->select(
                'c.nama',
                'c.id',
                'c.kode',
                'fp.tanggal as tanggal_penjualan',
                'p.nama as nama_produk',
                'fdp.qty as stok_produk',
                'fdp.total as total_penjualan',
                'fdp.cn_total as total_cn',                
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

        return view('laporan.laporantop.customer', [
            'data' => $data
        ]);
    }
}
