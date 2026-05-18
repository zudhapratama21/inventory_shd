<?php

namespace App\Http\Controllers\Penjualan;

use App\Exports\SyncronisasiDataExport;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\TempSj;
use App\Models\Product;
use App\Models\StokExp;
use App\Traits\CodeTrait;

use Illuminate\Http\Request;
use App\Models\StokExpDetail;
use App\Models\PengirimanBarang;
use App\Models\PesananPenjualan;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\FakturPenjualanDetail;
use App\Models\HargaNonExpired;
use App\Models\HargaNonExpiredDetail;
use App\Models\InventoryTransaction;
use App\Models\PenerimaanBarangDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\PengirimanBarangDetail;
use App\Models\PesananPenjualanDetail;
use App\Models\Satuan;
use Exception;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Svg\Tag\Rect;

class PengirimanBarangController extends Controller
{
    use CodeTrait;
    function __construct()
    {
        $this->middleware('permission:pengirimanbarang-list');
        $this->middleware('permission:pengirimanbarang-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:pengirimanbarang-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:pengirimanbarang-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = "Pengiriman Barang";
        $pengirimanbarang = PengirimanBarang::with(['customers'])->orderByDesc('id');

        if (request()->ajax()) {
            return Datatables::of($pengirimanbarang)
                ->addIndexColumn()
                ->addColumn('customer', function (PengirimanBarang $sj) {
                    return $sj->customers->nama;
                })
                ->editColumn('tanggal', function (PengirimanBarang $sj) {
                    return $sj->tanggal ? with(new Carbon($sj->tanggal))->format('d-m-Y') : '';
                })
                 ->editColumn('tanggal', function (PengirimanBarang $sj) {
                    return $sj->tanggal ? with(new Carbon($sj->tanggal))->format('d-m-Y') : '';
                })
                 ->editColumn('status', function (PengirimanBarang $sj) {
                    return $sj->status_sj_id;
                })              
                ->addColumn('action', function ($row) {
                    $editUrl = route('pengirimanbarang.edit', ['pengirimanbarang' => $row->id]);
                    $expUrl = route('pengirimanbarang.inputexp', ['pengirimanbarang' => $row->id]);
                    $showUrl = route('pengirimanbarang.show', ['pengirimanbarang' => $row->id]);
                    $id = $row->id;
                    $status = $row->status_sj_id;
                    return view('penjualan.pengirimanbarang._formAction', compact('editUrl', 'showUrl', 'id', 'status', 'expUrl'));
                })
                ->make(true);
        }

        return view('penjualan.pengirimanbarang.index', compact('title'));
    }

    public function listbarang()
    {
        $product = Product::with('merks')->where('status','Aktif')->get();
        return Datatables::of($product)
            ->addIndexColumn()
            ->editColumn('nama', function ($pb) {
                return $pb->nama;
            })
            ->editColumn('kode', function ($pb) {
                return $pb->kode;
            })
            ->editColumn('merk', function ($pb) {
                return $pb->merks->nama;
            })
            ->editColumn('satuan', function ($pb) {
                return $pb->satuan;
            })
            ->editColumn('stok', function ($pb) {
                return $pb->stok;
            })
            ->addColumn('action', function ($pb) {
                if ($pb->stok > 0) {
                    return '<button type="button" class="btn btn-sm btn-outline-primary" onclick="pilihbarang(' . $pb->id . ')">
                    Pilih
                </button>';
                } else {
                    return '<span class="text-danger">Stok Habis</span>';
                }
            })
            ->make(true);
    }

    public function setbarang(Request $request)
    {
        $product = Product::where('id', '=', $request->id)->first();
        $satuan = Satuan::get();
        return view('penjualan.pengirimanbarang.modal._setbarang', compact('product', 'satuan'));
    }


    public function inputbarang(Request $request)
    {
        $productId = $request->product_id;
        $product = Product::where('id', $productId)->first();
        $namaSession = 'PB' . Auth::user()->id;
        $items = session()->get($namaSession, []);

        // cek stok 
        if ($request->beda_satuan == 'on') {
            $qtyTerpilih = $request->qty * $request->qty_konversi;
            if ($product->stok < $qtyTerpilih) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Qty Melebihi Stok'
                ], 422);
            }
        } else {
            if ($product->stok < $request->qty) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Qty Melebihi Stok'
                ], 422);
            }
        }

        if (isset($items[$productId])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Produk Telah Terpilih'
            ], 422);
        } else {
            $items[$productId] = [
                'product_id'     => $productId,
                'qty'            => $request->qty,
                'satuan'         => $request->satuan,
                'beda_satuan'    => $request->beda_satuan,
                'satuan_konversi' => $request->satuan_konversi,
                'qty_konversi'     => $request->qty_konversi,
                'keterangan'     => $request->keterangan,
            ];
        }

        session()->put($namaSession, $items);

        return response()->json([
            'status' => 'success',
            'data'   => $items
        ]);
    }

    public function databarang()
    {
        $namaSession = 'PB' . Auth::user()->id;

        $items = session($namaSession, []);

        $data = [];

        if (!empty($items)) {
            foreach ($items as $value) {

                // amanin kalau key tidak ada
                $productId = $value['product_id'] ?? null;

                if (!$productId) {
                    continue; // skip kalau tidak ada product_id
                }

                $product = Product::select('nama')->where('id', $productId)->first();

                if ($value['beda_satuan'] == 'on') {
                    $satuan = $value['satuan_konversi'];
                } else {
                    $satuan = $value['satuan'];
                }

                $data[] = [
                    'id'              => $productId,
                    'nama'            => optional($product)->nama,
                    'qty'             => $value['qty'] ?? 0,
                    'satuan'          => $satuan,
                    'beda_satuan'     => $value['beda_satuan'] ?? null,
                    'satuan_konversi' => $value['satuan_konversi'] ?? null,
                    'qty_konversi'     => $value['qty_konversi'],
                    'keterangan'     => $value['keterangan'],
                ];
            }
        }

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($pb) {
                return '<button type="button" class="btn btn-danger btn-sm " onclick="hapusbarang(' . $pb['id'] . ')">Hapus</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function hapusItem(Request $request)
    {
        $productId = $request->id;
        $namaSession = 'PB' . Auth::user()->id;

        // ambil session lama
        $items = session()->get($namaSession, []);

        // hapus item berdasarkan product_id
        unset($items[$productId]);

        // simpan kembali ke session
        session()->put($namaSession, $items);

        return response()->json([
            'status' => 'success',
            'data'   => $items
        ]);
    }

    public function create()
    {
        $title = "Pengiriman Barang";
        $customer = Customer::get();
        $now = Carbon::parse(now())->format('d-m-Y');

        return view('penjualan.pengirimanbarang.create', compact('title', 'customer', 'now'));
    }

    public function storePB(Request $request)
    {
        DB::beginTransaction();
        try {
            $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');
            $namaSession = 'PB' . Auth::user()->id;

            // simpan dulu pengirimanbarang dan dapatkan id nya 
            // simpan barang yang ada di session dan simpan juga di inventory transaction 
            // cek status exp nya apakah ada yang expired .. kalau tidak ada maka semua di kasih 1 dan status sj berubah jadi 2  
            // status sj => 1 => belum input expired , 2 => menunggu di faktur , 3 => terfaktur sebagian , 4 => sudah terfaktur

            $pengirimanbarang = PengirimanBarang::create([
                'kode' => $this->getKodeTransaksi("pengiriman_barangs", "SJ"),
                'tanggal' => $tanggal,
                'customer_id' => $request->customer,
                'status_sj_id' => 1,
                'status_exp' => 0,
                'keterangan' => $request->keterangan
            ]);

            $customer = Customer::where('id', $request->customer)->select('nama')->first();

            $items = session($namaSession, []);

            if (count($items) < 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Item Kosong !!'
                ], 422);
            }

            foreach ($items as $item) {
                $product = Product::where('id', $item['product_id'])->select('status_exp', 'stok')->first();            

                PengirimanBarangDetail::create([
                    'tanggal' => $tanggal,
                    'pengiriman_barang_id' => $pengirimanbarang->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'qty_sisa' => $item['qty'],
                    'satuan' => $item['satuan'],
                    'beda_satuan'     => $item['beda_satuan'] ?? null,
                    'satuan_konversi' => $item['satuan_konversi'] ?? null,
                    'qty_konversi'      => $item['qty_konversi'] ?? 0,
                    'keterangan'      => $item['keterangan'] ?? 0,
                    'status_exp'      => 0
                ]);
                $qty = $item['qty'];
                $ket = '-';
                if ($item['beda_satuan'] == 'on') {
                    $qty = ($item['qty'] * $item['qty_konversi']);
                    $ket = "Pengiriman dengan beda satuan .[ Penjualan produk dengan satuan " . $item['satuan_konversi'] . " dengan satuan produk asli adalah " .  $item['satuan'] . " ]";
                }
                $stokProduk = $product->stok - $qty;

                Product::where('id', $item['product_id'])->update([
                    'stok' => $stokProduk
                ]);

                // simpan di inventory transaction        
                $invtransaction = InventoryTransaction::create([
                    'tanggal' => $tanggal,
                    'product_id' => $item['product_id'],
                    'qty' => (0 - $qty),
                    'stok' => $stokProduk,
                    'hpp' => 0,
                    'jenis' => 'SJ',
                    'jenis_id' => $pengirimanbarang->kode,
                    'customer' => $customer->nama,
                    'keterangan' => $ket
                ]);
            }

            // cek status exp di pengiriman barang
            $cekExp = PengirimanBarangDetail::where('pengiriman_barang_id', $pengirimanbarang->id)->where('status_exp', 0)->first();
            if (!$cekExp) {
                $pengirimanbarang->update([
                    'status_exp' => 1,
                    'status_sj_id' => 2
                ]);
            }

            session()->forget($namaSession);

            DB::commit();


            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Disimpan'
            ]);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }

    public function show(PengirimanBarang $pengirimanbarang)
    {
        $title = "pengiriman Barang Detail";
        $pengirimanbarangdetails = PengirimanBarangDetail::with('products')
            ->where('pengiriman_barang_id', '=', $pengirimanbarang->id)->get();
        $listExp = StokExpDetail::where('id_sj', '=', $pengirimanbarang->id)->get();
        return view('penjualan.pengirimanbarang.show', compact('title', 'listExp', 'pengirimanbarang', 'pengirimanbarangdetails'));
    }

    public function datatablebarang(Request $request)
    {
        $sodet = PesananPenjualanDetail::with('products', 'tempsj')->where('pesanan_penjualan_id', '=', $request->id);
        return Datatables::of($sodet)
            ->addIndexColumn()
            ->editColumn('product', function ($pb) {
                return $pb->products->nama;
            })
            ->editColumn('kode', function ($pb) {
                return $pb->products->kode;
            })
            ->editColumn('satuan', function ($pb) {
                return $pb->products->satuan;
            })
            ->editColumn('stok', function ($pb) {
                return $pb->products->stok;
            })
            ->editColumn('qty_sisa', function ($pb) {
                $sisa = $pb->qty_sisa;
                if ($pb->tempsj) {
                    $sisa = $pb->qty_sisa - $pb->tempsj->qty;
                }
                return $sisa;
            })
            ->editColumn('status', function ($pb) {
                if ($pb->tempsj) {
                    return 1;
                } else {
                    return 0;
                }
            })
            ->addColumn('action', function ($pb) {
                return $pb->id;
            })
            ->make(true);
    }

    public function daftarbarang(Request $request)
    {
        $tempsj = TempSj::with(['products'])->where('user_id', '=', Auth::user()->id);
        return Datatables::of($tempsj)
            ->addIndexColumn()
            ->editColumn('product', function ($pb) {
                return $pb->products->nama;
            })
            ->editColumn('kode', function ($pb) {
                return $pb->products->kode;
            })
            ->editColumn('satuan', function ($pb) {
                return $pb->products->satuan;
            })
            ->addColumn('action', function ($pb) {
                return $pb->id;
            })
            ->make(true);
    }



    public function inputtempsj(Request $request)
    {

        DB::beginTransaction();
        try {
            $id_detail = $request->detail_id;
            $qty_kirim = $request->qty;
            $keterangan = $request->keterangan;

            $detailSO = PesananPenjualanDetail::with('products')->where('id', '=', $id_detail)->first();
            $product_id = $detailSO->product_id;
            $qty_pesanan = $detailSO->qty;
            $satuan = $detailSO->satuan;
            $qty_sisa = $detailSO->qty_sisa;
            $qty_sisa_kirim = $qty_sisa - $qty_kirim;

            $product = Product::find($product_id);
            $stok = $product->stok;

            if ($qty_kirim > 0) {
                if ($qty_kirim <= $stok) {
                    if ($qty_kirim <= $qty_sisa) {
                        $datas['pesanan_penjualan_detail_id'] = $id_detail;
                        $datas['product_id'] = $product_id;
                        $datas['qty'] = $qty_kirim;
                        $datas['qty_sisa'] = $qty_sisa;
                        $datas['qty_pesanan'] = $qty_pesanan;
                        $datas['satuan'] = $satuan;
                        $datas['keterangan'] = $keterangan;
                        $datas['user_id'] = Auth::user()->id;
                        TempSj::create($datas);
                    } else {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Qty tidak boleh melebihi sisa kirim'
                        ], 422);
                    }
                } else {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Qty tidak boleh melebihi stok'
                    ], 422);
                }
            } else {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Qty tidak boleh 0'
                ], 422);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Disimpan'
            ]);
        } catch (Exception $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }

    public function deletetemp(Request $request)
    {
        $id = $request->id;
        TempSj::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Data Berhasil Disimpan'
        ]);
    }

    public function store(Request $request, PesananPenjualan $pesananpenjualan)
    {

        $request->validate([
            'tanggal' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            $datas = $request->all();
            $tanggal = $request->tanggal;
            if ($tanggal <> null) {
                $tanggal = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
            }
            $pesanan_penjualan_id = $pesananpenjualan->id;
            $customer_id = $pesananpenjualan->customer_id;


            $customer_name = Customer::findOrFail($customer_id);

            $dataTemp = TempSj::where('user_id', '=', Auth::user()->id)->get();
            $jmlTemp = $dataTemp->count();

            if ($jmlTemp < 1) {
                return redirect()->route('pengirimanbarang.index')->with('gagal', 'Tidak ada barang yang diinputkan, Pengiriman Barang Gagal Disimpan!');
            }

            $kode = $this->getKodeTransaksi("pengiriman_barangs", "SJ");
            $datas['kode'] = $kode;
            $datas['tanggal'] = $tanggal;
            $datas['pesanan_penjualan_id'] = $pesanan_penjualan_id;
            $datas['customer_id'] = $customer_id;
            $datas['status_sj_id'] = "1";

            $id_sj = PengirimanBarang::create($datas)->id;

            //isi detail
            foreach ($dataTemp as $a) {
                /////// calkulasi HPP  ///////
                $detailPesanan = PesananPenjualanDetail::find($a->pesanan_penjualan_detail_id);
                $hargajual = $detailPesanan->hargajual;
                $diskon_persen = $detailPesanan->diskon_persen;
                $diskon_rp = $detailPesanan->diskon_rp;
                $totaldiskon = ($hargajual * ($diskon_persen / 100)) + $diskon_rp;
                $hargajual_fix = $hargajual - $totaldiskon;

                $product_id = $a->product_id;
                $product = new Product;
                $product = Product::find($product_id);
                $stok_lama = $product->stok;
                $hpp = $product->hpp;
                $status_exp = $product->status_exp;

                // if ($status_exp == 1) {
                //     $status_exp_detil = 0;
                // } else {
                //     $status_exp_detil = 1;
                // }

                $stok_baru = $stok_lama - $a->qty;
                $product->stok = $stok_baru;
                $product->save();
                ////////// end hpp //////////

                // ########## start input detail ###########
                $detail = new PengirimanBarangDetail;
                $detail->tanggal = $tanggal;
                $detail->pengiriman_barang_id = $id_sj;
                $detail->pesanan_penjualan_id = $pesanan_penjualan_id;
                $detail->pesanan_penjualan_detail_id = $a->pesanan_penjualan_detail_id;
                $detail->product_id = $product_id;
                $detail->qty = $a->qty;
                $detail->qty_sisa = $a->qty_sisa;
                $detail->qty_pesanan = $a->qty_pesanan;
                $detail->satuan = $a->satuan;
                $detail->keterangan = $a->keterangan;
                $detail->status_exp = 0;
                $detail->save();
                // ########## end input detail #############

                //######### start update stok ##############
                // $product = new Product;
                // $product = Product::find($product_id)->first();
                // $product->stok = $stok_baru;
                // $product->hpp = $h pp;
                // $product->save();
                //######### end update stok ################

                //######### start add INV TRANS ############
                $inventoryTrans = new InventoryTransaction;
                $inventoryTrans->tanggal = $tanggal;
                $inventoryTrans->product_id = $product_id;
                $inventoryTrans->qty = (0 - $a->qty);
                $inventoryTrans->stok = $stok_baru;
                $inventoryTrans->hpp = $hpp;
                $inventoryTrans->jenis = "SJ";
                $inventoryTrans->jenis_id = $kode;
                $inventoryTrans->customer = $customer_name->nama;

                $inventoryTrans->save();

                //######### end add INV TRANS ############

                //############# start update Qty Sisa SO #############
                $detailSOupdate = new PesananPenjualanDetail;
                $detailSOupdate = PesananPenjualanDetail::find($a->pesanan_penjualan_detail_id);
                $detailSOupdate->qty_sisa = ($a->qty_sisa - $a->qty);
                $detailSOupdate->save();
                //############# end update Qty Sisa SO #############
            }
            //############# start update status SO #############
            $totalPesananSO = PesananPenjualanDetail::where('pesanan_penjualan_id', '=', $pesanan_penjualan_id)->sum('qty');
            $totalSisaSO = PesananPenjualanDetail::where('pesanan_penjualan_id', '=', $pesanan_penjualan_id)->sum('qty_sisa');
            $terkirim = $totalPesananSO - $totalSisaSO;

            if ($terkirim == $totalPesananSO) {
                $status = "4";
            } else {
                $status = "3";
            }
            $SOmain = PesananPenjualan::find($pesanan_penjualan_id);
            $SOmain->status_so_id = $status;
            $SOmain->save();
            //############# end update status SO #############
            DB::commit();
            return redirect()->route('pengirimanbarang.index')->with('status', 'Pengiriman barang berhasil dibuat !');
        } catch (Exception $th) {
            DB::rollBack();
        }
    }


    public function inputexp(PengirimanBarang $pengirimanbarang)
    {
        $title = "Pengaturan Expired Date";
        $pengirimanbarang_id =  $pengirimanbarang->id;
        $detailItem = PengirimanBarangDetail::where('pengiriman_barang_id', '=', $pengirimanbarang_id)->get();
        return view('penjualan.pengirimanbarang.inputexp', compact('pengirimanbarang', 'title', 'detailItem'));
    }

    public function setdaftarkirim($id)
    {
        $title = "Pengaturan Expired Date";
        $pengirimandet = PengirimanBarangDetail::with('products', 'PengirimanBarangs')->where('id', $id)->first();
        return view('penjualan.pengirimanbarang.setexp', compact('pengirimandet', 'title'));
    }

    public function daftarProduk(Request $request)
    {
        $stok = StokExp::with(['stokExpDetail' => function ($query) use ($request) {
            $query->where('id_sj_detail', $request->pengirimandet);
        }, 'products', 'supplier'])->where('product_id', $request->product_id)->where('qty', '>', '0');

        return Datatables::of($stok)
            ->addIndexColumn()
            ->editColumn('tanggal', function ($pb) {
                return Carbon::parse($pb->tanggal)->format('d-m-Y');
            })
            ->editColumn('qty', function ($pb) {
                return $pb->qty;
            })
            ->editColumn('status', function ($pb) {
                if (count($pb->stokExpDetail) > 0) {
                    return 1;
                } else {
                    return 0;
                }
            })
            ->addColumn('action', function ($pb) {
                return $pb->id;
            })
            ->make(true);
    }

    public function daftarProdukKirim(Request $request)
    {
        $stok = StokExpDetail::with(['stockExp.supplier', 'products'])->where('id_sj_detail', $request->pengirimandet);
        return Datatables::of($stok)
            ->addIndexColumn()
            ->editColumn('tanggal', function ($pb) {
                return Carbon::parse($pb->tanggal)->format('d-m-Y');
            })
            ->editColumn('qty', function ($pb) {
                return $pb->qty * -1;
            })
            ->editColumn('lot', function ($pb) {
                return $pb->stockExp->lot;
            })
            ->editColumn('supplier', function ($pb) {
                return $pb->stockExp->supplier ? $pb->stockExp->supplier->nama : '-';
            })
            ->addColumn('action', function ($pb) {
                $id = $pb->id;
                return view('penjualan.pengirimanbarang.partial.actionbarang', compact('id'));
            })
            ->make(true);
    }

    public function formBarang(Request $request)
    {
        $stok = StokExp::where('id', $request->id)->first();
        return view('penjualan.pengirimanbarang.modal.formbarang', compact('stok'));
    }

    public function simpanProdukKirim(Request $request)
    {
        DB::beginTransaction();
        try {
            $qty = $request->qty;
            if ($qty < 1) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Qty tidak boleh 0'
                ], 422);
            }

            $pengirimandet = PengirimanBarangDetail::where('id', $request->pengirimandet)->first();
            // $stokdet = StokExpDetail::where('id_sj_detail', $request->pengirimandet)->sum('qty') * -1;
            $stok = StokExp::where('id', $request->stok_id)->first();

            

            // cek qty kirim jika beda satuan 
            if ($pengirimandet->beda_satuan == 'on') {
                $qty = $qty * $pengirimandet->qty_konversi;
            }

            if ($stok->qty < $qty || $qty > $pengirimandet->qty_sisa) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Qty tidak boleh melebihi stok atau qty pengiriman'
                ], 422);
            }

            // $total = $stokdet + $qty;        
            $stokbaru = $stok->qty - $qty;
            $stok->update([
                'qty' => $stokbaru
            ]);


            //insert detail
            StokExpDetail::create([
                'tanggal' => $stok->tanggal,
                'stok_exp_id' => $stok->id,
                'product_id' => $pengirimandet->product_id,
                'qty' => $qty * -1,
                'id_sj' => $pengirimandet->pengiriman_barang_id,
                'id_sj_detail' => $pengirimandet->id,
                'harga_beli' => $stok->harga_beli,
                'diskon_persen_beli' => $stok->diskon_persen,
                'diskon_rupiah_beli' => $stok->diskon_rupiah,
            ]);

            $this->updateStatuData($pengirimandet->id, $request->status, $pengirimandet->qty, $pengirimandet->pengiriman_barang_id);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Disimpan'
            ]);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }


    public function hapusbarang(Request $request)
    {
        DB::beginTransaction();
        try {
            $pengirimandet = PengirimanBarangDetail::where('id', $request->pengirimandet)->first();            
            $stokdet = StokExpDetail::where('id', $request->id)->first();
            $stok = StokExp::where('id', $stokdet->stok_exp_id)->first();
            $qtytot = $stok->qty + ($stokdet->qty * -1);
            $stok->update([
                'qty' => $qtytot
            ]);
            $stokdet->delete();

            

            $this->updateStatuData($pengirimandet->id, $request->status, $pengirimandet->qty, $pengirimandet->pengiriman_barang_id);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Disimpan'
            ]);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }

    public function editexp(Request $request)
    {

        if ($request->status == 1) {
            $stok = StokExpDetail::where('id', $request->id)->first();
        } else {
            $stok = HargaNonExpiredDetail::where('id', $request->id)->first();
        }

        return view('penjualan.pengirimanbarang.modal.formexp', compact('stok'));
    }

    public function submitexp(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->harga_beli == 0) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Harga beli tidak boleh 0'
                ], 422);
            }
            if ($request->status == 1) {
                $stok = StokExpDetail::where('id', $request->id)->update([
                    'harga_beli' => $request->harga_beli,
                    'diskon_persen_beli' => $request->diskon_persen,
                    'diskon_rupiah_beli' => $request->diskon_rupiah
                ]);
            } else {
                $stok = HargaNonExpiredDetail::where('id', $request->id)->update([
                    'harga_beli' => $request->harga_beli,
                    'diskon_persen_beli' => $request->diskon_persen,
                    'diskon_rupiah_beli' => $request->diskon_rupiah
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Disimpan'
            ]);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {

        DB::beginTransaction();
        try {
            $tglNow = Carbon::now()->format('Y-m-d');
            $id = $request->id;
            $pengirimanbarang = PengirimanBarang::where('id', $id)->first();
            $fakturdet = FakturPenjualanDetail::where('pengirimanbarang_id',$id)->first();
            

            $customer = Customer::findOrFail($pengirimanbarang->customer_id);

            $pengirimanbarang_kode = $pengirimanbarang->kode;

            if ($fakturdet) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak bisa menghapus Surat Jalan , Surat Jalan sudah terfaktur , Hapus faktur terlebih dahulu'
                ], 422);
            }
            //validasi :
            $jmlExp = StokExpDetail::where('id_sj', '=', $id)->count();

            if ($jmlExp > 0) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak bisa menghapus Surat Jalan , hapus terlebih dahulu data expired date / lot di surat jalan ini'
                ], 422);
            }

            $detailSJ = PengirimanBarangDetail::where('pengiriman_barang_id', '=', $id)->get();
            foreach ($detailSJ as $a) {
                //update stok  dan cek jenis beda satuanya
                $qty = $a->qty;
                if ($a->beda_satuan == 'on') {
                    $qty = $a->qty * $a->qty_konversi;
                }


                $product = Product::find($a->product_id);
                $stok = $product->stok;
                $hpp = $product->hpp;
                $product->stok = $stok + $qty;
                $product->save();
                $stok_baru = $stok + $qty;

                //input inv trans
                //######### start add INV TRANS ############
                $inventoryTrans = new InventoryTransaction;
                $inventoryTrans->tanggal = $tglNow;
                $inventoryTrans->product_id = $a->product_id;
                $inventoryTrans->qty = $qty;
                $inventoryTrans->stok = $stok_baru;
                $inventoryTrans->hpp = $hpp;
                $inventoryTrans->jenis = "SJ (DEL)";
                $inventoryTrans->jenis_id = $pengirimanbarang_kode;
                $inventoryTrans->customer = $customer->nama;

                $inventoryTrans->save();
                //######### end add INV TRANS ############               
            }

            $pengirimanbarang->deleted_by = Auth::user()->id;
            $pengirimanbarang->save();
            PengirimanBarang::destroy($request->id);

            $detailSJ->each->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Disimpan'
            ]);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }

    public function print_a5(PengirimanBarang $pengirimanbarang)
    {

        $title = "Print Surat Jalan";
        $pengirimanbarangdetails = PengirimanBarangDetail::with(['products'])
            ->where('pengiriman_barang_id', '=', $pengirimanbarang->id)->get();

        // dd($pengirimanbarangdetails);
        $jmlBaris  = $pengirimanbarangdetails->count();        
        // $totalPage = ceil($jmlBaris / $perBaris);
        $listExp = StokExpDetail::with('products','stockExp','pengirimandetail')->where('id_sj', '=', $pengirimanbarang->id)->get();        
        $hitunglist = count($listExp);                
        //dd($listExp);
        $data = [            
            'list' => $hitunglist,
            'date' => date('m/d/Y'),
            'listExp' => $listExp,
            'pengirimanbarang' => $pengirimanbarang,
            'pengirimanbarangdetails' => $pengirimanbarangdetails
        ];

        $pdf = PDF::loadView('penjualan.pengirimanbarang.print_a5', $data)->setPaper('a5', 'landscape');;
        return $pdf->download($pengirimanbarang->kode . '.pdf');

        //return view('penjualan.fakturpenjualan.print_a4', compact('title',  'totalPage'));
    }

    public function showData($id)
    {
        $pengirimanbarang = PengirimanBarang::where('kode', $id)->first();

        $title = "pengiriman Barang Detail";
        $pengirimanbarangdetails = PengirimanBarangDetail::with('products')
            ->where('pengiriman_barang_id', '=', $pengirimanbarang->id)->get();
        $listExp = StokExpDetail::where('id_sj', '=', $pengirimanbarang->id)->with('stockExp')->get();

        return view('penjualan.pengirimanbarang.show', compact('title', 'listExp', 'pengirimanbarang', 'pengirimanbarangdetails'));
    }


    public function syncronisasi()
    {
        return Excel::download(new SyncronisasiDataExport(), 'laporanpembelian.xlsx');
    }

    public function updateStatuData($pengirimandet, $status, $qty, $pengiriman)
    {
        $pengirimanbarangdet = PengirimanBarangDetail::where('id', $pengirimandet)->first();
        $stokdet = $pengirimanbarangdet->qty;
        if ($pengirimanbarangdet->beda_satuan == 'on') {
            $stokdet = $pengirimanbarangdet->qty_konversi * $pengirimanbarangdet->qty;
        }

        $stokexp = StokExpDetail::where('id_sj_detail', $pengirimandet)->sum('qty') * -1;
        $statusexp = 0;

        if ($stokexp == $stokdet) {
            $statusexp = 1;
        }

        $pengirimanbarangdet->update([
            'status_exp' => $statusexp
        ]);

        $pengirimandetail = PengirimanBarangDetail::where('pengiriman_barang_id', $pengiriman)->where('status_exp', 1)->first();
        
        if (!$pengirimandetail) {
            PengirimanBarang::where('id', $pengiriman)->update([
                'status_sj_id' => 1,
                'status_exp' => 0
            ]);
        } else {
            PengirimanBarang::where('id', $pengiriman)->update([
                'status_sj_id' => 2,
                'status_exp' => 1
            ]);
        }
    }


    public function edit ($id)
    {
        $title = "Edit Pengiriman Barang";
        $pengirimanbarang = PengirimanBarang::with('PengirimanBarangDetails.products')->find($id);
        $customer = Customer::get();
        $tanggal = Carbon::parse($pengirimanbarang->tanggal)->format('d-m-Y');

        return view('penjualan.pengirimanbarang.edit', compact('title', 'customer', 'tanggal', 'pengirimanbarang'));
    }

    public function updatePB (  Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');

            $pengirimanbarang = PengirimanBarang::find($id);
            $pengirimanbarang->update([
                'tanggal' => $tanggal,
                'customer_id' => $request->customer,
                'keterangan' => $request->keterangan
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Disimpan'
            ]);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }
}
