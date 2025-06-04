<?php

namespace App\Http\Controllers\Pembelian;

use Carbon\Carbon;
use App\Models\Hutang;
use Barryvdh\DomPDF\Facade as PDF;
use App\Traits\CodeTrait;
use Illuminate\Http\Request;
use App\Models\TempFakturpos;
use App\Models\FakturPembelian;
use App\Models\PembayaranHutang;
use App\Models\PenerimaanBarang;
use App\Models\PesananPembelian;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\FakturPembelianDetail;
use App\Models\PenerimaanBarangDetail;
use App\Models\PesananPembelianDetail;
use App\Models\TempBiaya;
use Exception;
use Illuminate\Support\Facades\DB;

class FakturPembelianController extends Controller
{
    use CodeTrait;


    function __construct()
    {
        $this->middleware('permission:fakturpembelian-list');
        $this->middleware('permission:fakturpembelian-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:fakturpembelian-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:fakturpembelian-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = "Faktur Pembelian";
        $fakturpembelian = FakturPembelian::with(['suppliers',  'statusFB', 'po', 'pb'])->orderByDesc('id');

        if (request()->ajax()) {
            return Datatables::of($fakturpembelian)
                ->addIndexColumn()
                ->addColumn('supplier', function (FakturPembelian $pb) {
                    return $pb->suppliers->nama;
                })
                ->addColumn('kode_po', function (FakturPembelian $pb) {
                    return $pb->po->kode;
                })
                ->addColumn('no_so', function (FakturPembelian $pb) {
                    return $pb->po->no_so;
                })
                ->addColumn('kode_pb', function (FakturPembelian $pb) {
                    return $pb->pb->kode;
                })
                ->addColumn('status', function (FakturPembelian $pb) {
                    return $pb->StatusFB->nama;
                })
                ->editColumn('tanggal', function (FakturPembelian $pb) {
                    return $pb->tanggal ? with(new Carbon($pb->tanggal))->format('d-m-Y') : '';
                })
                ->addColumn('action', function ($row) {
                    //$editUrl = route('fakturpembelian.edit', ['fakturpembelian' => $row->id]);
                    $showUrl = route('fakturpembelian.show', ['fakturpembelian' => $row->id]);
                    $id = $row->id;
                    $status = $row->status_pb_id;
                    return view('pembelian.fakturpembelian._formAction', compact('id', 'status', 'showUrl'));
                })
                ->make(true);
        }


        return view('pembelian.fakturpembelian.index', compact('title'));
    }

    public function listpb()
    {
        $title = "Daftar Pesanan Pembelian";
        $penerimaanbarangs = PenerimaanBarang::with(['suppliers','statusPB','PO'])
            ->where('status_pb_id', '=', '1')
            ->orderBy('id','desc');

            
        if (request()->ajax()) {
            return Datatables::of($penerimaanbarangs)
                ->addIndexColumn()
                ->addColumn('supplier', function (PenerimaanBarang $pb) {
                    return $pb->suppliers->nama;
                })
                ->addColumn('no_so', function (PenerimaanBarang $pb) {
                    return $pb->PO->no_so;
                })
                ->addColumn('status', function (PenerimaanBarang $pb) {
                    return $pb->statusPB->nama;
                })
                ->editColumn('tanggal', function (PenerimaanBarang $pb) {
                    return $pb->tanggal ? with(new Carbon($pb->tanggal))->format('d-m-Y') : '';
                })
                ->addColumn('action', function ($row) {
                    $pilihUrl = route('fakturpembelian.create', ['penerimaanbarang' => $row->id]);
                    $id = $row->id;
                    return view('pembelian.fakturpembelian._pilihAction', compact('pilihUrl', 'id'));
                })
                ->make(true);
        }

        //dd($pesananpembelian);
        return view('pembelian.fakturpembelian.listpb', compact('title', 'penerimaanbarangs'));
    }


    public function create(PenerimaanBarang $penerimaanbarang)
    {
        $title = "Faktur Pembelian";
        $fakturpembelian = new FakturPembelian;
        $tglNow = Carbon::now()->format('d-m-Y');

        //masukkan tempDetil Faktur
        $id_pb = $penerimaanbarang->id;
        $id_po = $penerimaanbarang->pesanan_pembelian_id;
        $PBdetails = PenerimaanBarangDetail::where('penerimaan_barang_id', '=', $id_pb)->get();

           


        //start cek status exp date PB :
        $status_exp_pb = 1;
        foreach ($PBdetails as $s) {
            if ($s->status_exp == 0) {
                $status_exp_pb = 0;
            }
        }
        if ($status_exp_pb == 0) {
            return redirect()->route('fakturpembelian.listpb')->with('gagal', 'Terdapat Penerimaan Barang Yang Belum Diinputkan Exp. Date! Silahkah hubungi bagian Logistik untuk menginputnya !');
        }
        // end cek status exp date PB

        //delete temp
        $deletedTempDetil = TempFakturpos::where('created_at', '<', Carbon::today())->delete();
        $deletedTempDetil = TempFakturpos::where('user_id', '=', Auth::user()->id)->delete();
        $deletedTempBiaya = TempBiaya::where('created_at', '<', Carbon::today())->delete();
        $deletedTempBiaya = TempBiaya::where('user_id', '=', Auth::user()->id)->delete();        


         // input temp biaya
         $idtempbiaya = TempBiaya::create([
            'jenis' => 'FB',
            'rupiah' => 0,
            'user_id' => auth()->user()->id,
            'pengiriman_barang_id' => $id_pb
        ])->rupiah;


        $POdata = PesananPembelian::find($id_po);
        $ppn_po = $POdata->ppn;
        $diskon_rupiah_po = $POdata->diskon_rupiah;
        $diskon_persen_po = $POdata->diskon_persen;


        $total_det = 0;
        $ongkir_det = 0;
        foreach ($PBdetails as $pb) {
            $temp = new TempFakturpos;

            $podetail = new PesananPembelianDetail;
            $podetail = PesananPembelianDetail::find($pb->pesanan_pembelian_detail_id);
            $hargabeli = $podetail->hargabeli;
            $diskon_persen = $podetail->diskon_persen;
            $diskon_rp = $podetail->diskon_rp;
            $ongkir = $podetail->ongkir;
            $keterangan = $podetail->keterangan;

            $subtotal = $hargabeli * $pb->qty;
            $totaldiskon = (($subtotal * ($diskon_persen / 100)) + $diskon_rp);
            $total = $subtotal - $totaldiskon;
            $total_det = $total_det + $total;
            $ongkir_det = $ongkir_det + $ongkir;

            $temp->product_id = $pb->product_id;
            $temp->penerimaan_barang_id = $pb->penerimaan_barang_id;
            $temp->penerimaan_barang_detail_id = $pb->id;
            $temp->qty = $pb->qty;
            $temp->satuan = $pb->satuan;
            $temp->hargabeli = $hargabeli;
            $temp->diskon_persen = $diskon_persen;
            $temp->diskon_rp = $diskon_rp;
            $temp->subtotal = $subtotal;
            $temp->total_diskon = $totaldiskon;
            $temp->total = $total;
            $temp->ongkir = $ongkir;
            $temp->keterangan = $keterangan;
            $temp->user_id = Auth::user()->id;
            $temp->save();
        }

        $FBdetails = TempFakturpos::where('penerimaan_barang_id', '=', $id_pb)
            ->where('user_id', '=', Auth::user()->id)->get();
        //dd($FBdetails);
        $subtotal_header = $total_det;
        $ongkir_header = $ongkir_det;
        $total_diskon_header = ($subtotal_header * ($diskon_persen_po / 100)) + $diskon_rupiah_po;
        $total_header = $subtotal_header - $total_diskon_header;
        $ppn_header = round(($total_header * ($ppn_po / 100)), 2);
        $grandtotal_header = $total_header + $ppn_header + $ongkir_header;

        return view('pembelian.fakturpembelian.create', compact('title', 'FBdetails', 'tglNow', 'fakturpembelian', 'penerimaanbarang', 'PBdetails', 'subtotal_header', 'ongkir_header', 'total_diskon_header', 'total_header', 'ppn_header', 'grandtotal_header','idtempbiaya'));
    }

    public function store(Request $request, PenerimaanBarang $penerimaanbarang)
    {
        $request->validate([
            'tanggal' => ['required'],
        ]);

        $datas = $request->all();
        $tanggal = $request->tanggal;

        $biaya = TempBiaya::where('jenis', '=', "FB")
                ->where('user_id', '=', Auth::user()->id)
                ->first();
        
        DB::beginTransaction();
        try {
            $biayalainlain = $biaya->rupiah;

            if ($tanggal <> null) {
                $tanggal = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
            }
    
            $kode = $this->getKodeTransaksi("faktur_pembelians", "FB");
            $id_pb = $penerimaanbarang->id;
            $id_po = $penerimaanbarang->pesanan_pembelian_id;
    
            $tanggalPenerimaan = $penerimaanbarang->tanggal;
            $pembelian = PesananPembelian::where('id',$id_po)->first();
    
           $tanggal_top = date("Y-m-d", strtotime("+".$pembelian->top." days" . $tanggalPenerimaan));    
    
            //start cek status exp date PB :
            $PBdetails = PenerimaanBarangDetail::where('penerimaan_barang_id', '=', $id_pb)->get();
            $status_exp_pb = 1;
            foreach ($PBdetails as $s) {
                if ($s->status_exp == 0) {
                    $status_exp_pb = 0;
                }
            }
            if ($status_exp_pb == 0) {
                return redirect()->route('fakturpembelian.listpb')->with('gagal', 'Terdapat Penerimaan Barang Yang Belum Diinputkan Exp. Date! Silahkah hubungi bagian Logistik untuk menginputnya !');
            }
            // end cek status exp date PB
    
            $POdata = PesananPembelian::find($id_po);
            $ppn_po = $POdata->ppn;
            $diskon_rupiah_po = $POdata->diskon_rupiah;
            $diskon_persen_po = $POdata->diskon_persen;
    
            $FBdetails = TempFakturpos::where('penerimaan_barang_id', '=', $id_pb)
                ->where('user_id', '=', Auth::user()->id)->get();
            $subtotal_header = TempFakturpos::where('penerimaan_barang_id', '=', $id_pb)
                ->where('user_id', '=', Auth::user()->id)->sum('total');
            //$subtotal_header = $total_det;
            $ongkir_header = TempFakturpos::where('penerimaan_barang_id', '=', $id_pb)
                ->where('user_id', '=', Auth::user()->id)->sum('ongkir');
    
            $total_diskon_detail = TempFakturpos::where('penerimaan_barang_id', '=', $id_pb)
                ->where('user_id', '=', Auth::user()->id)->sum('total_diskon');
    
            $total_diskon_header = ($subtotal_header * ($diskon_persen_po / 100)) + $diskon_rupiah_po;
            $total_header = $subtotal_header - $total_diskon_header;
            $ppn_header = round(($total_header * ($ppn_po / 100)), 2);
            $grandtotal_header = $total_header + $ppn_header + $ongkir_header + $biayalainlain ;
    
            $datas['kode'] = $kode;
            $datas['tanggal'] = $tanggal;
            $datas['supplier_id'] = $penerimaanbarang->supplier_id;
            $datas['pesanan_pembelian_id'] = $id_po;
            $datas['penerimaan_barang_id'] = $id_pb;
            $datas['status_fakturpo_id'] = "1";
            $datas['keterangan'] = $request->keterangan;
            $datas['diskon_rupiah'] = $diskon_rupiah_po;
            $datas['diskon_persen'] = $diskon_persen_po;
            $datas['subtotal'] = $subtotal_header;
            $datas['total_diskon_detail'] = $total_diskon_detail;
            $datas['total_diskon_header'] = $total_diskon_header;
            $datas['total'] = $total_header;
            $datas['grandtotal'] = $grandtotal_header;
            $datas['ppn'] = $ppn_header;
            $datas['ongkir'] = $ongkir_header;
            $datas['biaya_lain'] = $biayalainlain;
            $datas['no_faktur_supplier'] = $request->no_faktur_supplier;
            $idFaktur = FakturPembelian::create($datas)->id;
    
            //$ongkir_header = $ongkir_det;
            foreach ($FBdetails as $pb) {
                $detil = new FakturPembelianDetail;
                $detil->faktur_pembelian_id = $idFaktur;
                $detil->penerimaan_barang_detail_id = $pb->penerimaan_barang_detail_id;
                $detil->product_id = $pb->product_id;
                $detil->qty = $pb->qty;
                $detil->satuan = $pb->satuan;
                $detil->hargabeli = $pb->hargabeli;
                $detil->diskon_persen = $pb->diskon_persen;
                $detil->diskon_rp = $pb->diskon_rp;
                $detil->subtotal = $pb->subtotal;
                $detil->total_diskon = $pb->total_diskon;
                $detil->total = $pb->total;
                $detil->ongkir = $pb->ongkir;
                $detil->keterangan = $pb->keterangan;
                $detil->save();
            }
            
            #################### update Status PB ##################
            $dataPB = PenerimaanBarang::find($id_pb);
            $dataPB->status_pb_id = "2";
            $dataPB->save();
            #################### END update status PB ##############
            #################### update Hutang ##################
            $hutang = new Hutang;
            $hutang->tanggal = $tanggal;
            $hutang->supplier_id = $penerimaanbarang->supplier_id;
            $hutang->pesanan_pembelian_id = $id_po;
            $hutang->penerimaan_barang_id = $id_pb;
            $hutang->faktur_pembelian_id = $idFaktur;
            $hutang->dpp = $total_header;
            $hutang->ppn = $ppn_header;
            $hutang->total = $grandtotal_header;
            $hutang->dibayar = "0";
            $hutang->status = "1"; //1 = belum lunas ; 2= lunas
            $hutang->tanggal_top = $tanggal_top;
            $hutang->save();
            #################### end update Hutang ##################
            $this->statusPesanan($id_po);
            DB::commit();

    
            return redirect()->route('fakturpembelian.index')->with('status', 'Faktur Pembelian berhasil dibuat !');
            
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->route('fakturpembelian.index')->with('gagal',$th->getMessage());
        }

       
    }

    public function delete(Request $request)
    {
        $data = FakturPembelian::where('id', '=', $request->id)->get()->first();
        $id = $request->id;

        return view('pembelian.fakturpembelian._confirmDelete', compact('id'));
    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        //validasi :
        $jmlExp = PembayaranHutang::where('faktur_pembelian_id', '=', $id)->count();
        if ($jmlExp > 0) {
            return redirect()->route('fakturpembelian.index')->with('gagal', 'Gagal Menghapus Faktur, Telah dilakukan pembayaran untuk faktur ini, silahkan hapus pembayaran terlebih dahulu !');
        }
        $fakturpembelian = FakturPembelian::find($id);
        $fakturpembelian->deleted_by = Auth::user()->id;
        $fakturpembelian->save();
        $id_pb = $fakturpembelian->penerimaan_barang_id;

        FakturPembelian::destroy($request->id);
        $detail = FakturPembelianDetail::where('faktur_pembelian_id', '=', $id)->get();
        foreach ($detail as $d) {
            FakturPembelianDetail::destroy($d->id);
        }

        //hapus hutang 
        $hapushutang = Hutang::where('faktur_pembelian_id', $id)->delete();

        //ubah status PB
        $PB = PenerimaanBarang::find($id_pb);
        $PB->status_pb_id = 1;
        $PB->save();
        $this->statusPesanan($fakturpembelian->pesanan_pembelian_id);

        return redirect()->route('fakturpembelian.index')->with('status', 'Data Faktur Pembelian Berhasil Dihapus !');
    }

    public function show(FakturPembelian $fakturpembelian)
    {
        $title = "Faktur Pembelian Detail";
        $fakturpembeliandetails = FakturPembelianDetail::with('products')
            ->where('faktur_pembelian_id', '=', $fakturpembelian->id)->get();
        return view('pembelian.fakturpembelian.show', compact('title',  'fakturpembelian', 'fakturpembeliandetails'));
    }

    public function print_a4(FakturPembelian $fakturpembelian)
    {
        $title = "Print Faktur Pembelian";
        $fakturpembeliandetail = FakturPembelianDetail::with('products')            
            ->where('faktur_pembelian_id', '=', $fakturpembelian->id)->get();
        $jmlBaris  = $fakturpembeliandetail->count();
        $perBaris = 20;
        $totalPage = ceil($jmlBaris / $perBaris);
        
        $data = [
            'totalPage' => $totalPage,
            'perBaris' => $perBaris,
            'date' => date('d/m/Y'),
            'fakturpembelian' => $fakturpembelian,
            'fakturpembeliandetail' => $fakturpembeliandetail
        ];
        $pdf = PDF::loadView('pembelian.fakturpembelian.print_a4', $data)->setPaper('a4', 'potrait');;
        return $pdf->download($fakturpembelian->kode.'.pdf');

        // return view('pembelian.fakturpembelian.print_a4', compact(
        //     'title',  
        //     'totalPage',
        //     'perBaris',
        //     'fakturpembelian',
        //     'fakturpembeliandetail'
        // ));
    }

    public function editbiaya(Request $request)
    {
        $item = TempBiaya::where('jenis', '=', "FB")
        ->where('user_id', '=', Auth::user()->id)
        ->get()->first();

        $id_biaya = $item->id;
        $biaya = $item->rupiah;        

        return view('penjualan.fakturpenjualan._setbiaya', compact('id_biaya', 'biaya'));
    }

    public function updatebiaya(Request $request)
    {        
        $id_biaya = $request->id_biaya;        
    
        $biaya = TempBiaya::find($id_biaya);                
        $biaya->rupiah = $request->biaya;
        $biaya->save();                  
    }

    public function hitungbiaya(Request $request)
    {        
        $biaya = TempBiaya::where('jenis', '=', "FB")
                ->where('user_id', '=', Auth::user()->id)
                ->first();
            
        $total_biaya = $biaya->rupiah;

        if ($total_biaya == 0) {
            return $total_biaya;
        } else {
            return number_format($total_biaya, 2, ',', '.');
        }
    }

    public function hitunggrandtotal(Request $request)
    {
        $grandtotal = $request->grandtotal;
        $biaya = TempBiaya::where('jenis', '=', "FB")
                ->where('user_id', '=', Auth::user()->id)
                ->first();
    
        $totalgrandtotal = $biaya->rupiah + $grandtotal;

        if ($totalgrandtotal == 0) {
            return $totalgrandtotal;
        } else {
            return number_format($totalgrandtotal, 2, ',', '.');
        }
    }

    public function statusPesanan ($id)
    {
        $pesananPembelian = PesananPembelian::find($id);

        if ($pesananPembelian->status_po_id == 4) {
            $fakturPembelian = FakturPembelian::where('pesanan_pembelian_id', $id)->first();

            if ($fakturPembelian) {
               $pesananPembelian->status_po_id = 5;
            } else {
               $pesananPembelian->status_po_id = 4;
            }
            
            $pesananPembelian->save();
        }elseif ($pesananPembelian->status_po_id == 5) {
            $fakturPembelian = FakturPembelian::where('pesanan_pembelian_id', $id)->first();
             if ($fakturPembelian) {
               $pesananPembelian->status_po_id = 5;
            } else {
               $pesananPembelian->status_po_id = 4;
            }
        }
    }

    public function syncronisasi ()
    {
        // cek semua pesanan pembelian yang status po nya 4
        $pesananPembelians = PesananPembelian::where('status_po_id', 4)->get();

        foreach ($pesananPembelians as $pesananPembelian) {
            // cek apakah ada faktur pembelian yang sudah dibuat untuk pesanan pembelian ini
            $fakturPembelian = FakturPembelian::where('pesanan_pembelian_id', $pesananPembelian->id)->first();

            if ($fakturPembelian) {
            // jika ada, ubah status po menjadi 5
            $pesananPembelian->status_po_id = 5;
            $pesananPembelian->save();
            }
        }

        return back();

    }


   


}
