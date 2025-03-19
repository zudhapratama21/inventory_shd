<?php

namespace App\Http\Controllers\Canvassing;

use App\Http\Controllers\Controller;
use App\Models\CanvassingPengembalian;
use App\Models\CanvassingPesanan;
use App\Models\CanvassingPesananDetail;
use App\Models\Customer;
use App\Models\HargaNonExpired;
use App\Models\HargaNonExpiredDetail;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\Sales;
use App\Models\StokExp;
use App\Models\StokExpDetail;
use App\Models\TempCanvas;
use App\Traits\CodeTrait;
use Carbon\Carbon;
use Dompdf\Canvas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade as PDF;

class CanvassingPesananController extends Controller
{
    use CodeTrait;
    public function index()
    {
        $title = "Canvassing";
        $canvassing = CanvassingPesanan::with('customer')->orderByDesc('id');

        if (request()->ajax()) {
            return Datatables::of($canvassing)
                ->addIndexColumn()
                ->addColumn('tanggal', function (CanvassingPesanan $pb) {
                    return $pb->tanggal;
                })
                ->addColumn('kode', function (CanvassingPesanan $pb) {
                    return $pb->kode;
                })
                ->addColumn('customer', function (CanvassingPesanan $pb) {
                    return $pb->customer->nama;
                })
                ->addColumn('status', function (CanvassingPesanan $pb) {
                    $status = $pb->status;
                    return view('canvassing.canvassing.partial._status', compact('status'));
                })
                ->addColumn('action', function ($row) {
                    $showUrl = route('canvassing.show', ['canvassing' => $row->id]);
                    $id = $row->id;
                    $status = $row->status_pb_id;
                    return view('canvassing.canvassing.modal._form-action', compact('showUrl', 'id'));
                })
                ->make(true);
        }

        return view('canvassing.canvassing.index', compact('title'));
    }

    public function create()
    {
        $title = "Canvassing";
        $customers = Customer::get();
        $tglNow = Carbon::now()->format('d-m-Y');
        $canvassing = new CanvassingPesanan();

        //delete temp
        $deletedTempCanvas = TempCanvas::where('created_at', '<', Carbon::today())->delete();
        $deletedTempCanvas = TempCanvas::where('user_id', '=', Auth::user()->id)->delete();

        return view('canvassing.canvassing.create', compact('title', 'tglNow', 'canvassing', 'customers'));
    }

    public function caribarang()
    {
        $products = Product::with(['categories', 'subcategories']);
        $produk = "";

        if (request()->ajax()) {
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $id = $row->id;
                    return view('canvassing.canvassing.partial._pilihBarang', compact('id'));
                })
                ->make(true);
        }

        return view('canvassing.canvassing.modal._caribarang', compact('produk'));
    }

    public function setbarang(Request $request)
    {
        $product = Product::where('id', '=', $request->id)->get()->first();
        $mode = "new";
        return view('canvassing.canvassing.modal._setbarang', compact('product', 'mode'));
    }

    public function inputTempCanvas(Request $request)
    {
        if ($request->stok < $request->qty) {
            $request->session()->flash('error', 'Qty tidak boleh melebihi stok!');
        } else {
            $temp = TempCanvas::create([
                'product_id' => $request->product_id,
                'qty' => $request->qty,
                'qty_sisa' => $request->qty,
                'keterangan' => $request->keterangan,
                'user_id' => auth()->user()->id
            ]);
        }
    }

    public function loadTempCanvas(Request $request)
    {
        $tempcanvas = TempCanvas::with(['product'])
            ->where('user_id', '=', Auth::user()->id)
            ->get();

        return view('canvassing.canvassing.partial._temptabelcanvas', compact('tempcanvas'));
    }

    public function editbarang(Request $request)
    {
        $item = TempCanvas::where('id', $request->id)->first();
        $id_product = $item->product_id;


        $product = Product::where('id', '=', $id_product)->first();
        $product_name = $product->nama;
        $mode = "edit";

        return view('canvassing.canvassing.modal._setbarang', compact('product_name', 'mode', 'item', 'product'));
    }


    public function updateBarang(Request $request)
    {

        TempCanvas::where('id', $request->id)->update([
            'qty' =>  $request->qty,
            'qty_sisa' => $request->qty,
            'keterangan' => $request->keterangan
        ]);
    }

    public function destroy_detail(Request $request)
    {
        $id = $request->id;
        TempCanvas::destroy($id);

        return response()->json($id);
    }

    public function store(Request $request)
    {
        $temp = TempCanvas::where('user_id', auth()->user()->id)->get();
        $totalqty = TempCanvas::where('user_id', auth()->user()->id)->sum('qty_sisa');
        $kode = $this->getKodeTransaksi('canvassing_pesanans', 'CV');
        $customer = Customer::where('id', $request->customer_id)->first();

        DB::beginTransaction();
        try {
            // save dicanvassing 
            $canvassing = CanvassingPesanan::create([
                'kode' => $kode,
                'kode_pesanan' => $request->kode_pesanan,
                'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
                'customer_id' => $request->customer_id,
                'qty' => $totalqty,
                'status' => 1
            ]);


            foreach ($temp as $item) {
                // kurangi stock produk sesuai di canvassing               
                // tambah stock_canvas produk 

                $product = Product::where('id', $item->product_id)->first();
                $stok = $product->stok - $item->qty;
                $product->stok = $stok;
                $stokcanvassing = $product->stok_canvassing + $item->qty;
                $product->stok_canvassing = $stokcanvassing;
                $product->save();

                // save di canvassing_detail          
                $canvasdet = CanvassingPesananDetail::create([
                    'canvassing_pesanan_id' => $canvassing->id,
                    'product_id' => $item->product_id,
                    'tanggal' => Carbon::parse($canvassing->tanggal)->format('Y-m-d'),
                    'qty' => $item->qty,
                    'qty_sisa' => $item->qty,
                    'keterangan' => $item->keterangan
                ]);

                // masukan data di inventory [ minus ]             
                $inv = InventoryTransaction::create([
                    'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
                    'product_id' => $item->product_id,
                    'qty' => $item->qty * -1,
                    'stok' =>  $stok,
                    'hpp' => $product->hpp,
                    'jenis' => 'CV',
                    'jenis_id' => $kode,
                    'customer' => $customer->nama
                ]);
            }

            $temp->each->delete();

            DB::commit();

            return redirect()->route('canvassing.index')->with('sukses', 'Canvassing Berhasil ditambahkan !');
        } catch (Exception $th) {

            return redirect()->route('canvassing.index')->with('gagal', $th->getMessage());
        }
    }



    public function delete(Request $request)
    {

        $data = CanvassingPesanan::where('id', $request->id)->first();
        $id = $data->id;
        $can_delete = 'YES';
        return view('canvassing.canvassing.modal._confirmDelete', compact('id', 'can_delete'));
    }

    public function destroy(Request $request)
    {

        // dapatkan data canvassing dan canvassing detail 
        $canvassing = CanvassingPesanan::where('id', $request->id)->first();
        $customer = Customer::where('id', $canvassing->customer_id)->first();
        $canvasdet = CanvassingPesananDetail::where('canvassing_pesanan_id', $request->id)->get();
        DB::beginTransaction();
        try {
            $cekPengembalian = CanvassingPengembalian::where('canvassing_pesanan_id', $canvassing->id)->first();
            if ($cekPengembalian) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Canvassing sudah ada di canvassing pengembalian . hapus data canvassing pengembalian terlebih dahulu'
                ], 422);
            }

            if ($canvassing->status == 2) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Produk Kirim Masih ada .. hapus data produk kirim terlebih dahulu'
                ], 422);
            }

            // tambah kembali stok produk sesuai dengan det canvassing            
            foreach ($canvasdet as $item) {
                $product = Product::where('id', $item->product_id)->first();
                $stok = $product->stok + $item->qty;
                $product->stok = $stok;
                $stokcanvassing = $product->stok_canvassing - $item->qty;
                $product->stok_canvassing = $stokcanvassing;
                $product->save();

                // masukan data di inventory [ minus ]             
                $inv = InventoryTransaction::create([
                    'tanggal' => Carbon::parse($canvassing->tanggal)->format('Y-m-d'),
                    'product_id' => $item->product_id,
                    'qty' => $item->qty * 1,
                    'stok' =>  $stok,
                    'hpp' => $product->hpp,
                    'jenis' => 'CV (DEL)',
                    'jenis_id' => $canvassing->kode,
                    'customer' => $customer->nama
                ]);
            }

            // 4. hapus canvassing dan canvassing detail 
            $canvasdet->each->delete();
            $canvassing->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Dihapus'
            ]);
        } catch (Exception $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500); // Mengembalikan status 500 untuk error server
        }
    }


    public function show($id)
    {
        $title =  "Canvassing ";
        $canvas = CanvassingPesanan::with(['creator', 'updater', 'customer'])->where('id', $id)->first();
        $canvasdet = CanvassingPesananDetail::with(['product', 'creator', 'updater'])->where('canvassing_pesanan_id', $id)->get();

        return view('canvassing.canvassing.show', compact('canvas', 'canvasdet', 'title'));
    }

    public function listexp($id)
    {
        $title = 'Canvassing';
        $canvassingdetail = CanvassingPesananDetail::with('product')->where('canvassing_pesanan_id', $id)->get();
        return view('canvassing.canvassing.listexp', compact('canvassingdetail', 'title'));
    }

    public function setexp($canvassingdetail_id, $product_id)
    {
        $title = 'Canvassing';
        $canvassingdetail = CanvassingPesananDetail::with('product')->where('id', $canvassingdetail_id)->first();
        if ($canvassingdetail->product->status_exp == 1) {
            $stok = StokExp::where('product_id', $product_id)->get();
        } else {
            $stok =  HargaNonExpired::where('product_id', $product_id)->get();
        }

        return view('canvassing.canvassing.setexp', compact('canvassingdetail', 'stok', 'title'));
    }

    public function productexp(Request $request)
    {
        $stokexp = StokExp::with('products')->where('product_id', $request->product)->where('qty', '>', 0);
        return Datatables::of($stokexp)
            ->addIndexColumn()
            ->editColumn('tanggal', function (StokExp $pb) {
                return $pb->tanggal ? with(new Carbon($pb->tanggal))->format('d-m-Y') : '';
            })
            ->editColumn('harga_beli', function (StokExp $pb) {
                return number_format($pb->harga_beli, 0, ',', '.');
            })
            ->addColumn('action', function (StokExp $row) {
                $id = $row->id;
                $status = 1;
                $hapusexp = false;
                return view('canvassing.canvassing.partial._actionexp', compact('id', 'status', 'hapusexp'));
            })
            ->make(true);
    }

    public function productnonexp(Request $request)
    {
        $stok = HargaNonExpired::with('product')->where('product_id', $request->product)->where('qty', '>', 0);
        return Datatables::of($stok)
            ->addIndexColumn()
            ->editColumn('product', function ($pb) {
                return $pb->product->nama;
            })
            ->editColumn('harga_beli', function ($pb) {
                return number_format($pb->harga_beli, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                $id = $row->id;
                $status = 0;
                $hapusexp = false;
                return view('canvassing.canvassing.partial._actionexp', compact('id', 'status', 'hapusexp'));
            })
            ->make(true);
    }

    public function formsetexp(Request  $request)
    {
        if ($request->status == 1) {
            $stok = StokExp::with('products')->where('id', $request->id)->first();    
        }else{
            $stok = HargaNonExpired::with('product')->where('id', $request->id)->first();    
        }

        return view('canvassing.canvassing.modal._formsetexp', [
            'stok' => $stok,
            'status' => $request->status
        ]);
    }

    public function inputexp(Request $request)
    {        
        $canvassingdetail = CanvassingPesananDetail::where('id', $request->canvassingdetail_id)->first();
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
                $stok = StokExp::where('id', $request->id)->first();
                if ($stok->qty < $request->qty) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Qty tidak boleh melebihi stok'
                    ], 422);
                }
                $qty = $stok->qty - $request->qty;
                $stok->update([
                    'qty' => $qty
                ]);


                $stoktotal = StokExpDetail::where('canvassing_detail_id',$canvassingdetail->id)->where('product_id',$canvassingdetail->product_id)->sum('qty');
                
                if ((($stoktotal * -1) + $request->qty) > $canvassingdetail->qty ) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Qty tidak boleh melebihi total canvassing'
                    ], 422);
                }

                $expdet = StokExpDetail::create([
                    'tanggal' => $stok->tanggal,
                    'stok_exp_id' => $stok->id,
                    'product_id' => $stok->product_id,
                    'qty' => $request->qty * -1,
                    'canvassing_id' => $canvassingdetail->canvassing_pesanan_id,
                    'canvassing_detail_id' => $canvassingdetail->id,
                    'harga_beli' => $stok->harga_beli,
                    'diskon_persen_beli' => $stok->diskon_persen,
                    'diskon_rupiah_beli' => $stok->diskon_rupiah,
                ]);
            } else {
                $stok = HargaNonExpired::where('id', $request->id)->first();
                if ($stok->qty < $request->qty) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Qty tidak boleh melebihi stok'
                    ], 422);
                }
                $qty = $stok->qty - $request->qty;
                $stok->update([
                    'qty' => $qty
                ]);

                $stoktotal = HargaNonExpiredDetail::where('canvassing_detail_id',$canvassingdetail->id)->where('product_id',$canvassingdetail->product_id)->sum('qty');
                
                if ((($stoktotal * -1) + $request->qty) > $canvassingdetail->qty ) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Qty tidak boleh melebihi total canvassing'
                    ], 422);
                }

                $harganonexp = HargaNonExpiredDetail::create([
                    'tanggal' => now()->format('Y-m-d'),
                    'harganonexpired_id' => $stok->id,
                    'product_id' => $stok->product_id,
                    'qty' => $request->qty * -1,
                    'canvassing_id' => $canvassingdetail->canvassing_pesanan_id,
                    'canvassing_detail_id' => $canvassingdetail->id,
                    'harga_beli' => $stok->harga_beli,
                    'diskon_persen_beli' => $stok->diskon_persen,
                    'diskon_rupiah_beli' => $stok->diskon_rupiah
                ]);
            }

            // ubah status di canvassing detail
            $this->cekStatusData($canvassingdetail->id, $request->status);
            // ubah status di canvassing 


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

    public function daftarprodukkirim(Request $request)
    {
        $canvassingdetail = CanvassingPesananDetail::with('product')->where('id', $request->canvassingdetail_id)->first();

        if ($canvassingdetail->product->status_exp == 1) {
            $stok = StokExpDetail::with('products', 'stockExp')->where('canvassing_detail_id', $request->canvassingdetail_id)->where('product_id',$canvassingdetail->product_id);
        } else {
            $stok = HargaNonExpiredDetail::with('product')->where('canvassing_detail_id', $request->canvassingdetail_id)->where('product_id',$canvassingdetail->product_id);
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
                return $pb->qty * -1;
            })
            ->editColumn('harga_beli', function ($pb) {
                return number_format($pb->harga_beli, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                $id = $row->id;
                $status = $row->products ? 1 : 0;
                $hapusexp = true;
                return view('canvassing.canvassing.partial._actionexp', compact('id', 'status', 'hapusexp'));
            })
            ->make(true);
    }

    public function hapusexp(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->status == 1) {
                $stok = StokExpDetail::where('id', $request->id)->first();
                $qty = $stok->qty * -1;
                $stokexp = StokExp::where('id', $stok->stok_exp_id)->first();
                $qtyexp = $stokexp->qty + $qty;
                $stokexp->update([
                    'qty' => $qtyexp
                ]);
                $stok->delete();
            } else {
                $stok = HargaNonExpiredDetail::where('id', $request->id)->first();
                $qty = $stok->qty * -1;
                $stokexp = HargaNonExpired::where('id', $stok->harganonexpired_id)->first();
                $qtyexp = $stokexp->qty + $qty;
                $stokexp->update([
                    'qty' => $qtyexp
                ]);
                $stok->delete();
            }

            $this->cekStatusData($stok->canvassing_detail_id, $request->status);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Dihapus'
            ]);
        } catch (Exception $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500); // Mengembalikan status 500 untuk error server
        }
    }

    public function cekStatusData($id, $status)
    {

        $canvassingdetail = CanvassingPesananDetail::where('id', $id)->first();
        // cek dulu di stok exp detail jumlah yang ada di exp detail sama atau tidak dengan qty di canvassing detail
        if ($status == 1) {
            $stok = StokExpDetail::where('canvassing_detail_id', $id)->sum('qty');
        } else {
            $stok = HargaNonExpiredDetail::where('canvassing_detail_id', $id)->sum('qty');
        }

        $qty = $canvassingdetail->qty;
        if ($stok - ($qty * -1) == 0) {
            $canvassingdetail->update([
                'status_data' => 1
            ]);
        } else {
            $canvassingdetail->update([
                'status_data' => 0
            ]);
        }
        
        // cek di canvassing pesanan apakah ada di canvassing pesanan detail yang status_data nya masih 0 jika tidak maka ubah ke status 2
        $canvassing = CanvassingPesanan::where('id', $canvassingdetail->canvassing_pesanan_id)->first();
        $details = CanvassingPesananDetail::where('canvassing_pesanan_id', $canvassing->id)->get();

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
        }else{
            $canvassing->update([
                'status' => 1
            ]);
        }
    }


    public function print ($id)
    {
        $canvassing = CanvassingPesanan::with('customer')->where('id', $id)->first();
        $canvassingdetails = CanvassingPesananDetail::with(['canvassing','product', 'creator', 'updater'])->where('canvassing_pesanan_id', $id)->get();
        $jmlBaris  = $canvassingdetails->count();
        $perBaris = 7;
        $totalPage = ceil($jmlBaris / $perBaris);
        $listExp = StokExpDetail::with('stockExp')->where('canvassing_id', '=', $canvassing->id)->get();
        $data = [
            'totalPage' => $totalPage,
            'perBaris' => $perBaris,
            'date' => date('m/d/Y'),
            'listExp' => $listExp,
            'canvassing' => $canvassing,
            'canvassingdetails' => $canvassingdetails
        ];

        $pdf = PDF::loadView('canvassing.canvassing.print', $data)->setPaper('a5', 'landscape');
        return $pdf->download($canvassing->kode . '.pdf');
       
    }
}
