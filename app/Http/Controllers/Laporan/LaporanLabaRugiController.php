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

        $fakturpenjualan =  FakturPenjualanDetail::whereHas('fakturpenjualan.SO', function ($q) use ($request) {
            if ($request->sales !== 'All') {
                $q->where('sales_id', $request->sales);
            }
            if ($request->kategori !== 'All') {
                $q->where('kategoripesanan_id', $request->kategori);
            }
        })
            ->with('pengirimanbarangdetail.stokexpdetail')
            ->with('pengirimanbarangdetail.harganonexpireddetail')
            ->whereHas('fakturpenjualan', function ($q) use ($request) {
                $q->whereYear('tanggal', '=', $request->year);

                if ($request->bulan !== 'All') {
                    $q->whereMonth('tanggal', $request->bulan);
                }
            })
            ->get();


        $labarugi = [];
        foreach ($fakturpenjualan as $item) {

            if ($item->products->status_exp == 0) {
                foreach ($item->pengirimanbarangdetail->harganonexpireddetail as $nonexpired) {

                    $subtotal = $nonexpired->qty * $nonexpired->harga_beli * -1;
                    $total_diskon = ($nonexpired->diskon_persen_beli * $subtotal / 100) + $nonexpired->diskon_rupiah_beli;
                    $hpp = ($subtotal - $total_diskon) * 1.11;

                    //  penjualan
                    $totalJual = $nonexpired->qty * $item->hargajual * -1;
                    $subtotalPenjualan  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                    if ($item->pph) {
                        $pph = $subtotalPenjualan * $item->pph / 100;
                    } else {
                        $pph = 0;
                    }
                    $cn = $subtotalPenjualan * $item->cn_persen / 100;
                    $nett = $subtotalPenjualan - $cn - $pph;

                    $labarugi[] = array(                        
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'products' => $item->products->nama,
                        'customer' => $item->fakturpenjualan->customers->nama,
                        'customer_id' => $item->fakturpenjualan->customers->id,
                        'qty' => $nonexpired->qty,
                        'hargajual' => $item->hargajual,
                        'diskon_persen' => $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => $item->total_diskon,
                        'subtotal' => $subtotalPenjualan,
                        'total' => $subtotalPenjualan,
                        'cn_rupiah' => $cn,
                        'nett' => $subtotalPenjualan - $cn,
                        'harga_beli' => $nonexpired->harga_beli,
                        'diskon_beli_persen' => $nonexpired->diskon_persen_beli,
                        'diskon_beli_rupiah' => $nonexpired->diskon_rupiah_beli,
                        'total_diskon_beli' => $total_diskon,
                        'hpp' => $hpp,
                        'laba_kotor' =>  $nett - $hpp
                    );
                }
            } else {
                foreach ($item->pengirimanbarangdetail->stokexpdetail as $expired) {
                    $subtotalexpired = $expired->qty * $expired->harga_beli * -1;
                    $total_diskon_expired = ($expired->diskon_persen_beli * $subtotalexpired / 100) + $expired->diskon_rupiah_beli;
                    $hpp_expired = ($subtotalexpired - $total_diskon_expired) * 1.11;

                    //  penjualan
                    $totalJual = $expired->qty * $item->hargajual * -1;
                    $subtotalPenjualanExpired  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                    if ($item->pph) {
                        $pph = $subtotalPenjualanExpired * $item->pph / 100;
                    } else {
                        $pph = 0;
                    }
                    $cnExpired = $subtotalPenjualanExpired * $item->cn_persen / 100;
                    $nettExpired = $subtotalPenjualanExpired - $cnExpired - $pph;
                    $labarugi[] = array(                        
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'products' => $item->products->nama,
                        'customer' => $item->fakturpenjualan->customers->nama,
                        'customer_id' => $item->fakturpenjualan->customers->id,
                        'qty' => $expired->qty,
                        'hargajual' => $item->hargajual,
                        'diskon_persen' => $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => $item->total_diskon,
                        'subtotal' => $subtotalPenjualanExpired,
                        'total' => $subtotalPenjualanExpired,
                        'cn_rupiah' => $cnExpired,
                        'nett' => $nettExpired,
                        'harga_beli' => $expired->harga_beli,
                        'diskon_beli_persen' => $expired->diskon_persen_beli,
                        'diskon_beli_rupiah' => $expired->diskon_rupiah_beli,
                        'total_diskon_beli' => $total_diskon_expired,
                        'hpp' => $hpp_expired,
                        'laba_kotor' =>  $nettExpired - $hpp_expired
                    );
                }
            }
        }
        $total_laba_kotor = [];

        // Array untuk menyimpan laba kotor per customer
        $labaKotorPerCustomer = [];

        // Iterasi data transaksi
        foreach ($labarugi as $transaksi) {
            $customerId = $transaksi['customer_id'];
            $customerName = $transaksi['customer'];
            $labaKotor = $transaksi['laba_kotor'];

            // Jika customer belum ada dalam array, inisialisasi
            if (!isset($labaKotorPerCustomer[$customerId])) {
                $labaKotorPerCustomer[$customerId] = [
                    'nama' => $customerName,
                    'id' => $customerId,
                    'laba_kotor' => 0,
                ];
            }

            // Tambahkan laba kotor ke customer yang sesuai
            $labaKotorPerCustomer[$customerId]['laba_kotor'] += $labaKotor;
        }

        // Format ulang hasil sebagai array indeks numerik
        $output = array_values($labaKotorPerCustomer);

        // Urutkan berdasarkan laba kotor dari terbesar ke terkecil
        usort($output, function ($a, $b) {
            return $b['laba_kotor'] <=> $a['laba_kotor'];
        });


        return DataTables::of($output)
            ->addIndexColumn()
            ->editColumn('nama', function ($k) {
                return $k['nama'];
            })
            ->editColumn('laba_kotor', function ($k) {
                return  number_format($k['laba_kotor'], 0, ',', '.');
            })->addColumn('action', function ($row) {
                $customer_id =  $row['id'];
                return view('laporan.labarugi.partial._form-action', compact('customer_id'));
            })
            ->make(true);
    }


    public function chartprinciple(Request $request)
    {

        $fakturpenjualan =  FakturPenjualanDetail::whereHas('fakturpenjualan.SO', function ($q) use ($request) {
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
            ->with('pengirimanbarangdetail.stokexpdetail')
            ->with('pengirimanbarangdetail.harganonexpireddetail')
            ->whereHas('fakturpenjualan', function ($q) use ($request) {
                $q->whereYear('tanggal', '=', $request->year);
            })
            ->get();

        $labarugi = [];
        foreach ($fakturpenjualan as $item) {

            if ($item->products->status_exp == 0) {
                foreach ($item->pengirimanbarangdetail->harganonexpireddetail as $nonexpired) {

                    $subtotal = $nonexpired->qty * $nonexpired->harga_beli * -1;
                    $total_diskon = ($nonexpired->diskon_persen_beli * $subtotal / 100) + $nonexpired->diskon_rupiah_beli;
                    $hpp = ($subtotal - $total_diskon) * 1.11;

                    //  penjualan
                    $totalJual = $nonexpired->qty * $item->hargajual * -1;
                    $subtotalPenjualan  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                    if ($item->pph) {
                        $pph = $subtotalPenjualan * $item->pph / 100;
                    } else {
                        $pph = 0;
                    }

                    $cn = $subtotalPenjualan * $item->cn_persen / 100;
                    $nett = $subtotalPenjualan - $cn - $pph;

                    $labarugi[] = array(                        
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'products' => $item->products->nama,
                        'customer' => $item->fakturpenjualan->customers->nama,
                        'qty' => $nonexpired->qty,
                        'hargajual' => $item->hargajual,
                        'pph' => $pph,
                        'diskon_persen' => $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => $item->total_diskon,
                        'subtotal' => $subtotalPenjualan,
                        'total' => $subtotalPenjualan,
                        'cn_rupiah' => $cn,
                        'nett' => $subtotalPenjualan - $cn,
                        'harga_beli' => $nonexpired->harga_beli,
                        'diskon_beli_persen' => $nonexpired->diskon_persen_beli,
                        'diskon_beli_rupiah' => $nonexpired->diskon_rupiah_beli,
                        'total_diskon_beli' => $total_diskon,
                        'hpp' => $hpp,
                        'laba_kotor' =>  $nett - $hpp
                    );
                }
            } else {
                foreach ($item->pengirimanbarangdetail->stokexpdetail as $expired) {
                    $subtotalexpired = $expired->qty * $expired->harga_beli * -1;
                    $total_diskon_expired = ($expired->diskon_persen_beli * $subtotalexpired / 100) + $expired->diskon_rupiah_beli;
                    $hpp_expired = ($subtotalexpired - $total_diskon_expired) * 1.11;

                    //  penjualan
                    $totalJual = $expired->qty * $item->hargajual * -1;
                    $subtotalPenjualanExpired  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                    if ($item->pph) {
                        $pph = $subtotalPenjualanExpired * $item->pph / 100;
                    } else {
                        $pph = 0;
                    }
                    $cnExpired = $subtotalPenjualanExpired * $item->cn_persen / 100;
                    $nettExpired = $subtotalPenjualanExpired - $cnExpired - $pph;
                    $labarugi[] = array(                        
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'products' => $item->products->nama,
                        'customer' => $item->fakturpenjualan->customers->nama,
                        'qty' => $expired->qty,
                        'hargajual' => $item->hargajual,
                        'pph' => $pph,
                        'diskon_persen' => $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => $item->total_diskon,
                        'subtotal' => $subtotalPenjualanExpired,
                        'total' => $subtotalPenjualanExpired,
                        'cn_rupiah' => $cnExpired,
                        'nett' => $nettExpired,
                        'harga_beli' => $expired->harga_beli,
                        'diskon_beli_persen' => $expired->diskon_persen_beli,
                        'diskon_beli_rupiah' => $expired->diskon_rupiah_beli,
                        'total_diskon_beli' => $total_diskon_expired,
                        'hpp' => $hpp_expired,
                        'laba_kotor' =>  $nettExpired - $hpp_expired
                    );
                }
            }
        }

        // dd($labarugi);


        $laba = array();
        $grandtotal = 0;

        for ($i = 0; $i <= 12; $i++) {
            if ($i == 0) {
                $laba[$i] = 0;
            } else {
                $angka = 0;
                foreach ($labarugi as $value) {
                    if ($value['tanggal'] == $i) {
                        $angka += $value['laba_kotor'];
                        $laba[$i] = $angka;
                    }
                }
            }

            $databulan = '1-' . $i . '-2023';
            if ($i == 0) {
                $months[] = [0];
            } else {
                $months[] = [
                    Carbon::parse($databulan)->format('F')
                ];
            }
        }
        $profit = [];
        for ($i = 0; $i <= 12; $i++) {
            if (!isset($laba[$i])) {
                $profit[$i] = 0;
            } else {
                $profit[$i] = $laba[$i];
                $grandtotal += $laba[$i];
            }
        }

        return response()->json([
            'laba' => $profit,
            'bulan' => $months,
            'grandtotal' => number_format($grandtotal, 0, ',', '.')
        ]);
    }

    public function datatableCustomerProduct(Request $request)
    {
        $fakturpenjualan =  FakturPenjualanDetail::whereHas('fakturpenjualan.SO', function ($q) use ($request) {
            if ($request->sales !== 'All') {
                $q->where('sales_id', $request->sales);
            }
            if ($request->kategori !== 'All') {
                $q->where('kategoripesanan_id', $request->kategori);
            }
        })
            ->with('pengirimanbarangdetail.stokexpdetail')
            ->with('pengirimanbarangdetail.harganonexpireddetail')
            ->whereHas('fakturpenjualan', function ($q) use ($request) {
                $q->whereYear('tanggal', '=', $request->year);

                if ($request->bulan !== 'All') {
                    $q->whereMonth('tanggal', $request->bulan);
                }

                $q->where('customer_id', $request->customer_id);
            })
            ->get();


        $labarugi = [];
        foreach ($fakturpenjualan as $item) {
            if ($item->products->status_exp == 0) {
                foreach ($item->pengirimanbarangdetail->harganonexpireddetail as $nonexpired) {

                    $subtotal = $nonexpired->qty * $nonexpired->harga_beli * -1;
                    $total_diskon = ($nonexpired->diskon_persen_beli * $subtotal / 100) + $nonexpired->diskon_rupiah_beli;
                    $hpp = ($subtotal - $total_diskon) * 1.11;

                    //  penjualan
                    $totalJual = $nonexpired->qty * $item->hargajual * -1;
                    $subtotalPenjualan  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                    if ($item->pph) {
                        $pph = $subtotalPenjualan * $item->pph / 100;
                    } else {
                        $pph = 0;
                    }

                    $cn = $subtotalPenjualan * $item->cn_persen / 100;
                    $nett = $subtotalPenjualan - $cn - $pph;

                    $labarugi[] = array(                        
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'products' => $item->products->nama,
                        'products_id' => $item->products->id,
                        'qty' => $nonexpired->qty * -1,
                        'hargajual' => number_format($item->hargajual, 0, ',', '.'),
                        'pph' => number_format($pph, 0, ',', '.'),
                        'diskon_persen' =>  $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => number_format($item->total_diskon, 0, ',', '.'),
                        'subtotal' => number_format($subtotalPenjualan, 0, ',', '.'),
                        'cn_persen' =>  $item->cn_persen ? $item->cn_persen : 0,
                        'cn_rupiah' => number_format($cn, 0, ',', '.'),
                        'nett' => number_format($subtotalPenjualan - $cn, 0, ',', '.'),
                        'harga_beli' => number_format($nonexpired->harga_beli, 0, ',', '.'),
                        'diskon_beli_persen' => $nonexpired->diskon_persen_beli,
                        'diskon_beli_rupiah' => $nonexpired->diskon_rupiah_beli,
                        'total_diskon_beli' => number_format($total_diskon, 0, ',', '.'),
                        'ppn_beli' => number_format(($subtotal - $total_diskon) * 11 / 100, 0, ',', '.'),
                        'hpp' => number_format($hpp, 0, ',', '.'),
                        'laba_kotor' => number_format($nett - $hpp, 0, ',', '.')
                    );
                }
            } else {
                foreach ($item->pengirimanbarangdetail->stokexpdetail as $expired) {
                    $subtotalexpired = $expired->qty * $expired->harga_beli * -1;
                    $total_diskon_expired = ($expired->diskon_persen_beli * $subtotalexpired / 100) + $expired->diskon_rupiah_beli;
                    $hpp_expired = ($subtotalexpired - $total_diskon_expired) * 1.11;

                    //  penjualan
                    $totalJual = $expired->qty * $item->hargajual * -1;
                    $subtotalPenjualanExpired  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                    if ($item->pph) {
                        $pph = $subtotalPenjualanExpired * $item->pph / 100;
                    } else {
                        $pph = 0;
                    }

                    $cnExpired = $subtotalPenjualanExpired * $item->cn_persen / 100;
                    $nettExpired = $subtotalPenjualanExpired - $cnExpired - $pph;
                    $labarugi[] = array(                        
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'products' => $item->products->nama,
                        'products_id' => $item->products->id,
                        'qty' => $expired->qty * -1,
                        'hargajual' => number_format($item->hargajual, 0, ',', '.'),
                        'pph' => number_format($pph, 0, ',', '.'),
                        'diskon_persen' =>  $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => number_format($item->total_diskon, 0, ',', '.'),
                        'subtotal' => number_format($subtotalPenjualanExpired, 0, ',', '.'),
                        'cn_persen' => number_format($item->cn_persen, 0, ',', '.'),
                        'cn_rupiah' => number_format($cnExpired, 0, ',', '.'),
                        'nett' =>  number_format($nettExpired, 0, ',', '.'),
                        'harga_beli' => number_format($expired->harga_beli, 0, ',', '.'),
                        'diskon_beli_persen' => number_format($expired->diskon_persen_beli, 0, ',', '.'),
                        'diskon_beli_rupiah' => number_format($expired->diskon_rupiah_beli, 0, ',', '.'),
                        'total_diskon_beli' => number_format($total_diskon_expired, 0, ',', '.'),
                        'ppn_beli' => number_format(($subtotalexpired - $total_diskon_expired) * 11 / 100, 0, ',', '.'),
                        'hpp' => number_format($hpp_expired, 0, ',', '.'),
                        'laba_kotor' => number_format($nettExpired - $hpp_expired, 0, ',', '.')
                    );
                }
            }
        }


        // Urutkan berdasarkan laba kotor dari terbesar ke terkecil
        usort($labarugi, function ($a, $b) {
            return $b['laba_kotor'] <=> $a['laba_kotor'];
        });


        return DataTables::of($labarugi)
            ->addIndexColumn()
            ->make(true);
    }

    public function filterLabaRugi()
    {
        $title = 'Laporan Laba Rugi';
        return view('laporan.labarugi.laporan.filter', compact('title'));
    }

    public function exportLabaRugi(Request $request)
    {
        $data = $request->all();
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LabaRugiExport($data), 'laporanlabarugi-' . $now . '.xlsx');
    }

    public function datatablePrinciple(Request $request)
    {

        $fakturpenjualan =  FakturPenjualanDetail::whereHas('fakturpenjualan.SO', function ($q) use ($request) {
            if ($request->sales !== 'All') {
                $q->where('sales_id', $request->sales);
            }
            if ($request->kategori !== 'All') {
                $q->where('kategoripesanan_id', $request->kategori);
            }
        })
            ->with('products.merks.supplier')
            ->with('pengirimanbarangdetail.stokexpdetail')
            ->with('pengirimanbarangdetail.harganonexpireddetail')
            ->whereHas('fakturpenjualan', function ($q) use ($request) {
                $q->whereYear('tanggal', '=', $request->year);

                if ($request->bulan !== 'All') {
                    $q->whereMonth('tanggal', $request->bulan);
                }
            })
            ->get();


        $labarugi = [];
        foreach ($fakturpenjualan as $item) {
            if ($item->products->status_exp == 0) {
                foreach ($item->pengirimanbarangdetail->harganonexpireddetail as $nonexpired) {

                    $subtotal = $nonexpired->qty * $nonexpired->harga_beli * -1;
                    $total_diskon = ($nonexpired->diskon_persen_beli * $subtotal / 100) + $nonexpired->diskon_rupiah_beli;
                    $hpp = ($subtotal - $total_diskon) * 1.11;

                    //  penjualan
                    $totalJual = $nonexpired->qty * $item->hargajual * -1;
                    $subtotalPenjualan  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                    if ($item->pph) {
                        $pph = $subtotalPenjualan * $item->pph / 100;
                    } else {
                        $pph = 0;
                    }
                    $cn = $subtotalPenjualan * $item->cn_persen / 100;
                    $nett = $subtotalPenjualan - $cn - $pph;

                    $labarugi[] = array(                        
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'products' => $item->products->nama,
                        'supplier' => $item->products->merks->supplier->nama,
                        'supplier_id' => $item->products->merks->supplier->id,
                        'qty' => $nonexpired->qty,
                        'hargajual' => $item->hargajual,
                        'diskon_persen' => $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => $item->total_diskon,
                        'subtotal' => $subtotalPenjualan,
                        'total' => $subtotalPenjualan,
                        'cn_rupiah' => $cn,
                        'nett' => $subtotalPenjualan - $cn,
                        'harga_beli' => $nonexpired->harga_beli,
                        'diskon_beli_persen' => $nonexpired->diskon_persen_beli,
                        'diskon_beli_rupiah' => $nonexpired->diskon_rupiah_beli,
                        'total_diskon_beli' => $total_diskon,
                        'hpp' => $hpp,
                        'laba_kotor' =>  $nett - $hpp
                    );
                }
            } else {
                foreach ($item->pengirimanbarangdetail->stokexpdetail as $expired) {
                    $subtotalexpired = $expired->qty * $expired->harga_beli * -1;
                    $total_diskon_expired = ($expired->diskon_persen_beli * $subtotalexpired / 100) + $expired->diskon_rupiah_beli;
                    $hpp_expired = ($subtotalexpired - $total_diskon_expired) * 1.11;

                    //  penjualan
                    $totalJual = $expired->qty * $item->hargajual * -1;
                    $subtotalPenjualanExpired  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                    if ($item->pph) {
                        $pph = $subtotalPenjualanExpired * $item->pph / 100;
                    } else {
                        $pph = 0;
                    }
                    $cnExpired = $subtotalPenjualanExpired * $item->cn_persen / 100;
                    $nettExpired = $subtotalPenjualanExpired - $cnExpired - $pph;
                    $labarugi[] = array(                        
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'products' => $item->products->nama,
                        'supplier' => $item->products->merks->supplier->nama,
                        'supplier_id' => $item->products->merks->supplier->id,
                        'qty' => $expired->qty,
                        'hargajual' => $item->hargajual,
                        'diskon_persen' => $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => $item->total_diskon,
                        'subtotal' => $subtotalPenjualanExpired,
                        'total' => $subtotalPenjualanExpired,
                        'cn_rupiah' => $cnExpired,
                        'nett' => $nettExpired,
                        'harga_beli' => $expired->harga_beli,
                        'diskon_beli_persen' => $expired->diskon_persen_beli,
                        'diskon_beli_rupiah' => $expired->diskon_rupiah_beli,
                        'total_diskon_beli' => $total_diskon_expired,
                        'hpp' => $hpp_expired,
                        'laba_kotor' =>  $nettExpired - $hpp_expired
                    );
                }
            }
        }
        $total_laba_kotor = [];

        // Array untuk menyimpan laba kotor per customer
        $labaKotorPerSupplier = [];

        // Iterasi data transaksi
        foreach ($labarugi as $transaksi) {
            $supplierId = $transaksi['supplier_id'];
            $supplierName = $transaksi['supplier'];
            $labaKotor = $transaksi['laba_kotor'];

            // Jika customer belum ada dalam array, inisialisasi
            if (!isset($labaKotorPerSupplier[$supplierId])) {
                $labaKotorPerSupplier[$supplierId] = [
                    'nama' => $supplierName,
                    'id' => $supplierId,
                    'laba_kotor' => 0,
                ];
            }

            // Tambahkan laba kotor ke customer yang sesuai
            $labaKotorPerSupplier[$supplierId]['laba_kotor'] += $labaKotor;
        }

        // Format ulang hasil sebagai array indeks numerik
        $output = array_values($labaKotorPerSupplier);

        // Urutkan berdasarkan laba kotor dari terbesar ke terkecil
        usort($output, function ($a, $b) {
            return $b['laba_kotor'] <=> $a['laba_kotor'];
        });


        return DataTables::of($output)
            ->addIndexColumn()
            ->editColumn('nama', function ($k) {
                return $k['nama'];
            })
            ->editColumn('laba_kotor', function ($k) {
                return  number_format($k['laba_kotor'], 0, ',', '.');
            })->addColumn('action', function ($row) {
                $principle_id =  $row['id'];
                return view('laporan.labarugi.partial._form-action-principle', compact('principle_id'));
            })
            ->make(true);
    }

    public function datatablePrinciplePerProduct(Request $request)
    {

        $fakturpenjualan =  FakturPenjualanDetail::whereHas('fakturpenjualan.SO', function ($q) use ($request) {
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
            ->with('pengirimanbarangdetail.stokexpdetail')
            ->with('pengirimanbarangdetail.harganonexpireddetail')
            ->whereHas('fakturpenjualan', function ($q) use ($request) {
                $q->whereYear('tanggal', '=', $request->year);

                if ($request->bulan !== 'All') {
                    $q->whereMonth('tanggal', $request->bulan);
                }
            })
            ->get();



        $labarugi = [];
        foreach ($fakturpenjualan as $item) {
            if ($item->products->status_exp == 0) {
                foreach ($item->pengirimanbarangdetail->harganonexpireddetail as $nonexpired) {

                    $subtotal = $nonexpired->qty * $nonexpired->harga_beli * -1;
                    $total_diskon = ($nonexpired->diskon_persen_beli * $subtotal / 100) + $nonexpired->diskon_rupiah_beli;
                    $hpp = ($subtotal - $total_diskon) * 1.11;

                    //  penjualan
                    $totalJual = $nonexpired->qty * $item->hargajual * -1;
                    $subtotalPenjualan  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                    if ($item->pph) {
                        $pph = $subtotalPenjualan * $item->pph / 100;
                    } else {
                        $pph = 0;
                    }
                    $cn = $subtotalPenjualan * $item->cn_persen / 100;
                    $nett = $subtotalPenjualan - $cn - $pph;

                    $labarugi[] = array(                        
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'product' => $item->products->nama,
                        'product_id' => $item->products->id,
                        'qty' => $nonexpired->qty * -1,
                        'hargajual' => $item->hargajual,
                        'diskon_persen' => $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => $item->total_diskon,
                        'subtotal' => $subtotalPenjualan,
                        'total' => $subtotalPenjualan,
                        'cn_rupiah' => $cn,
                        'nett' => $subtotalPenjualan - $cn,
                        'harga_beli' => $nonexpired->harga_beli,
                        'diskon_beli_persen' => $nonexpired->diskon_persen_beli,
                        'diskon_beli_rupiah' => $nonexpired->diskon_rupiah_beli,
                        'total_diskon_beli' => $total_diskon,
                        'hpp' => $hpp,
                        'laba_kotor' =>  $nett - $hpp
                    );
                }
            } else {
                foreach ($item->pengirimanbarangdetail->stokexpdetail as $expired) {
                    $subtotalexpired = $expired->qty * $expired->harga_beli * -1;
                    $total_diskon_expired = ($expired->diskon_persen_beli * $subtotalexpired / 100) + $expired->diskon_rupiah_beli;
                    $hpp_expired = ($subtotalexpired - $total_diskon_expired) * 1.11;

                    //  penjualan
                    $totalJual = $expired->qty * $item->hargajual * -1;
                    $subtotalPenjualanExpired  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                    if ($item->pph) {
                        $pph = $subtotalPenjualanExpired * $item->pph / 100;
                    } else {
                        $pph = 0;
                    }
                    $cnExpired = $subtotalPenjualanExpired * $item->cn_persen / 100;
                    $nettExpired = $subtotalPenjualanExpired - $cnExpired - $pph;
                    $labarugi[] = array(                        
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'product' => $item->products->nama,
                        'product_id' => $item->products->id,
                        'qty' => $expired->qty  * -1,
                        'hargajual' => $item->hargajual,
                        'diskon_persen' => $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => $item->total_diskon,
                        'subtotal' => $subtotalPenjualanExpired,
                        'total' => $subtotalPenjualanExpired,
                        'cn_rupiah' => $cnExpired,
                        'nett' => $nettExpired,
                        'harga_beli' => $expired->harga_beli,
                        'diskon_beli_persen' => $expired->diskon_persen_beli,
                        'diskon_beli_rupiah' => $expired->diskon_rupiah_beli,
                        'total_diskon_beli' => $total_diskon_expired,
                        'hpp' => $hpp_expired,
                        'laba_kotor' =>  $nettExpired - $hpp_expired
                    );
                }
            }
        }
        $total_laba_kotor = [];

        // Array untuk menyimpan laba kotor per customer
        $labaKotorPerSupplier = [];

        // Iterasi data transaksi
        foreach ($labarugi as $transaksi) {
            $productId = $transaksi['product_id'];
            $productName = $transaksi['product'];
            $labaKotor = $transaksi['laba_kotor'];
            $qty = $transaksi['qty'];

            // Jika customer belum ada dalam array, inisialisasi
            if (!isset($labaKotorPerSupplier[$productId])) {
                $labaKotorPerSupplier[$productId] = [
                    'nama' => $productName,
                    'id' => $productId,
                    'laba_kotor' => 0,
                    'qty' => 0
                ];
            }

            // Tambahkan laba kotor ke customer yang sesuai
            $labaKotorPerSupplier[$productId]['laba_kotor'] += $labaKotor;
            $labaKotorPerSupplier[$productId]['qty'] += $qty;
        }

        // Format ulang hasil sebagai array indeks numerik
        $output = array_values($labaKotorPerSupplier);

        // Urutkan berdasarkan laba kotor dari terbesar ke terkecil
        usort($output, function ($a, $b) {
            return $b['laba_kotor'] <=> $a['laba_kotor'];
        });


        return DataTables::of($output)
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


    public function datatableProduct(Request $request)
    {
        $fakturpenjualan =  FakturPenjualanDetail::whereHas('fakturpenjualan.SO', function ($q) use ($request) {
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
            ->with('pengirimanbarangdetail.stokexpdetail')
            ->with('pengirimanbarangdetail.harganonexpireddetail')
            ->whereHas('fakturpenjualan', function ($q) use ($request) {
                $q->whereYear('tanggal', '=', $request->year);

                if ($request->bulan !== 'All') {
                    $q->whereMonth('tanggal', $request->bulan);
                }
            })
            ->get();


        $labarugi = [];
        foreach ($fakturpenjualan as $item) {
            if ($item->products->status_exp == 0) {
                foreach ($item->pengirimanbarangdetail->harganonexpireddetail as $nonexpired) {

                    $subtotal = $nonexpired->qty * $nonexpired->harga_beli * -1;
                    $total_diskon = ($nonexpired->diskon_persen_beli * $subtotal / 100) + $nonexpired->diskon_rupiah_beli;
                    $hpp = ($subtotal - $total_diskon) * 1.11;

                    //  penjualan
                    $totalJual = $nonexpired->qty * $item->hargajual * -1;
                    $subtotalPenjualan  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                    if ($item->pph) {
                        $pph = $subtotalPenjualan * $item->pph / 100;
                    } else {
                        $pph = 0;
                    }
                    $cn = $subtotalPenjualan * $item->cn_persen / 100;
                    $nett = $subtotalPenjualan - $cn - $pph;

                    $labarugi[] = array(                        
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'products' => $item->products->nama,                        
                        'product_id' => $item->products->id,
                        'merk' => $item->products->merks->nama,
                        'qty' => $nonexpired->qty * -1,
                        'hargajual' => $item->hargajual,
                        'diskon_persen' => $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => $item->total_diskon,
                        'subtotal' => $subtotalPenjualan,
                        'total' => $subtotalPenjualan,
                        'cn_rupiah' => $cn,
                        'nett' => $subtotalPenjualan - $cn,
                        'harga_beli' => $nonexpired->harga_beli,
                        'diskon_beli_persen' => $nonexpired->diskon_persen_beli,
                        'diskon_beli_rupiah' => $nonexpired->diskon_rupiah_beli,
                        'total_diskon_beli' => $total_diskon,
                        'hpp' => $hpp,
                        'laba_kotor' =>  $nett - $hpp
                    );
                }
            } else {
                foreach ($item->pengirimanbarangdetail->stokexpdetail as $expired) {
                    $subtotalexpired = $expired->qty * $expired->harga_beli * -1;
                    $total_diskon_expired = ($expired->diskon_persen_beli * $subtotalexpired / 100) + $expired->diskon_rupiah_beli;
                    $hpp_expired = ($subtotalexpired - $total_diskon_expired) * 1.11;

                    //  penjualan
                    $totalJual = $expired->qty * $item->hargajual * -1;
                    $subtotalPenjualanExpired  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                    if ($item->pph) {
                        $pph = $subtotalPenjualanExpired * $item->pph / 100;
                    } else {
                        $pph = 0;
                    }
                    $cnExpired = $subtotalPenjualanExpired * $item->cn_persen / 100;
                    $nettExpired = $subtotalPenjualanExpired - $cnExpired - $pph;
                    $labarugi[] = array(                        
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'products' => $item->products->nama,                        
                        'product_id' => $item->products->id,
                        'merk' => $item->products->merks->nama,
                        'qty' => $expired->qty * -1,
                        'hargajual' => $item->hargajual,
                        'diskon_persen' => $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => $item->total_diskon,
                        'subtotal' => $subtotalPenjualanExpired,
                        'total' => $subtotalPenjualanExpired,
                        'cn_rupiah' => $cnExpired,
                        'nett' => $nettExpired,
                        'harga_beli' => $expired->harga_beli,
                        'diskon_beli_persen' => $expired->diskon_persen_beli,
                        'diskon_beli_rupiah' => $expired->diskon_rupiah_beli,
                        'total_diskon_beli' => $total_diskon_expired,
                        'hpp' => $hpp_expired,
                        'laba_kotor' =>  $nettExpired - $hpp_expired
                    );
                }
            }
        }
        $total_laba_kotor = [];

        // Array untuk menyimpan laba kotor per customer
        $labaKotorPerSupplier = [];

        // Iterasi data transaksi
        foreach ($labarugi as $transaksi) {
            $productId = $transaksi['product_id'];
            $productName = $transaksi['products'];
            $labaKotor = $transaksi['laba_kotor'];
            $merk = $transaksi['merk'];
            $qty = $transaksi['qty'];

            // Jika customer belum ada dalam array, inisialisasi
            if (!isset($labaKotorPerSupplier[$productId])) {
                $labaKotorPerSupplier[$productId] = [
                    'nama' => $productName,
                    'id' => $productId,
                    'merk' => $merk,
                    'laba_kotor' => 0,
                    'qty' => 0
                ];
            }

            // Tambahkan laba kotor ke customer yang sesuai
            $labaKotorPerSupplier[$productId]['laba_kotor'] += $labaKotor;
            $labaKotorPerSupplier[$productId]['qty'] += $qty;
        }

        // Format ulang hasil sebagai array indeks numerik
        $output = array_values($labaKotorPerSupplier);

        // Urutkan berdasarkan laba kotor dari terbesar ke terkecil
        usort($output, function ($a, $b) {
            return $b['laba_kotor'] <=> $a['laba_kotor'];
        });


        return DataTables::of($output)
            ->addIndexColumn()
            ->editColumn('nama', function ($k) {
                return $k['nama'];
            })
            ->editColumn('merk', function ($k) {
                return $k['merk'];
            })
            ->editColumn('qty', function ($k) {
                return $k['qty'];
            })
            ->editColumn('laba_kotor', function ($k) {
                return  number_format($k['laba_kotor'], 0, ',', '.');
            })->addColumn('action', function ($row) {
                $product_id =  $row['id'];
                return view('laporan.labarugi.partial._form-action-product', compact('product_id'));
            })
            ->make(true);
    }

    public function datatableProductPerCustomer(Request $request)
    {
        $fakturpenjualan =  FakturPenjualanDetail::whereHas('fakturpenjualan.SO', function ($q) use ($request) {
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
            ->with('pengirimanbarangdetail.stokexpdetail')
            ->with('pengirimanbarangdetail.harganonexpireddetail')
            ->whereHas('fakturpenjualan', function ($q) use ($request) {
                $q->whereYear('tanggal', '=', $request->year);

                if ($request->bulan !== 'All') {
                    $q->whereMonth('tanggal', $request->bulan);
                }
            })
            ->where('product_id',$request->product_id)
            ->get();
        
        // dd($fakturpenjualan);

        $labarugi = [];
        foreach ($fakturpenjualan as $item) {
            if ($item->products->status_exp == 0) {
                foreach ($item->pengirimanbarangdetail->harganonexpireddetail as $nonexpired) {

                    $subtotal = $nonexpired->qty * $nonexpired->harga_beli * -1;
                    $total_diskon = ($nonexpired->diskon_persen_beli * $subtotal / 100) + $nonexpired->diskon_rupiah_beli;
                    $hpp = ($subtotal - $total_diskon) * 1.11;

                    //  penjualan
                    $totalJual = $nonexpired->qty * $item->hargajual * -1;
                    $subtotalPenjualan  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                    if ($item->pph) {
                        $pph = $subtotalPenjualan * $item->pph / 100;
                    } else {
                        $pph = 0;
                    }
                    $cn = $subtotalPenjualan * $item->cn_persen / 100;
                    $nett = $subtotalPenjualan - $cn - $pph;

                    $labarugi[] = array(                        
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'customers' => $item->fakturpenjualan->customers->nama,                        
                        'customer_id' => $item->fakturpenjualan->customers->id,                       
                        'qty' => $nonexpired->qty * -1,
                        'hargajual' => $item->hargajual,
                        'diskon_persen' => $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => $item->total_diskon,
                        'subtotal' => $subtotalPenjualan,
                        'total' => $subtotalPenjualan,
                        'cn_rupiah' => $cn,
                        'nett' => $subtotalPenjualan - $cn,
                        'harga_beli' => $nonexpired->harga_beli,
                        'diskon_beli_persen' => $nonexpired->diskon_persen_beli,
                        'diskon_beli_rupiah' => $nonexpired->diskon_rupiah_beli,
                        'total_diskon_beli' => $total_diskon,
                        'hpp' => $hpp,
                        'laba_kotor' =>  $nett - $hpp
                    );
                }
            } else {
                foreach ($item->pengirimanbarangdetail->stokexpdetail as $expired) {
                    $subtotalexpired = $expired->qty * $expired->harga_beli * -1;
                    $total_diskon_expired = ($expired->diskon_persen_beli * $subtotalexpired / 100) + $expired->diskon_rupiah_beli;
                    $hpp_expired = ($subtotalexpired - $total_diskon_expired) * 1.11;

                    //  penjualan
                    $totalJual = $expired->qty * $item->hargajual * -1;
                    $subtotalPenjualanExpired  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                    if ($item->pph) {
                        $pph = $subtotalPenjualanExpired * $item->pph / 100;
                    } else {
                        $pph = 0;
                    }
                    $cnExpired = $subtotalPenjualanExpired * $item->cn_persen / 100;
                    $nettExpired = $subtotalPenjualanExpired - $cnExpired - $pph;
                    $labarugi[] = array(                        
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'customers' => $item->fakturpenjualan->customers->nama,                        
                        'customer_id' => $item->fakturpenjualan->customers->id,                       
                        'qty' => $expired->qty * -1,
                        'hargajual' => $item->hargajual,
                        'diskon_persen' => $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => $item->total_diskon,
                        'subtotal' => $subtotalPenjualanExpired,
                        'total' => $subtotalPenjualanExpired,
                        'cn_rupiah' => $cnExpired,
                        'nett' => $nettExpired,
                        'harga_beli' => $expired->harga_beli,
                        'diskon_beli_persen' => $expired->diskon_persen_beli,
                        'diskon_beli_rupiah' => $expired->diskon_rupiah_beli,
                        'total_diskon_beli' => $total_diskon_expired,
                        'hpp' => $hpp_expired,
                        'laba_kotor' =>  $nettExpired - $hpp_expired
                    );
                }
            }
        }
        $total_laba_kotor = [];

        // Array untuk menyimpan laba kotor per customer
        $labaKotorPerSupplier = [];

        // Iterasi data transaksi
        foreach ($labarugi as $transaksi) {
            $customerId = $transaksi['customer_id'];
            $customerName = $transaksi['customers'];
            $labaKotor = $transaksi['laba_kotor'];            
            $qty = $transaksi['qty'];

            // Jika customer belum ada dalam array, inisialisasi
            if (!isset($labaKotorPerSupplier[$customerId])) {
                $labaKotorPerSupplier[$customerId] = [
                    'nama' => $customerName,
                    'id' => $customerId,        
                    'laba_kotor' => 0,
                    'qty' => 0
                ];
            }

            // Tambahkan laba kotor ke customer yang sesuai
            $labaKotorPerSupplier[$customerId]['laba_kotor'] += $labaKotor;
            $labaKotorPerSupplier[$customerId]['qty'] += $qty;
        }

        // Format ulang hasil sebagai array indeks numerik
        $output = array_values($labaKotorPerSupplier);

        // Urutkan berdasarkan laba kotor dari terbesar ke terkecil
        usort($output, function ($a, $b) {
            return $b['laba_kotor'] <=> $a['laba_kotor'];
        });


        return DataTables::of($output)
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
}
