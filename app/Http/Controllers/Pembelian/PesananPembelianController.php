<?php

namespace App\Http\Controllers\Pembelian;

use Carbon\Carbon;
use App\Models\TempPo;
use App\Models\Product;
use App\Models\TempPpn;
use App\Models\Supplier;
use App\Models\Komoditas;
use App\Traits\CodeTrait;
use App\Models\TempDiskon;
use Illuminate\Http\Request;
use App\Models\Kategoripesanan;
use App\Models\PesananPembelian;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FakturPembelianDetail;
use App\Models\FakturPenjualanDetail;
use App\Models\PesananPembelianDetail;
use App\Models\Satuan;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Illuminate\Support\Facades\Auth;

class PesananPembelianController extends Controller
{
    use CodeTrait;

    function __construct()
    {
        $this->middleware('permission:pesananpembelian-list');
        $this->middleware('permission:pesananpembelian-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:pesananpembelian-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:pesananpembelian-delete', ['only' => ['destroy']]);
    }

    public function index()
    {

        $title = "Pesanan Pembelian";
        $product = Product::get();
        return view('pembelian.pesananpembelian.index', compact('title', 'product'));
    }

    public function datatable(Request $request)
    {
        $pesananpembelian = PesananPembelian::with([
            'suppliers',
            'kategoripesanan',
            'komoditas',
            'statusPO',
            'pesananpembeliandetail' => function ($query) use ($request) {
                $query->when($request->product_id !== 'All', function ($q) use ($request) {
                    $q->where('product_id', $request->product_id);
                });
            }
        ])
            ->when($request->product_id !== 'All', function ($query) use ($request) {
                $query->whereHas('pesananpembeliandetail', function ($q) use ($request) {
                    $q->where('product_id', $request->product_id);
                });
            })
            ->orderByDesc('id');

        return Datatables::of($pesananpembelian)
            ->addIndexColumn()
            ->addColumn('supplier', function (PesananPembelian $po) {
                return $po->suppliers->nama;
            })
            ->addColumn('status', function (PesananPembelian $po) {
                return $po->status_po_id;
            })
            ->editColumn('tanggal', function (PesananPembelian $po) {
                return $po->tanggal ? with(new Carbon($po->tanggal))->format('d-m-Y') : '';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('pesananpembelian.edit', ['pesananpembelian' => $row->id]);
                $showUrl = route('pesananpembelian.show', ['pesananpembelian' => $row->id]);
                $id = $row->id;
                $status = $row->status_po_id;
                return view('pembelian.pesananpembelian._formAction', compact('editUrl', 'showUrl', 'id', 'status'));
            })
            ->make(true);
    }

    public function create()
    {
        $title = "Pesanan Pembelian";
        $pesananpembelian = new PesananPembelian;
        $suppliers = Supplier::get();
        $komoditass = Komoditas::get();
        $kategoris = Kategoripesanan::get();
        $tglNow = Carbon::now()->format('d-m-Y');

        //delete temp
        $deletedTempDetil = TempPo::where('created_at', '<', Carbon::today())->delete();
        $deletedTempDetil = TempPo::where('user_id', '=', Auth::user()->id)->delete();
        $deletedTempDiskon = TempDiskon::where('jenis', '=', "PO")
            ->where('user_id', '=', Auth::user()->id)
            ->delete();
        $deletedTempPPN = TempPpn::where('jenis', '=', "PO")
            ->where('user_id', '=', Auth::user()->id)
            ->delete();

        //insertt temp
        $tempDiskon = TempDiskon::create(['jenis' => 'PO', 'persen' => '0', 'rupiah' => '0', 'user_id' => Auth::user()->id]);
        $tempPPN    = TempPpn::create(['jenis' => 'PO', 'persen' => '11', 'user_id' => Auth::user()->id]);

        $namaSession = 'PO_BIAYA_' . Auth::user()->id;
        session()->get($namaSession, []);

        session()->put($namaSession, [
            'ongkir' =>  0,
        ]);


        return view('pembelian.pesananpembelian.create', [
            'title' => $title,
            'tglNow' => $tglNow,
            'suppliers' => $suppliers,
            'pesananpembelian' => $pesananpembelian,
            'komoditass' => $komoditass,
            'kategoris' => $kategoris
        ]);
    }

    public function store(Request $request)
    {
        //dd($request);
        $request->validate([
            'supplier_id' => ['required'],
            'tanggal' => ['required'],
            'komoditas_id' => ['required'],
            'kategoripesanan_id' => ['required'],
        ]);
        $datas = $request->all();

        $namaSessionBiaya = 'PO_BIAYA_' . Auth::user()->id;
        $biaya = session()->get($namaSessionBiaya, []);

        DB::beginTransaction();
        try {
            $subtotal = TempPo::where('user_id', '=', Auth::user()->id)->sum('total');
            $ongkir = $biaya['ongkir'];
            $diskon = TempDiskon::where('jenis', '=', "PO")
                ->where('user_id', '=', Auth::user()->id)
                ->get()->first();

            $diskon_persen = $diskon->persen;
            $diskon_rupiah = $diskon->rupiah;

            $total_diskon = ($subtotal * ($diskon_persen / 100)) + $diskon_rupiah;
            $total_diskon_header = $total_diskon;
            $total_diskon_detail = TempPo::where('user_id', '=', Auth::user()->id)->sum('total_diskon');

            $total = $subtotal - $total_diskon + $ongkir;

            $ppn = $total * (11 / 100);
            $grandtotal = $total + $ppn;

            $tanggal = $request->tanggal;
            if ($tanggal <> null) {
                $tanggal = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
            }

            $dataTemp = TempPo::where('user_id', '=', Auth::user()->id)->get();
            $jmlTemp = $dataTemp->count();
            if ($jmlTemp < 1) {
                return redirect()->route('pesananpembelian.index')->with('gagal', 'Tidak ada barang yang diinputkan, Pesanan Pembelian Gagal Disimpan!');
            }

            $datas['kode'] = $this->getKodeTransaksi("pesanan_pembelians", "PO");

            // jika mau input kode sendiri 
            $noUrut = null;
            if ($request->no_so) {
                $noUrut = $request->no_so;
            }

            $noSurat = $this->noPerusahaan($tanggal, $noUrut);
            $datas['tanggal'] = $tanggal;
            $datas['status_po_id'] = "1";
            $datas['diskon_persen'] = $diskon_persen;
            $datas['diskon_rupiah'] = $diskon_rupiah;
            $datas['subtotal'] = $subtotal;
            $datas['total_diskon_header'] = $total_diskon_header;
            $datas['total_diskon_detail'] = $total_diskon_detail;
            $datas['total'] =  $total;
            $datas['ppn'] = $ppn;
            $datas['ongkir'] = $ongkir;
            $datas['grandtotal'] = $grandtotal;
            $datas['no_so_customer'] = $request->no_so_customer;

            $datas['no_so'] = $noSurat['no_surat'];
            $datas['no_urut'] = $noSurat['no_urut'];

            $id_po = PesananPembelian::create($datas)->id;

            //insert detail
            foreach ($dataTemp as $a) {

                $detail = new PesananPembelianDetail;
                $detail->pesanan_pembelian_id = $id_po;
                $detail->tanggal = $tanggal;
                $detail->product_id = $a->product_id;
                $detail->qty = $a->qty;
                $detail->qty_sisa = $a->qty;
                $detail->satuan = $a->satuan;
                $detail->hargabeli = $a->hargabeli;
                $detail->ppn = $a->ppn;
                $detail->diskon_persen = $a->diskon_persen;
                $detail->diskon_rp = $a->diskon_rp;
                $detail->subtotal = $a->subtotal;
                $detail->total_diskon = $a->total_diskon;
                $detail->total = $a->total;
                $detail->ongkir = $a->ongkir;
                $detail->keterangan = $a->keterangan;
                $detail->beda_satuan = $a->beda_satuan;
                $detail->satuan_konversi = $a->satuan_konversi;
                $detail->qty_konversi = $a->qty_konversi;
                $detail->save();
            }
            session()->forget($namaSessionBiaya);
            DB::commit();


            return redirect()->route('pesananpembelian.index')->with('status', 'Pesanan Pembelian (Purchase Order) berhasil dibuat !');
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->route('pesananpembelian.index')->with('error', $th->getMessage());
        }
    }

    public function caribarang()
    {
        $products = Product::where('status', 'Aktif')->with(['categories', 'subcategories']);
        $produk = "";


        if (request()->ajax()) {
            return Datatables::of($products)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $id = $row->id;
                    return view('pembelian.pesananpembelian._pilihBarang', compact('id'));
                })
                ->make(true);
        }

        return view('pembelian.pesananpembelian._caribarang', compact('produk'));
    }
    public function setbarang(Request $request)
    {        
        $product = Product::where('id', '=', $request->id)->get()->first();
        $penjualan = FakturPenjualanDetail::where('product_id', $request->id)->with('fakturpenjualan.customers')->take(30)->orderBy('id', 'desc')->get();
        $pembelian = FakturPembelianDetail::where('product_id', $request->id)->with('fakturpembelian.suppliers')->take(30)->orderBy('id', 'desc')->get();

        $mode = "new";
        $status_po_id = 0;

        $satuan = Satuan::get();
        return view('pembelian.pesananpembelian._setbarang', compact('product', 'mode', 'penjualan', 'pembelian', 'satuan','status_po_id'));
    }

    public function inputtemppo(Request $request)
    {

        $datas = $request->all();
        $harga1 = $request->hargabeli;
        $diskon = $request->diskon_persen;

        $harga = str_replace(',', '.', $harga1) * 1;

        if ($request->ppn > 0) {
            $harga = $harga / (1 + $request->ppn / 100);
        }

        $diskonpersen = str_replace(',', '.', $diskon) * 1;

        $subtotal = $request->qty * $harga;
        $total_diskon = (($subtotal * ($diskonpersen / 100)) + $request->diskon_rp);
        $total = $subtotal - $total_diskon;

        $datas['hargabeli'] = $request->hargabeli;
        $datas['diskon_persen'] = $diskonpersen;
        $datas['subtotal'] = $subtotal;
        $datas['total_diskon'] = $total_diskon;
        $datas['total'] = $total;
        $datas['user_id'] = Auth::user()->id;
        $datas['ongkir'] = 0;
        $datas['ppn'] = $request->ppn;
        $datas['beda_satuan'] = $request->beda_satuan;
        $datas['qty_konversi'] = $request->qty_konversi;
        $datas['satuan_konversi'] = $request->satuan_konversi;

        TempPo::create($datas);
    }

    public function loadtemppo(Request $request)
    {

        $temppo = TempPo::with(['products'])
            ->where('user_id', '=', Auth::user()->id)
            ->get();
        return view('pembelian.pesananpembelian._temptabelpo', compact('temppo'));
    }


    public function destroy_detail(Request $request)
    {
        $id = $request->id;
        TempPo::destroy($id);
    }

    public function editbarang(Request $request)
    {
        $item = TempPo::where('id', '=', $request->id)->get()->first();
        $id_product = $item->product_id;
        $product = new Product;
        $productx = Product::where('id', '=', $id_product)->get()->first();
        $product_name = $productx->nama;
        $status = null;
        $mode = "edit";

        $status_po_id = 0;

        $satuan = Satuan::get();
        return view('pembelian.pesananpembelian._setbarang', compact('product_name', 'mode', 'item', 'product', 'status', 'satuan','status_po_id'));
    }

    public function updatebarang(Request $request)
    {
        //dd($request->hargabeli);
        $harga1 = $request->hargabeli;
        $harga2 = str_replace('.', '', $harga1);
        $harga = str_replace(',', '.', $harga2) * 1;
        $diskon = $request->diskon_persen;

        if ($request->ppn > 0) {
            $harga = $harga / (1 + $request->ppn / 100);
        }

        $diskonpersen = str_replace(',', '.', $diskon) * 1;

        $subtotal = $request->qty * $harga;
        $total_diskon = (($subtotal * ($diskonpersen / 100)) + $request->diskon_rp);
        $total = $subtotal - $total_diskon;

        $temp = TempPo::find($request->id);
        $temp->hargabeli = $request->hargabeli;
        $temp->qty = $request->qty;
        $temp->diskon_persen = $diskonpersen;
        $temp->diskon_rp = $request->diskon_rp;
        $temp->ongkir = 0;
        $temp->keterangan = $request->keterangan;
        $temp->subtotal = $subtotal;
        $temp->total_diskon = $total_diskon;
        $temp->total = $total;
        $temp->ppn = $request->ppn;
        $temp->beda_satuan =  $request->beda_satuan;

        $temp->save();
    }

    public function updateongkir(Request $request)
    {
        $namaSession = 'PO_BIAYA_' . Auth::user()->id;
        session()->get($namaSession, []);

        $ongkir = $request->ongkir ?? 0;

        session()->put($namaSession, [
            'ongkir' =>  $ongkir,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'ongkir' => $ongkir,
            ]
        ]);
    }

    public function hitunggrandtotal(Request $request)
    {
        $subtotal = TempPo::where('user_id', '=', Auth::user()->id)->sum('total');
        $diskon = TempPo::where('user_id', '=', Auth::user()->id)->sum('total_diskon');        

        $namaSessionBiaya = 'PO_BIAYA_' . Auth::user()->id;
        $biaya = session()->get($namaSessionBiaya, []);
        
        $total = $subtotal - $diskon + ($biaya['ongkir'] ?? 0);
        $ppn = $total * (11 / 100);

        // $ongkir = TempPo::where('user_id', '=', Auth::user()->id)->sum('ongkir');
        $grandtotal = $total + $ppn;

        return response()->json([
            'status' => 'success',
            'data' => [
                'subtotal' => $subtotal,
                'total_diskon' => $diskon,
                'ppn' => $ppn,
                'total' => $total,
                'grandtotal' => $grandtotal,
                'ongkir' => $biaya['ongkir']
            ]
        ]);
    }

    public function delete(Request $request)
    {
        $data = PesananPembelian::where('id', '=', $request->id)->get()->first();
        $id = $request->id;
        $status_po_id = $data->status_po_id;
        if ($status_po_id >= 3) {
            $can_delete = "NO";
        } else {
            $can_delete = "YES";
        }

        return view('pembelian.pesananpembelian._confirmDelete', compact('id', 'can_delete'));
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $pesananpembelian = PesananPembelian::find($id);
        $pesananpembelian->deleted_by = Auth::user()->id;
        $pesananpembelian->save();

        PesananPembelian::destroy($request->id);

        $detail = PesananPembelianDetail::where('pesanan_pembelian_id', '=', $id)->get();
        foreach ($detail as $d) {
            PesananPembelianDetail::destroy($d->id);
        }

        return redirect()->route('pesananpembelian.index')->with('status', 'Data Pesanan Pembelian Berhasil Dihapus !');
    }

    public function posting(Request $request)
    {
        $data = PesananPembelian::where('id', '=', $request->id)->get()->first();
        $id = $request->id;
        $status_po_id = $data->status_po_id;
        if ($status_po_id == 1) {
            $can_posting = "YES";
        } else {
            $can_posting = "NO";
        }

        return view('pembelian.pesananpembelian._confirmPosting', compact('id', 'can_posting'));
    }

    public function posted(Request $request)
    {
        $id = $request->id;
        $pesananpembelian = PesananPembelian::find($id);
        $pesananpembelian->status_po_id = "2";
        $pesananpembelian->save();

        return redirect()->route('pesananpembelian.index')->with('status', 'Pesanan Pembelian (PO) berhasil di posting !');
    }

    public function show(PesananPembelian $pesananpembelian)
    {
        $title = "Pesanan Pembelian Detail";
        $pesananpembeliandetails = PesananPembelianDetail::with('products.merks')
            ->where('pesanan_pembelian_id', '=', $pesananpembelian->id)->get();

        return view('pembelian.pesananpembelian.show', compact('title', 'pesananpembelian', 'pesananpembeliandetails'));
    }

    public function print_a4(PesananPembelian $pesananpembelian)
    {

        $title = "Print Pesanan Penj";
        $pesananpembeliandetail = PesananPembelianDetail::with('products.merks')
            ->where('pesanan_pembelian_id', '=', $pesananpembelian->id)->get();
        $jmlBaris  = $pesananpembeliandetail->count();
        $perBaris = 20;
        $totalPage = ceil($jmlBaris / $perBaris);
        $jumlahproduk = count($pesananpembeliandetail);

        $pesananpembeliandetail =  $pesananpembeliandetail;
        $data = [
            'totalPage' => $totalPage,
            'perBaris' => $perBaris,
            'date' => date('d/m/Y'),
            'pesananpembelian' => $pesananpembelian,
            'pesananpembeliandetail' => $pesananpembeliandetail,
            'jumlahproduk' => $jumlahproduk
        ];

        $pdf = PDF::loadView('pembelian.pesananpembelian.print_a4', $data)->setPaper('a4', 'potrait');;
        return $pdf->download($pesananpembelian->no_so . '-' . '.pdf');

        // return view('pembelian.pesananpembelian.print_a4', compact('title',  'totalPage','pesananpembelian','pesananpembeliandetail','date'
        //                                                             ,'perBaris'
        //                                                            ));
    }


    public function editstatus(Request $request)
    {
        $pembelian = PesananPembelian::findOrFail($request->id);

        $pembelian->update([
            'status_po_id' => 1
        ]);

        return back()->with('sukses', 'status Pembelian berhasil dirubah');
    }


    // ===================================== EDIT PESANAN PEMBELIAN ===============================================

    public function edit($id)
    {
        $title = "Pesanan Pembelian";
        $pesananpembelian =  PesananPembelian::findOrFail($id);
        $suppliers = Supplier::get();
        $komoditass = Komoditas::get();
        $kategoris = Kategoripesanan::get();
        $tglNow = Carbon::now()->format('d-m-Y');

        //delete temp
        $deletedTempDetil = TempPo::where('created_at', '<', Carbon::today())->delete();
        $deletedTempDetil = TempPo::where('user_id', '=', Auth::user()->id)->delete();
        $deletedTempDiskon = TempDiskon::where('jenis', '=', "PO")
            ->where('user_id', '=', Auth::user()->id)
            ->delete();

        $deletedTempPPN = TempPpn::where('jenis', '=', "PO")
            ->where('user_id', '=', Auth::user()->id)
            ->delete();

        //insertt temp
        $tempDiskon = TempDiskon::create(['jenis' => 'PO', 'persen' => '0', 'rupiah' => '0', 'user_id' => Auth::user()->id]);
        $tempPPN    = TempPpn::create(['jenis' => 'PO', 'persen' => '11', 'user_id' => Auth::user()->id]);


        return view('pembelian.pesananpembelian.edit', compact('title', 'tglNow', 'suppliers', 'pesananpembelian', 'komoditass', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $data = request()->except(['_token', '_method']);;
        $tanggal = $request->tanggal;
        if ($tanggal <> null) {
            $tanggal = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
        }

        $pesananpembelian = PesananPembelian::findOrFail($id);
        $no_urut =  $pesananpembelian->no_urut;
        if ($request->no_urut) {
            $no_urut = $request->no_urut;
        }        
        $noSurat = $this->noPerusahaan($tanggal, $no_urut);
        $data['tanggal'] = $tanggal;
        PesananPembelian::where('id', $id)->update([
            'supplier_id' => $request->supplier_id,
            'tanggal' => $tanggal,
            'komoditas_id' => $request->komoditas_id,
            'top' => $request->top,
            'kategoripesanan_id' => $request->kategoripesanan_id,
            'keterangan' => $request->keterangan,
            'no_so' => $noSurat['no_surat'],
            'no_urut' => $no_urut,
            'no_so_customer' => $request->no_so_customer,
            'keterangan' => $request->keterangan,            
        ]);



        return redirect()->route('pesananpembelian.index')->with('status', 'Pesanan Pembelian berhasil diubah !');
    }


    public function caribarangedit(Request $request)
    {
        $products = Product::where('status', 'Aktif')->with(['categories', 'subcategories']);
        $produk = "";

        if (request()->ajax()) {
            return Datatables::of($products)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $id = $row->id;
                    return view('pembelian.pesananpembelian._pilihBarang', compact('id'));
                })
                ->make(true);
        }

        return view('pembelian.pesananpembelian._caribarang', compact('produk'));
    }

    public function inputPesananDetail(Request $request)
    {
        DB::beginTransaction();
        try {
            $datas = $request->all();
            $id = $request->pesanan_pembelian_id;
            $harga1 = $request->hargabeli;

            $harga = str_replace(',', '.', $harga1) * 1;
            if ($request->ppn > 0) {
                $harga = $harga / (1 + $request->ppn / 100);
            }

            $subtotal = $request->qty * $harga;
            $total_diskon = (($subtotal * ($request->diskon_persen / 100)) + $request->diskon_rp);
            $total = $subtotal - $total_diskon;

            $datas['hargabeli'] = $request->hargabeli;
            $datas['subtotal'] = $subtotal;
            $datas['total_diskon'] = $total_diskon;
            $datas['total'] = $total;
            $datas['user_id'] = Auth::user()->id;
            $datas['ongkir'] = 0;
            $datas['ppn'] = $request->ppn;

            $datas['beda_satuan'] = $request->beda_satuan;
            $datas['qty_konversi'] = $request->qty_konversi;
            $datas['satuan_konversi'] = $request->satuan_konversi;

            // get data dari pesanan pembelian
            $pembelian = PesananPembelian::where('id', $id)->first();
            $datas['tanggal'] =  $pembelian->tanggal;

            // save di detail
            PesananPembelianDetail::create($datas);

            // ambil data transaksi terbaru 
            $totaldetail  = PesananPembelianDetail::where('pesanan_pembelian_id', $id)->sum('total');
            $totaldiskon  = PesananPembelianDetail::where('pesanan_pembelian_id', $id)->sum('total_diskon');

            // kalkulasi
            $pembelian->subtotal = $totaldetail;
            $pembelian->total = $pembelian->subtotal - $totaldiskon + $pembelian->ongkir;

            $pembelian->total_diskon_header = $totaldiskon;
            $pembelian->total_diskon_detail = $totaldiskon;
            $ppn = $pembelian->total * (11 / 100);

            $grandtotal = $pembelian->total + $ppn;

            $pembelian->grandtotal = $grandtotal;
            $pembelian->update();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'Message'   =>  'Data Berhasil Di Tambahkan'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function loadPesananDetail(Request $request)
    {

        $id = $request->pembelian_id;
        $pesananpembelian = PesananPembelianDetail::with(['products', 'pesananpembelian'])
            ->where('pesanan_pembelian_id', $id)
            ->get();

        return view('pembelian.pesananpembelian._temptabelpodetail', compact('pesananpembelian'));
    }

    public function destroyPesananDetaiL(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            $idpembelian = $request->pembelian_id;
            PesananPembelianDetail::destroy($id);            

            $total = PesananPembelianDetail::where('pesanan_pembelian_id', $idpembelian)->sum('total');
            $totaldiskon = PesananPembelianDetail::where('pesanan_pembelian_id', $idpembelian)->sum('total_diskon');

            $pembelian = PesananPembelian::where('id', $idpembelian)->first();
            // dd($pembelian);
            $pembelian->subtotal = $total;
            $pembelian->total_diskon_detail = $totaldiskon;
            $pembelian->total_diskon_header = $totaldiskon;
            $totalDetail = $total - $totaldiskon + $pembelian->ongkir;

            $ppn = $totalDetail * 11 / 100;
            $pembelian->total = $totalDetail;

            $grandtotal = $totalDetail + $ppn;
            $pembelian->grandtotal = $grandtotal;
            $pembelian->ppn = $ppn;
            $pembelian->update();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'Message'   =>  'Data Berhasil Di Tambahkan'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function editBarangDetail(Request $request)
    {
        $item = PesananPembelianDetail::with('pesananpembelian')->where('id', '=', $request->id)->first();
        $id_product = $item->product_id;
        
        $product = new Product;
        $productx = Product::where('id', '=', $id_product)->first();
        $product_name = $productx->nama;
        $status = $item->pesananpembelian->status_po_id;
        $satuan = Satuan::get();
        $mode = "edit";
        $status_po_id = $item->pesananpembelian->status_po_id;
        return view('pembelian.pesananpembelian._setbarang', compact('product_name', 'mode', 'item', 'product', 'status', 'satuan', 'status_po_id'));
    }

    public function updateBarangDetail(Request $request)
    {
        DB::beginTransaction();
        try {
            //dd($request->hargajual);
            $harga1 = $request->hargabeli;
            $harga2 = str_replace('.', '', $harga1);
            $harga = str_replace(',', '.', $harga2) * 1;
            $diskon = $request->diskon_persen;

            if ($request->ppn > 0) {
                $harga = $harga / (1 + $request->ppn / 100);
            }

            $diskonpersen = str_replace(',', '.', $diskon) * 1;

            $subtotal = $request->qty * $harga;
            $total_diskon = (($subtotal * ($diskonpersen / 100)) + $request->diskon_rp);

            $total = $subtotal - $total_diskon;

            $PP = PesananPembelianDetail::find($request->id);
            $PP->hargabeli = $harga1;
            $PP->qty = $request->qty;
            $PP->qty_sisa = $request->qty;
            $PP->diskon_persen = $diskonpersen;
            $PP->diskon_rp = $request->diskon_rp;
            $PP->keterangan = $request->keterangan;
            $PP->subtotal = $subtotal;
            $PP->total_diskon = $total_diskon;
            $PP->total = $total;
            $PP->ppn = $request->ppn;
            $PP->beda_satuan = $request->beda_satuan;
            $PP->qty_konversi = $request->qty_konversi;
            $PP->satuan_konversi = $request->satuan_konversi;
            $PP->update();

            // kalkulasi header
            $totaldetail = PesananPembelianDetail::where('pesanan_pembelian_id', $PP->pesanan_pembelian_id)->sum('total');
            $totalDiskon = PesananPembelianDetail::where('pesanan_pembelian_id', $PP->pesanan_pembelian_id)->sum('total_diskon');
            // hitung semua data baru di detail dan kalkulasi total dan ongkir
            $pesanan = PesananPembelian::where('id', $PP->pesanan_pembelian_id)->first();

            // hitung total di header 
            $pesanan->ongkir = $pesanan->ongkir;
            $pesanan->subtotal = $totaldetail;
            $pesanan->total_diskon_header = $totalDiskon;
            $pesanan->total_diskon_detail = $totalDiskon;
            $totalHead = $totaldetail - $totalDiskon + $pesanan->ongkir;
            $pesanan->total = $totalHead;
            $ppn = $totalHead * 11 / 100;
            $pesanan->ppn = $ppn;
            $grandtotal = $totalHead + $ppn;
            $pesanan->grandtotal = $grandtotal;
            $pesanan->update();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'Message'   =>  'Data Berhasil Di Tambahkan'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateOngkirDetail(Request $request)
    {

        $pesanan = PesananPembelian::where('id', $request->id)->first();
        $total = $pesanan->subtotal - $pesanan->total_diskon_header + $request->ongkir;
        $ppn = $total * 11 / 100;
        $grandtotal = $total + $ppn;
        $pesanan->update([
            'ongkir' => $request->ongkir,
            'total' => $total,
            'ppn'   => $ppn,
            'grandtotal' => $grandtotal
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'ongkir' => $request->ongkir,
            ]
        ]);
    }

    public function hitungGrandTotalDetail(Request $request)
    {
        $data =  PesananPembelian::where('id', $request->id)->first();
        return response()->json([
            'status' => 'success',
            'data' => [
                'subtotal' => $data->subtotal,
                'total_diskon' => $data->total_diskon_detail,
                'ppn' => $data->ppn,
                'total' => $data->total,
                'grandtotal' => $data->grandtotal,
                'ongkir' => $data->ongkir
            ]
        ]);
    }

    public function noPerusahaan($tanggal, $noUrut)
    {

        $bulan = Carbon::parse($tanggal)->format('n');
        $tahun = Carbon::parse($tanggal)->format('Y');

        $max = PesananPembelian::whereYear('created_at', $tahun)
            ->lockForUpdate() // penting biar ga bentrok
            ->max('no_urut');

        if ($noUrut) {
            $no_urut = $noUrut;
        } else {
            $no_urut = $max ? $max + 1 : 1;
        }


        $romawi = [
            1 => 'I',
            'II',
            'III',
            'IV',
            'V',
            'VI',
            'VII',
            'VIII',
            'IX',
            'X',
            'XI',
            'XII'
        ];

        $bulan_romawi = $romawi[$bulan];

        $no_surat = sprintf('%03d', $no_urut) . "/SHD/SP/$bulan_romawi/" . $tahun;


        $data = [
            'no_urut' => $no_urut,
            'no_surat' => $no_surat
        ];

        return $data;
    }

    public function cekNoSurat(Request $request)
    {
        $bulan = Carbon::parse($request->tanggal)->format('n');
        $tahun = Carbon::parse($request->tanggal)->format('Y');
        if (!$request->no_urut) {
            return response()->json([
                'status' => 'error',
                'message' => 'No urut belum di isi !'
            ], 422);
        }

        $ceksurat = PesananPembelian::whereYear('tanggal', $tahun)
            ->where('no_urut', $request->no_urut)
            ->exists();

        if ($ceksurat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor urut sudah digunakan di tahun ini!'
            ], 422);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Nomor Urut aman digunakan!'
            ], 200);
        }
    }
}
