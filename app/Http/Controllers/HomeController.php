<?php

namespace App\Http\Controllers;

use App\Exports\TopCustomerExport;
use App\Exports\TopProductExport;
use App\Models\Customer;
use App\Models\HRD\Pengumuman;
use App\Models\Kategoripesanan;
use App\Models\Merk;
use App\Models\Product;
use App\Models\Sales;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    public function index()
    {
        $kategori = Kategoripesanan::get();
        $produk = Product::get();
        $customer = Customer::get();
        $supplier = Supplier::get();
        $merk = Merk::get();
        $sales = Sales::get();

        $months =  [];
        for ($i = 1; $i <= 12; $i++) {
            $databulan = '1-' . $i . '-2023';
            $months[] = [
                'nama' => Carbon::parse($databulan)->format('F'),
                'id' => $i
            ];
        }

        $pengumuman = Pengumuman::with('topic', 'pembuat')->latest()->first();

        return view('home', [
            'kategori' => $kategori,
            'bulan' => $months,
            'produk' => $produk,
            'sales' => $sales,
            'supplier' => $supplier,
            'customer' => $customer,
            'merk' => $merk,
            'pengumuman' => $pengumuman
        ]);
    }

    public function datatablePengumuman(Request $request) {}


    public function chartyear(Request $request)
    {
        $results = DB::table('faktur_penjualans as fp')
            ->join('faktur_penjualan_details as fdp', 'fdp.faktur_penjualan_id', '=', 'fp.id')
            ->join('pesanan_penjualans as pp', 'fp.pesanan_penjualan_id', '=', 'pp.id')
            ->join('products as p', 'fdp.product_id', '=', 'p.id')
            ->join('merks as m', 'm.id', '=', 'p.merk_id')
            ->join('suppliers as s', 's.id', '=', 'm.supplier_id')
            ->where('fp.deleted_at', '=', null)
            ->orderBy('fp.tanggal');


        if ($request->year) {
            $res = $results->whereYear('fp.tanggal', $request->year);
        } else {
            $res = $results;
        }


        if ($request->kategori !== 'All') {
            $kategori = $res->where('pp.kategoripesanan_id', $request->kategori);
        } else {
            $kategori = $res;
        }


        if ($request->principlegrafik !== 'All') {
            $principle = $kategori->where('m.supplier_id', $request->principlegrafik);
        } else {
            $principle = $kategori;
        }

        if ($request->customergrafik !== 'All') {
            $customer = $principle->where('pp.customer_id', $request->customergrafik);
        } else {
            $customer = $principle;
        }

        if ($request->merkgrafik !== 'All') {
            $merk = $customer->where('p.merk_id', $request->merkgrafik);
        } else {
            $merk = $customer;
        }

        if ($request->salesgrafik !== 'All') {
            $sales = $merk->where('pp.sales_id', $request->salesgrafik);
        } else {
            $sales = $merk;
        }

        $bulan = $sales;
        $tipe = $bulan
            ->groupBy(DB::raw("DATE_FORMAT(fp.tanggal, '%m-%Y')"))
            ->select(
                DB::raw("DATE_FORMAT(fp.tanggal, '%m') as tanggal_penjualan"),
                DB::raw("sum(fdp.total) as grandtotal_penjualan"),
                DB::raw("sum(fdp.cn_total) as total_cn"),
                DB::raw("sum(fdp.ongkir) as total_ongkir"),
            );

        $hasil = $tipe->get();


        $laba = array();
        $data = [];
        $grandtotal = 0;

        foreach ($hasil as $key => $value) {
            $data[(int)$value->tanggal_penjualan] = [
                'grandtotal' => $value->grandtotal_penjualan - $value->total_cn
            ];

            $grandtotal += ($value->grandtotal_penjualan - $value->total_cn);
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
            'bulan' => $months,
            'total_penjualan' => number_format($grandtotal, 0, ',', '.')
        ]);
    }

    public function chartkategori(Request $request)
    {
        $results = DB::table('faktur_penjualans as fp')
            ->join('pesanan_penjualans as pp', 'fp.pesanan_penjualan_id', '=', 'pp.id')
            ->join('kategoripesanans as kp', 'pp.kategoripesanan_id', '=', 'kp.id')
            ->where('fp.deleted_at', '=', null);

        if ($request->year) {
            $res = $results->whereYear('fp.tanggal', $request->year);
        } else {
            $res = $results;
        }



        $hasil = $res->select(
            'kp.nama as kategori',
            DB::raw("sum(fp.grandtotal) as grandtotal_penjualan"),
            DB::raw("sum(fp.ppn) as ppn_penjualan"),
            DB::raw("sum(fp.total_cn) as total_cn"),
            DB::raw("sum(fp.ongkir) as total_ongkir")
        )
            ->groupBy('pp.kategoripesanan_id')
            ->get();


        foreach ($hasil as  $value) {
            $kategori[] = $value->kategori;
            $penjualan[] = (int) ($value->grandtotal_penjualan - $value->ppn_penjualan - $value->total_cn - $value->total_ongkir);
        }



        return response()->json([
            'datakategori' => $kategori,
            'datapenjualan' => $penjualan
        ]);
    }


    public function grafikProduk(Request $request)
    {

        $results = DB::table('faktur_penjualans as fp')
            ->join('faktur_penjualan_details as fdp', 'fdp.faktur_penjualan_id', '=', 'fp.id')
            ->join('pesanan_penjualans as pp', 'fp.pesanan_penjualan_id', '=', 'pp.id')
            ->join('products as p', 'fdp.product_id', '=', 'p.id')
            ->where('fp.deleted_at', '=', null);

        if ($request->year) {
            $res = $results->whereYear('fp.tanggal', $request->year);
        } else {
            $res = $results;
        }

        if ($request->produk) {
            $productFilter = $res->where('fdp.product_id', $request->produk);
        } else {
            $productFilter = $res;
        }

        $hasil = $productFilter
            ->groupBy('fdp.product_id')
            ->groupBy(DB::raw("DATE_FORMAT(fp.tanggal, '%m-%Y')"))
            ->select(
                'p.nama',
                'p.id',
                DB::raw("DATE_FORMAT(fp.tanggal, '%m') as tanggal_penjualan"),
                DB::raw("sum(fdp.qty) as stok_produk")
            )
            ->get();

        foreach ($hasil as $key => $value) {
            $data[(int)$value->tanggal_penjualan] = [
                'stok' => (int)$value->stok_produk
            ];
        }

        for ($i = 0; $i <= 12; $i++) {
            if ($i == 0) {
                $stok[] = 0;
            } else {
                if (!empty($data[$i])) {
                    $stok[] = $data[$i]['stok'];
                } else {
                    $stok[] = 0;
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
            'stok' => $stok,
            'bulan' => $months
        ]);
    }


    public function grafikPenjualanProdukTerbaik(Request $request)
    {
        $results = DB::table('faktur_penjualans as fp')
            ->join('faktur_penjualan_details as fdp', 'fdp.faktur_penjualan_id', '=', 'fp.id')
            ->join('pesanan_penjualans as pp', 'fp.pesanan_penjualan_id', '=', 'pp.id')
            ->join('products as p', 'fdp.product_id', '=', 'p.id')
            ->join('merks as m', 'm.id', '=', 'p.merk_id')
            ->join('suppliers as s', 's.id', '=', 'm.supplier_id')
            ->where('fp.deleted_at', '=', null)
            ->orderBy('fp.tanggal');

        if ($request->year) {
            $res = $results->whereYear('fp.tanggal', $request->year);
        } else {
            $res = $results;
        }

        if ($request->bulan !== 'All') {
            $bulan = $res->whereMonth('fp.tanggal', $request->bulan)
                ->groupBy(DB::raw("DATE_FORMAT(fp.tanggal, '%m-%Y')"));
        } else {
            $bulan = $res;
        }

        if ($request->kategori !== 'All') {
            $kategori = $bulan->where('pp.kategoripesanan_id', $request->kategori);
        } else {
            $kategori = $bulan;
        }

        if ($request->sales !== 'All') {
            $sales = $kategori->where('pp.sales_id', $request->sales);
        } else {
            $sales = $kategori;
        }

        if ($request->merk !== 'All') {
            $merk = $sales->where('p.merk_id', $request->merk);
        } else {
            $merk = $sales;
        }




        $hasil = $merk
            ->groupBy('fdp.product_id')
            ->select(
                'p.nama',
                'p.id',
                'p.kode',
                DB::raw("DATE_FORMAT(fp.tanggal, '%m') as tanggal_penjualan"),
                DB::raw("DATE_FORMAT(fp.tanggal, '%Y') as tahun_penjualan"),
                DB::raw("sum(fdp.qty) as stok_produk"),
                DB::raw("sum(fdp.total) as total_penjualan"),
                DB::raw("sum(fdp.cn_total) as total_cn")
            )
            ->get();

        $count = count($hasil);

        $tmp = null;

        if ($count > 0) {
            if ($request->tipe == 'stok') {
                for ($i = 0; $i < $count - 1; $i++) {
                    for ($j = $i + 1; $j < $count; $j++) {
                        if ($hasil[$i]->stok_produk < $hasil[$j]->stok_produk) {
                            $tmp = $hasil[$i];
                            $hasil[$i] = $hasil[$j];
                            $hasil[$j] = $tmp;
                        }
                    }
                }
            } else {
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
        }

        $data = $hasil;
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('tanggal', function ($data) {
                return $data->tanggal_penjualan . '-' . $data->tahun_penjualan;
            })
            ->editColumn('total', function ($data) {
                return 'Rp.' . number_format($data->total_penjualan - $data->total_cn, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                $product_id =  $row->id;
                return view('partial.button', compact('product_id'));
            })
            ->make(true);
    }


    public function listCustomer(Request $request)
    {
        $results = DB::table('faktur_penjualans as fp')
            ->join('faktur_penjualan_details as fdp', 'fdp.faktur_penjualan_id', '=', 'fp.id')
            ->join('pesanan_penjualans as pp', 'fp.pesanan_penjualan_id', '=', 'pp.id')
            ->join('customers as c', 'fp.customer_id', '=', 'c.id')
            ->join('products as p', 'fdp.product_id', '=', 'p.id')
            ->join('merks as m', 'm.id', '=', 'p.merk_id')
            ->join('suppliers as s', 's.id', '=', 'm.supplier_id')
            ->where('fp.deleted_at', '=', null)
            ->where('fdp.product_id', $request->product_id);

        if ($request->year) {
            $res = $results->whereYear('fp.tanggal', $request->year);
        } else {
            $res = $results;
        }

        if ($request->bulan !== 'All') {
            $bulan = $res->whereMonth('fp.tanggal', $request->bulan)
                ->groupBy(DB::raw("DATE_FORMAT(fp.tanggal, '%m-%Y')"));
        } else {
            $bulan = $res;
        }

        if ($request->kategori !== 'All') {
            $kategori = $bulan->where('pp.kategoripesanan_id', $request->kategori);
        } else {
            $kategori = $bulan;
        }

        if ($request->sales !== 'All') {
            $sales = $kategori->where('pp.sales_id', $request->sales);
        } else {
            $sales = $kategori;
        }

        if ($request->merk !== 'All') {
            $merk = $sales->where('p.merk_id', $request->merk);
        } else {
            $merk = $sales;
        }


        $hasil = $merk
            ->groupBy('fp.customer_id')
            ->select(
                'c.nama',
                'c.id',
                DB::raw("DATE_FORMAT(fp.tanggal, '%m') as tanggal_penjualan"),
                DB::raw("DATE_FORMAT(fp.tanggal, '%Y') as tahun_penjualan"),
                DB::raw("sum(fdp.qty) as stok_produk"),
                DB::raw("sum(fdp.total) as total_penjualan"),
                DB::raw("sum(fdp.cn_total) as total_cn")
            )
            ->get();


        $count = count($hasil);
        $tmp = null;

        if ($count > 0) {
            if ($request->tipe == 'stok') {
                for ($i = 0; $i < $count - 1; $i++) {
                    for ($j = $i + 1; $j < $count; $j++) {
                        if ($hasil[$i]->stok_produk < $hasil[$j]->stok_produk) {
                            $tmp = $hasil[$i];
                            $hasil[$i] = $hasil[$j];
                            $hasil[$j] = $tmp;
                        }
                    }
                }
            } else {
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
        }

        $data = $hasil;
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('total', function ($data) {
                return 'Rp.' . number_format($data->total_penjualan - $data->total_cn, 0, ',', '.');
            })
            ->make(true);
    }

    public function datatableTopCustomer(Request $request)
    {
        $results = DB::table('faktur_penjualans as fp')
            ->join('faktur_penjualan_details as fdp', 'fdp.faktur_penjualan_id', '=', 'fp.id')
            ->join('pesanan_penjualans as pp', 'fp.pesanan_penjualan_id', '=', 'pp.id')
            ->join('products as p', 'fdp.product_id', '=', 'p.id')
            ->join('customers as c', 'fp.customer_id', '=', 'c.id')
            ->where('fp.deleted_at', '=', null);

        if ($request->year) {
            $res = $results->whereYear('fp.tanggal', $request->year);
        } else {
            $res = $results;
        }

        if ($request->bulan !== 'All') {
            $bulan = $res->whereMonth('fp.tanggal', $request->bulan)
                ->groupBy(DB::raw("DATE_FORMAT(fp.tanggal, '%m-%Y')"));
        } else {
            $bulan = $res;
        }

        if ($request->kategori !== 'All') {
            $kategori = $bulan->where('pp.kategoripesanan_id', $request->kategori);
        } else {
            $kategori = $bulan;
        }

        if ($request->sales !== 'All') {
            $sales = $kategori->where('pp.sales_id', $request->sales);
        } else {
            $sales = $kategori;
        }

        $hasil = $sales
            ->groupBy('fp.customer_id')
            ->select(
                'c.nama',
                'c.id',
                'c.kode',
                DB::raw("DATE_FORMAT(fp.tanggal, '%m') as tanggal_penjualan"),
                DB::raw("DATE_FORMAT(fp.tanggal, '%Y') as tahun_penjualan"),
                DB::raw("sum(fdp.qty) as stok_produk"),
                DB::raw("sum(fdp.total) as total_penjualan"),
                DB::raw("sum(fdp.cn_total) as total_cn")
            )
            ->get();

        $count = count($hasil);
        $tmp = null;

        if ($count > 0) {
            if ($request->tipe == 'stok') {
                for ($i = 0; $i < $count - 1; $i++) {
                    for ($j = $i + 1; $j < $count; $j++) {
                        if ($hasil[$i]->stok_produk < $hasil[$j]->stok_produk) {
                            $tmp = $hasil[$i];
                            $hasil[$i] = $hasil[$j];
                            $hasil[$j] = $tmp;
                        }
                    }
                }
            } else {
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
        }

        $data = $hasil;
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('tanggal', function ($data) {
                return $data->tanggal_penjualan . '-' . $data->tahun_penjualan;
            })
            ->editColumn('total', function ($data) {
                return 'Rp.' . number_format($data->total_penjualan - $data->total_cn, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                $customer_id =  $row->id;
                return view('partial.buttontopcustomer', compact('customer_id'));
            })
            ->make(true);
    }

    public function listProduct(Request $request)
    {
        $results = DB::table('faktur_penjualans as fp')
            ->join('faktur_penjualan_details as fdp', 'fdp.faktur_penjualan_id', '=', 'fp.id')
            ->join('pesanan_penjualans as pp', 'fp.pesanan_penjualan_id', '=', 'pp.id')
            ->join('products as p', 'fdp.product_id', '=', 'p.id')
            ->join('merks as m', 'm.id', '=', 'p.merk_id')
            ->join('suppliers as s', 's.id', '=', 'm.supplier_id')
            ->where('fp.deleted_at', '=', null)
            ->orderBy('fp.tanggal');

        if ($request->year) {
            $res = $results->whereYear('fp.tanggal', $request->year);
        } else {
            $res = $results;
        }

        if ($request->bulan !== 'All') {
            $bulan = $res->whereMonth('fp.tanggal', $request->bulan)
                ->groupBy(DB::raw("DATE_FORMAT(fp.tanggal, '%m-%Y')"));
        } else {
            $bulan = $res;
        }

        if ($request->kategori !== 'All') {
            $kategori = $bulan->where('pp.kategoripesanan_id', $request->kategori);
        } else {
            $kategori = $bulan;
        }

        if ($request->sales !== 'All') {
            $sales = $kategori->where('pp.sales_id', $request->sales);
        } else {
            $sales = $kategori;
        }

        $hasil = $sales
            ->where('fp.customer_id', $request->customer)
            ->groupBy('fdp.product_id')
            ->select(
                'p.nama',
                'p.id',
                'p.kode',
                'm.nama as nama_merk',
                DB::raw("DATE_FORMAT(fp.tanggal, '%m') as tanggal_penjualan"),
                DB::raw("DATE_FORMAT(fp.tanggal, '%Y') as tahun_penjualan"),
                DB::raw("sum(fdp.qty) as stok_produk"),
                DB::raw("sum(fdp.total) as total_penjualan"),
                DB::raw("sum(fdp.cn_total) as total_cn")
            )
            ->get();

        $count = count($hasil);
        $tmp = null;

        if ($count > 0) {
            if ($request->tipe == 'stok') {
                for ($i = 0; $i < $count - 1; $i++) {
                    for ($j = $i + 1; $j < $count; $j++) {
                        if ($hasil[$i]->stok_produk < $hasil[$j]->stok_produk) {
                            $tmp = $hasil[$i];
                            $hasil[$i] = $hasil[$j];
                            $hasil[$j] = $tmp;
                        }
                    }
                }
            } else {
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
        }

        $data = $hasil;
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('tanggal', function ($data) {
                return $data->tanggal_penjualan . '-' . $data->tahun_penjualan;
            })
            ->editColumn('total', function ($data) {
                return 'Rp.' . number_format($data->total_penjualan - $data->total_cn, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                $customer_id =  $row->id;
                return view('partial.buttontopcustomer', compact('customer_id'));
            })
            ->make(true);
    }

    public function exportTopProduk(Request $request)
    {
        $data = $request->all();
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new TopProductExport($data), 'LAPORANTOPPRODUCT-' . $now . '.xlsx');
    }

    public function exportTopCustomer(Request $request)
    {
        $data = $request->all();
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new TopCustomerExport($data), 'LAPORANTOPCUSTOMER-' . $now . '.xlsx');
    }

    public function DatatableTopPrinciple(Request $request)
    {
        $results = DB::table('faktur_penjualans as fp')
            ->join('faktur_penjualan_details as fdp', 'fdp.faktur_penjualan_id', '=', 'fp.id')
            ->join('pesanan_penjualans as pp', 'fp.pesanan_penjualan_id', '=', 'pp.id')
            ->join('products as p', 'fdp.product_id', '=', 'p.id')
            ->join('merks as m', 'm.id', '=', 'p.merk_id')
            ->join('suppliers as s', 's.id', '=', 'm.supplier_id')
            ->where('fp.deleted_at', '=', null);

        if ($request->year) {
            $res = $results->whereYear('fp.tanggal', $request->year);
        } else {
            $res = $results;
        }

        if ($request->bulan !== 'All') {
            $bulan = $res->whereMonth('fp.tanggal', $request->bulan)
                ->groupBy(DB::raw("DATE_FORMAT(fp.tanggal, '%m-%Y')"));
        } else {
            $bulan = $res;
        }

        if ($request->kategori !== 'All') {
            $kategori = $bulan->where('pp.kategoripesanan_id', $request->kategori);
        } else {
            $kategori = $bulan;
        }

        if ($request->sales !== 'All') {
            $sales = $kategori->where('pp.sales_id', $request->sales);
        } else {
            $sales = $kategori;
        }

        $hasil = $sales
            ->groupBy('s.id')
            ->select(
                's.nama as nama_supplier',
                's.id as supplier_id',
                'p.id',
                'p.kode',
                DB::raw("DATE_FORMAT(fp.tanggal, '%m') as tanggal_penjualan"),
                DB::raw("DATE_FORMAT(fp.tanggal, '%Y') as tahun_penjualan"),
                DB::raw("sum(fdp.qty) as stok_produk"),
                DB::raw("sum(fdp.total) as total_penjualan"),
                DB::raw("sum(fdp.cn_total) as total_cn")
            )
            ->get();

        $count = count($hasil);

        $tmp = null;

        if ($count > 0) {
            if ($request->tipe == 'stok') {
                for ($i = 0; $i < $count - 1; $i++) {
                    for ($j = $i + 1; $j < $count; $j++) {
                        if ($hasil[$i]->stok_produk < $hasil[$j]->stok_produk) {
                            $tmp = $hasil[$i];
                            $hasil[$i] = $hasil[$j];
                            $hasil[$j] = $tmp;
                        }
                    }
                }
            } else {
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
        }

        $data = $hasil;
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('tanggal', function ($data) {
                return $data->tanggal_penjualan . '-' . $data->tahun_penjualan;
            })
            ->editColumn('total', function ($data) {
                return 'Rp.' . number_format($data->total_penjualan - $data->total_cn, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                $product_id =  $row->supplier_id;
                return view('partial.buttontopprinciple', compact('product_id'));
            })
            ->make(true);
    }

    public function datatableProdukbyPrinciple(Request $request)
    {

        // dd($request->all());
        $results = DB::table('faktur_penjualans as fp')
            ->join('faktur_penjualan_details as fdp', 'fdp.faktur_penjualan_id', '=', 'fp.id')
            ->join('pesanan_penjualans as pp', 'fp.pesanan_penjualan_id', '=', 'pp.id')
            ->join('products as p', 'fdp.product_id', '=', 'p.id')
            ->join('merks as m', 'm.id', '=', 'p.merk_id')
            ->join('suppliers as s', 's.id', '=', 'm.supplier_id')
            ->where('fp.deleted_at', '=', null);

        if ($request->year) {
            $res = $results->whereYear('fp.tanggal', $request->year);
        } else {
            $res = $results;
        }

        if ($request->bulan !== 'All') {
            $bulan = $res->whereMonth('fp.tanggal', $request->bulan)
                ->groupBy(DB::raw("DATE_FORMAT(fp.tanggal, '%m-%Y')"));
        } else {
            $bulan = $res;
        }

        if ($request->kategori !== 'All') {
            $kategori = $bulan->where('pp.kategoripesanan_id', $request->kategori);
        } else {
            $kategori = $bulan;
        }

        if ($request->sales !== 'All') {
            $sales = $kategori->where('pp.sales_id', $request->sales);
        } else {
            $sales = $kategori;
        }

        $hasil = $sales
            ->where('m.supplier_id', $request->supplier)
            ->groupBy('p.id')
            ->select(
                'p.nama as nama_produk',
                'p.id',
                'p.kode',
                'm.nama as nama_merek',
                DB::raw("DATE_FORMAT(fp.tanggal, '%m') as tanggal_penjualan"),
                DB::raw("DATE_FORMAT(fp.tanggal, '%Y') as tahun_penjualan"),
                DB::raw("sum(fdp.qty) as stok_produk"),
                DB::raw("sum(fdp.total) as total_penjualan"),
                DB::raw("sum(fdp.cn_total) as total_cn")
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

        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('tanggal', function ($data) {
                return $data->tanggal_penjualan . '-' . $data->tahun_penjualan;
            })
            ->editColumn('total', function ($data) {
                return 'Rp.' . number_format($data->total_penjualan - $data->total_cn, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                $product_id =  $row->id;
                return view('partial.button', compact('product_id'));
            })
            ->make(true);
    }
}
