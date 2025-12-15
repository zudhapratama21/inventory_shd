<?php

namespace App\Http\Controllers\Canvassing;

use App\Http\Controllers\Controller;
use App\Models\CanvassingPengembalian;
use App\Models\CanvassingPengembalianDetail;
use App\Models\CanvassingPesanan;
use App\Models\CanvassingPesananDetail;
use App\Models\Customer;
use App\Models\HargaNonExpired;
use App\Models\HargaNonExpiredDetail;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\StokExp;
use App\Models\StokExpDetail;
use App\Models\TempCanvasPengembalian;
use App\Traits\CodeTrait;
use Carbon\Carbon;
use Dompdf\Canvas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CanvassingPengembalianController extends Controller
{
    use CodeTrait;

    function __construct()
    {
        $this->middleware('permission:canvassingpengembalian-list');
        $this->middleware('permission:canvassingpengembalian-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:canvassingpengembalian-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:canvassingpengembalian-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = "Canvassing Pengembalian";
        $konversi = CanvassingPengembalian::with(['customer', 'canvassing'])->orderByDesc('id');

        if (request()->ajax()) {
            return Datatables::of($konversi)                            
                ->editColumn('customer', function (CanvassingPengembalian $pb) {
                    return $pb->customer->nama;
                })
                ->editColumn('status', function (CanvassingPengembalian $pb) {
                    return $pb->status;
                })
                ->addColumn('action', function ($row) {
                    $showUrl = route('canvassingpengembalian.show', ['canvassingpengembalian' => $row->id]);
                    $id = $row->id;
                    $status = $row->status_pb_id;
                    return view('canvassing.canvassingpengembalian.modal._form-action', compact('showUrl', 'id'));
                })
                ->make(true);
        }


        return view('canvassing.canvassingpengembalian.index', compact('title', 'konversi'));
    }

    public function listcanvas()
    {
        $deletedTempCanvas = TempCanvasPengembalian::where('created_at', '<', Carbon::today())->delete();
        $deletedTempCanvas = TempCanvasPengembalian::where('user_id', '=', Auth::user()->id)->delete();

        $title = "List Canvassing Pembelian";
        $konversi = CanvassingPesanan::with(['customer'])->whereIn('status', [2, 3])->orderByDesc('id');

        if (request()->ajax()) {
            return Datatables::of($konversi)
                ->addIndexColumn()              
                ->editColumn('customer', function (CanvassingPesanan $pb) {
                    return $pb->customer->nama;
                })
                ->addColumn('action', function ($row) {
                    $pilihUrl = route('canvassingpengembalian.create', ['canvassingpengembalian' => $row->id]);
                    $id = $row->id;
                    return view('canvassing.canvassingpengembalian.partial._pilihAction', compact('pilihUrl', 'id'));
                })
                ->make(true);
        }

        return view('canvassing.canvassingpengembalian.listcanvas', compact('title'));
    }


    public function create($id)
    {
        $title = 'Canvassing Pengembalian';
        DB::beginTransaction();
        try {
            //delete temp
            $deletedTempCanvas = TempCanvasPengembalian::where('created_at', '<', Carbon::today())->delete();
            $deletedTempCanvas = TempCanvasPengembalian::where('user_id', '=', Auth::user()->id)->delete();
            $canvas = CanvassingPesanan::with('customer')->where('id', $id)->first();
            $tglNow = Carbon::now()->format('d-m-Y');

            DB::commit();
            return view('canvassing.canvassingpengembalian.create', compact('canvas', 'title', 'tglNow'));
        } catch (Exception $th) {
            DB::rollBack();
            return back();
        }
    }

    public function datacanvassing(Request $request)
    {
        $canvassingdet = CanvassingPesananDetail::with('product', 'tempcanvaskembali')->where('canvassing_pesanan_id', $request->canvassing_id)->where('qty_sisa', '<>', 0)->get();
        return Datatables::of($canvassingdet)
            ->addIndexColumn()
            ->editColumn('product', function ($pb) {
                return $pb->product->nama;
            })
            ->editColumn('temp_canvas', function ($pb) {
                return $pb->tempcanvaskembali ? 1 : 0;
            })
            ->addColumn('action', function ($row) {
                return $row->id;
            })
            ->make(true);
    }

    public function setbarang(Request $request)
    {
        $canvas = CanvassingPesananDetail::with('product')->where('id', '=', $request->id)->get()->first();
        $mode = "new";
        return view('canvassing.canvassingpengembalian.modal._setbarang', compact('mode', 'canvas'));
    }

    public function inputTempCanvas(Request $request)
    {
        DB::beginTransaction();
        try {
            $canvas = CanvassingPesananDetail::where('id', $request->canvassingdetail_id)->first();

            if ($canvas->qty_sisa < $request->qty_kembali) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Qty tidak boleh Qty Sisa'
                ], 422);
            }

            $temp = TempCanvasPengembalian::create([
                'canvassing_pesanan_id' => $canvas->canvassing_pesanan_id,
                'canvassing_pesanan_detail_id' => $canvas->canvassing_pesanan_id,
                'product_id' => $canvas->product_id,
                'tanggal' => Carbon::parse($canvas->tanggal)->format('Y-m-d'),
                'qty' => $canvas->qty,
                'qty_sisa' => $canvas->qty - $request->qty_kembali,
                'qty_kirim' =>  $request->qty_kembali,
                'user_id' => auth()->user()->id,
                'keterangan' => $request->keterangan
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Disimpan'
            ]);
        } catch (Exception $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500); // Mengembalikan status 500 untuk error server
        }
    }

    public function loadTempCanvas(Request $request)
    {
        $tempcanvas = TempCanvasPengembalian::with(['product'])
            ->where('user_id', '=', Auth::user()->id)
            ->get();

        return Datatables::of($tempcanvas)
            ->addIndexColumn()
            ->editColumn('product', function ($pb) {
                return $pb->product->nama;
            })
            ->addColumn('action', function ($row) {
                return $row->id;
            })
            ->make(true);
    }

    public function hapustemp(Request $request)
    {
        $id = $request->id;
        TempCanvasPengembalian::destroy($id);

        return response()->json('Data Berhasil Dihapus');
    }

    public function store(Request $request)
    {
        $canvassing = CanvassingPesanan::where('id', $request->canvassing_id)->first();

        $customer = Customer::where('id', $canvassing->customer_id)->first();
        DB::beginTransaction();

        try {            
            $kode = $this->getKodeTransaksi('canvassing_pengembalians', 'CVB');
            $canvaspengembalian = CanvassingPengembalian::create([
                'kode' => $kode,
                'canvassing_pesanan_id' => $canvassing->id,
                'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
                'customer_id' => $canvassing->customer_id,
                'keterangan' => $request->keterangan,
                'status' => 1
            ]);

            $temp = TempCanvasPengembalian::where('user_id', auth()->user()->id)->get();
            $status = 0;

            foreach ($temp as $item) {
                // 1. tambah stock produk sesuai di canvassing
                $product = Product::where('id', $item->product_id)->first();
                $stok = $product->stok + $item->qty_kirim;
                $product->stok = $stok;
                $stokcanvas = $product->stok_canvassing - $item->qty_kirim;
                $product->stok_canvassing = $stokcanvas;
                $product->save();

                // kurangi qty_sisa pada canvassing_pesanan
                $canvasdet = CanvassingPesananDetail::where('canvassing_pesanan_id', $item->canvassing_pesanan_id)
                    ->where('product_id', $item->product_id)->first();

                // save di canvassing pengembalian detail
                $canvasPengembalian = CanvassingPengembalianDetail::create([
                    'canvassing_kembali_id' => $canvaspengembalian->id,
                    'canvassing_pesanan_detail_id' => $canvasdet->id,
                    'product_id' => $item->product_id,
                    'tanggal' => Carbon::parse($item->tanggal)->format('Y-m-d'),
                    'qty' => $item->qty,
                    'qty_sisa' => $item->qty_sisa,
                    'qty_kirim' => $item->qty_kirim,
                    'status_data' => 0
                ]);

                // 3. masukan data di inventory [ tambah ]
                $inv = InventoryTransaction::create([
                    'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
                    'product_id' => $item->product_id,
                    'qty' => $item->qty_kirim,
                    'stok' =>  $stok,
                    'hpp' => $product->hpp,
                    'jenis' => 'CVB',
                    'jenis_id' => $kode,
                    'customer' => $customer->nama
                ]);

                $sisa = $canvasdet->qty_sisa - $item->qty_kirim;
                $canvasdet->qty_sisa = $sisa;
                if ($sisa > 0) {
                    $canvasdet->status_data = 3;
                } else {
                    $status  = 1;
                    $canvasdet->status_data = 4;
                }
                $canvasdet->save();
            }

            // total qty pada canvassing pesanan dengan menjumlahkan qty sisa
            $qtycanvas = CanvassingPesananDetail::where('canvassing_pesanan_id', $item->canvassing_pesanan_id)
                ->where('product_id', $item->product_id)->sum('qty_sisa');
            $canvassing->qty = $qtycanvas;
            if ($status  == 1) {
                $canvassing->status = 4;
            } else {
                $canvassing->status = 3;
            }
            $canvassing->save();

            DB::commit();
            return redirect()->route('canvassingpengembalian.index')->with('suskes', 'Data Canvassing Pengembalian berhasil ditambahkan');
        } catch (Exception $th) {

            DB::rollBack();
            return redirect()->route('canvassingpengembalian.index')->with('gagal', $th->getMessage());
        }
    }

    public function delete(Request $request)
    {

        $data = CanvassingPengembalian::where('id', $request->id)->first();
        $id = $data->id;
        $can_delete = 'YES';
        return view('canvassing.canvassingpengembalian.modal._confirmDelete', compact('id', 'can_delete'));
    }


    public function destroy(Request $request)
    {
        
        DB::beginTransaction();
        try {
            $canvaspengembalian = CanvassingPengembalian::where('id', $request->id)->first();
            $canvaspengembaliandetail = CanvassingPengembalianDetail::where('canvassing_kembali_id', $request->id)->get();

            $canvas = CanvassingPesanan::where('id', $canvaspengembalian->canvassing_pesanan_id)->first();
            $customer = Customer::where('id',$canvas->customer_id)->first();

            if ($canvaspengembalian->status == 2) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Produk Kirim Masih ada .. hapus data produk kirim terlebih dahulu'
                ], 422);
            }


            foreach ($canvaspengembaliandetail as $item) {
                // 1. kurangi stok product dan tambah pada stok canvas sesuai dengan canvaspemb detail    
                $product = Product::where('id', $item->product_id)->first();
                $stok = $product->stok - $item->qty_kirim;
                $stok_canvassing = $product->stok_canvassing + $item->qty_kirim;
                $product->stok = $stok;
                $product->stok_canvassing = $stok_canvassing;
                $product->save();

                // 2. tambah stok canvas pesanan detail dan tidak
                $canvasdet = CanvassingPesananDetail::where('canvassing_pesanan_id', $canvas->id)
                    ->where('product_id', $item->product_id)
                    ->first();

                $stoksisa =   $canvasdet->qty_sisa + $item->qty_kirim;
                $canvasdet->qty_sisa = $stoksisa;
                $canvasdet->status_data = 1;
                $canvasdet->save();

                // 3. insert inv transaction                                                        
                $inv = InventoryTransaction::create([
                    'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
                    'product_id' => $item->product_id,
                    'qty' => $item->qty_sisa * -1,
                    'stok' =>  $stok,
                    'hpp' => $product->hpp,
                    'jenis' => 'CVB (DEL)',
                    'jenis_id' => $canvaspengembalian->kode,
                    'customer' => $customer->nama
                ]);
            }

            $qty = CanvassingPesananDetail::where('canvassing_pesanan_id', $canvas->id)->sum('qty_sisa');
            $canvas->qty = $qty;
            $canvas->status = 2;
            $canvas->save();

            $canvaspengembaliandetail->each->delete();
            $canvaspengembalian->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Dihapus'
            ]);
        } catch (Exception $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $title =  "Canvassing Pengembalian";
        $canvas = CanvassingPengembalian::with(['creator', 'updater', 'customer', 'canvassing'])->where('id', $id)->first();
        $canvasdet = CanvassingPengembalianDetail::with(['product', 'creator', 'updater'])->where('canvassing_kembali_id', $id)->get();

        return view('canvassing.canvassingpengembalian.show', compact('canvas', 'canvasdet', 'title'));
    }

    public function listexp($id)
    {
        $title = 'Canvassing Pengembalian';
        $canvasdet = CanvassingPengembalianDetail::with('product')->where('canvassing_kembali_id', $id)->get();
        return view('canvassing.canvassingpengembalian.listexp', compact('title', 'canvasdet'));
    }

    public function setexp($canvassingpengembalian_id, $product_id)
    {
        $canvasdet = CanvassingPengembalianDetail::with('product')->where('id', $canvassingpengembalian_id)->where('product_id', $product_id)->first();
        $title = 'Canvassing Pengembalian';
        return view('canvassing.canvassingpengembalian.setexp', compact('canvasdet', 'title'));
    }

    public function productexp(Request $request)
    {
        // ambil dari data stokexp detail yang canvasdet sama dan product sama 
        $canvasdet = CanvassingPengembalianDetail::where('id', $request->canvaspengembaliandet_id)->first();
        

        $stokexp = StokExpDetail::with('products','stockExp')
            ->where('canvassing_detail_id', $canvasdet->canvassing_pesanan_detail_id)
            ->where('product_id', $request->product)
            ->where('qty', '<', 0);            

        return Datatables::of($stokexp)
            ->addIndexColumn()
            ->editColumn('tanggal', function ($pb) {
                return $pb->tanggal ? with(new Carbon($pb->tanggal))->format('d-m-Y') : '';
            })
            ->editColumn('lot', function ($pb) {
                return $pb->stockExp->lot;
            })
            ->editColumn('qty', function ($pb) {
                return $pb->qty * -1;
            })
            ->editColumn('harga_beli', function ($pb) {
                return number_format($pb->harga_beli, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                $id = $row->id;
                $status = 1;
                $hapusexp = false;
                return view('canvassing.canvassing.partial._actionexp', compact('id', 'status', 'hapusexp'));
            })
            ->make(true);
    }

    public function productnonexp(Request $request)
    {
        // ambil dari data stokexp detail yang canvasdet sama dan product sama 
        $canvasdet = CanvassingPengembalianDetail::where('id', $request->canvaspengembaliandet_id)->first();
        $stok = HargaNonExpiredDetail::with('product')
                ->where('canvassing_detail_id', $canvasdet->canvassing_pesanan_detail_id)
                ->where('product_id', $request->product)
                ->where('qty', '<', 0);

        return Datatables::of($stok)
            ->addIndexColumn()
            ->editColumn('harga_beli', function ($pb) {
                return number_format($pb->harga_beli, 0, ',', '.');
            })
            ->editColumn('qty', function ($pb) {
                return $pb->qty * -1;
            })
            ->addColumn('action', function ($pb) {
                return $pb->id . ',' . $pb->product->status_exp;
            })
            ->make(true);
    }

    public function formsetexp(Request $request)
    {
        
        $status = $request->status;
        if ($status == 1) {
            $stok = StokExpDetail::with('products')->where('id', $request->id)->first();
        }else{
            $stok = HargaNonExpiredDetail::with('product')->where('id', $request->id)->first();
        }
        return view('canvassing.canvassingpengembalian.modal._formsetexp', compact('status', 'stok'));
    }

    public function inputexp(Request $request)
    {

        $canvassingpengembaliandet = CanvassingPengembalianDetail::with('canvassingpesanandetail', 'product')->where('id', $request->canvassingdetail_id)->first();
        DB::beginTransaction();
        try {
            if ($request->status == 1) {
                if ($request->qty == 0) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Qty tidak boleh 0'
                    ], 422);
                }
                $stok = StokExpDetail::where('id', $request->id)->first();   
                $exp = StokExp::where('id', $stok->stok_exp_id)->first();         
                if (!$stok) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Stok Exp tidak ditemukan'
                    ], 422);
                }
                
                if ((-1 * $stok->qty) < $request->qty) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Qty tidak boleh melebihi stok'
                    ], 422);
                }       
                $stok->update([
                    'qty' => $stok->qty + $request->qty
                ]);

                $qty = $exp->qty + $request->qty;
                $exp->update([
                    'qty' => $qty
                ]);

                $stoktotal = StokExpDetail::where('canvassingkembali_detail_id',$canvassingpengembaliandet->id)->where('product_id',$canvassingpengembaliandet->product_id)->sum('qty');                
                
               
               if (($stoktotal + $request->qty) > $canvassingpengembaliandet->qty_kirim ) {
                   DB::rollBack();
                   return response()->json([
                       'status' => 'error',
                       'message' => 'Qty tidak boleh melebihi total canvassing'
                   ], 422);
               }
               
                $expdet = StokExpDetail::create([
                    'tanggal' => $stok->tanggal,
                    'stok_exp_id' => $stok->stok_exp_id,
                    'product_id' => $stok->product_id,
                    'qty' => $request->qty,
                    'canvassing_id' => $canvassingpengembaliandet->canvassingpesanandetail->canvassing_pesanan_id,
                    'canvassing_detail_id' => $canvassingpengembaliandet->canvassingpesanandetail->id,
                    'canvassingkembali_id' => $canvassingpengembaliandet->canvassing_kembali_id,
                    'canvassingkembali_detail_id' => $canvassingpengembaliandet->id,
                    'harga_beli' => $stok->harga_beli,
                    'diskon_persen_beli' => $stok->diskon_persen_beli,
                    'diskon_rupiah_beli' => $stok->diskon_rupiah_beli,
                    'stokexpdet_id' => $stok->id
                ]);

               
                
            } else {
                $stok = HargaNonExpiredDetail::where('id', $request->id)->first();
                $nonexp = HargaNonExpired::where('id', $stok->harganonexpired_id)->first();
                if ((-1 * $stok->qty) < $request->qty) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Qty tidak boleh melebihi stok'
                    ], 422);
                }
                $qty = $nonexp->qty + $request->qty;
                $stok->update([
                    'qty' => $stok->qty + $request->qty
                ]);
                $nonexp->update([
                    'qty' => $qty
                ]);

                $stoktotal = HargaNonExpiredDetail::where('canvassingkembali_detail_id',$canvassingpengembaliandet->id)->where('product_id',$canvassingpengembaliandet->product_id)->sum('qty');
                
                if (($stoktotal + $request->qty) > $canvassingpengembaliandet->qty_kirim) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Qty tidak boleh melebihi total canvassing'
                    ], 422);
                }

                $harganonexp = HargaNonExpiredDetail::create([
                    'tanggal' => now()->format('Y-m-d'),
                    'harganonexpired_id' => $stok->harganonexpired_id,
                    'product_id' => $stok->product_id,
                    'qty' => $request->qty,
                    'canvassing_id' => $canvassingpengembaliandet->canvassingpesanandetail->canvassing_pesanan_id,
                    'canvassing_detail_id' => $canvassingpengembaliandet->canvassingpesanandetail->id,
                    'canvassingkembali_id' => $canvassingpengembaliandet->canvassing_kembali_id,
                    'canvassingkembali_detail_id' => $canvassingpengembaliandet->id,
                    'harga_beli' => $stok->harga_beli,
                    'diskon_persen_beli' => $stok->diskon_persen_beli,
                    'diskon_rupiah_beli' => $stok->diskon_rupiah_beli,
                    'harganonexpdet_id' => $stok->id
                ]);
            }

            $this->updateStatus($canvassingpengembaliandet->id, $canvassingpengembaliandet->product->status_exp);

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

    public function daftarkembali(Request $request)
    {

        $canvassingdetail = CanvassingPengembalianDetail::with('product')->where('id', $request->canvassingdetail_id)->first();

        if ($canvassingdetail->product->status_exp == 1) {
            $stok = StokExpDetail::with('products', 'stockExp')->where('canvassingkembali_detail_id', $request->canvassingdetail_id)->where('product_id', $canvassingdetail->product_id);
        } else {
            $stok = HargaNonExpiredDetail::with('product')->where('canvassingkembali_detail_id', $request->canvassingdetail_id)->where('product_id', $canvassingdetail->product_id);
        }

        return Datatables::of($stok)
            ->addIndexColumn()
            ->editColumn('tanggal', function ($pb) {
                return $pb->tanggal ? with(new Carbon($pb->tanggal))->format('d-m-Y') : '';
            })
            ->editColumn('product', function ($pb) {
                return $pb->product ? $pb->product->nama : $pb->products->nama;
            })
            ->editColumn('lot', function ($pb) {
                return $pb->products ? $pb->stockExp->lot : 'Non Expired';
            })
            ->editColumn('qty', function ($pb) {
                return $pb->qty;
            })
            ->editColumn('harga_beli', function ($pb) {
                return number_format($pb->harga_beli, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                $id = $row->id;
                $status = $row->products ? 1 : 0;

                return $id . ',' . $status;
            })
            ->make(true);
    }

    public function hapusexp(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->status == 1) {
                $stok = StokExpDetail::where('id', $request->id)->first();
                $qty = $stok->qty;
                $stokexp = StokExpDetail::where('id', $stok->stokexpdet_id)->first();
                $qtyexp =  -1 * ($qty -  ($stokexp->qty));                           
                $stokexp->update([
                    'qty' => $qtyexp
                ]);

                $exp = StokExp::where('id', $stokexp->stok_exp_id)->first();
                $stokqty = $exp->qty - $qty;
                $exp->update([
                    'qty' => $stokqty  
                ]);
                $stok->delete();
            } else {
                $stok = HargaNonExpiredDetail::where('id', $request->id)->first();
                $qty = $stok->qty;
                $stokexp = HargaNonExpiredDetail::where('id', $stok->harganonexpdet_id)->first();
                $qtyexp =  -1 * ($qty -  (-1 * $stokexp->qty));
                $stokexp->update([
                    'qty' => $qtyexp
                ]);

                $nonexp = HargaNonExpired::where('id', $stokexp->harganonexpired_id)->first();
                $stokqty = $nonexp->qty - $qty;    
                $nonexp->update([
                    'qty' => $stokqty
                ]);
                $stok->delete();
            }

            $this->updateStatus($stok->canvassingkembali_detail_id, $request->status);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Dihapus'
            ]);
        } catch (Exception $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }


    public function updateStatus($id, $status)
    {
        $canvassingdetail = CanvassingPengembalianDetail::where('id', $id)->first();        
        // cek dulu di stok exp detail jumlah yang ada di exp detail sama atau tidak dengan qty di canvassing detail
        if ($status == 1) {
            $stok = StokExpDetail::where('canvassingkembali_detail_id', $id)->sum('qty');
        } else {
            $stok = HargaNonExpiredDetail::where('canvassingkembali_detail_id', $id)->sum('qty');
        }        

        $qty = $canvassingdetail->qty_kirim;
        $count = $stok - $qty;        
        if ($count == 0) {                                    
            $canvassingdetail->update([
                'status_data' => 1
            ]);
        } else {
            $canvassingdetail->update([
                'status_data' => 0
            ]);
        }

        // cek di canvassing pesanan apakah ada di canvassing pesanan detail yang status_data nya masih 0 jika tidak maka ubah ke status 2
        $canvassing = CanvassingPengembalian::where('id', $canvassingdetail->canvassing_kembali_id)->first();
        $details = CanvassingPengembalianDetail::where('canvassing_kembali_id', $canvassing->id)->get();

        $allDetailsCompleted = true;
        foreach ($details as $detail) {
            if ($detail->status_data == 0) {
                $allDetailsCompleted = false;
                break;
            }
        }

        if ($allDetailsCompleted) {
            $canvassing->update([
                'status' => 2
            ]);
        } else {
            $canvassing->update([
                'status' => 1
            ]);
        }
    }
}
