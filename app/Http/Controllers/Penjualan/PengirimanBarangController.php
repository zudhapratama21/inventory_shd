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
use App\Models\HargaNonExpired;
use App\Models\HargaNonExpiredDetail;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Auth;
use App\Models\PengirimanBarangDetail;
use App\Models\PesananPenjualanDetail;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
        $pengirimanbarang = PengirimanBarang::with(['customers',  'statusSJ', 'so'])
            ->with(['PengirimanBarangDetails' =>  function ($query) {
                $query->where('status_exp', 0);
            }])->orderByDesc('id');

        if (request()->ajax()) {
            return Datatables::of($pengirimanbarang)
                ->addIndexColumn()
                ->addColumn('customer', function (PengirimanBarang $sj) {
                    return $sj->customers->nama;
                })
                ->addColumn('kode_so', function (PengirimanBarang $sj) {
                    return $sj->so->kode;
                })
                ->addColumn('status', function (PengirimanBarang $sj) {
                    $status_pengiriman = $sj->status_sj_id;
                    $dataSj = $sj;
                    return view('penjualan.pengirimanbarang.partial._status', compact('status_pengiriman', 'dataSj'));
                })
                ->editColumn('tanggal', function (PengirimanBarang $sj) {
                    return $sj->tanggal ? with(new Carbon($sj->tanggal))->format('d-m-Y') : '';
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

    public function listso()
    {
        $title = "Daftar Pesanan Penjualan";
        $pesananpenjualans = PesananPenjualan::with('customers', 'statusSO')
            ->where('status_so_id', '<=', '3')
            ->where('status_so_id', '<>', '1')
            ->get();

        if (request()->ajax()) {
            return Datatables::of($pesananpenjualans)
                ->addIndexColumn()
                ->addColumn('customer', function (PesananPenjualan $so) {
                    return $so->customers->nama;
                })
                ->addColumn('status', function (PesananPenjualan $so) {
                    return $so->statusSO->nama;
                })
                ->editColumn('tanggal', function (PesananPenjualan $so) {
                    return $so->tanggal ? with(new Carbon($so->tanggal))->format('d-m-Y') : '';
                })
                ->addColumn('action', function ($row) {
                    $pilihUrl = route('pengirimanbarang.create', ['pesananpenjualan' => $row->id]);
                    $id = $row->id;
                    return view('penjualan.pengirimanbarang._pilihAction', compact('pilihUrl', 'id'));
                })
                ->make(true);
        }

        //dd($pesananpenjualan);
        return view('penjualan.pengirimanbarang.listso', compact('title', 'pesananpenjualans'));
    }

    public function create(PesananPenjualan $pesananpenjualan)
    {
        $title = "Pengiriman Barang";
        //delete temp
        $deletedTempDetil = TempSj::where('user_id', '=', Auth::user()->id)->delete();
        $deletedTempDetil = TempSj::where('created_at', '<', Carbon::today())->delete();

        return view('penjualan.pengirimanbarang.create', compact('title', 'pesananpenjualan'));
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

    public function setbarang(Request $request)
    {
        $product = PesananPenjualanDetail::with('products')->where('id', '=', $request->id)->first();
        return view('penjualan.pengirimanbarang._setbarang', compact('product'));
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
        if ($request->status == 1) {
            $stok = StokExp::with(['stokExpDetail' => function($query) use($request) {
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
                ->editColumn('supplier', function ($pb) {
                    return $pb->supplier ? $pb->supplier->nama : '-';
                })
                ->editColumn('harga_beli', function ($pb) {
                    return number_format($pb->harga_beli, 0, ',', '.');
                })
                ->editColumn('status', function ($pb) {
                    if (count($pb->stokExpDetail) > 0) {
                        return 1;
                    }else{
                        return 0;
                    }                    
                })
                ->addColumn('action', function ($pb) {                    
                    return $pb->id;
                })
                ->make(true);
        } else {
            $stok = HargaNonExpired::with(['harganonexpireddetail' => function($query) use($request) {
                $query->where('id_sj_detail', $request->pengirimandet);
            }, 'product', 'supplier'])->where('product_id', $request->product_id)->where('qty', '>', '0');
            return Datatables::of($stok)
                ->addIndexColumn()
                ->editColumn('tanggal', function ($pb) {
                    return 'Non Exp';
                })
                ->editColumn('qty', function ($pb) {
                    return $pb->qty;
                })
                ->editColumn('lot', function ($pb) {
                    return 'Non Exp';
                })
                ->editColumn('supplier', function ($pb) {
                    return $pb->supplier ? $pb->supplier->nama : '-';
                })
                ->editColumn('harga_beli', function ($pb) {
                    return number_format($pb->harga_beli, 0, ',', '.');
                })
                ->editColumn('status', function ($pb) {
                    return $pb->harganonexpireddetail ? 1 : 0;
                })
                ->addColumn('action', function ($pb) {
                    return $pb->id;
                })
                ->make(true);
        }
    }

    public function daftarProdukKirim(Request $request)
    {
        if ($request->status == 1) {
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
                ->editColumn('harga_beli', function ($pb) {
                    return number_format($pb->harga_beli, 0, ',', '.');
                })
                ->addColumn('action', function ($pb) {
                    $id = $pb->id;
                    return view('penjualan.pengirimanbarang.partial.actionbarang', compact('id'));
                })
                ->make(true);
        } else {
            $stok = HargaNonExpiredDetail::with(['harganonexpired.supplier', 'product'])->where('id_sj_detail', $request->pengirimandet);
            // dd($stok);
            return Datatables::of($stok)
                ->addIndexColumn()
                ->editColumn('tanggal', function ($pb) {
                    return 'Non Exp';
                })
                ->editColumn('qty', function ($pb) {
                    return $pb->qty * -1;
                })
                ->editColumn('lot', function ($pb) {
                    return 'Non Exp';
                })
                ->editColumn('supplier', function ($pb) {
                    return $pb->harganonexpired->supplier ? $pb->harganonexpired->supplier->nama : '-';
                })
                ->editColumn('harga_beli', function ($pb) {
                    return number_format($pb->harga_beli, 0, ',', '.');
                })
                ->addColumn('action', function ($pb) {
                    $id = $pb->id;
                    return view('penjualan.pengirimanbarang.partial.actionbarang', compact('id'));
                })
                ->make(true);
        }
    }

    public function formBarang(Request $request)
    {
        if ($request->status == 1) {
            $stok = StokExp::where('id', $request->id)->first();
        } else {
            $stok = HargaNonExpired::where('id', $request->id)->first();
        }
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
            if ($request->status == 1) {
                $stokdet = StokExpDetail::where('id_sj_detail', $request->pengirimandet)->sum('qty') * -1;
                $stok = StokExp::where('id', $request->stok_id)->first();
                if ($stok->qty < $qty) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Qty tidak boleh melebihi stok'
                    ], 422);
                }
                $total = $stokdet + $qty;
                if ($qty > $pengirimandet->qty || $total > $pengirimandet->qty) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Qty tidak boleh melebihi stok yang di kirim'
                    ], 422);
                }

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
            } else {
                $stokdet = HargaNonExpiredDetail::where('id_sj_detail', $request->pengirimandet)->sum('qty') * -1;
                $stok = HargaNonExpired::where('id', $request->stok_id)->first();
                if ($stok->qty < $qty) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Qty tidak boleh melebihi stok'
                    ], 422);
                }

                $total = $stokdet + $qty;                
                if ($qty > $pengirimandet->qty || $total > $pengirimandet->qty) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Qty tidak boleh melebihi stok yang di kirim'
                    ], 422);
                }

             

                $stokbaru = $stok->qty - $qty;
                $stok->update([
                    'qty' => $stokbaru
                ]);

                HargaNonExpiredDetail::create([
                    'tanggal' => now()->format('Y-m-d'),
                    'harganonexpired_id' => $stok->id,
                    'product_id' => $pengirimandet->product_id,
                    'qty' => $qty * -1,
                    'id_sj' => $pengirimandet->pengiriman_barang_id,
                    'id_sj_detail' => $pengirimandet->id,
                    'harga_beli' => $stok->harga_beli,
                    'diskon_persen_beli' => $stok->diskon_persen,
                    'diskon_rupiah_beli' => $stok->diskon_rupiah,
                ]);
            }

            $this->updateStatuData($pengirimandet->id,$request->status,$pengirimandet->qty,$pengirimandet->pengiriman_barang_id);
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
        try {
            $pengirimandet = PengirimanBarangDetail::where('id', $request->pengirimandet)->first();
            if ($request->status == 1) {
                $stokdet = StokExpDetail::where('id', $request->id)->first();
                $stok = StokExp::where('id', $stokdet->stok_exp_id)->first();
                $qtytot = $stok->qty + ($stokdet->qty * -1);
                $stok->update([
                    'qty' => $qtytot
                ]);
                $stokdet->delete();
            } else {
                $stokdet = HargaNonExpiredDetail::where('id', $request->id)->first();
                $stok = HargaNonExpired::where('id', $stokdet->harganonexpired_id)->first();
                $qtytot = $stok->qty + ($stokdet->qty * -1);
                $stok->update([
                    'qty' => $qtytot
                ]);
                HargaNonExpiredDetail::destroy($request->id);
            }

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

    public function editexp (Request $request)
    {
        
       if ($request->status == 1) {
           $stok = StokExpDetail::where('id',$request->id)->first();
       }else{
            $stok = HargaNonExpiredDetail::where('id',$request->id)->first();
       }

       return view('penjualan.pengirimanbarang.modal.formexp',compact('stok'));
    }

    public function submitexp (Request $request)
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
              $stok = StokExpDetail::where('id',$request->id)->update([
                    'harga_beli' => $request->harga_beli,
                    'diskon_persen_beli' => $request->diskon_persen,
                    'diskon_rupiah_beli' => $request->diskon_rupiah
              ]);  
            }else{
                $stok = HargaNonExpiredDetail::where('id',$request->id)->update([
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
            $pengirimanbarang = PengirimanBarang::with('FakturSO')->where('id',$id)->first();
            // dd($pengirimanbarang);
            $customer = Customer::findOrFail($pengirimanbarang->customer_id);
    
            $pengirimanbarang_kode = $pengirimanbarang->kode;
            $pesanan_penjualan_id = $pengirimanbarang->pesanan_penjualan_id;

            if (count($pengirimanbarang->FakturSo) > 0) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak bisa menghapus Surat Jalan , Surat Jalan sudah terfaktur'
                ], 422);
            }
            //validasi :
            $jmlExp = StokExpDetail::where('id_sj', '=', $id)->count();            
    
            // cek produk non expired 
            $jmlnonexp = HargaNonExpiredDetail::where('id_sj', '=', $id)->count();
    
            if ($jmlExp > 0 || $jmlnonexp > 0) {
                DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Tidak bisa menghapus Surat Jalan , hapus terlebih dahulu data kirim'
                    ], 422);
            }               
    
            $detailSJ = PengirimanBarangDetail::where('pengiriman_barang_id', '=', $id)->get();
            foreach ($detailSJ as $a) {
                //update stok                
                $product = Product::find($a->product_id);
                $stok = $product->stok;
                $hpp = $product->hpp;
                $product->stok = $stok + $a->qty;
                $product->save();
    
                $pesanan_penjualan_detail_id = $a->pesanan_penjualan_detail_id;
                $stok_baru = $stok + $a->qty;
    
                //input inv trans
                //######### start add INV TRANS ############
                $inventoryTrans = new InventoryTransaction;
                $inventoryTrans->tanggal = $tglNow;
                $inventoryTrans->product_id = $a->product_id;
                $inventoryTrans->qty = $a->qty;
                $inventoryTrans->stok = $stok_baru;
                $inventoryTrans->hpp = $hpp;
                $inventoryTrans->jenis = "SJ (DEL)";
                $inventoryTrans->jenis_id = $pengirimanbarang_kode;
                $inventoryTrans->customer = $customer->nama;
    
                $inventoryTrans->save();
                //######### end add INV TRANS ############
    
                //############# start update Qty Sisa SO #############
                $detailSOupdate = new PesananPenjualanDetail;
                $detailSOupdate = PesananPenjualanDetail::find($a->pesanan_penjualan_detail_id);
                $detailSOupdate->qty_sisa +=  $a->qty;
                $detailSOupdate->save();
                //############# end update Qty Sisa SO #############
    
            }

            $pengirimanbarang->deleted_by = Auth::user()->id;
            $pengirimanbarang->save();
            PengirimanBarang::destroy($request->id);
    
            $detailSJ->each->delete();
    
            //############# start update status PO #############
            $jmlSJinSO = PengirimanBarang::where('pesanan_penjualan_id', '=', $pesanan_penjualan_id)->count();
    
            if ($jmlSJinSO > 0) {
                $status = "3";
            } else {
                $status = "2";
            }
            $SOmain = PesananPenjualan::find($pesanan_penjualan_id);
            $SOmain->status_so_id = $status;
            $SOmain->save();
            //############# end update status PO #############
            
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

    public function print_a5(PengirimanBarang $pengirimanbarang)
    {

        $title = "Print Surat Jalan";
        $pengirimanbarangdetails = PengirimanBarangDetail::with(['products', 'pesananpenjualan'])
            ->where('pengiriman_barang_id', '=', $pengirimanbarang->id)->get();

        // dd($pengirimanbarangdetails);
        $jmlBaris  = $pengirimanbarangdetails->count();
        $perBaris = 7;
        $totalPage = ceil($jmlBaris / $perBaris);
        $listExp = StokExpDetail::with('stockExp')->where('id_sj', '=', $pengirimanbarang->id)->get();
        //dd($listExp);
        $data = [
            'totalPage' => $totalPage,
            'perBaris' => $perBaris,
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
        $listExp = StokExpDetail::where('id_sj', '=', $pengirimanbarang->id)->get();

        return view('penjualan.pengirimanbarang.show', compact('title', 'listExp', 'pengirimanbarang', 'pengirimanbarangdetails'));
    }


    public function syncronisasi()
    {
        return Excel::download(new SyncronisasiDataExport(), 'laporanpembelian.xlsx');
    }

    public function updateStatuData($pengirimandet, $status, $qty, $pengiriman)
    {
        $statusexp = 0;
        if ($status == 1) {
            $stokdet = StokExpDetail::where('id_sj_detail', $pengirimandet)->sum('qty') * -1;
        } else {
            $stokdet = HargaNonExpiredDetail::where('id_sj_detail', $pengirimandet)->sum('qty') * -1;
        }

        if ($stokdet == $qty) {
            $statusexp = 1;
        }
        PengirimanBarangDetail::where('id', $pengirimandet)->update([
            'status_exp' => $statusexp
        ]);

        $pengiriman = PengirimanBarangDetail::where('pengiriman_barang_id', $pengiriman)->where('status_exp', 1)->first();
        if (!$pengiriman) {
            PengirimanBarang::where('id', $pengiriman)->update([
                'status_sj_id' => 2,
                'status_exp' => 2
            ]);
        }else{
            PengirimanBarang::where('id', $pengiriman)->update([
                'status_sj_id' => 2,
                'status_exp' => 1
            ]);
        }
    }
}
