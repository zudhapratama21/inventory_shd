<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\FakturPenjualan;
use App\Models\Merk;
use App\Models\Sales;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LaporanLabaRugiController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:laporanlabarugi-list');
        $this->middleware('permission:laporanlabarugi-print', ['only' => ['print']]);
        $this->middleware('permission:laporanlabarugi-show', ['only' => ['show']]);
    }

    public function index()
    {
        $title = "Laporan Laba & Rugi";
        $supplier = Supplier::with('namakota')->get();
        $merk = Merk::get();
        $sales = Sales::get();
        for ($i = 1; $i <= 12; $i++) {
            $databulan = '1-' . $i . '-2023';
            $months[] = [
                'nama' => Carbon::parse($databulan)->format('F'),
                'id' => $i
            ];
        }

        return view('laporan.labarugi.index', compact('title', 'supplier', 'merk', 'months', 'sales'));
    }


    public function datatable(Request $request)
    {

        $results = DB::table('faktur_penjualan_details as fpd')
                        ->join('faktur_penjualans as fp', 'fpd.faktur_penjualan_id', '=', 'fp.id')
                        ->join('pesanan_penjualans as pp', 'fp.pesanan_penjualan_id', '=', 'pp.id')
                        ->join('products as p', 'p.id', '=', 'fpd.product_id')
                        ->join('merks as m', 'p.merk_id', '=', 'm.id')
                        ->join('suppliers as s', 'm.supplier_id', '=', 's.id')
                        ->join('customers as c', 'fp.customer_id', '=', 'c.id')
                        ->where('fpd.deleted_at', '=', null)
                        ->orderBy('fp.tanggal');

        if ($request->year) {
            $res = $results->whereYear('fp.tanggal', $request->year);
        } else {
            $res = $results;
        }

        if ($request->principle !== 'All') {
            $principle = $res->where('m.supplier_id', $request->principle);
        } else {
            $principle = $res;
        }

        if ($request->merk !== 'All') {
            $merk = $principle->where('m.id', $request->merk);
        } else {
            $merk = $principle;
        }

        if ($request->sales !== 'All') {
            $sales = $merk->where('pp.sales_id', $request->sales);
        } else {
            $sales = $merk;
        }

        $data = $sales->select(
                    'c.nama as nama_customer',
                    'p.nama as nama_product',
                    'fp.tanggal as tanggal_penjualan',
                    'fpd.total as total_penjualan',
                    'fpd.qty as qty_barang',
                    'fpd.cn_total as cn_total',
                )->get();

            
        $count = count($data);
        $tmp = null;

        for ($i = 0; $i < $count - 1; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $awal = $data[$i]->total_penjualan - ($data[$i]->cn_total ? $data[$i]->cn_total : 0);
                $akhir = $data[$j]->total_penjualan - ($data[$j]->cn_total ? $data[$j]->cn_total : 0);

                if ($awal < $akhir) {
                    $tmp = $data[$i];
                    $data[$i] = $data[$j];
                    $data[$j] = $tmp;
                }
            }
        }

        $hasil = $data;
        return DataTables::of($hasil)
            ->addIndexColumn()
            ->editColumn('tanggal', function ($k) {
                return $k->tanggal_penjualan;
            })
            ->editColumn('customer', function ($k) {
                return $k->nama_customer;
            })
            ->editColumn('product', function ($k) {
                return $k->nama_product;
            })
            ->editColumn('total', function ($k) {
                return 'Rp.' . number_format($k->total_penjualan - $k->cn_total, 0, ',', '.');
            })
            ->make(true);
    }


    public function chartprinciple(Request $request)
    {
        $results = DB::table('faktur_penjualan_details as fpd')
            ->join('faktur_penjualans as fp', 'fpd.faktur_penjualan_id', '=', 'fp.id')
            ->join('pesanan_penjualans as pp', 'fp.pesanan_penjualan_id', '=', 'pp.id')
            ->join('products as p', 'p.id', '=', 'fpd.product_id')
            ->join('merks as m', 'p.merk_id', '=', 'm.id')
            ->join('suppliers as s', 'm.supplier_id', '=', 's.id')
            ->where('fpd.deleted_at', '=', null)
            ->orderBy('fp.tanggal');


        if ($request->year) {
            $res = $results->whereYear('fp.tanggal', $request->year);
        } else {
            $res = $results;
        }

        if ($request->principle !== 'All') {
            $principle = $res->where('m.supplier_id', $request->principle);
        } else {
            $principle = $res;
        }

        if ($request->merk !== 'All') {
            $merk = $principle->where('m.id', $request->merk);
        } else {
            $merk = $principle;
        }

        if ($request->sales !== 'All') {
            $sales = $merk->where('pp.sales_id', $request->sales);
        } else {
            $sales = $merk;
        }

        $tipe = $sales->groupBy(DB::raw("DATE_FORMAT(fp.tanggal, '%m-%Y')"))
            ->select(
                DB::raw("DATE_FORMAT(fp.tanggal, '%m') as tanggal_penjualan"),
                DB::raw("sum(fpd.total) as total_penjualan"),
                DB::raw("sum(fpd.cn_total) as cn_total"),
            );

        $hasil = $tipe->get();

        $laba = array();
        $data = [];

        foreach ($hasil as $key => $value) {
            $data[(int)$value->tanggal_penjualan] = [
                'grandtotal' => $value->total_penjualan - $value->cn_total
            ];
        }


        for ($i = 0; $i <= 12; $i++) {
            if ($i == 0) {
                $laba[] = 0;
            } else {
                if (!empty($data[$i])) {
                    $laba[] = $data[$i]['grandtotal'];
                } else {
                    $laba[] = 0;
                }
            }
        }

        for ($i = 0; $i <= 12; $i++) {
            $databulan = '1-' . $i . '-2023';
            if ($i == 0) {
                $months[] = [0];
            } else {
                $months[] = [
                    Carbon::parse($databulan)->format('F')
                ];
            }
        }

        return response()->json([
            'laba' => $laba,
            'bulan' => $months
        ]);
    }
}
