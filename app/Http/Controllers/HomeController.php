<?php

namespace App\Http\Controllers;

use App\Exports\TopCustomerExport;
use App\Exports\TopProductExport;
use App\Models\Customer;
use App\Models\FakturPenjualanDetail;
use App\Models\HargaNonExpired;
use App\Models\HRD\Pengumuman;
use App\Models\Hutang;
use App\Models\Kategoripesanan;
use App\Models\Merk;
use App\Models\PesananPembelian;
use App\Models\PesananPenjualan;
use App\Models\Piutang;
use App\Models\Product;
use App\Models\Sales;
use App\Models\StokExp;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Hapus semua cache
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        $kategori = Kategoripesanan::get();
        $produk = Product::get();
        $customer = Customer::get();
        $supplier = Supplier::get();
        $merk = Merk::get();
        $sales = Sales::get();
        $months =  [];

        $permission = auth()->user()->getAllPermissions();

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
            'pengumuman' => $pengumuman,
            'permission' => $permission
        ]);
    }


    public function chartyear(Request $request)
    {
        $results = DB::table('faktur_penjualans as fp')
            ->where('fp.deleted_at', '=', null)
            ->orderBy('fp.tanggal');


        if ($request->year) {
            $res = $results->whereYear('fp.tanggal', $request->year);
        } else {
            $res = $results;
        }

        if ($request->kategori !== 'All') {
            $kategori = $res->where('fp.kategoripesanan_id', $request->kategori);
        } else {
            $kategori = $res;
        }

        if ($request->customergrafik !== 'All') {
            $customer = $kategori->where('fp.customer_id', $request->customergrafik);
        } else {
            $customer = $kategori;
        }

        if ($request->salesgrafik !== 'All') {
            $sales = $customer->where('fp.sales_id', $request->salesgrafik);
        } else {
            $sales = $customer;
        }

        $bulan = $sales;
        $tipe = $bulan
            ->groupBy(DB::raw("DATE_FORMAT(fp.tanggal, '%m-%Y')"))
            ->select(
                DB::raw("DATE_FORMAT(fp.tanggal, '%m') as tanggal_penjualan"),
                DB::raw("sum(fp.grandtotal) as grandtotal_penjualan"),
            );

        $hasil = $tipe->get();
        $laba = array();
        $data = [];

        $grandtotal = 0;

        foreach ($hasil as $key => $value) {
            $data[(int)$value->tanggal_penjualan] = [
                'grandtotal' => $value->grandtotal_penjualan
            ];

            $grandtotal += ($value->grandtotal_penjualan);
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


    public function grafikPenjualanProdukTerbaik(Request $request)
    {
        $results = DB::table('faktur_penjualans as fp')
            ->join('faktur_penjualan_details as fdp', 'fdp.faktur_penjualan_id', '=', 'fp.id')
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
            $kategori = $bulan->where('fp.kategoripesanan_id', $request->kategori);
        } else {
            $kategori = $bulan;
        }

        if ($request->sales !== 'All') {
            $sales = $kategori->where('fp.sales_id', $request->sales);
        } else {
            $sales = $kategori;
        }

        $hasil = $sales
            ->groupBy('fdp.product_id')
            ->select(
                'p.nama',
                'p.id',
                'p.kode',
                DB::raw("DATE_FORMAT(fp.tanggal, '%m') as tanggal_penjualan"),
                DB::raw("DATE_FORMAT(fp.tanggal, '%Y') as tahun_penjualan"),
                DB::raw("sum(fdp.total) as total_penjualan"),
            )
            ->get();

        $count = count($hasil);

        $tmp = null;

        if ($count > 0) {
            for ($i = 0; $i < $count - 1; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {

                    $awal = $hasil[$i]->total_penjualan;
                    $akhir = $hasil[$j]->total_penjualan;

                    if ($awal < $akhir) {
                        $tmp = $hasil[$i];
                        $hasil[$i] = $hasil[$j];
                        $hasil[$j] = $tmp;
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
                return 'Rp.' . number_format($data->total_penjualan, 0, ',', '.');
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
            $kategori = $bulan->where('fp.kategoripesanan_id', $request->kategori);
        } else {
            $kategori = $bulan;
        }

        if ($request->sales !== 'All') {
            $sales = $kategori->where('fp.sales_id', $request->sales);
        } else {
            $sales = $kategori;
        }


        $hasil = $sales
            ->groupBy('fp.customer_id')
            ->select(
                'c.nama',
                'c.id',
                DB::raw("DATE_FORMAT(fp.tanggal, '%m') as tanggal_penjualan"),
                DB::raw("DATE_FORMAT(fp.tanggal, '%Y') as tahun_penjualan"),
                DB::raw("sum(fdp.total) as total_penjualan"),
            )
            ->get();


        $count = count($hasil);
        $tmp = null;

        if ($count > 0) {
            for ($i = 0; $i < $count - 1; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {
                    $awal = $hasil[$i]->total_penjualan;
                    $akhir = $hasil[$j]->total_penjualan;

                    if ($awal < $akhir) {
                        $tmp = $hasil[$i];
                        $hasil[$i] = $hasil[$j];
                        $hasil[$j] = $tmp;
                    }
                }
            }
        }

        $data = $hasil;
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('total', function ($data) {
                return 'Rp.' . number_format($data->total_penjualan, 0, ',', '.');
            })
            ->make(true);
    }

    public function datatableTopCustomer(Request $request)
    {
        $results = DB::table('faktur_penjualans as fp')
            ->join('faktur_penjualan_details as fdp', 'fdp.faktur_penjualan_id', '=', 'fp.id')
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
            $kategori = $bulan->where('fp.kategoripesanan_id', $request->kategori);
        } else {
            $kategori = $bulan;
        }

        if ($request->sales !== 'All') {
            $sales = $kategori->where('fp.sales_id', $request->sales);
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
                DB::raw("sum(fdp.total) as total_penjualan"),
            )
            ->get();

        $count = count($hasil);
        $tmp = null;

        if ($count > 0) {
            for ($i = 0; $i < $count - 1; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {
                    $awal = $hasil[$i]->total_penjualan;
                    $akhir = $hasil[$j]->total_penjualan;

                    if ($awal < $akhir) {
                        $tmp = $hasil[$i];
                        $hasil[$i] = $hasil[$j];
                        $hasil[$j] = $tmp;
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
                return 'Rp.' . number_format($data->total_penjualan, 0, ',', '.');
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
                DB::raw("sum(fdp.total) as total_penjualan"),
            )
            ->get();

        $count = count($hasil);
        $tmp = null;

        if ($count > 0) {
            for ($i = 0; $i < $count - 1; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {

                    $awal = $hasil[$i]->total_penjualan;
                    $akhir = $hasil[$j]->total_penjualan;

                    if ($awal < $akhir) {
                        $tmp = $hasil[$i];
                        $hasil[$i] = $hasil[$j];
                        $hasil[$j] = $tmp;
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
                return 'Rp.' . number_format($data->total_penjualan, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                $customer_id =  $row->id;
                return view('partial.buttontopcustomer', compact('customer_id'));
            })
            ->make(true);
    }
}
