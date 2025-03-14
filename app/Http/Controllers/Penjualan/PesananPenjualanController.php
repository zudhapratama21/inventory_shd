<?php

namespace App\Http\Controllers\Penjualan;

use Carbon\Carbon;
use App\Models\TempSo;
use App\Models\Product;
use App\Models\TempPpn;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Komoditas;
use App\Traits\CodeTrait;
use App\Models\TempDiskon;
use Illuminate\Http\Request;
use App\Models\Kategoripesanan;
use App\Models\PesananPenjualan;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\FakturPembelianDetail;
use App\Models\FakturPenjualan;
use App\Models\FakturPenjualanDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\PesananPenjualanDetail;
use App\Models\Sales;
use Barryvdh\DomPDF\Facade as PDF;

class PesananPenjualanController extends Controller
{
    use CodeTrait;
    function __construct()
    {
        $this->middleware('permission:pesananpenjualan-list');
        $this->middleware('permission:pesananpenjualan-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:pesananpenjualan-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:pesananpenjualan-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = "Pesanan Penjualan";
        $pesananpenjualan = PesananPenjualan::with(['customers', 'kategoripesanan', 'komoditas', 'statusSO','FakturSO'])->orderByDesc('id');        
        if (request()->ajax()) {
            return Datatables::of($pesananpenjualan)
                ->addIndexColumn()
                ->addColumn('customer', function (PesananPenjualan $so) {
                    return $so->customers->nama;
                })
                ->addColumn('status', function (PesananPenjualan $so) {
                    $status = $so->status_so_id;                    
                    return view('penjualan.pesananpenjualan.partial.badge',compact('status'));
                })
                ->editColumn('tanggal', function (PesananPenjualan $so) {
                    return $so->tanggal ? with(new Carbon($so->tanggal))->format('d-m-Y') : '';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('pesananpenjualan.edit', ['pesananpenjualan' => $row->id]);
                    $showUrl = route('pesananpenjualan.show', ['pesananpenjualan' => $row->id]);
                    $id = $row->id;
                    $status = $row->status_so_id;
                    return view('penjualan.pesananpenjualan._formAction', compact('editUrl', 'showUrl', 'id', 'status'));
                })
                ->make(true);
        }
        return view('penjualan.pesananpenjualan.index', compact('title'));
    }

    public function create()
    {
        $title = "Pesanan Penjualan";
        $pesananpenjualan = new PesananPenjualan;
        $customers = Customer::get();
        $komoditass = Komoditas::get();
        $kategoris = Kategoripesanan::get();
        $saless = Sales::get();
        $tglNow = Carbon::now()->format('d-m-Y');

        //delete temp
        $deletedTempDetil = TempSo::where('created_at', '<', Carbon::today())->delete();
        $deletedTempDetil = TempSo::where('user_id', '=', Auth::user()->id)->delete();
        $deletedTempDiskon = TempDiskon::where('jenis', '=', "SO")
            ->where('user_id', '=', Auth::user()->id)
            ->delete();
        $deletedTempPPN = TempPpn::where('jenis', '=', "SO")
            ->where('user_id', '=', Auth::user()->id)
            ->delete();
        //insertt temp
        $tempDiskon = TempDiskon::create(['jenis' => 'SO', 'persen' => '0', 'rupiah' => '0', 'user_id' => Auth::user()->id]);
        $tempPPN    = TempPpn::create(['jenis' => 'SO', 'persen' => '11', 'user_id' => Auth::user()->id]);


        return view('penjualan.pesananpenjualan.create', compact('title', 'saless', 'tglNow', 'customers', 'pesananpenjualan', 'komoditass', 'kategoris'));
    }

    public function store(Request $request)
    {
        // dd($request->tanggal_pesanan_customer);
        $request->validate([
            'customer_id' => ['required'],
            'tanggal' => ['required'],
            'komoditas_id' => ['required'],
            'kategoripesanan_id' => ['required'],
            'sales_id' => ['required'],
        ]);

        $datas = $request->all();

        // subtotal
        $subtotal = TempSo::where('user_id', '=', Auth::user()->id)->sum('total');
        $ongkir = TempSo::where('user_id', '=', Auth::user()->id)->sum('ongkir');

        // ambil diskon dari temporary
        $diskon = TempDiskon::where('jenis', '=', "SO")
            ->where('user_id', '=', Auth::user()->id)
            ->get()->first();

        $diskon_persen = $diskon->persen;
        $diskon_rupiah = $diskon->rupiah;

        $total_diskon = ($subtotal * ($diskon_persen / 100)) + $diskon_rupiah;
        $total_diskon_header = $total_diskon;
        $total_diskon_detail = TempSo::where('user_id', '=', Auth::user()->id)->sum('total_diskon');

        $datatotal = $subtotal - $total_diskon + $ongkir;

        $total = sprintf("%.2f", $datatotal);

        $ppnData = TempPpn::where('jenis', '=', "SO")
            ->where('user_id', '=', Auth::user()->id)
            ->get()->first();

        $ppn_persen = $ppnData->persen;
        $ppn = $total * ($ppn_persen / 100);
        $grandtotal = $total + $ppn;

        $tanggal = $request->tanggal;
        $tanggalcustomer = null;
        if ($tanggal <> null) {
            $tanggal = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
        }

        if ($request->tanggal_pesanan_customer <> null) {
            $tanggalcustomer = Carbon::parse($request->tanggal_pesanan_customer)->format('Y-m-d');
        }

        $dataTemp = TempSo::where('user_id', '=', Auth::user()->id)->get();

        $jmlTemp = $dataTemp->count();
        if ($jmlTemp < 1) {
            return redirect()->route('pesananpenjualan.index')->with('gagal', 'Tidak ada barang yang diinputkan, Pesanan Penjualan Gagal Disimpan!');
        }

        $datas['kode'] = $this->getKodeTransaksi("pesanan_penjualans", "SO");
        $datas['tanggal'] = $tanggal;
        $datas['tanggal_pesanan_customer'] = $tanggalcustomer;
        $datas['status_so_id'] = "1";
        $datas['diskon_persen'] = $diskon_persen;
        $datas['diskon_rupiah'] = $diskon_rupiah;
        $datas['subtotal'] = $subtotal;
        $datas['total_diskon_header'] = $total_diskon_header;
        $datas['total_diskon_detail'] = $total_diskon_detail;
        $datas['total'] =  $total;
        $datas['ppn'] = $ppn_persen;
        $datas['ongkir'] = $ongkir;
        $datas['grandtotal'] = $grandtotal;

        $id_so = PesananPenjualan::create($datas)->id;

        //insert detail

        foreach ($dataTemp as $a) {

            $detail = new PesananPenjualanDetail;
            $detail->pesanan_penjualan_id = $id_so;
            $detail->tanggal = $tanggal;
            $detail->product_id = $a->product_id;
            $detail->qty = $a->qty;
            $detail->qty_sisa = $a->qty;
            $detail->satuan = $a->satuan;
            $detail->hargajual = $a->hargajual;
            $detail->diskon_persen = $a->diskon_persen;
            $detail->diskon_rp = $a->diskon_rp;
            $detail->subtotal = $a->subtotal;
            $detail->total_diskon = $a->total_diskon;
            $detail->total = $a->total;
            $detail->ongkir = $a->ongkir;
            $detail->keterangan = $a->keterangan;
            $detail->ppn = $a->ppn;

            $detail->save();
        }

        return redirect()->route('pesananpenjualan.index')->with('status', 'Pesanan Penjualan (Sales Order) berhasil dibuat !');
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
                    return view('penjualan.pesananpenjualan._pilihBarang', compact('id'));
                })
                ->make(true);
        }

        return view('penjualan.pesananpenjualan._caribarang', compact('produk'));
    }

    public function setbarang(Request $request)
    {
        $product = Product::where('id', '=', $request->id)->get()->first();
        $penjualan = FakturPenjualanDetail::where('product_id', $request->id)->with('fakturpenjualan.customers')->take(30)->orderBy('id', 'desc')->get();
        $pembelian = FakturPembelianDetail::where('product_id', $request->id)->with('fakturpembelian.suppliers')->take(30)->orderBy('id', 'desc')->get();
        $mode = "new";

        $hargaProduk = FakturPenjualanDetail::where('product_id', $request->id)->orderBy('id', 'desc')->latest()->first();
        return view('penjualan.pesananpenjualan._setbarang', compact('product', 'mode', 'penjualan', 'pembelian','hargaProduk'));
    }

    public function inputtempso(Request $request)
    {
        $ppnOngkir = 0;
        $datas = $request->all();

        $harga1 = $request->hargajual;
        $harga2 = str_replace('.', '', $harga1);
        $harga = str_replace(',', '.', $harga2) * 1;


        if ($request->ppn > 0) {
            $harga = $harga / (1 + $request->ppn / 100);
        }


        $ongkir1 = $request->ongkir;
        $ongkir2 = str_replace('.', '', $ongkir1);
        $ongkir = str_replace(',', '.', $ongkir2) * 1;

        if ($request->ppn_ongkir > 0) {
            $ongkir = $ongkir / (1 + $request->ppn_ongkir / 100);
        }

        $subtotal = $request->qty * $harga;
        $total_diskon = (($subtotal * ($request->diskon_persen / 100)) + $request->diskon_rp);
        $total = $subtotal - $total_diskon;

        $datas['hargajual'] = $harga;
        $datas['ppn'] = $request->ppn;
        $datas['subtotal'] = $subtotal;
        $datas['total_diskon'] = $total_diskon;
        $datas['total'] = $total;
        $datas['user_id'] = Auth::user()->id;
        $datas['ongkir'] = $ongkir;

        TempSo::create($datas);
    }

    public function loadtempso(Request $request)
    {
        $tempso = TempSo::with(['products'])
            ->where('user_id', '=', Auth::user()->id)
            ->get();

        return view('penjualan.pesananpenjualan._temptabelso', compact('tempso'));
    }

    // update
    public function update(Request $request, $id)
    {        
        $data = request()->except(['_token', '_method']);;
        $tanggal = $request->tanggal;
        if ($tanggal <> null) {
            $tanggal = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
        }

        $tanggalcustomer = null;

        if ($request->tanggal_pesanan_customer <> null) {
            $tanggalcustomer = Carbon::createFromFormat('d/m/Y', $request->tanggal_pesanan_customer)->format('Y-m-d');;
        }

        $data['tanggal'] = $tanggal;
        $data['tanggal_pesanan_customer'] = $tanggalcustomer;
        $hasil = PesananPenjualan::where('id', $id)->update($data);        

        return redirect()->route('pesananpenjualan.index')->with('status', 'Pesanan Penjualan (Sales Order) berhasil diubah !');
    }

    // menghapus data temp = done
    public function destroy_detail(Request $request)
    {
        $id = $request->id;
        TempSo::destroy($id);
    }



    public function editbarang(Request $request)
    {
        $item = TempSo::where('id', '=', $request->id)->get()->first();
        $id_product = $item->product_id;
        $product = new Product;
        $productx = Product::where('id', '=', $id_product)->get()->first();
        $product_name = $productx->nama;
        $mode = "edit";
        $status = null;


        return view('penjualan.pesananpenjualan._setbarang', compact('product_name', 'mode', 'item', 'product','status'));
    }

    public function updatebarang(Request $request)
    {
        $harga1 = $request->hargajual;
        $harga2 = str_replace('.', '', $harga1);
        $harga = str_replace(',', '.', $harga2) * 1;

        if ($request->ppn > 0) {
            $harga = $harga / (1 + $request->ppn / 100);
        }

        $ongkir1 = $request->ongkir;
        $ongkir2 = str_replace('.', '', $ongkir1);
        $ongkir = str_replace(',', '.', $ongkir2) * 1;

        $ppnOngkir = 0;
        if ($request->ppn_ongkir > 0) {
            $ongkir = $ongkir / (1 + $request->ppn_ongkir / 100);
        }

        $subtotal = $request->qty * $harga;
        $total_diskon = (($subtotal * ($request->diskon_persen / 100)) + $request->diskon_rp);
        $total = $subtotal - $total_diskon;

        $temp = TempSo::find($request->id);
        $temp->hargajual = $harga;
        $temp->qty = $request->qty;
        $temp->diskon_persen = $request->diskon_persen;
        $temp->diskon_rp = $request->diskon_rp;
        $temp->ongkir = $ongkir;
        $temp->keterangan = $request->keterangan;
        $temp->subtotal = $subtotal;
        $temp->total_diskon = $total_diskon;
        $temp->total = $total;
        $temp->save();
    }

    public function editdiskon(Request $request)
    {
        $item = TempDiskon::where('jenis', '=', "SO")
            ->where('user_id', '=', Auth::user()->id)
            ->get()->first();
        $id_diskon = $item->id;
        $diskon_persen = $item->persen;
        $diskon_rupiah = $item->rupiah;

        return view('penjualan.pesananpenjualan._setdiskon', compact('id_diskon', 'diskon_persen', 'diskon_rupiah'));
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
        $item = TempPpn::where('jenis', '=', "SO")
            ->where('user_id', '=', Auth::user()->id)
            ->get()->first();
        $id_ppn = $item->id;
        $ppn = $item->persen;

        return view('penjualan.pesananpenjualan._setppn', compact('id_ppn', 'ppn'));
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
        $subtotal = TempSo::where('user_id', '=', Auth::user()->id)->sum('total');

        return number_format($subtotal, 2, ',', '.');
    }

    public function hitungdiskon(Request $request)
    {
        $subtotal = TempSo::where('user_id', '=', Auth::user()->id)->sum('total');
        $diskon = TempDiskon::where('jenis', '=', "SO")
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
        $subtotal = TempSo::where('user_id', '=', Auth::user()->id)->sum('total');
        $ongkir = TempSo::where('user_id', '=', Auth::user()->id)->sum('ongkir');
        $diskon = TempDiskon::where('jenis', '=', "SO")
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
        $subtotal = TempSo::where('user_id', '=', Auth::user()->id)->sum('total');
        $diskon = TempDiskon::where('jenis', '=', "SO")
            ->where('user_id', '=', Auth::user()->id)
            ->get()->first();
        $ongkir = TempSo::where('user_id', '=', Auth::user()->id)->sum('ongkir');


        $diskon_persen = $diskon->persen;
        $diskon_rupiah = $diskon->rupiah;

        $total_diskon = ($subtotal * ($diskon_persen / 100)) + $diskon_rupiah;
        $total = $subtotal - $total_diskon + $ongkir;

        $item = TempPpn::where('jenis', '=', "SO")
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
        $ongkir = TempSo::where('user_id', '=', Auth::user()->id)->sum('ongkir');

        if ($ongkir == 0) {
            return $ongkir;
        } else {
            return number_format($ongkir, 2, ',', '.');
        }
    }

    public function hitunggrandtotal(Request $request)
    {
        $subtotal = TempSo::where('user_id', '=', Auth::user()->id)->sum('total');
        $ongkir = TempSo::where('user_id', '=', Auth::user()->id)->sum('ongkir');
        $diskon = TempDiskon::where('jenis', '=', "SO")
            ->where('user_id', '=', Auth::user()->id)
            ->get()->first();

        $diskon_persen = $diskon->persen;
        $diskon_rupiah = $diskon->rupiah;

        $total_diskon = ($subtotal * ($diskon_persen / 100)) + $diskon_rupiah;
        $total = $subtotal - $total_diskon + $ongkir;

        $item = TempPpn::where('jenis', '=', "SO")
            ->where('user_id', '=', Auth::user()->id)
            ->get()->first();

        $persen = $item->persen;
        $ppn = $total * ($persen / 100);

        $grandtotal = $total + $ppn;

        if ($grandtotal == 0) {
            return $grandtotal;
        } else {
            return number_format($grandtotal, 2, ',', '.');
        }
    }

    public function delete(Request $request)
    {
        $data = PesananPenjualan::where('id', '=', $request->id)->get()->first();

        $id = $request->id;
        $status_so_id = $data->status_so_id;
        if ($status_so_id >= 3) {
            $can_delete = "NO";
        } else {
            $can_delete = "YES";
        }

        return view('penjualan.pesananpenjualan._confirmDelete', compact('id', 'can_delete'));
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $pesananpenjualan = PesananPenjualan::find($id);
        $pesananpenjualan->deleted_by = Auth::user()->id;
        $pesananpenjualan->save();

        PesananPenjualan::destroy($request->id);

        $detail = PesananPenjualanDetail::where('pesanan_penjualan_id', '=', $id)->get();
        foreach ($detail as $d) {
            PesananPenjualanDetail::destroy($d->id);
        }

        return redirect()->route('pesananpenjualan.index')->with('status', 'Data Pesanan Penjualan Berhasil Dihapus !');
    }

    public function posting(Request $request)
    {
        $data = PesananPenjualan::where('id', '=', $request->id)->get()->first();

        $id = $request->id;
        $status_so_id = $data->status_so_id;
        if ($status_so_id == 1) {
            $can_posting = "YES";
        } else {
            $can_posting = "NO";
        }

        return view('penjualan.pesananpenjualan._confirmPosting', compact('id', 'can_posting'));
    }

    public function posted(Request $request)
    {
        $id = $request->id;
        $pesananpenjualan = PesananPenjualan::find($id);
        $pesananpenjualan->status_so_id = "2";
        $pesananpenjualan->save();

        return redirect()->route('pesananpenjualan.index')->with('status', 'Pesanan Penjualan (SO) berhasil di posting !');
    }

    public function show(PesananPenjualan $pesananpenjualan)
    {
        $title = "Pesanan Pembelian Detail";
        $pesananpenjualandetails = PesananPenjualanDetail::with('products')
            ->where('pesanan_penjualan_id', '=', $pesananpenjualan->id)->get();

        return view('penjualan.pesananpenjualan.show', compact('title', 'pesananpenjualan', 'pesananpenjualandetails'));
    }

    public function editStatus(Request $request)
    {
        $so = PesananPenjualan::findOrFail($request->id);
        $so->status_so_id = "1";
        $data = $so->update();
        return back();
    }

    public function edit($id)
    {
        $title = "Pesanan Penjualan";
        $pesananpenjualan = PesananPenjualan::with('FakturSO','customers')->findOrFail($id);
        $customers = Customer::get();
        $komoditass = Komoditas::get();
        $kategoris = Kategoripesanan::get();
        $saless = Sales::get();
        $tglNow = Carbon::now()->format('d/m/Y');

        //delete temp
        $deletedTempDetil = TempSo::where('created_at', '<', Carbon::today())->delete();
        $deletedTempDetil = TempSo::where('user_id', '=', Auth::user()->id)->delete();
        $deletedTempDiskon = TempDiskon::where('jenis', '=', "SO")
            ->where('user_id', '=', Auth::user()->id)
            ->delete();
        $deletedTempPPN = TempPpn::where('jenis', '=', "SO")
            ->where('user_id', '=', Auth::user()->id)
            ->delete();
        $countfaktur = count($pesananpenjualan->FakturSO);

        //insertt temp
        $tempDiskon = TempDiskon::create(['jenis' => 'SO', 'persen' => '0', 'rupiah' => '0', 'user_id' => Auth::user()->id]);
        $tempPPN    = TempPpn::create(['jenis' => 'SO', 'persen' => '11', 'user_id' => Auth::user()->id]);


        return view('penjualan.pesananpenjualan.edit', compact('title', 'saless', 'tglNow', 'customers', 'pesananpenjualan', 'komoditass', 'kategoris','countfaktur'));
    }

    public function caribarangdetail($id)
    {

        $idpesanan = $id;
        $products = Product::where('status', 'Aktif')->with(['categories', 'subcategories']);

        $produk = "";
        if (request()->ajax()) {
            return Datatables::of($products)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $id = $row->id;
                    return view('penjualan.pesananpenjualan._pilihBarang', [
                        'id' => $id
                    ]);
                })
                ->make(true);
        }

        // return view('penjualan.pesananpenjualan._caribarang', compact('produk'));
    }



    public function inputPesananDetail(Request $request)
    {
        // sekali input ke detail maka header nya akan mengubah data total dll nya         

        $datas = $request->all();
        $id = $request->pesanan_penjualan_id;

        $harga1 = $request->hargajual;


        $harga = str_replace(',', '.', $harga1) * 1;

        if ($request->ppn > 0) {
            $harga = $harga / (1 + $request->ppn / 100);
        }

        $ongkir1 = $request->ongkir;

        $ongkir = str_replace('.', ',', $ongkir1) * 1;

        $ppnOngkir = 0;
        if ($request->ppn_ongkir > 0) {
            $ongkir = $ongkir / (1 + $request->ppn_ongkir / 100);
        }

        $subtotal = $request->qty * $harga;
        $total_diskon = (($subtotal * ($request->diskon_persen / 100)) + $request->diskon_rp);
        $total = $subtotal - $total_diskon;


        $datas['qty_sisa'] = $datas['qty'];
        $datas['hargajual'] = $harga;
        $datas['subtotal'] = $subtotal;
        $datas['total_diskon'] = $total_diskon;
        $datas['total'] = $total;
        $datas['user_id'] = Auth::user()->id;
        $datas['ongkir'] = $ongkir;
        $datas['ppn'] = $request->ppn;
        // $datas['pesanan_penjualan_id'] = $;        



        // ambil data dulu ke pesananan penjualan buat edit total dan grand totalnya 
        $pesanan = PesananPenjualan::where('id', $id)->first();

        $datas['tanggal'] = $pesanan->tanggal;

        // save ke pesananpenjualandetail
        PesananPenjualanDetail::create($datas);

        $totaldetail  = PesananPenjualanDetail::where('pesanan_penjualan_id', $id)->sum('total');
        $totalongkir  = PesananPenjualanDetail::where('pesanan_penjualan_id', $id)->sum('ongkir');
        $totaldiskon  = PesananPenjualanDetail::where('pesanan_penjualan_id', $id)->sum('total_diskon');




        $pesanan->subtotal = $totaldetail;
        $totaldiskonheader = ($pesanan->subtotal * ($pesanan->diskon_persen / 100)) + $pesanan->diskon_rupiah;
        $pesanan->total = $pesanan->subtotal - $totaldiskonheader + $totalongkir;

        $pesanan->ongkir = $totalongkir;
        $pesanan->total_diskon_header = $totaldiskonheader;
        $pesanan->total_diskon_detail = $totaldiskon;
        $ppn_persen = $pesanan->ppn;
        $ppn = $pesanan->total * ($ppn_persen / 100);

        $grandtotal = $pesanan->total + $ppn;


        $pesanan->grandtotal = $grandtotal;
        $pesanan->update();
        // kalkulasi datanya  


    }

    public function loadPesananDetail(Request $request)
    {
        $id = $request->pesanan_id;
        $dataBarangDetail = PesananPenjualanDetail::with('products')->where('pesanan_penjualan_id', $id)->get();
        $pesanan = PesananPenjualan::with('FakturSO')->where('id', $id)->first();        
        $status = $pesanan->status_so_id;
        return view('penjualan.pesananpenjualan._tabeldetailso', compact('dataBarangDetail','status'));        
    }

    public function destroyPesananDetail(Request $request)
    {
        $id = $request->id;
        $idpesanan = $request->pesanan_id;
        // kurangi dulu total , grand total dll di pesanan penjualan        
        PesananPenjualanDetail::destroy($id);

        $total = PesananPenjualanDetail::where('pesanan_penjualan_id', $idpesanan)->sum('total');
        $ongkir = PesananPenjualanDetail::where('pesanan_penjualan_id', $idpesanan)->sum('ongkir');

        // hitung di header          
        $pesanan = PesananPenjualan::where('id', $idpesanan)->first();

        $pesanan->subtotal = $total;
        $pesanan->ongkir = $ongkir;
        //   grandtotal
        $pesanan->total = $pesanan->subtotal - $pesanan->total_diskon_header + $pesanan->ongkir;

        $ppn_persen = $pesanan->ppn;
        $ppn = $pesanan->total * ($ppn_persen / 100);

        $grandtotal = $pesanan->total + $ppn;
        $pesanan->grandtotal = $grandtotal;
        $pesanan->update();
        // kalkulasi datanya 

    }

    public function editBarangDetail(Request $request)
    {
        $item = PesananPenjualanDetail::with('pesananpenjualan')->where('id', $request->id)->first();        

        $id_product = $item->product_id;

        $product = new Product;
        $productx = Product::where('id', '=', $id_product)->first();
        $product_name = $productx->nama;
        $status = $item->pesananpenjualan->status_so_id;
        $mode = "edit";
        return view('penjualan.pesananpenjualan._setbarang', compact('product_name', 'mode', 'item', 'product','status'));
    }

    public function updateBarangDetail(Request $request)
    {

        $harga1 = $request->hargajual;
        $harga2 = str_replace('.', '', $harga1);
        $harga = str_replace(',', '.', $harga2) * 1;

        $ongkir1 = $request->ongkir;
        $ongkir2 = str_replace('.', '', $ongkir1);
        $ongkir = str_replace(',', '.', $ongkir2) * 1;

        if ($request->ppn > 0) {
            $harga = $harga / (1 + $request->ppn / 100);
        }

        $ppnOngkir = 0;
        if ($request->ppn_ongkir > 0) {
            $ongkir = $ongkir / (1 + $request->ppn_ongkir / 100);
        }

        $subtotal = $request->qty * $harga;
        $total_diskon = (($subtotal * ($request->diskon_persen / 100)) + $request->diskon_rp);

        $total = $subtotal - $total_diskon;

        $PJ = PesananPenjualanDetail::find($request->id);
        $PJ->hargajual = $harga;
        $PJ->qty = $request->qty;
        $PJ->qty_sisa = $request->qty;
        $PJ->diskon_persen = $request->diskon_persen;
        $PJ->diskon_rp = $request->diskon_rp;
        $PJ->ongkir = $ongkir;
        $PJ->keterangan = $request->keterangan;
        $PJ->subtotal = $subtotal;
        $PJ->total_diskon = $total_diskon;
        $PJ->total = $total;
        $PJ->ppn = $request->ppn;

        $PJ->update();

        // kalkulasi header
        $totaldetail = PesananPenjualanDetail::where('pesanan_penjualan_id', $request->pesanan_id)->sum('total');
        $ongkirdetail = PesananPenjualanDetail::where('pesanan_penjualan_id', $request->pesanan_id)->sum('ongkir');
        $totalDiskon = PesananPenjualanDetail::where('pesanan_penjualan_id', $request->pesanan_id)->sum('total_diskon');
        // hitung semua data baru di detail dan kalkulasi total dan ongkir
        $pesanan = PesananPenjualan::where('id', $request->pesanan_id)->first();


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
        $item = PesananPenjualan::where('id', $request->id)->first();
        // kalkulasi persen diskon        
        $id_diskon = $item->id;
        $diskon_persen = $item->diskon_persen;
        $diskon_rupiah = $item->diskon_rupiah;

        return view('penjualan.pesananpenjualan._setdiskondetail', compact('id_diskon', 'diskon_persen', 'diskon_rupiah'));
    }

    public function updateDiskonDetail(Request $request)
    {
        $pesanan = PesananPenjualan::where('id', $request->id)->first();

        $id_diskon = $request->id_diskon;
        $diskon_persen = $request->diskon_persen;
        $diskon_rupiah = $request->diskon_rupiah;


        $total_diskon = (($pesanan->subtotal * ($diskon_persen / 100)) + $diskon_rupiah);
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
        $item = PesananPenjualan::where('id', $request->id)->select('ppn', 'id')->first();
        $ppn = $item->ppn;
        $id_ppn = $item->id;

        return view('penjualan.pesananpenjualan._setppndetail', compact('id_ppn', 'ppn'));
    }

    public function updatePPNDetail(Request $request)
    {
        $id = $request->id;
        $pesanan = PesananPenjualan::where('id', $id)->first();
        $pesanan->ppn = $request->persen;

        $ppn = $pesanan->total * ($request->persen / 100);
        $grandtotal = $pesanan->total + $ppn;

        $pesanan->grandtotal = $grandtotal;
        $pesanan->update();
    }

    public function hitungSubTotalDetail(Request $request)
    {
        $subtotal = PesananPenjualanDetail::where('pesanan_penjualan_id', '=', $request->id)->sum('total');
        return number_format($subtotal, 2, ',', '.');
    }

    public function hitungDiskonDetail(Request $request)
    {
        $diskon = PesananPenjualan::where('id', $request->id)->first();

        $total_diskon = $diskon->total_diskon_header;

        if ($total_diskon == 0) {
            return $total_diskon;
        } else {
            return number_format($total_diskon, 2, ',', '.');
        }
    }

    public function hitungTotalDetail(Request $request)
    {
        $subtotal = PesananPenjualan::where('id', $request->id)->first();
        $total = $subtotal->total;

        if ($total == 0) {
            return $total;
        } else {
            return number_format($total, 2, ',', '.');
        }
    }

    public function hitungPPNDetail(Request $request)
    {
        $subtotal = PesananPenjualan::where('id', '=', $request->id)->first();
        $ppn = $subtotal->total * ($subtotal->ppn / 100);

        if ($ppn == 0) {
            return $ppn;
        } else {
            return number_format($ppn, 2, ',', '.');
        }
    }

    public function hitungOngkirDetail(Request $request)
    {
        $data = PesananPenjualan::where('id', $request->id)->first();
        $ongkir = $data->ongkir;
        if ($ongkir == 0) {
            return $ongkir;
        } else {
            return number_format($ongkir, 2, ',', '.');
        }
    }

    public function hitungGrandTotalDetail(Request $request)
    {
        $data = PesananPenjualan::where('id', $request->id)->first();
        $grandtotal = $data->grandtotal;

        if ($grandtotal == 0) {
            return $grandtotal;
        } else {
            return number_format($grandtotal, 2, ',', '.');
        }
    }

    public function print_a4($id)
    {
        $pesananpenjualan = PesananPenjualan::with(['customers', 'creator'])->findOrFail($id);
        $title = "Print Pesanan Pembelian";
        $pesananpenjualandetail = PesananPenjualanDetail::with('products.merks')
            ->where('pesanan_penjualan_id', '=', $pesananpenjualan->id)->get();
        $jmlBaris  = $pesananpenjualandetail->count();
        $perBaris = 13;
        $totalPage = ceil($jmlBaris / $perBaris);
        // dd($totalPage);

        $pesananpenjualandetail =  $pesananpenjualandetail;
        $date = date('d/m/Y');
        $data = [
            'totalPage' => $totalPage,
            'perBaris' => $perBaris,
            'date' => date('d/m/Y'),
            'pesananpenjualan' => $pesananpenjualan,
            'pesananpenjualandetail' => $pesananpenjualandetail
        ];

        // dd($data);

        $pdf = PDF::loadView('penjualan.pesananpenjualan.print_a4', $data)->setPaper('a4', 'potrait');;
        return $pdf->download($pesananpenjualan->kode . '.pdf');

        // return view('pembelian.pesananpembelian.print_a4', compact('title',  'totalPage','pesananpembelian','pesananpenjualandetail','date'
        //                                                             ,'perBaris'
        //                                                            ));
    }

    // public function hitungpph (Request $request)
    // {
    //     $tmp = TempSo::where('user_id', '=', Auth::user()->id)->get();
        


    // }
}
