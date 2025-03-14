<?php

namespace App\Http\Controllers\Pembelian;

use Carbon\Carbon;
use App\Models\TempPb;
use App\Models\Product;
use App\Models\StokExp;
use App\Traits\CodeTrait;
use Illuminate\Http\Request;
use App\Models\StokExpDetail;
use App\Models\PenerimaanBarang;
use App\Models\PesananPembelian;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\HargaNonExpired;
use App\Models\HargaNonExpiredDetail;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Auth;
use App\Models\PenerimaanBarangDetail;
use App\Models\PesananPembelianDetail;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class PenerimaanBarangController extends Controller
{
    use CodeTrait;

    function __construct()
    {
        $this->middleware('permission:penerimaanbarang-list');
        $this->middleware('permission:penerimaanbarang-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:penerimaanbarang-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:penerimaanbarang-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = "Penerimaan Barang";
        $penerimaanbarang = PenerimaanBarang::with(['suppliers',  'statusPB', 'po', 'PenerimaanBarangDetails' => function ($query) {
            $query->where('status_exp', 0);
        }])->orderByDesc('id');

        if (request()->ajax()) {
            return Datatables::of($penerimaanbarang)
                ->addIndexColumn()
                ->addColumn('supplier', function (PenerimaanBarang $pb) {
                    return $pb->suppliers->nama;
                })
                ->addColumn('kode_po', function (PenerimaanBarang $pb) {
                    return $pb->po->kode;
                })
                ->addColumn('no_so', function (PenerimaanBarang $pb) {
                    return $pb->po->no_so;
                })
                ->addColumn('status', function (PenerimaanBarang $pb) {
                    $status_penerimaan = $pb->status_pb_id;
                    if (count($pb->PenerimaanBarangDetails) > 0) {
                        $status_exp = 0;
                    } else {
                        $status_exp = 1;
                    }

                    return view('pembelian.penerimaanbarang.partials.status', compact('status_penerimaan', 'status_exp'));
                })
                ->editColumn('tanggal', function (PenerimaanBarang $pb) {
                    return $pb->tanggal ? with(new Carbon($pb->tanggal))->format('d-m-Y') : '';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('penerimaanbarang.edit', ['penerimaanbarang' => $row->id]);
                    $expUrl = route('penerimaanbarang.inputexp', ['penerimaanbarang' => $row->id]);
                    $showUrl = route('penerimaanbarang.show', ['penerimaanbarang' => $row->id]);
                    $id = $row->id;
                    $status = $row->status_pb_id;
                    return view('pembelian.penerimaanbarang._formAction', compact('editUrl', 'showUrl', 'id', 'status', 'expUrl'));
                })
                ->make(true);
        }


        return view('pembelian.penerimaanbarang.index', compact('title'));
    }

    public function listpo()
    {
        $title = "Daftar Pesanan Pembelian";
        $pesananpembelians = PesananPembelian::with('suppliers', 'statusPO')
            ->where('status_po_id', '<=', '3')
            ->where('status_po_id', '<>', '1');

        if (request()->ajax()) {
            return Datatables::of($pesananpembelians)
                ->addIndexColumn()
                ->addColumn('supplier', function (PesananPembelian $po) {
                    return $po->suppliers->nama;
                })
                ->addColumn('status', function (PesananPembelian $po) {
                    return $po->statusPO->nama;
                })
                ->editColumn('tanggal', function (PesananPembelian $po) {
                    return $po->tanggal ? with(new Carbon($po->tanggal))->format('d-m-Y') : '';
                })
                ->addColumn('action', function ($row) {
                    $pilihUrl = route('penerimaanbarang.create', ['pesananpembelian' => $row->id]);
                    $id = $row->id;
                    return view('pembelian.penerimaanbarang._pilihAction', compact('pilihUrl', 'id'));
                })
                ->make(true);
        }

        //dd($pesananpembelian);
        return view('pembelian.penerimaanbarang.listpo', compact('title', 'pesananpembelians'));
    }

    public function create(PesananPembelian $pesananpembelian)
    {
        $title = "Penerimaan Barang";
        //delete temp
        $deletedTempDetil = TempPb::where('created_at', '<', Carbon::today())->delete();
        $deletedTempDetil = TempPb::where('user_id', '=', Auth::user()->id)->delete();

        return view('pembelian.penerimaanbarang.create', compact('title', 'pesananpembelian'));
    }

    public function datatablebarang(Request $request)
    {
        $sodet = PesananPembelianDetail::with(['products', 'temppb'])->where('pesanan_pembelian_id', '=', $request->id);
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
                if ($pb->temppb) {
                    $sisa = $sisa - $pb->temppb->qty;
                }
                return $sisa;
            })
            ->editColumn('status', function ($pb) {
                if ($pb->temppb) {
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

    public function datatablebarangterima(Request $request)
    {
        $temppb = TempPb::with(['products'])
            ->where('user_id', '=', Auth::user()->id);
        return Datatables::of($temppb)
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
                return $sisa;
            })
            ->addColumn('action', function ($pb) {
                return $pb->id;
            })
            ->make(true);
    }

    public function setbarang(Request $request)
    {

        $product = PesananPembelianDetail::with('products')->where('id', '=', $request->id)->first();
        if ($product->qty_sisa == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Produk sudah diterima semua'
            ], 422);
        }
        return view('pembelian.penerimaanbarang._setbarang', compact('product'));
    }

    public function inputtemppb(Request $request)
    {
        DB::beginTransaction();
        try {
            $detailPO = PesananPembelianDetail::with('products')->where('id', '=', $request->detail_id)->first();
            //1. cek item sudah dimasukkan apa belum
            $jmlItem = TempPb::where('product_id', '=', $detailPO->product_id)->where('user_id', auth()->user()->id)->count();
            if ($request->qty > 0) {
                if ($jmlItem == 0) {
                    if ($request->qty <= $detailPO->qty_sisa) {
                        $datas['pesanan_pembelian_detail_id'] = $request->detail_id;
                        $datas['product_id'] = $detailPO->product_id;
                        $datas['qty'] = $request->qty;
                        $datas['qty_sisa'] = $detailPO->qty_sisa;
                        $datas['qty_pesanan'] = $detailPO->qty;
                        $datas['satuan'] =  $detailPO->satuan;
                        $datas['keterangan'] = $request->keterangan;
                        $datas['user_id'] = Auth::user()->id;
                        TempPb::create($datas);
                    } else {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Penerimaan melebihi pesanan'
                        ], 422);
                    }
                } else {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Produk sudah diinputkan, silahkan periksa kembali inputan anda!'
                    ], 422);
                }
            } else {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Qty tidak boleh 0!'
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

    public function hapusbarang(Request $request)
    {
        $id = $request->id;
        TempPb::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Data Berhasil Dihapus'
        ]);
    }
    public function store(Request $request, PesananPembelian $pesananpembelian)
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
            $pesanan_pembelian_id = $pesananpembelian->id;
            $supplier_id = $pesananpembelian->supplier_id;

            $dataTemp = TempPb::where('user_id', '=', Auth::user()->id)->get();
            $jmlTemp = $dataTemp->count();
            if ($jmlTemp < 1) {
                return redirect()->route('penerimaanbarang.index')->with('gagal', 'Tidak ada barang yang diinputkan, Penerimaan Barang Gagal Disimpan!');
            }

            $kode = $this->getKodeTransaksi("penerimaan_barangs", "PB");
            $datas['kode'] = $kode;
            $datas['tanggal'] = $tanggal;
            $datas['pesanan_pembelian_id'] = $pesanan_pembelian_id;
            $datas['supplier_id'] = $supplier_id;
            $datas['status_pb_id'] = "1";
            $datas['status_exp'] = 0;

            $id_pb = PenerimaanBarang::create($datas)->id;

            $supplier = Supplier::findOrFail($supplier_id);

            //isi detail
            $test = "";
            foreach ($dataTemp as $a) {

                /////// calkulasi HPP  ///////
                $detailPesanan = PesananPembelianDetail::find($a->pesanan_pembelian_detail_id);
                $hargabeli = $detailPesanan->hargabeli;
                $diskon_persen = $detailPesanan->diskon_persen;
                $diskon_rp = $detailPesanan->diskon_rp;
                $totaldiskon = ($hargabeli * ($diskon_persen / 100)) + $diskon_rp;
                $hargabeli_fix = $hargabeli - $totaldiskon;

                $stok_lama = 0;
                $hpp_lama = 0;
                $stok_baru = 0;

                $product_id = $a->product_id;
                $product = new Product;
                $product = Product::find($a->product_id);
                $stok_lama = $product->stok;
                $hpp_lama = $product->hpp;
                $nilai_lama = $stok_lama * $hpp_lama;
                $status_exp = $product->status_exp;

                // status exp = 1 artinya ada expirednya 
                // status exp detil = 1 berarti exp sudah terinput atau tidak perlu ada exp nya 

                if ($status_exp == 1) {
                    $status_exp_detil = 0;
                } else {
                    $status_exp_detil = 1;
                }

                $nilai_terima = $a->qty * $hargabeli_fix;
                $stok_baru = $stok_lama + $a->qty;
                $nilai_baru = $nilai_lama + $nilai_terima;
                $hpp_baru = ROUND(($nilai_baru / $stok_baru), 2);
                $product->stok = $stok_baru;
                $product->hpp = $hpp_baru;
                $product->hargabeli = $hargabeli;
                $product->save();
                ////////// end hpp //////////

                // ########## start input detail ###########
                $detail = new PenerimaanBarangDetail;
                $detail->tanggal = $tanggal;
                $detail->penerimaan_barang_id = $id_pb;
                $detail->pesanan_pembelian_id = $pesanan_pembelian_id;
                $detail->pesanan_pembelian_detail_id = $a->pesanan_pembelian_detail_id;
                $detail->product_id = $product_id;
                $detail->qty = $a->qty;
                $detail->qty_sisa = $a->qty_sisa;
                $detail->qty_pesanan = $a->qty_pesanan;
                $detail->satuan = $a->satuan;
                $detail->keterangan = $a->keterangan;
                $detail->status_exp = $status_exp_detil;
                $detail->save();


                // ============= UNTUK INPUT HARGA NON EXPIRED DETAIL  ===============================

                if ($status_exp_detil == 1) {

                    $hargaNonExpired = HargaNonExpired::where('product_id', $product_id)
                        ->where('harga_beli', $hargabeli)
                        ->where('supplier_id', $supplier_id)
                        ->where('diskon_persen', $diskon_persen)
                        ->where('diskon_rupiah', $diskon_rp)
                        ->first();

                    if ($hargaNonExpired) {
                        $qtynonexpired =  $hargaNonExpired->qty + $a->qty;
                        $hargaNonExpired->update([
                            'qty' => $qtynonexpired,
                            'penerimaanbarang_id' => $id_pb,
                            'tanggal' => $tanggal
                        ]);

                        $idexpired = $hargaNonExpired->id;
                    } else {
                        $harganonExpired =  HargaNonExpired::create([
                            'product_id' => $product_id,
                            'qty' => $a->qty,
                            'harga_beli' => $hargabeli,
                            'ppn' => $detailPesanan->ppn,
                            'diskon_persen' => $diskon_persen,
                            'diskon_rupiah' => $diskon_rp,
                            'tanggal_transaksi' => $tanggal,
                            'supplier_id' => $supplier_id,
                            'penerimaanbarang_id' => $id_pb
                        ]);

                        $idexpired = $harganonExpired->id;
                    }

                    HargaNonExpiredDetail::create([
                        'tanggal' => $tanggal,
                        'harganonexpired_id' => $idexpired,
                        'product_id' => $product_id,
                        'qty' => $a->qty,
                        'id_pb' => $id_pb,
                        'id_pb_detail' => $detail->id,
                        'harga_beli' => $hargabeli,
                        'diskon_persen_beli' => $diskon_persen,
                        'diskon_rupiah_beli' => $diskon_rp
                    ]);
                }

                //######### start add INV TRANS ############
                $inventoryTrans = new InventoryTransaction;
                $inventoryTrans->tanggal = $tanggal;
                $inventoryTrans->product_id = $product_id;
                $inventoryTrans->qty = $a->qty;
                $inventoryTrans->stok = $stok_baru;
                $inventoryTrans->hpp = $hpp_baru;
                $inventoryTrans->jenis = "PB";
                $inventoryTrans->jenis_id = $kode;
                $inventoryTrans->supplier = $supplier->nama;

                $inventoryTrans->save();
                //######### end add INV TRANS ############
                $test = $test . $a->product_id . "(" . $stok_baru . ");";

                //############# start update Qty Sisa PO #############
                $detailPOupdate = new PesananPembelianDetail;
                $detailPOupdate = PesananPembelianDetail::find($a->pesanan_pembelian_detail_id);
                $detailPOupdate->qty_sisa = ($a->qty_sisa - $a->qty);
                $detailPOupdate->save();
                //############# end update Qty Sisa PO #############
            }
            //dd($index);
            //############# start update status PO #############
            $totalPesananPO = PesananPembelianDetail::where('pesanan_pembelian_id', '=', $pesanan_pembelian_id)->sum('qty');
            $totalSisaPO = PesananPembelianDetail::where('pesanan_pembelian_id', '=', $pesanan_pembelian_id)->sum('qty_sisa');
            $terkirim = $totalPesananPO - $totalSisaPO;

            if ($terkirim == $totalPesananPO) {
                $status = "4";
            } else {
                $status = "3";
            }

            $POmain = PesananPembelian::find($pesanan_pembelian_id);
            $POmain->status_po_id = $status;
            $POmain->save();
            //############# end update status PO #############

            DB::commit();

            return redirect()->route('penerimaanbarang.index')->with('status', 'Penerimaan barang berhasil dibuat !');
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->route('penerimaanbarang.index')->with('erorr', $th->getMessage());
        }
    }

    public function listpesanan(Request $request)
    {
        $pesananpembeliandet = PenerimaanBarangDetail::with('products')->where('penerimaan_barang_id', $request->id);
        return Datatables::of($pesananpembeliandet)
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
            ->editColumn('qty', function ($pb) {
                $sisa = $pb->qty;
                return $sisa;
            })
            ->editColumn('status_exp', function ($pb) {
                if ($pb->products->status_exp == 1) {
                    return 1;
                } else {
                    return 0;
                }
            })
            ->editColumn('status', function ($pb) {
                if ($pb->status_exp == 1) {
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


    public function inputexp(PenerimaanBarang $penerimaanbarang)
    {
        $title = "Pengaturan Expired Date";
        $penerimaanbarang_id =  $penerimaanbarang->id;
        return view('pembelian.penerimaanbarang.inputexp', compact('penerimaanbarang', 'title'));
    }

    public function setexp(Request $request)
    {
        $penerimaanbarangdet = PenerimaanBarangDetail::with('products')->where('id', $request->id)->first();
        return view('pembelian.penerimaanbarang.modal.setexp', compact('penerimaanbarangdet'));
    }


    public function saveexp(Request $request)
    {
        DB::beginTransaction();
        $penerimaanbarangdetail = PenerimaanBarangDetail::where('id', $request->detail_id)->first();
        try {
            $datas = $request->all();
            $tanggal = now()->format('Y-m-d');

            $lot = $request->lot;
            if ($request->tanggal <> null) {
                $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');
            }

            $qty = $request->qty;
            if ($qty < 1) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Qty harus lebih dari 0!'
                ], 422);
            }

            $penerimaanbarangdetail_id = $penerimaanbarangdetail->id;
            $product_id =  $penerimaanbarangdetail->product_id;
            $penerimaanbarang_id = $penerimaanbarangdetail->penerimaan_barang_id;
            $qty_diterima = $penerimaanbarangdetail->qty;


            // get harga dari pesanan pembelian
            $pesananpembelian = PesananPembelianDetail::with('pesananpembelian')->where('id', $penerimaanbarangdetail->pesanan_pembelian_detail_id)->first();
            // dd($pesananpembelian);

            //get jumlah qty di exp data
            $totalQtyExp = StokExpDetail::where('id_pb_detail', '=', $penerimaanbarangdetail_id)->sum('qty');
            $qtyExpNow = $totalQtyExp + $qty;

            // untuk ngecek apakah qty nya melebihi atau tidak
            if ($qtyExpNow <= $qty_diterima) {

                $mainStokExp = StokExp::where('tanggal', '=', $tanggal)
                    ->where('product_id', '=', $product_id)
                    ->where('lot', $lot)
                    ->where('harga_beli', $pesananpembelian->hargabeli)
                    ->where('diskon_persen', $pesananpembelian->diskon_persen)
                    ->where('diskon_rupiah', $pesananpembelian->diskon_rp)
                    ->where('supplier_id', $pesananpembelian->pesananpembelian->supplier_id)
                    ->first();


                // dd($mainStokExp);

                if ($mainStokExp) {
                    $id_stokExp = $mainStokExp->id;
                    $mainStokExp->qty += $qty;
                    $mainStokExp->save();

                    //insert detail
                    $stokExpDetail = new StokExpDetail;
                    $stokExpDetail->tanggal = $tanggal;
                    $stokExpDetail->stok_exp_id = $id_stokExp;
                    $stokExpDetail->product_id = $product_id;
                    $stokExpDetail->qty = $qty;
                    $stokExpDetail->id_pb = $penerimaanbarang_id;
                    $stokExpDetail->id_pb_detail = $penerimaanbarangdetail_id;
                    $stokExpDetail->harga_beli = $pesananpembelian->hargabeli;
                    $stokExpDetail->diskon_persen_beli = $pesananpembelian->diskon_persen;
                    $stokExpDetail->diskon_rupiah_beli = $pesananpembelian->diskon_rp;
                    $stokExpDetail->save();
                } else {
                    //tidak ada data, harus insert stok
                    $datas['tanggal'] = $tanggal;
                    $datas['product_id'] = $product_id;
                    $datas['qty'] = $qty;
                    $datas['lot'] = $lot;
                    $datas['harga_beli'] = $pesananpembelian->hargabeli;
                    $datas['diskon_persen'] = $pesananpembelian->diskon_persen;
                    $datas['diskon_rupiah'] = $pesananpembelian->diskon_rp;
                    $datas['supplier_id'] = $pesananpembelian->pesananpembelian->supplier_id;
                    $id_stokExp = StokExp::create($datas)->id;

                    //insert detail;
                    $stokExpDetail = new StokExpDetail;
                    $stokExpDetail->tanggal = $tanggal;
                    $stokExpDetail->stok_exp_id = $id_stokExp;
                    $stokExpDetail->product_id = $product_id;
                    $stokExpDetail->qty = $qty;
                    $stokExpDetail->id_pb = $penerimaanbarang_id;
                    $stokExpDetail->id_pb_detail = $penerimaanbarangdetail_id;
                    $stokExpDetail->harga_beli = $pesananpembelian->hargabeli;
                    $stokExpDetail->diskon_persen_beli = $pesananpembelian->diskon_persen;
                    $stokExpDetail->diskon_rupiah_beli = $pesananpembelian->diskon_rp;
                    $stokExpDetail->save();
                }

                // cek jumlah quantity di exp dan ubah status_exp dari penerimaan barang.
                if ($qtyExpNow == $qty_diterima) {
                    $penerimaanbarangdetail = PenerimaanBarangDetail::find($penerimaanbarangdetail_id);
                    $penerimaanbarangdetail->status_exp = "1";
                    $penerimaanbarangdetail->save();
                }
            } else {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Qty melebihi data yang diterima'
                ], 422);
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

    public function listexp(Request $request)
    {
        $stokexp = StokExpDetail::with('stockExp')->where('id_pb_detail', $request->id);
        return Datatables::of($stokexp)
            ->addIndexColumn()
            ->editColumn('tanggal', function ($pb) {
                return Carbon::parse($pb->tanggal)->format('d-m-Y');
            })
            ->editColumn('lot', function ($pb) {
                return $pb->stockExp->lot;
            })
            ->editColumn('qty', function ($pb) {
                return $pb->qty;
            })
            ->addColumn('action', function ($pb) {
                return $pb->id;
            })
            ->make(true);
    }
    public function hapusexp(Request $request)
    {
        $id = $request->id;

        $stokExpDetail = StokExpDetail::find($id);
        $penerimaanbarangdetail_id =  $stokExpDetail->id_pb_detail;
        $stokExp_id = $stokExpDetail->stok_exp_id;
        $qtyDetail  = $stokExpDetail->qty;

        $stokExp = StokExp::find($stokExp_id);
        $stokMain = $stokExp->qty;
        $stokSisa = $stokMain - $qtyDetail;
        $penerimaanbarangdetail = PenerimaanBarangDetail::find($penerimaanbarangdetail_id);

        if ($stokSisa >= 0) {
            StokExpDetail::destroy($id);
            $stokExp->qty = $stokSisa;
            $stokExp->save();

            $penerimaanbarangdetail->status_exp = "0";
            $penerimaanbarangdetail->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Dihapus'
            ]);
        } else {
            //stok jadi minus, dilarang hapus
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak bisa dihapus karena sudah terpakai di pengiriman'
            ], 422);
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $tglNow = Carbon::now()->format('Y-m-d');
            $id = $request->id;
            $penerimaanbarang = PenerimaanBarang::find($id);
            $penerimaanbarang_kode = $penerimaanbarang->kode;
            $pesanan_pembelian_id = $penerimaanbarang->pesanan_pembelian_id;

            $supplier = Supplier::findOrFail($penerimaanbarang->supplier_id);
            //validasi :
            $jmlExp = StokExpDetail::where('id_pb', '=', $id)->count();

            //dd($jmlExp);
            if ($jmlExp > 0) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak bisa dihapus karena sudah terdapat data expired , Hapus expired terlebih dahulu'
                ], 422);
            }

            $detailPB = PenerimaanBarangDetail::where('penerimaan_barang_id', '=', $id)->get();
            foreach ($detailPB as $a) {
                //update stok
                $product = new Product;
                $product = Product::find($a->product_id);
                $stok = $product->stok;

                if ($stok < $a->qty) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Data tidak bisa dihapus karena sudah dibuat surat jalan . hapus surat jalan terlebih dahulu'
                    ], 422);
                }
                $hpp = $product->hpp;
                $product->stok = $stok - $a->qty;
                $product->save();

                $pesananPembelianDetail = PesananPembelianDetail::where('id', $a->pesanan_pembelian_detail_id)->first();
                $pesananpembelian = PesananPembelian::where('id', $pesananPembelianDetail->pesanan_pembelian_id)->first();
                $harganonexpired = HargaNonExpired::where('harga_beli', $pesananPembelianDetail->hargabeli)
                    ->where('product_id', $pesananPembelianDetail->product_id)
                    ->where('diskon_persen', $pesananPembelianDetail->diskon_persen)
                    ->where('diskon_rupiah', $pesananPembelianDetail->diskon_rp)
                    ->where('supplier_id', $pesananpembelian->supplier_id)
                    ->first();
                $typeproduct = Product::where('id', $pesananPembelianDetail->product_id)->first();

                if ($harganonexpired) {
                    if ($harganonexpired->qty < $a->qty) {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data tidak bisa dihapus karena sudah dibuat surat jalan . hapus surat jalan terlebih dahulu'
                        ], 422);
                    } else {
                        $stokNonExpired = $harganonexpired->qty - $a->qty;
                        $harganonexpired->update([
                            'qty' => $stokNonExpired
                        ]);
                    }
                }

                $pesanan_pembelian_detail_id = $a->pesanan_pembelian_detail_id;
                $stok_baru = $stok - $a->qty;

                //######### start add INV TRANS ############
                $inventoryTrans = new InventoryTransaction;
                $inventoryTrans->tanggal = $tglNow;
                $inventoryTrans->product_id = $a->product_id;
                $inventoryTrans->qty = ($a->qty * -1);
                $inventoryTrans->stok = $stok_baru;
                $inventoryTrans->hpp = $hpp;
                $inventoryTrans->jenis = "PB (DEL)";
                $inventoryTrans->jenis_id = $penerimaanbarang_kode;
                $inventoryTrans->supplier =  $supplier->nama;
                $inventoryTrans->save();
                //######### end add INV TRANS ############

                //############# start update Qty Sisa PO #############
                $detailPOupdate = new PesananPembelianDetail;
                $detailPOupdate = PesananPembelianDetail::find($a->pesanan_pembelian_detail_id);
                $detailPOupdate->qty_sisa +=  $a->qty;
                $detailPOupdate->save();
                //############# end update Qty Sisa PO #############
            }


            $penerimaanbarang->deleted_by = Auth::user()->id;
            $penerimaanbarang->save();
            PenerimaanBarang::destroy($request->id);

            $detailPB->each->delete();

            //############# start update status PO #############
            $jmlPBinPO = PenerimaanBarang::where('pesanan_pembelian_id', '=', $pesanan_pembelian_id)->count();
            if ($jmlPBinPO > 0) {
                $status = "3";
            } else {
                $status = "2";
            }
            $POmain = PesananPembelian::find($pesanan_pembelian_id);
            $POmain->status_po_id = $status;
            $POmain->save();
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

    public function show(PenerimaanBarang $penerimaanbarang)
    {
        $title = "Penerimaan Barang Detail";
        $penerimaanbarangdetails = PenerimaanBarangDetail::with('products')
            ->where('penerimaan_barang_id', '=', $penerimaanbarang->id)->get();

        $listExp = StokExpDetail::where('id_pb', '=', $penerimaanbarang->id)->get();

        return view('pembelian.penerimaanbarang.show', compact('title', 'listExp', 'penerimaanbarang', 'penerimaanbarangdetails'));
    }

    public function showData($id)
    {
        $title = "Penerimaan Barang Detail";
        $penerimaanbarang = PenerimaanBarang::where('kode', $id)->with('creator', 'updater')->first();
        if (!$penerimaanbarang) {
            return back()->with('status', 'Data Tidak Ditemukan !');
        }

        $penerimaanbarangdetails = PenerimaanBarangDetail::with('products')
            ->where('penerimaan_barang_id', '=', $penerimaanbarang->id)->get();
        $listExp = StokExpDetail::where('id_pb', '=', $penerimaanbarang->id)->get();
        return view('pembelian.penerimaanbarang.show', compact('title', 'listExp', 'penerimaanbarang', 'penerimaanbarangdetails'));
    }

    public function print_a5(PenerimaanBarang $penerimaanbarang)
    {
        // dd($penerimaanbarang);
        $title = "Print Surat Jalan";
        $penerimaanbarangdetail = PenerimaanBarangDetail::with('products')
            ->where('penerimaan_barang_id', '=', $penerimaanbarang->id)->get();
        $jmlBaris  = $penerimaanbarangdetail->count();
        $perBaris = 7;
        $totalPage = ceil($jmlBaris / $perBaris);
        $listExp = StokExpDetail::with('stockExp')->where('id_sj', '=', $penerimaanbarang->id)->get();
        //dd($listExp);
        $data = [
            'totalPage' => $totalPage,
            'perBaris' => $perBaris,
            'date' => date('m/d/Y'),
            'listExp' => $listExp,
            'penerimaanbarang' => $penerimaanbarang,
            'penerimaanbarangdetail' => $penerimaanbarangdetail
        ];

        $pdf = PDF::loadView('pembelian.penerimaanbarang.print_a5', $data)->setPaper('a5', 'landscape');;
        return $pdf->download($penerimaanbarang->kode . '.pdf');
    }
}
