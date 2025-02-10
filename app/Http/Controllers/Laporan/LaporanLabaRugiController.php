<?php

namespace App\Http\Controllers\Laporan;

use App\Exports\LabaRugiExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\FakturPenjualan;
use App\Models\FakturPenjualanDetail;
use App\Models\Kategoripesanan;
use App\Models\Merk;
use App\Models\Sales;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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
        $customer = Customer::get();
        $kategori = Kategoripesanan::get();

        for ($i = 1; $i <= 12; $i++) {
            $databulan = '1-' . $i . '-2023';
            $months[] = [
                'nama' => Carbon::parse($databulan)->format('F'),
                'id' => $i
            ];
        }

        return view('laporan.labarugi.index', compact('title', 'supplier', 'merk', 'months', 'sales', 'customer', 'kategori'));
    }


    public function datatable(Request $request)
    {

        $fakturpenjualan = FakturPenjualanDetail::whereHas('fakturpenjualan.SO', function ($q) use ($request) {
            if ($request->sales !== 'All') {
                $q->where('sales_id', $request->sales);
            }
            if ($request->kategori !== 'All') {
                $q->where('kategoripesanan_id', $request->kategori);
            }
        })
            ->with([
                'pengirimanbarangdetail.stokexpdetail',
                'pengirimanbarangdetail.harganonexpireddetail',
                'fakturpenjualan.customers',
                'products',
            ])
            ->whereHas('fakturpenjualan', function ($q) use ($request) {
                $q->whereYear('tanggal', '=', $request->year);
                if ($request->bulan !== 'All') {
                    $q->whereMonth('tanggal', $request->bulan);
                }
            })
            ->get();

        // Array untuk menyimpan laba kotor per customer
        $labaKotorPerCustomer = [];

        foreach ($fakturpenjualan as $item) {
            // Tentukan apakah stoknya expired atau tidak
            $details = $item->products->status_exp == 0
                ? $item->pengirimanbarangdetail->harganonexpireddetail
                : $item->pengirimanbarangdetail->stokexpdetail;

            foreach ($details as $detail) {
                // Hitung subtotal, diskon, HPP, dan penjualan hanya sekali untuk setiap detail
                $subtotal = $detail->qty * $detail->harga_beli * -1;
                $totalDiskon = ($detail->diskon_persen_beli * $subtotal / 100) + $detail->diskon_rupiah_beli;
                $hpp = ($subtotal - $totalDiskon) * 1.11;

                // Penjualan
                $totalJual = $detail->qty * $item->hargajual * -1;
                $subtotalPenjualan = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                $pph = $item->pph ? $subtotalPenjualan * $item->pph / 100 : 0;
                $cn = ($subtotalPenjualan-$pph) * $item->cn_persen / 100;
                $nett = $subtotalPenjualan - $cn - $pph;

                // Agregasi laba kotor per customer menggunakan array
                $customerId = $item->fakturpenjualan->customers->id;

                // Gunakan groupBy untuk menyatukan laba kotor per customer
                if (!isset($labaKotorPerCustomer[$customerId])) {
                    $labaKotorPerCustomer[$customerId] = [
                        'nama' => $item->fakturpenjualan->customers->nama,
                        'id' => $customerId,
                        'laba_kotor' => 0,
                    ];
                }

                // Tambahkan laba kotor pada customer
                $labaKotorPerCustomer[$customerId]['laba_kotor'] += ($nett - $hpp);
            }
        }

        // Mengurutkan berdasarkan laba kotor tertinggi
        $output = array_values($labaKotorPerCustomer);
        usort($output, function ($a, $b) {
            return $b['laba_kotor'] <=> $a['laba_kotor'];
        });

        return DataTables::of($output)
            ->addIndexColumn()
            ->editColumn('nama', function ($k) {
                return $k['nama'];
            })
            ->editColumn('laba_kotor', function ($k) {
                return number_format($k['laba_kotor'], 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                return view('laporan.labarugi.partial._form-action', ['customer_id' => $row['id']]);
            })
            ->make(true);
    }


    public function chartprinciple(Request $request)
    {

        $fakturpenjualan = FakturPenjualanDetail::whereHas('fakturpenjualan.SO', function ($q) use ($request) {
            if ($request->sales !== 'All') {
                $q->where('sales_id', $request->sales);
            }

            if ($request->customer !== 'All') {
                $q->where('customer_id', $request->customer);
            }
        })
            ->whereHas('products.merks', function ($q) use ($request) {
                if ($request->merk !== 'All') {
                    $q->where('id', $request->merk);
                }
                if ($request->principle !== 'All') {
                    $q->where('supplier_id', $request->principle);
                }
            })
            ->with(['pengirimanbarangdetail.stokexpdetail', 'pengirimanbarangdetail.harganonexpireddetail'])
            ->whereHas('fakturpenjualan', function ($q) use ($request) {
                $q->whereYear('tanggal', '=', $request->year);
            })
            ->get();

        $labarugi = collect();
        foreach ($fakturpenjualan as $item) {
            $bulan = ltrim(Carbon::parse($item->fakturpenjualan->tanggal)->format('m'), '0');

            $details = $item->products->status_exp == 0 ? $item->pengirimanbarangdetail->harganonexpireddetail : $item->pengirimanbarangdetail->stokexpdetail;

            foreach ($details as $detail) {
                $subtotal = $detail->qty * $detail->harga_beli * -1;
                $total_diskon = ($detail->diskon_persen_beli * $subtotal / 100) + $detail->diskon_rupiah_beli;
                $hpp = ($subtotal - $total_diskon) * 1.11;

                $totalJual = $detail->qty * $item->hargajual * -1;
                $subtotalPenjualan = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                $pph = $item->pph ? $subtotalPenjualan * $item->pph / 100 : 0;
                $cn = ($subtotalPenjualan-$pph) * $item->cn_persen / 100;
                $nett = $subtotalPenjualan - $cn - $pph;

                $labarugi->push([
                    'tanggal' => $bulan,
                    'laba_kotor' => $nett - $hpp
                ]);
            }
        }

        $laba_kotor_per_bulan = array_fill(0, 13, 0);
        foreach ($labarugi as $item) {
            $laba_kotor_per_bulan[$item['tanggal']] += $item['laba_kotor'];
        }

        ksort($laba_kotor_per_bulan);
        $grandTotal = array_sum($laba_kotor_per_bulan);

        $bulan_nama = collect(range(0, 12))->map(fn($m) => $m === 0 ? 0 : Carbon::create(null, $m, 1)->format('F'))->toArray();

        return response()->json([
            'laba' => $laba_kotor_per_bulan,
            'bulan' => $bulan_nama,
            'grandtotal' => number_format($grandTotal, 0, ',', '.')
        ]);
    }

    public function datatableCustomerProduct(Request $request)
    {
        $fakturpenjualan = FakturPenjualanDetail::whereHas('fakturpenjualan.SO', function ($q) use ($request) {
            if ($request->sales !== 'All') {
                $q->where('sales_id', $request->sales);
            }
            if ($request->kategori !== 'All') {
                $q->where('kategoripesanan_id', $request->kategori);
            }
        })
            ->with(['pengirimanbarangdetail.stokexpdetail', 'pengirimanbarangdetail.harganonexpireddetail'])
            ->whereHas('fakturpenjualan', function ($q) use ($request) {
                $q->whereYear('tanggal', '=', $request->year);
                if ($request->bulan !== 'All') {
                    $q->whereMonth('tanggal', $request->bulan);
                }
                $q->where('customer_id', $request->customer_id);
            })
            ->get();

        $labarugi = collect();
        foreach ($fakturpenjualan as $item) {
            $details = $item->products->status_exp == 0
                ? $item->pengirimanbarangdetail->harganonexpireddetail
                : $item->pengirimanbarangdetail->stokexpdetail;

            foreach ($details as $detail) {
                $subtotal = $detail->qty * $detail->harga_beli * -1;
                $total_diskon = ($detail->diskon_persen_beli * $subtotal / 100) + $detail->diskon_rupiah_beli;
                $hpp = ($subtotal - $total_diskon) * 1.11;

                $totalJual = $detail->qty * $item->hargajual * -1;
                $subtotalPenjualan = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                $pph = $item->pph ? $subtotalPenjualan * $item->pph / 100 : 0;
                $cn = ($subtotalPenjualan-$pph) * $item->cn_persen / 100;
                $nett = $subtotalPenjualan - $cn - $pph;

                $labarugi->push([
                    'no_kpa' => $item->fakturpenjualan->no_kpa,
                    'products' => $item->products->nama,
                    'products_id' => $item->products->id,
                    'qty' => $detail->qty * -1,
                    'hargajual' => number_format($item->hargajual, 0, ',', '.'),
                    'pph' => number_format($pph, 0, ',', '.'),
                    'diskon_persen' => $item->diskon_persen,
                    'diskon_rp' => $item->diskon_rp,
                    'total_diskon' => number_format($item->total_diskon, 0, ',', '.'),
                    'subtotal' => number_format($subtotalPenjualan, 0, ',', '.'),
                    'cn_persen' => $item->cn_persen ?: 0,
                    'cn_rupiah' => number_format($cn, 0, ',', '.'),
                    'nett' => number_format($nett, 0, ',', '.'),
                    'harga_beli' => number_format($detail->harga_beli, 0, ',', '.'),
                    'diskon_beli_persen' => $detail->diskon_persen_beli,
                    'diskon_beli_rupiah' => $detail->diskon_rupiah_beli,
                    'total_diskon_beli' => number_format($total_diskon, 0, ',', '.'),
                    'ppn_beli' => number_format(($subtotal - $total_diskon) * 11 / 100, 0, ',', '.'),
                    'hpp' => number_format($hpp, 0, ',', '.'),
                    'laba_kotor' => number_format($nett - $hpp, 0, ',', '.')
                ]);
            }
        }

        $labarugi = $labarugi->sortByDesc('laba_kotor')->sortBy('no_kpa')->values()->all();

        return DataTables::of($labarugi)
            ->addIndexColumn()
            ->make(true);
    }

    public function datatableCustomerProductReview(Request $request)
    {
        $fakturpenjualan = FakturPenjualanDetail::whereHas('fakturpenjualan.SO', function ($q) use ($request) {
            if ($request->sales !== 'All') {
                $q->where('sales_id', $request->sales);
            }
            if ($request->kategori !== 'All') {
                $q->where('kategoripesanan_id', $request->kategori);
            }
        })->with(['pengirimanbarangdetail.stokexpdetail', 'pengirimanbarangdetail.harganonexpireddetail'])
            ->whereHas('fakturpenjualan', function ($q) use ($request) {
                $q->whereYear('tanggal', '=', $request->year)
                    ->where('customer_id', $request->customer_id);

                if ($request->bulan !== 'All') {
                    $q->whereMonth('tanggal', $request->bulan);
                }
            })
            ->get();

        $labarugi = collect();

        foreach ($fakturpenjualan as $item) {
            $details = $item->products->status_exp == 0
                ? $item->pengirimanbarangdetail->harganonexpireddetail
                : $item->pengirimanbarangdetail->stokexpdetail;

            foreach ($details as $detail) {
                $subtotal = $detail->qty * $detail->harga_beli * -1;
                $total_diskon = ($detail->diskon_persen_beli * $subtotal / 100) + $detail->diskon_rupiah_beli;
                $hpp = ($subtotal - $total_diskon) * 1.11;

                $totalJual = $detail->qty * $item->hargajual * -1;
                $subtotalPenjualan = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                $pph = $item->pph ? $subtotalPenjualan * $item->pph / 100 : 0;
                $cn = ($subtotalPenjualan-$pph) * $item->cn_persen / 100;
                $nett = $subtotalPenjualan - $cn - $pph;

                $labarugi->push([
                    'no_kpa' => $item->fakturpenjualan->no_kpa,
                    'products' => $item->products->nama,
                    'products_id' => $item->products->id,
                    'qty' => $detail->qty * -1,
                    'hargajual' => number_format($item->hargajual, 0, ',', '.'),
                    'pph' => number_format($pph, 0, ',', '.'),
                    'diskon_persen' => $item->diskon_persen,
                    'diskon_rp' => $item->diskon_rp,
                    'subtotal' => number_format($subtotalPenjualan, 0, ',', '.'),
                    'cn_persen' => $item->cn_persen ?? 0,
                    'cn_rupiah' => number_format($cn, 0, ',', '.'),
                    'nett' => number_format($nett, 0, ',', '.'),
                    'harga_beli' => number_format($detail->harga_beli, 0, ',', '.'),
                    'diskon_beli_persen' => $detail->diskon_persen_beli,
                    'diskon_beli_rupiah' => $detail->diskon_rupiah_beli,
                    'total_diskon_beli' => number_format($total_diskon, 0, ',', '.'),
                    'ppn_beli' => number_format(($subtotal - $total_diskon) * 11 / 100, 0, ',', '.'),
                    'hpp' => number_format($hpp, 0, ',', '.'),
                    'laba_kotor' => $nett - $hpp
                ]);
            }
        }

        $labaKotorPerSupplier = $labarugi->groupBy('products_id')->map(function ($group, $productId) {
            return [
                'nama' => $group->first()['products'],
                'id' => $productId,
                'laba_kotor' => $group->sum('laba_kotor'),
                'qty' => $group->sum('qty')
            ];
        })->values()->sortByDesc('laba_kotor')->toArray();

        return DataTables::of($labaKotorPerSupplier)
            ->addIndexColumn()
            ->editColumn('laba_kotor', fn($k) => number_format($k['laba_kotor'], 0, ',', '.'))
            ->make(true);
    }

    public function filterLabaRugi()
    {
        $title = 'Laporan Laba Rugi';
        $supplier = Supplier::with('namakota')->get();
        $merk = Merk::get();
        $sales = Sales::get();
        $customer = Customer::get();
        $kategori = Kategoripesanan::get();
        return view('laporan.labarugi.laporan.filter', compact('title', 'supplier', 'merk', 'sales', 'customer', 'kategori'));
    }

    public function exportLabaRugi(Request $request)
    {
        $data = $request->all();
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LabaRugiExport($data), 'laporanlabarugi-' . $now . '.xlsx');
    }

    public function datatablePrinciple(Request $request)
    {

        $fakturpenjualan = FakturPenjualanDetail::whereHas('fakturpenjualan.SO', function ($q) use ($request) {
            if ($request->sales !== 'All') {
                $q->where('sales_id', $request->sales);
            }
            if ($request->kategori !== 'All') {
                $q->where('kategoripesanan_id', $request->kategori);
            }
        })
            ->with([
                'products.merks.supplier', // Memuat supplier dan merk produk hanya sekali
                'pengirimanbarangdetail.stokexpdetail',
                'pengirimanbarangdetail.harganonexpireddetail',
                'fakturpenjualan.customers'
            ])
            ->whereHas('fakturpenjualan', function ($q) use ($request) {
                $q->whereYear('tanggal', '=', $request->year);
                if ($request->bulan !== 'All') {
                    $q->whereMonth('tanggal', $request->bulan);
                }
            })
            ->get();

        $labaKotorPerSupplier = [];

        foreach ($fakturpenjualan as $item) {
            // Pilih apakah menggunakan stok expired atau non-expired
            $details = $item->products->status_exp == 0
                ? $item->pengirimanbarangdetail->harganonexpireddetail
                : $item->pengirimanbarangdetail->stokexpdetail;

            foreach ($details as $detail) {
                // Hitung subtotal, diskon, HPP dan penjualan
                $subtotal = $detail->qty * $detail->harga_beli * -1;
                $totalDiskon = ($detail->diskon_persen_beli * $subtotal / 100) + $detail->diskon_rupiah_beli;
                $hpp = ($subtotal - $totalDiskon) * 1.11;

                // Penjualan
                $totalJual = $detail->qty * $item->hargajual * -1;
                $subtotalPenjualan = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                $pph = $item->pph ? $subtotalPenjualan * $item->pph / 100 : 0;
                $cn = ($subtotalPenjualan-$pph) * $item->cn_persen / 100;
                $nett = $subtotalPenjualan - $cn - $pph;

                // Agregasi laba kotor per supplier
                $supplierId = $item->products->merks->supplier->id;
                $supplierName = $item->products->merks->supplier->nama;

                // Jika supplier belum ada dalam array, inisialisasi
                if (!isset($labaKotorPerSupplier[$supplierId])) {
                    $labaKotorPerSupplier[$supplierId] = [
                        'nama' => $supplierName,
                        'id' => $supplierId,
                        'laba_kotor' => 0,
                    ];
                }

                // Tambahkan laba kotor per supplier
                $labaKotorPerSupplier[$supplierId]['laba_kotor'] += ($nett - $hpp);
            }
        }

        // Format ulang hasil menjadi array indeks numerik
        $output = array_values($labaKotorPerSupplier);

        // Urutkan berdasarkan laba kotor tertinggi
        usort($output, function ($a, $b) {
            return $b['laba_kotor'] <=> $a['laba_kotor'];
        });

        return DataTables::of($output)
            ->addIndexColumn()
            ->editColumn('nama', function ($k) {
                return $k['nama'];
            })
            ->editColumn('laba_kotor', function ($k) {
                return number_format($k['laba_kotor'], 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                return view('laporan.labarugi.partial._form-action-principle', ['principle_id' => $row['id']]);
            })
            ->make(true);
    }

    public function datatablePrinciplePerProduct(Request $request)
    {

        // Mengambil faktur penjualan dengan relasi yang lebih efisien
        $fakturpenjualan = FakturPenjualanDetail::with([
            'fakturpenjualan.SO',
            'products.merks.supplier',
            'pengirimanbarangdetail.stokexpdetail',
            'pengirimanbarangdetail.harganonexpireddetail'
        ])
            ->whereHas('fakturpenjualan', function ($q) use ($request) {
                $q->whereYear('tanggal', '=', $request->year);
                if ($request->bulan !== 'All') {
                    $q->whereMonth('tanggal', $request->bulan);
                }
            })
            ->whereHas('fakturpenjualan.SO', function ($q) use ($request) {
                if ($request->sales !== 'All') {
                    $q->where('sales_id', $request->sales);
                }
                if ($request->kategori !== 'All') {
                    $q->where('kategoripesanan_id', $request->kategori);
                }
            })
            ->whereHas('products.merks.supplier', function ($q) use ($request) {
                if ($request->supplier_id) {
                    $q->where('id', $request->supplier_id);
                }
            })
            ->get();

        $labarugi = [];

        foreach ($fakturpenjualan as $item) {
            // Pilih detail barang yang sudah kadaluarsa atau belum
            $details = $item->products->status_exp == 0
                ? $item->pengirimanbarangdetail->harganonexpireddetail
                : $item->pengirimanbarangdetail->stokexpdetail;

            foreach ($details as $detail) {
                // Hitung subtotal dan diskon untuk produk yang sama
                $subtotal = $detail->qty * $detail->harga_beli * -1;
                $total_diskon = ($detail->diskon_persen_beli * $subtotal / 100) + $detail->diskon_rupiah_beli;
                $hpp = ($subtotal - $total_diskon) * 1.11;

                // Penjualan
                $totalJual = $detail->qty * $item->hargajual * -1;
                $subtotalPenjualan = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                $pph = $item->pph ? $subtotalPenjualan * $item->pph / 100 : 0;
                $cn = ($subtotalPenjualan-$pph) * $item->cn_persen / 100;
                $nett = $subtotalPenjualan - $cn - $pph;

                // Simpan hasil perhitungan laba kotor per produk
                $labarugi[] = [
                    'no_kpa' => $item->fakturpenjualan->no_kpa,
                    'product' => $item->products->nama,
                    'product_id' => $item->products->id,
                    'qty' => $detail->qty * -1,
                    'hargajual' => $item->hargajual,
                    'diskon_persen' => $item->diskon_persen,
                    'diskon_rp' => $item->diskon_rp,
                    'total_diskon' => $item->total_diskon,
                    'subtotal' => $subtotalPenjualan,
                    'total' => $subtotalPenjualan,
                    'cn_rupiah' => $cn,
                    'nett' => $nett,
                    'harga_beli' => $detail->harga_beli,
                    'diskon_beli_persen' => $detail->diskon_persen_beli,
                    'diskon_beli_rupiah' => $detail->diskon_rupiah_beli,
                    'total_diskon_beli' => $total_diskon,
                    'hpp' => $hpp,
                    'laba_kotor' =>  $nett - $hpp
                ];
            }
        }

        // Agregasi laba kotor per produk dalam array
        $labaKotorPerProduct = [];

        foreach ($labarugi as $transaksi) {
            $productId = $transaksi['product_id'];
            $productName = $transaksi['product'];
            $labaKotor = $transaksi['laba_kotor'];
            $qty = $transaksi['qty'];

            // Akumulasi laba kotor per produk
            if (!isset($labaKotorPerProduct[$productId])) {
                $labaKotorPerProduct[$productId] = [
                    'nama' => $productName,
                    'id' => $productId,
                    'laba_kotor' => 0,
                    'qty' => 0
                ];
            }

            $labaKotorPerProduct[$productId]['laba_kotor'] += $labaKotor;
            $labaKotorPerProduct[$productId]['qty'] += $qty;
        }

        // Format ulang dan urutkan berdasarkan laba kotor
        $output = array_values($labaKotorPerProduct);
        usort($output, function ($a, $b) {
            return $b['laba_kotor'] <=> $a['laba_kotor'];
        });

        // Kembalikan hasil ke DataTables dengan format yang lebih optimal
        return DataTables::of($output)
            ->addIndexColumn()
            ->editColumn('nama', function ($k) {
                return $k['nama'];
            })
            ->editColumn('qty', function ($k) {
                return $k['qty'];
            })
            ->editColumn('laba_kotor', function ($k) {
                return number_format($k['laba_kotor'], 0, ',', '.');
            })
            ->make(true);
    }


    public function datatableProduct(Request $request)
    {
        // Mengambil faktur penjualan dengan relasi yang lebih efisien
        $fakturpenjualan = FakturPenjualanDetail::query()
            ->whereHas('fakturpenjualan.SO', function ($q) use ($request) {
                $q->when($request->sales !== 'All', fn($query) => $query->where('sales_id', $request->sales))
                    ->when($request->kategori !== 'All', fn($query) => $query->where('kategoripesanan_id', $request->kategori));
            })
            ->whereHas('products.merks.supplier', function ($q) use ($request) {
                $q->when($request->supplier !== 'All', fn($query) => $query->where('id', $request->supplier));
            })
            ->whereHas('products.merks', function ($q) use ($request) {
                $q->when($request->merk !== 'All', fn($query) => $query->where('id', $request->merk));
            })
            ->with([
                'pengirimanbarangdetail.stokexpdetail',
                'pengirimanbarangdetail.harganonexpireddetail'
            ])
            ->whereHas('fakturpenjualan', function ($q) use ($request) {
                $q->whereYear('tanggal', $request->year)
                    ->when($request->bulan !== 'All', fn($query) => $query->whereMonth('tanggal', $request->bulan));
            })
            ->get();

        // Hitung laba rugi
        $labarugi = $fakturpenjualan->flatMap(function ($item) {
            $detailType = $item->products->status_exp == 0 ? 'harganonexpireddetail' : 'stokexpdetail';

            return $item->pengirimanbarangdetail->$detailType->map(function ($detail) use ($item) {
                $subtotal = $detail->qty * $detail->harga_beli * -1;
                $total_diskon_beli = ($detail->diskon_persen_beli * $subtotal / 100) + $detail->diskon_rupiah_beli;
                $hpp = ($subtotal - $total_diskon_beli) * 1.11;

                // Penjualan
                $totalJual = $detail->qty * $item->hargajual * -1;
                $subtotalPenjualan = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                $pph = $item->pph ? $subtotalPenjualan * $item->pph / 100 : 0;
                $cn = ($subtotalPenjualan-$pph) * $item->cn_persen / 100;
                $nett = $subtotalPenjualan - $cn - $pph;

                return [
                    'no_kpa' => $item->fakturpenjualan->no_kpa,
                    'product_id' => $item->products->id,
                    'products' => $item->products->nama,
                    'merk' => $item->products->merks->nama,
                    'qty' => $detail->qty * -1,
                    'hargajual' => $item->hargajual,
                    'diskon_persen' => $item->diskon_persen,
                    'diskon_rp' => $item->diskon_rp,
                    'total_diskon' => $item->total_diskon,
                    'subtotal' => $subtotalPenjualan,
                    'total' => $subtotalPenjualan,
                    'cn_rupiah' => $cn,
                    'nett' => $nett,
                    'harga_beli' => $detail->harga_beli,
                    'diskon_beli_persen' => $detail->diskon_persen_beli,
                    'diskon_beli_rupiah' => $detail->diskon_rupiah_beli,
                    'total_diskon_beli' => $total_diskon_beli,
                    'hpp' => $hpp,
                    'laba_kotor' => $nett - $hpp
                ];
            });
        });

        // Agregasi laba kotor per produk
        $labaKotorPerProduct = $labarugi->groupBy('product_id')->map(function ($items, $productId) {
            return [
                'id' => $productId,
                'nama' => $items->first()['products'],
                'merk' => $items->first()['merk'],
                'qty' => $items->sum('qty'),
                'laba_kotor' => $items->sum('laba_kotor')
            ];
        })->values()->sortByDesc('laba_kotor')->toArray();

        // Kembalikan hasil ke DataTables dengan format yang lebih optimal
        return DataTables::of($labaKotorPerProduct)
            ->addIndexColumn()
            ->editColumn('nama', fn($k) => $k['nama'])
            ->editColumn('merk', fn($k) => $k['merk'])
            ->editColumn('qty', fn($k) => $k['qty'])
            ->editColumn('laba_kotor', fn($k) => number_format($k['laba_kotor'], 0, ',', '.'))
            ->addColumn('action', function ($row) {
                return view('laporan.labarugi.partial._form-action-product', ['product_id' => $row['id']]);
            })
            ->make(true);
    }

    public function datatableProductPerCustomer(Request $request)
    {
        $fakturpenjualan = FakturPenjualanDetail::with([
            'fakturpenjualan.SO',
            'fakturpenjualan.customers',
            'products.merks.supplier',
            'products.merks',
            'pengirimanbarangdetail.stokexpdetail',
            'pengirimanbarangdetail.harganonexpireddetail'
        ])
            ->whereHas('fakturpenjualan.SO', function ($q) use ($request) {
                if ($request->sales !== 'All') {
                    $q->where('sales_id', $request->sales);
                }
                if ($request->kategori !== 'All') {
                    $q->where('kategoripesanan_id', $request->kategori);
                }
            })
            ->whereHas('products.merks.supplier', function ($q) use ($request) {
                if ($request->supplier !== 'All') {
                    $q->where('id', $request->supplier);
                }
            })
            ->whereHas('products.merks', function ($q) use ($request) {
                if ($request->merk !== 'All') {
                    $q->where('id', $request->merk);
                }
            })
            ->whereHas('fakturpenjualan', function ($q) use ($request) {
                $q->whereYear('tanggal', '=', $request->year);
                if ($request->bulan !== 'All') {
                    $q->whereMonth('tanggal', $request->bulan);
                }
            })
            ->where('product_id', $request->product_id)
            ->get();

        $labarugi = [];
        foreach ($fakturpenjualan as $item) {
            $isExpired = $item->products->status_exp == 0;

            // Pilih detail yang sesuai berdasarkan status expired
            $details = $isExpired ? $item->pengirimanbarangdetail->harganonexpireddetail : $item->pengirimanbarangdetail->stokexpdetail;

            foreach ($details as $detail) {
                // Hitung subtotal dan laba kotor
                $subtotal = $detail->qty * $detail->harga_beli * -1;
                $total_diskon = ($detail->diskon_persen_beli * $subtotal / 100) + $detail->diskon_rupiah_beli;
                $hpp = ($subtotal - $total_diskon) * 1.11;

                // Penghitungan penjualan
                $totalJual = $detail->qty * $item->hargajual * -1;
                $subtotalPenjualan  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                $pph = $item->pph ? $subtotalPenjualan * $item->pph / 100 : 0;
                $cn = ($subtotalPenjualan-$pph) * $item->cn_persen / 100;
                $nett = $subtotalPenjualan - $cn - $pph;

                $labarugi[] = [
                    'no_kpa' => $item->fakturpenjualan->no_kpa,
                    'customers' => $item->fakturpenjualan->customers->nama,
                    'customer_id' => $item->fakturpenjualan->customers->id,
                    'qty' => $detail->qty * -1,
                    'hargajual' => $item->hargajual,
                    'diskon_persen' => $item->diskon_persen,
                    'diskon_rp' => $item->diskon_rp,
                    'total_diskon' => $item->total_diskon,
                    'subtotal' => $subtotalPenjualan,
                    'total' => $subtotalPenjualan,
                    'cn_rupiah' => $cn,
                    'nett' => $nett,
                    'harga_beli' => $detail->harga_beli,
                    'diskon_beli_persen' => $detail->diskon_persen_beli,
                    'diskon_beli_rupiah' => $detail->diskon_rupiah_beli,
                    'total_diskon_beli' => $total_diskon,
                    'hpp' => $hpp,
                    'laba_kotor' =>  $nett - $hpp
                ];
            }
        }

        // Mengelompokkan laba kotor berdasarkan customer
        $labaKotorPerSupplier = collect($labarugi)->groupBy('customer_id')->map(function ($item) {
            return [
                'nama' => $item->first()['customers'],
                'id' => $item->first()['customer_id'],
                'laba_kotor' => $item->sum('laba_kotor'),
                'qty' => $item->sum('qty'),
            ];
        })->values()->sortByDesc('laba_kotor')->toArray();

        // Kembalikan hasil ke DataTables
        return DataTables::of($labaKotorPerSupplier)
            ->addIndexColumn()
            ->editColumn('nama', function ($k) {
                return $k['nama'];
            })
            ->editColumn('qty', function ($k) {
                return $k['qty'];
            })
            ->editColumn('laba_kotor', function ($k) {
                return  number_format($k['laba_kotor'], 0, ',', '.');
            })
            ->make(true);
    }

    public function datatableCN(Request $request)
    {
        $fakturpenjualan = FakturPenjualanDetail::whereHas('fakturpenjualan.SO', function ($q) use ($request) {
            if ($request->sales !== 'All') {
                $q->where('sales_id', $request->sales);
            }
            if ($request->kategori !== 'All') {
                $q->where('kategoripesanan_id', $request->kategori);
            }
        })
            ->with([
                'pengirimanbarangdetail.stokexpdetail',
                'pengirimanbarangdetail.harganonexpireddetail',
                'fakturpenjualan.customers',
                'products',
            ])
            ->whereHas('fakturpenjualan', function ($q) use ($request) {
                $q->whereYear('tanggal', '=', $request->year);

                if ($request->bulan !== 'All') {
                    $q->whereMonth('tanggal', $request->bulan);
                }
            })
            ->get();

        $labarugi = [];

        foreach ($fakturpenjualan as $item) {
            $details = $item->products->status_exp == 0
                ? $item->pengirimanbarangdetail->harganonexpireddetail
                : $item->pengirimanbarangdetail->stokexpdetail;

            foreach ($details as $detail) {
                // Hitung subtotal, diskon, dan HPP
                $subtotal = $detail->qty * $detail->harga_beli * -1;
                $total_diskon = ($detail->diskon_persen_beli * $subtotal / 100) + $detail->diskon_rupiah_beli;
                $hpp = ($subtotal - $total_diskon) * 1.11;

                // Hitung penjualan
                $totalJual = $detail->qty * $item->hargajual * -1;
                $subtotalPenjualan = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                $pph = $item->pph ? $subtotalPenjualan * $item->pph / 100 : 0;
                $cn = ($subtotalPenjualan-$pph) * $item->cn_persen / 100;
                $nett = $subtotalPenjualan - $cn - $pph;

                // Menyimpan data untuk laba kotor
                $labarugi[] = [
                    'no_kpa' => $item->fakturpenjualan->no_kpa,
                    'products' => $item->products->nama,
                    'customer' => $item->fakturpenjualan->customers->nama,
                    'customer_id' => $item->fakturpenjualan->customers->id,
                    'qty' => $detail->qty,
                    'hargajual' => $item->hargajual,
                    'diskon_persen' => $item->diskon_persen,
                    'diskon_rp' => $item->diskon_rp,
                    'total_diskon' => $item->total_diskon,
                    'subtotal' => $subtotalPenjualan,
                    'total' => $subtotalPenjualan,
                    'cn_rupiah' => $cn,
                    'nett' => $nett,
                    'harga_beli' => $detail->harga_beli,
                    'diskon_beli_persen' => $detail->diskon_persen_beli,
                    'diskon_beli_rupiah' => $detail->diskon_rupiah_beli,
                    'total_diskon_beli' => $total_diskon,
                    'hpp' => $hpp,
                    'laba_kotor' => $nett - $hpp
                ];
            }
        }

        // Kelompokkan laba kotor per customer
        $labaKotorPerCustomer = collect($labarugi)
            ->groupBy('customer_id')
            ->map(function ($item) {
                return [
                    'nama' => $item->first()['customer'],
                    'id' => $item->first()['customer_id'],
                    'cn_rupiah' => $item->sum('cn_rupiah'),
                    'laba_kotor' => $item->sum('laba_kotor'),
                    'omset' => $item->sum('nett')
                ];
            })
            ->values()
            ->sortByDesc('cn_rupiah') // Urutkan berdasarkan laba kotor terbesar
            ->toArray();

        // Mengembalikan data ke DataTables
        return DataTables::of($labaKotorPerCustomer)
            ->addIndexColumn()
            ->editColumn('nama', function ($k) {
                return $k['nama'];
            })
            ->editColumn('omset', function ($k) {
                return number_format($k['omset'], 0, ',', '.');
            })
            ->editColumn('laba_kotor', function ($k) {
                return number_format($k['laba_kotor'], 0, ',', '.');
            })
            ->editColumn('cn_rupiah', function ($k) {
                return number_format($k['cn_rupiah'], 0, ',', '.');
            })
            ->make(true);
    }

    public function totalCN(Request $request)
    {
        $fakturpenjualan = FakturPenjualanDetail::whereHas('fakturpenjualan.SO', function ($q) use ($request) {
            if ($request->sales !== 'All') {
                $q->where('sales_id', $request->sales);
            }
            if ($request->kategori !== 'All') {
                $q->where('kategoripesanan_id', $request->kategori);
            }
        })
            ->with([
                'pengirimanbarangdetail.stokexpdetail',
                'pengirimanbarangdetail.harganonexpireddetail',
                'fakturpenjualan.customers',
                'products',
            ])
            ->whereHas('fakturpenjualan', function ($q) use ($request) {
                $q->whereYear('tanggal', '=', $request->year);

                if ($request->bulan !== 'All') {
                    $q->whereMonth('tanggal', $request->bulan);
                }
            })
            ->get();

        $labarugi = [];
        foreach ($fakturpenjualan as $item) {
            // Menentukan detail barang (expired atau non-expired)
            $details = $item->products->status_exp == 0
                ? $item->pengirimanbarangdetail->harganonexpireddetail
                : $item->pengirimanbarangdetail->stokexpdetail;

            foreach ($details as $detail) {
                // Menghitung subtotal beli
                $subtotal = $detail->qty * $detail->harga_beli * -1;
                $total_diskon = ($detail->diskon_persen_beli * $subtotal / 100) + $detail->diskon_rupiah_beli;
                $hpp = ($subtotal - $total_diskon) * 1.11;

                // Menghitung subtotal penjualan
                $totalJual = $detail->qty * $item->hargajual * -1;
                $subtotalPenjualan = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                $pph = $item->pph ? $subtotalPenjualan * $item->pph / 100 : 0;
                $cn = ($subtotalPenjualan-$pph) * $item->cn_persen / 100;
                $nett = $subtotalPenjualan - $cn - $pph;

                // Menyimpan data laba kotor
                $labarugi[] = [
                    'no_kpa' => $item->fakturpenjualan->no_kpa,
                    'products' => $item->products->nama,
                    'customer' => $item->fakturpenjualan->customers->nama,
                    'customer_id' => $item->fakturpenjualan->customers->id,
                    'qty' => $detail->qty,
                    'hargajual' => $item->hargajual,
                    'diskon_persen' => $item->diskon_persen,
                    'diskon_rp' => $item->diskon_rp,
                    'total_diskon' => $item->total_diskon,
                    'subtotal' => $subtotalPenjualan,
                    'total' => $subtotalPenjualan,
                    'cn_rupiah' => $cn,
                    'nett' => $nett,
                    'harga_beli' => $detail->harga_beli,
                    'diskon_beli_persen' => $detail->diskon_persen_beli,
                    'diskon_beli_rupiah' => $detail->diskon_rupiah_beli,
                    'total_diskon_beli' => $total_diskon,
                    'hpp' => $hpp,
                    'laba_kotor' => $nett - $hpp
                ];
            }
        }

        // Menghitung total cn_rupiah, laba_kotor, dan nett menggunakan koleksi
        $total_cn_rupiah = collect($labarugi)->sum('cn_rupiah');
        $total_laba_kotor = collect($labarugi)->sum('laba_kotor');
        $total_nett = collect($labarugi)->sum('nett');

        // Mengembalikan hasil dalam format yang sesuai
        return response()->json([
            'total_cn_rupiah' => number_format($total_cn_rupiah, 0, ',', '.'),
            'total_laba_kotor' => number_format($total_laba_kotor, 0, ',', '.'),
            'total_nett' => number_format($total_nett, 0, ',', '.'),
        ]);
    }
}
