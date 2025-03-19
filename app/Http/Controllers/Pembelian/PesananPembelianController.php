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
use App\Models\HargaNonExpired;
use App\Models\HargaNonExpiredDetail;
use App\Models\PenerimaanBarangDetail;
use App\Models\PesananPembelianDetail;
use App\Models\StokExp;
use App\Models\StokExpDetail;
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
        $pesananpembelian = PesananPembelian::with(['suppliers', 'kategoripesanan', 'komoditas', 'statusPO'])->orderByDesc('id');

        if (request()->ajax()) {
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


        return view('pembelian.pesananpembelian.index', compact('title'));
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


        return view('pembelian.pesananpembelian.create', compact('title', 'tglNow', 'suppliers', 'pesananpembelian', 'komoditass', 'kategoris'));
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
        DB::beginTransaction();
        try {
            $subtotal = TempPo::where('user_id', '=', Auth::user()->id)->sum('total');
            $ongkir = TempPo::where('user_id', '=', Auth::user()->id)->sum('ongkir');
            $diskon = TempDiskon::where('jenis', '=', "PO")
                ->where('user_id', '=', Auth::user()->id)
                ->get()->first();
                
            $diskon_persen = $diskon->persen;
            $diskon_rupiah = $diskon->rupiah;
    
            $total_diskon = ($subtotal * ($diskon_persen / 100)) + $diskon_rupiah;
            $total_diskon_header = $total_diskon;
            $total_diskon_detail = TempPo::where('user_id', '=', Auth::user()->id)->sum('total_diskon');
    
            $total = $subtotal - $total_diskon + $ongkir;
    
            $ppnData = TempPpn::where('jenis', '=', "PO")
                ->where('user_id', '=', Auth::user()->id)
                ->get()->first();
            $ppn_persen = $ppnData->persen;
            $ppn = $total * ($ppn_persen / 100);        
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
            $datas['tanggal'] = $tanggal;
            $datas['status_po_id'] = "1";
            $datas['diskon_persen'] = $diskon_persen;
            $datas['diskon_rupiah'] = $diskon_rupiah;
            $datas['subtotal'] = $subtotal;
            $datas['total_diskon_header'] = $total_diskon_header;
            $datas['total_diskon_detail'] = $total_diskon_detail;
            $datas['total'] =  $total;
            $datas['ppn'] = $ppn_persen;
            $datas['ongkir'] = $ongkir;
            $datas['grandtotal'] = $grandtotal;
            $datas['no_so_customer'] = $request->no_so_customer;
    
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
                $detail->diskon_persen = $a->diskon_persen;
                $detail->diskon_rp = $a->diskon_rp;
                $detail->subtotal = $a->subtotal;
                $detail->total_diskon = $a->total_diskon;
                $detail->total = $a->total;
                $detail->ongkir = $a->ongkir;
                $detail->keterangan = $a->keterangan;
                $detail->save();
                
            }
            DB::commit();
    
            return redirect()->route('pesananpembelian.index')->with('status', 'Pesanan Pembelian (Purchase Order) berhasil dibuat !');

        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->route('pesananpembelian.index')->with('error',$th->getMessage());
        }

       
    }

    public function caribarang()
    {
        $products = Product::where('status','Aktif')->with(['categories', 'subcategories']);
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
        $penjualan = FakturPenjualanDetail::where('product_id',$request->id)->with('fakturpenjualan.customers')->take(30)->orderBy('id','desc')->get();
        $pembelian = FakturPembelianDetail::where('product_id',$request->id)->with('fakturpembelian.suppliers')->take(30)->orderBy('id','desc')->get();
        
        $mode = "new";
        return view('pembelian.pesananpembelian._setbarang', compact('product', 'mode','penjualan','pembelian'));
    }

    public function inputtemppo(Request $request)
    {
        $datas = $request->all();
        $harga1 = $request->hargabeli;
        $diskon = $request->diskon_persen;
        
        $harga = str_replace(',', '.', $harga1) * 1;

        if ($request->ppn > 0) {
            $harga = $harga / (1 + $request->ppn/100);
            
        }

        $ongkir1 = $request->ongkir;        
        $ongkir = str_replace(',', '.', $ongkir1) * 1;
        $diskonpersen = str_replace(',', '.', $diskon) * 1;

        $subtotal = $request->qty * $harga;
        $total_diskon = (($subtotal * ($diskonpersen / 100)) + $request->diskon_rp);
        $total = $subtotal - $total_diskon;

        $datas['hargabeli'] = $harga;
        $datas['diskon_persen'] = $diskonpersen;
        $datas['subtotal'] = $subtotal;
        $datas['total_diskon'] = $total_diskon;
        $datas['total'] = $total;
        $datas['user_id'] = Auth::user()->id;
        $datas['ongkir'] = $ongkir;
        $datas['ppn'] = $request->ppn;

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
        return view('pembelian.pesananpembelian._setbarang', compact('product_name', 'mode', 'item', 'product','status'));
    }

    public function updatebarang(Request $request)
    {
        //dd($request->hargabeli);
        $harga1 = $request->hargabeli;
        $harga2 = str_replace('.', '', $harga1);
        $harga = str_replace(',', '.', $harga2) * 1;
        $diskon = $request->diskon_persen;

        if ($request->ppn > 0) {
            $harga = $harga / (1 + $request->ppn/100);
            
        }

        $diskonpersen = str_replace(',', '.', $diskon) * 1;

        $subtotal = $request->qty * $harga;
        $total_diskon = (($subtotal * ($diskonpersen / 100)) + $request->diskon_rp);
        $total = $subtotal - $total_diskon;
        $ongkir1 = $request->ongkir;
        $ongkir2 = str_replace('.', '', $ongkir1);
        $ongkir = str_replace(',', '.', $ongkir2) * 1;

        $temp = TempPo::find($request->id);
        $temp->hargabeli = $harga;
        $temp->qty = $request->qty;
        $temp->diskon_persen = $diskonpersen;
        $temp->diskon_rp = $request->diskon_rp;
        $temp->ongkir = $ongkir;
        $temp->keterangan = $request->keterangan;
        $temp->subtotal = $subtotal;
        $temp->total_diskon = $total_diskon;
        $temp->total = $total;
        $temp->ppn = $request->ppn;

        $temp->save();
    }

    public function editdiskon(Request $request)
    {
        $item = TempDiskon::where('jenis', '=', "PO")
            ->where('user_id', '=', Auth::user()->id)
            ->get()->first();
        $id_diskon = $item->id;
        $diskon_persen = $item->persen;
        $diskon_rupiah = $item->rupiah;

        return view('pembelian.pesananpembelian._setdiskon', compact('id_diskon', 'diskon_persen', 'diskon_rupiah'));
    }

    public function updatediskon(Request $request)
    {
        $id_diskon = $request->id_diskon;
        $diskon = TempDiskon::find($id_diskon);
        $diskon->persen = $request->diskon_persen;
        $diskon->rupiah = $request->diskon_rupiah;
        $diskon->save();
    }

    public function editppn(Request $request)
    {
        $item = TempPpn::where('jenis', '=', "PO")
            ->where('user_id', '=', Auth::user()->id)
            ->get()->first();
        $id_ppn = $item->id;
        $persen = $item->persen;

        return view('pembelian.pesananpembelian._setppn', compact('id_ppn', 'persen'));
    }

    public function updateppn(Request $request)
    {
        $id_ppn = $request->id_ppn;
        $ppn = TempPpn::find($id_ppn);
        $ppn->persen = $request->persen;
        $ppn->save();
    }

    public function hitungsubtotal(Request $request)
    {
        $subtotal = TempPo::where('user_id', '=', Auth::user()->id)->sum('total');

        return number_format($subtotal, 2, ',', '.');
    }

    public function hitungdiskon(Request $request)
    {
        $subtotal = TempPo::where('user_id', '=', Auth::user()->id)->sum('total');
        $diskon = TempDiskon::where('jenis', '=', "PO")
            ->where('user_id', '=', Auth::user()->id)
            ->get()->first();
        $diskon_persen = $diskon->persen;
        $diskon_rupiah = $diskon->rupiah;

        $total_diskon = ($subtotal * ($diskon_persen / 100)) + $diskon_rupiah;
        if ($total_diskon == 0) {
            return $total_diskon;
        } else {
            return number_format($total_diskon, 2, ',', '.');
        }
    }

    public function hitungtotal(Request $request)
    {
        $subtotal = TempPo::where('user_id', '=', Auth::user()->id)->sum('total');
        $ongkir = TempPo::where('user_id', '=', Auth::user()->id)->sum('ongkir');
        $diskon = TempDiskon::where('jenis', '=', "PO")
            ->where('user_id', '=', Auth::user()->id)
            ->get()->first();
        $diskon_persen = $diskon->persen;
        $diskon_rupiah = $diskon->rupiah;

        $total_diskon = ($subtotal * ($diskon_persen / 100)) + $diskon_rupiah;
        $total = $subtotal - $total_diskon + $ongkir;
        if ($total == 0) {
            return $total;
        } else {
            return number_format($total, 2, ',', '.');
        }
    }

    public function hitungppn(Request $request)
    {
        $subtotal = TempPo::where('user_id', '=', Auth::user()->id)->sum('total');
        $ongkir = TempPo::where('user_id', '=', Auth::user()->id)->sum('ongkir');
        $diskon = TempDiskon::where('jenis', '=', "PO")
            ->where('user_id', '=', Auth::user()->id)
            ->get()->first();
        $diskon_persen = $diskon->persen;
        $diskon_rupiah = $diskon->rupiah;

        $total_diskon = ($subtotal * ($diskon_persen / 100)) + $diskon_rupiah;
        $total = $subtotal - $total_diskon + $ongkir;

        $item = TempPpn::where('jenis', '=', "PO")
            ->where('user_id', '=', Auth::user()->id)
            ->get()->first();
        $persen = $item->persen;
        $ppn = $total * ($persen / 100);

        if ($ppn == 0) {
            return $ppn;
        } else {
            return number_format($ppn, 2, ',', '.');
        }
    }

    public function hitungongkir(Request $request)
    {
        $ongkir = TempPo::where('user_id', '=', Auth::user()->id)->sum('ongkir');

        if ($ongkir == 0) {
            return $ongkir;
        } else {
            return number_format($ongkir, 2, ',', '.');
        }
    }

    public function hitunggrandtotal(Request $request)
    {
        $subtotal = TempPo::where('user_id', '=', Auth::user()->id)->sum('total');
        $ongkir = TempPo::where('user_id', '=', Auth::user()->id)->sum('ongkir');
        $diskon = TempDiskon::where('jenis', '=', "PO")
            ->where('user_id', '=', Auth::user()->id)
            ->get()->first();
        $diskon_persen = $diskon->persen;
        $diskon_rupiah = $diskon->rupiah;

        $total_diskon = ($subtotal * ($diskon_persen / 100)) + $diskon_rupiah;
        $total = $subtotal - $total_diskon + $ongkir; 

        $item = TempPpn::where('jenis', '=', "PO")
            ->where('user_id', '=', Auth::user()->id)
            ->get()->first();
        $persen = $item->persen;
        $ppn = $total * ($persen / 100);

        $ongkir = TempPo::where('user_id', '=', Auth::user()->id)->sum('ongkir');
        $grandtotal = $total + $ppn;

        if ($grandtotal == 0) {
            return $grandtotal;
        } else {
            return number_format($grandtotal, 2, ',', '.');
        }
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
        $perBaris = 8;
        $totalPage = ceil($jmlBaris / $perBaris);        
       
        $pesananpembeliandetail =  $pesananpembeliandetail;
        $date = date('d/m/Y');
        $data = [
            'totalPage' => $totalPage,
            'perBaris' => $perBaris,
            'date' => date('d/m/Y'),
            'pesananpembelian' => $pesananpembelian,
            'pesananpembeliandetail' => $pesananpembeliandetail
        ];

        $pdf = PDF::loadView('pembelian.pesananpembelian.print_a4', $data)->setPaper('a4', 'potrait');;
        return $pdf->download($pesananpembelian->no_so.'-'.$pesananpembelian->kode.'.pdf');

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

        return back()->with('sukses','status Pembelian berhasil dirubah');
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

    public function update(Request $request , $id)
    {                
        $data = request()->except(['_token','_method']);;
        $tanggal = $request->tanggal;
        if ($tanggal <> null) {
            $tanggal = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
        }
        $data['tanggal'] = $tanggal;
        PesananPembelian::where('id',$id)->update([
            'supplier_id' => $request->supplier_id,
            'tanggal' => $tanggal,
            'komoditas_id' => $request->komoditas_id,
            'top' => $request->top,
            'kategoripesanan_id' => $request->kategoripesanan_id,
            'keterangan' => $request->keterangan,
            'no_so' => $request->no_so,
            'no_so_customer' => $request->no_so_customer
        ]);
        


        return redirect()->route('pesananpembelian.index')->with('status', 'Pesanan Pembelian berhasil diubah !');
    }


    public function caribarangedit(Request $request)
    {
         $products = Product::where('status','Aktif')->with(['categories', 'subcategories']);
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
       
        $datas = $request->all();
        $id = $request->pesanan_pembelian_id; 

        $harga1 = $request->hargabeli;

        
        $harga = str_replace(',', '.', $harga1) * 1;
         if ($request->ppn > 0) {
            $harga = $harga / (1 + $request->ppn/100);
            
        }

        $ongkir1 = $request->ongkir;        
        $ongkir = str_replace(',', '.', $ongkir1) * 1;

        $subtotal = $request->qty * $harga;
        $total_diskon = (($subtotal * ($request->diskon_persen / 100)) + $request->diskon_rp);
        $total = $subtotal - $total_diskon; 

        
        $datas['hargabeli'] = $harga;
        $datas['subtotal'] = $subtotal;
        $datas['total_diskon'] = $total_diskon;
        $datas['total'] = $total;
        $datas['user_id'] = Auth::user()->id;
        $datas['ongkir'] = $ongkir;
        $datas['ppn'] = $request->ppn;
    

        // get data dari pesanan pembelian
        $pembelian = PesananPembelian::where('id',$id)->first();
        $datas['tanggal'] =  $pembelian->tanggal;

        // save di detail
        PesananPembelianDetail::create($datas);


        // ambil data transaksi terbaru 
        $totaldetail  = PesananPembelianDetail::where('pesanan_pembelian_id',$id)->sum('total');
        $totalongkir  = PesananPembelianDetail::where('pesanan_pembelian_id',$id)->sum('ongkir');
        $totaldiskon  = PesananPembelianDetail::where('pesanan_pembelian_id',$id)->sum('total_diskon');

        // kalkulasi

        $pembelian->subtotal = $totaldetail;
        $totaldiskonheader = ($pembelian->subtotal * ($pembelian->diskon_persen / 100)) + $pembelian->diskon_rupiah;
        $pembelian->total = $pembelian->subtotal - $totaldiskonheader + $totalongkir;
        
        $pembelian->ongkir = $totalongkir;
        $pembelian->total_diskon_header = $totaldiskonheader;
        $pembelian->total_diskon_detail = $totaldiskon;
        $ppn_persen = $pembelian->ppn;
        $ppn = $pembelian->total * ($ppn_persen / 100);   
        
        $grandtotal = $pembelian->total + $ppn;
        
        
        $pembelian->grandtotal = $grandtotal;        
        $pembelian->update();    

    }

    public function loadPesananDetail(Request $request)
    {
      
        $id = $request->pembelian_id;
        $pesananpembelian = PesananPembelianDetail::with(['products','pesananpembelian'])
                        ->where('pesanan_pembelian_id', $id)
                        ->get();
                        
        return view('pembelian.pesananpembelian._temptabelpodetail', compact('pesananpembelian'));
    }

    public function destroyPesananDetaiL(Request $request)
    {
        $id = $request->id;
        $idpembelian = $request->pembelian_id;

         // kurangi dulu total , grand total dll di pesanan penjualan        
         PesananPembelianDetail::destroy($id);

         $total = PesananPembelianDetail::where('pesanan_pembelian_id',$idpembelian)->sum('total');        
         $ongkir = PesananPembelianDetail::where('pesanan_pembelian_id',$idpembelian)->sum('ongkir');        
             
        // hitung di header          
          $pembelian = PesananPembelian::where('id',$idpembelian)->first();                         

          $pembelian->subtotal = $total;
          $pembelian->ongkir = $ongkir;    
        //   grandtotal
          $pembelian->total = $pembelian->subtotal - $pembelian->total_diskon_header + $pembelian->ongkir;                    

          $ppn_persen = $pembelian->ppn;
          $ppn = $pembelian->total * ($ppn_persen / 100);          
                          
          $grandtotal = $pembelian->total + $ppn ;        
          $pembelian->grandtotal = $grandtotal;        
          $pembelian->update(); 

    }

    public function editBarangDetail(Request $request)
    {
        $item = PesananPembelianDetail::with('pesananpembelian')->where('id', '=', $request->id)->first();        
        $id_product = $item->product_id;    

        $product = new Product;
        $productx = Product::where('id', '=', $id_product)->first();
        $product_name = $productx->nama;
        $status = $item->pesananpembelian->status_po_id;        
        $mode = "edit";
        return view('pembelian.pesananpembelian._setbarang', compact('product_name', 'mode', 'item', 'product','status'));
    }

    public function updateBarangDetail(Request $request)
    {
        //dd($request->hargajual);
        $harga1 = $request->hargabeli;        
        $harga2 = str_replace('.', '', $harga1);
        $harga = str_replace(',', '.', $harga2) * 1;
        $diskon = $request->diskon_persen;

        if ($request->ppn > 0) {
            $harga = $harga / (1 + $request->ppn/100);            
        }

        $ongkir1 = $request->ongkir;
        $ongkir2 = str_replace('.', '', $ongkir1);
        $ongkir = str_replace(',', '.', $ongkir2) * 1;

        $diskonpersen = str_replace(',', '.', $diskon) * 1;

        $subtotal = $request->qty * $harga;
        $total_diskon = (($subtotal * ($diskonpersen / 100)) + $request->diskon_rp);

        $total = $subtotal - $total_diskon;

        $PP = PesananPembelianDetail::find($request->id);
        $PP->hargabeli = $harga;
        $PP->qty = $request->qty;
        $PP->qty_sisa = $request->qty;
        $PP->diskon_persen = $diskonpersen;
        $PP->diskon_rp = $request->diskon_rp;
        $PP->ongkir = $ongkir;
        $PP->keterangan = $request->keterangan;
        $PP->subtotal = $subtotal;
        $PP->total_diskon = $total_diskon;
        $PP->total = $total;
        $PP->ppn = $request->ppn;
        $PP->update();

        // kalkulasi header
        $totaldetail = PesananPembelianDetail::where('pesanan_pembelian_id',$PP->pesanan_pembelian_id)->sum('total');
        $ongkirdetail = PesananPembelianDetail::where('pesanan_pembelian_id',$PP->pesanan_pembelian_id)->sum('ongkir');        
        $totalDiskon = PesananPembelianDetail::where('pesanan_pembelian_id',$PP->pesanan_pembelian_id)->sum('total_diskon');
        // hitung semua data baru di detail dan kalkulasi total dan ongkir
        $pesanan = PesananPembelian::where('id',$PP->pesanan_pembelian_id)->first();

        // hitung total di header 
        $pesanan->ongkir = $ongkirdetail;
        $pesanan->subtotal = $totaldetail;
        $pesanan->total_diskon_header = ($pesanan->subtotal * ($pesanan->diskon_persen / 100)) + $pesanan->diskon_rupiah;
        $pesanan->total_diskon_detail = $totalDiskon;
        $pesanan->total = $pesanan->subtotal - $pesanan->total_diskon_header + $ongkirdetail;
        $ppn_persen = $pesanan->ppn;
        $ppn = $pesanan->total * ($ppn_persen / 100);
        $ongkir = $pesanan->ongkir;
        $grandtotal = $pesanan->total + $ppn;

        $pesanan->grandtotal = $grandtotal;        
        $pesanan->update();        
        // hitung grandtotaldiheader
    }

    public function editDiskonDetail(Request $request)
    {  
        $item = PesananPembelian::where('id',$request->id)->first();        
        // kalkulasi persen diskon        
        $id_diskon = $item->id;
        $diskon_persen = $item->diskon_persen;
        $diskon_rupiah = $item->diskon_rupiah;        

        return view('pembelian.pesananpembelian._setdiskondetail', compact('id_diskon', 'diskon_persen', 'diskon_rupiah'));
    }

    public function updateDiskonDetail(Request $request)
    {
        
        $pesanan = PesananPembelian::where('id',$request->id)->first();

        $id_diskon = $request->id_diskon;
        $diskon_persen = $request->diskon_persen;
        $diskon_rupiah = $request->diskon_rupiah;


        $total_diskon = (($pesanan->subtotal * ( $diskon_persen / 100)) + $diskon_rupiah);
        $total = $pesanan->subtotal - $total_diskon + $pesanan->ongkir;
        $pesanan->diskon_persen = $diskon_persen;
        $pesanan->diskon_rupiah = $diskon_rupiah;
        $pesanan->total_diskon_header = $total_diskon;
            
        $pesanan->total = $total;

        $ppn_persen = $pesanan->ppn;

        $ppn = $pesanan->total * ($ppn_persen / 100);
        $ongkir = $pesanan->ongkir;
        $pesanan->grandtotal = $pesanan->total + $ppn;
        $pesanan->update();
        
    }

    public function editPPNDetail(Request $request)
    {
        $item = PesananPembelian::where('id',$request->id)->select('ppn','id')->first();                 
        $persen = $item->ppn;
        $id_ppn = $item->id;

        return view('pembelian.pesananpembelian._setppndetail', compact('id_ppn','persen'));
    }

    public function updatePPNDetail(Request $request)
    {
        $id = $request->id;        
        $pembelian = PesananPembelian::where('id',$id)->first();
        $pembelian->ppn = $request->persen;

        $ppn = $pembelian->total * ($request->persen / 100);          
        $grandtotal = $pembelian->total + $ppn;        

        $pembelian->grandtotal = $grandtotal;        
        $pembelian->update();                

    }

    public function hitungSubTotalDetail(Request $request)
    {
        $subtotal = PesananPembelianDetail::where('pesanan_pembelian_id', $request->id)->sum('total');
        return number_format($subtotal, 2, ',', '.');
    }

    public function hitungDiskonDetail(Request $request)
    {   
        $diskon =  PesananPembelian::where('id',$request->id)->first();     
        
        $total_diskon = $diskon->total_diskon_header;                

        if ($total_diskon == 0) {
            return $total_diskon;
        } else {
            return number_format($total_diskon, 2, ',', '.');
        }
    }

    public function hitungTotalDetail(Request $request)
    {
        $subtotal = PesananPembelian::where('id',$request->id)->first();
        $total = $subtotal->total;

        if ($total == 0) {
            return $total;
        } else {
            return number_format($total, 2, ',', '.');
        }
    }

    public function hitungPPNDetail(Request $request)
    {
        $subtotal =  PesananPembelian::where('id', '=', $request->id)->first();                
        $ppn = $subtotal->total * ($subtotal->ppn / 100);

        if ($ppn == 0) {
            return $ppn;
        } else {
            return number_format($ppn, 2, ',', '.');
        }
    }

    public function hitungOngkirDetail(Request $request)
    {
        $data =  PesananPembelian::where('id', $request->id)->first();
        $ongkir = $data->ongkir;
        if ($ongkir == 0) {
            return $ongkir;
        } else {
            return number_format($ongkir, 2, ',', '.');
        }
    }

    public function hitungGrandTotalDetail(Request $request)
    {
       $data =  PesananPembelian::where('id',$request->id)->first();
        $grandtotal = $data->grandtotal;

        if ($grandtotal == 0) {
            return $grandtotal;
        } else {
            return number_format($grandtotal, 2, ',', '.');
        }
    }






    
}
