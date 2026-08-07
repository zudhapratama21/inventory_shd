<?php

namespace App\Http\Controllers\Penjualan;

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Piutang;
use App\Traits\CodeTrait;
use Illuminate\Http\Request;
use App\Models\TempFaktursos;
use App\Models\FakturPenjualan;
use App\Models\PengirimanBarang;
use App\Models\PesananPenjualan;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use App\Models\FakturPenjualanDetail;
use App\Models\Kategoripesanan;
use App\Models\Komoditas;
use App\Models\LogNoFakturPajak;
use App\Models\NoFakturPajak;
use App\Models\NoKPA;
use App\Models\PembayaranPiutang;
use App\Models\PengirimanBarangDetail;
use App\Models\PesananPenjualanDetail;
use App\Models\Product;
use App\Models\Sales;
use App\Models\Satuan;
use App\Models\TempBiaya;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use function App\Traits\textKoma;
use function App\Traits\wordOfNumber;

class FakturPenjualanController extends Controller
{
    use CodeTrait;

    function __construct()
    {
        $this->middleware('permission:fakturpenjualan-list');
        $this->middleware('permission:fakturpenjualan-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:fakturpenjualan-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:fakturpenjualan-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = "Faktur Penjualan";
        return view('penjualan.fakturpenjualan.index', compact('title'));
    }

    public function datatable()
    {
        $fakturpenjualan = FakturPenjualan::with(['customers', 'fakturpenjualandetail'])->orderBy('id', 'desc');
        return Datatables::of($fakturpenjualan)
            ->addIndexColumn()
            ->editColumn('tanggal', function (FakturPenjualan $sj) {
                return $sj->tanggal ? with(new Carbon($sj->tanggal))->format('d-m-Y') : '';
            })
            ->addColumn('kode', function (FakturPenjualan $sj) {
                return $sj->kode;
            })
            ->editColumn('no_perusahaan', function (FakturPenjualan $sj) {
                return $sj->no_perusahaan;
            })
            ->addColumn('customer', function (FakturPenjualan $sj) {
                return $sj->customers->nama;
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('fakturpenjualan.edit', ['fakturpenjualan' => $row->id]);
                $showUrl = route('fakturpenjualan.show', ['fakturpenjualan' => $row->id]);
                $id = $row->id;
                $status = $row->status_sj_id;

                return view('penjualan.fakturpenjualan._formAction', compact('id', 'status', 'showUrl', 'editUrl'));
            })
            ->make(true);
    }

    public function datatablelistsj(Request $request)
    {
        $pengirimanbarangs = PengirimanBarang::with('customers')
            ->where(function ($q) {
                $q->where('status_sj_id', 2)
                    ->orWhere('status_sj_id', 3);
            })
            ->when($request->customer_id != 'all', function ($q) use ($request) {
                $q->where('customer_id', $request->customer_id);
            })
            ->orderBy('id', 'desc');
        return Datatables::of($pengirimanbarangs)
            ->addIndexColumn()
            ->editColumn('id', function ($row) {
                return $row->id;
            })
            ->editColumn('customer', function (PengirimanBarang $pb) {
                return $pb->customers->nama;
            })
            ->editColumn('tanggal', function (PengirimanBarang $pb) {
                return $pb->tanggal ? with(new Carbon($pb->tanggal))->format('d-m-Y') : '';
            })
            ->make(true);
    }


    public function create()
    {
        $title = "Faktur Penjualan";
        $tglNow = Carbon::now()->format('d-m-Y');
        $customer = Customer::get();
        $komoditas = Komoditas::get();
        $kategori = Kategoripesanan::get();
        $sales = Sales::get();

        $namaSession = "PB" . Auth::user()->id;


        $namaSessionBiaya = 'FB_BIAYA_' . Auth::user()->id;
        session()->forget($namaSession);
        session()->forget($namaSessionBiaya);


        return view('penjualan.fakturpenjualan.create', compact('customer', 'komoditas', 'kategori', 'sales', 'title', 'tglNow'));
    }

    public function store(Request $request, PengirimanBarang $pengirimanbarang)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'tanggal' => ['required'],
            ]);

            $datas = $request->all();
            $tanggal = $request->tanggal;

            $biaya = TempBiaya::where('jenis', '=', "FJ")
                ->where('user_id', '=', Auth::user()->id)
                ->first();

            $biayalainlain = $biaya->rupiah;

            if ($tanggal <> null) {
                $tanggal = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
            }

            $kode = $this->getKodeTransaksi("faktur_penjualans", "FJ");
            $id_sj = $pengirimanbarang->id;
            $id_so = $pengirimanbarang->pesanan_penjualan_id;
            $tanggalPengiriman = $pengirimanbarang->tanggal;

            $pesanan = PesananPenjualan::where('id', $id_so)->first();
            $tanggal_top = date("Y-m-d", strtotime("+" . $pesanan->top . " days" . $tanggal));

            $SJdetails = PengirimanBarangDetail::where('pengiriman_barang_id', '=', $id_sj)->get();

            // pajak
            $pajak = NoFakturPajak::where('id', $request->pajak_id)->first();

            // nokpa
            $kpa = NoKPA::where('id', $request->kpa_id)->first();


            //start cek status exp date SJ :
            $status_exp_sj = 1;
            foreach ($SJdetails as $s) {
                if ($s->status_exp == 0) {
                    $status_exp_sj = 0;
                }
            }

            if ($status_exp_sj == 0) {
                return redirect()->route('fakturpenjualan.listsj')->with('gagal', 'Terdapat Pengiriman Barang Yang Belum Diinputkan Exp. Date! Silahkah hubungi bagian Logistik untuk menginputnya !');
            }
            // end cek status exp date SJ

            $SOdata = PesananPenjualan::with('customers')->find($id_so);
            $ppn_so = $SOdata->ppn;
            $diskon_rupiah_so = $SOdata->diskon_rupiah;
            $diskon_persen_so = $SOdata->diskon_persen;
            $sales_id = $SOdata->sales_id;

            $FJdetails = TempFaktursos::where('pengiriman_barang_id', '=', $id_sj)
                ->where('user_id', '=', Auth::user()->id)->get();
            $subtotal_header = TempFaktursos::where('pengiriman_barang_id', '=', $id_sj)
                ->where('user_id', '=', Auth::user()->id)->sum('total');
            //$subtotal_header = $total_det;
            $ongkir_header = TempFaktursos::where('pengiriman_barang_id', '=', $id_sj)
                ->where('user_id', '=', Auth::user()->id)->sum('ongkir');

            $total_diskon_detail = TempFaktursos::where('pengiriman_barang_id', '=', $id_sj)
                ->where('user_id', '=', Auth::user()->id)->sum('total_diskon');

            $total_diskon_header = ($subtotal_header * ($diskon_persen_so / 100)) + $diskon_rupiah_so;
            $total_header = $subtotal_header - $total_diskon_header + $ongkir_header;
            $ppn_header = round(($total_header * ($ppn_so / 100)), 2);
            $grandtotal_header = $total_header + $ppn_header  + $biayalainlain;

            $datas['kode'] = $kode;
            $datas['tanggal'] = $tanggal;
            $datas['customer_id'] = $pengirimanbarang->customer_id;
            $datas['pesanan_penjualan_id'] = $id_so;
            $datas['pengiriman_barang_id'] = $id_sj;
            $datas['status_fakturso_id'] = "1";
            $datas['keterangan'] = $request->keterangan;
            $datas['diskon_rupiah'] = $diskon_rupiah_so;
            $datas['diskon_persen'] = $diskon_persen_so;
            $datas['subtotal'] = $subtotal_header;
            $datas['total_diskon_detail'] = $total_diskon_detail;
            $datas['total_diskon_header'] = $total_diskon_header;
            $datas['total'] = $total_header;
            $datas['grandtotal'] = $grandtotal_header;
            $datas['ppn'] = $ppn_header;
            $datas['ongkir'] = $ongkir_header;
            $datas['sales_id'] = $sales_id;
            $datas['no_kpa'] = $kpa->no_kpa;
            $datas['biaya_lain'] = $biayalainlain;
            $datas['pajak_id'] = $pajak->id;
            $datas['no_seri_pajak'] = $request->no_seri_pajak;
            $datas['no_pajak'] = $pajak->no_pajak;
            $idFaktur = FakturPenjualan::create($datas)->id;

            // save di log faktur pajak dan ubah faktur pajak menjadi tidak aktif
            $logpajak = LogNoFakturPajak::create([
                'nofaktur_id' => $pajak->id,
                'jenis' => 'FJ',
                'jenis_id' => $kode
            ]);

            // log no kpa
            // ubah status menjadi tidak aktif
            $pajak->update([
                'status' => 'Tidak Aktif'
            ]);

            // // ubah status no kpa menjadi tidak aktif
            $kpa->update([
                'status' => 'Tidak Aktif'
            ]);

            //$ongkir_header = $ongkir_det;
            foreach ($FJdetails as $pb) {
                $detil = new FakturPenjualanDetail;
                $detil->faktur_penjualan_id = $idFaktur;
                $detil->pengiriman_barang_detail_id = $pb->pengiriman_barang_detail_id;
                $detil->product_id = $pb->product_id;
                $detil->qty = $pb->qty;
                $detil->satuan = $pb->satuan;
                $detil->hargajual = $pb->hargajual;
                $detil->diskon_persen = $pb->diskon_persen;
                $detil->diskon_rp = $pb->diskon_rp;
                $detil->subtotal = $pb->subtotal;
                $detil->total_diskon = $pb->total_diskon;
                $detil->total = $pb->total;
                $detil->ongkir = $pb->ongkir;
                $detil->keterangan = $pb->keterangan;

                if ($SOdata->customers->kategori_id == 17 || $SOdata->customers->kategori_id == 13) {
                    if ($pb->total > 2000000) {
                        $detil->pph = 1.5;
                        $detil->total_pph = $pb->total * 1.5 / 100;
                    }
                }

                $detil->save();
            }
            #################### update Status PB ##################
            $dataPB = PengirimanBarang::find($id_sj);
            $dataPB->status_sj_id = "2";
            $dataPB->save();
            #################### END update status PB ##############

            #################### update Piutang ##################
            $piutang = new Piutang;
            $piutang->tanggal = $tanggal;
            $piutang->customer_id = $pengirimanbarang->customer_id;
            $piutang->pesanan_penjualan_id = $id_so;
            $piutang->pengiriman_barang_id = $id_sj;
            $piutang->faktur_penjualan_id = $idFaktur;
            $piutang->dpp = $total_header;
            $piutang->ppn = $ppn_header;
            $piutang->total = $grandtotal_header;
            $piutang->dibayar = "0";
            $piutang->status = "1"; //1 = belum lunas ; 2= lunas        
            $piutang->tanggal_top = $tanggal_top;
            $piutang->save();
            #################### end update Piutang ##################


            // ubah status pesanan penjualan 
            $this->statusPesanan($id_so);
            DB::commit();
            return redirect()->route('fakturpenjualan.index')->with('status', 'Faktur Penjualan berhasil dibuat !');
        } catch (Exception $th) {
            return back()->with('error', $th->getMessage());
            DB::rollBack();
        }
    }

    public function delete(Request $request)
    {
        $data = FakturPenjualan::where('id', '=', $request->id)->get()->first();
        $id = $request->id;

        return view('penjualan.fakturpenjualan._confirmDelete', compact('id'));
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $pembayaran = PembayaranPiutang::where('faktur_penjualan_id', $request->id)->first();
            if ($pembayaran) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Faktur ini sudah melakukan pembayaran , hapus dulu pembayaran piutang'
                ], 422);
            }

            $faktur = FakturPenjualan::where('id', $request->id)->with('fakturpenjualandetail')->first();
            $pb = [];

            foreach ($faktur->fakturpenjualandetail as $item) {
                // ubah qty sisa yang ada di pengiriman det
                if ($item->pengiriman_barang_detail_id) {
                    $qty = PengirimanBarangDetail::where('id', $item->pengiriman_barang_detail_id)->first();
                    $qty->qty_sisa = $qty->qty_sisa + $item->qty;
                    $qty->save();

                    // simpan kode pengiriman barang
                    $pb[] = [
                        'pengiriman_id' => $item->pengirimanbarang_id
                    ];
                }
            }

            // hapus piutang
            Piutang::where('faktur_penjualan_id', $request->id)->delete();

            // hapus detail dulu
            $faktur->fakturpenjualandetail()->delete();

            // hapus header
            $faktur->delete();

            if (count($pb) > 0) {
                foreach ($pb as $k) {
                    $fakturdet = FakturPenjualanDetail::where('pengirimanbarang_id', $k['pengiriman_id'])->first();
                    $status = 2;
                    if ($fakturdet) {
                        $status = 3;
                    }

                    PengirimanBarang::where('id', $k['pengiriman_id'])->update([
                        'status_sj_id' =>  $status
                    ]);
                }
            }

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

    public function edit(FakturPenjualan $fakturpenjualan)
    {
        $title = "Faktur Penjualan ";
        $fakturpenjualan = FakturPenjualan::where('id', $fakturpenjualan->id)->first();
        $cekPembayaran = PembayaranPiutang::where('faktur_penjualan_id', $fakturpenjualan->id)->count();
        $status = 0;
        if ($cekPembayaran > 0) {
            $status = 1;
        }

        $customer = Customer::get();
        $komoditas = Komoditas::get();
        $kategori = Kategoripesanan::get();
        $sales = Sales::get();

        return view('penjualan.fakturpenjualan.edit', compact('title', 'fakturpenjualan', 'customer', 'komoditas', 'kategori', 'sales', 'status'));
    }


    public function update(Request $request, $id)
    {

        DB::beginTransaction();
        try {
            // ambil data dari temp     
            $fj = FakturPenjualan::where('id', $id)->first();
            $tanggal = $request->tanggal;

            // ubah status menjadi aktif
            $pajak = NoFakturPajak::where('id', $fj->pajak_id)->update([
                'status' => 'Aktif'
            ]);

            // pajak
            $pajak = NoFakturPajak::where('id', $request->pajak_id)->first();

            // nokpa
            $kpa = NoKPA::where('no_kpa', $request->kpa_id)->first();

            if ($fj->no_kpa !== $kpa->no_kpa) {
                NoKPA::where('no_kpa', $fj->no_kpa)->update([
                    'status' => 'Aktif'
                ]);
            }

            $biaya = TempBiaya::where('jenis', '=', "FJ")
                ->where('user_id', '=', Auth::user()->id)
                ->first();


            if ($biaya) {
                $grandtotal = $fj->grandtotal + $biaya->rupiah - $fj->biaya_lain;
                $rupiah = $biaya->rupiah;

                $biaya->delete();
            } else {
                $grandtotal = $fj->grandtotal;
                $rupiah = $fj->biaya_lain;
            }

            if ($tanggal <> null) {
                $tanggal = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
            }

            $fj->update([
                'grandtotal' => $grandtotal,
                'no_kpa' => $kpa->no_kpa,
                'pajak_id' => $request->pajak_id,
                'biaya_lain'  => $rupiah,
                'no_seri_pajak' => $request->no_seri_pajak,
                'no_pajak' => $pajak->no_pajak,
                'tanggal' => $tanggal,
                'keterangan' => $request->keterangan
            ]);

            // ubah status menjadi aktif
            $pajak = NoFakturPajak::where('id', $request->pajak_id)->update([
                'status' => 'Tidak Aktif'
            ]);

            // ubah data yang ada di faktur penjualan    
            DB::commit();

            return redirect()->route('fakturpenjualan.index')->with('status', 'Faktur Penjualan berhasil diubah!');
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->route('fakturpenjualan.index')->with('error', $th->getMessage());
        }
    }

    public function show(FakturPenjualan $fakturpenjualan)
    {
        $title = "Faktur penjualan Detail";
        $fakturpenjualan = FakturPenjualan::where('id', $fakturpenjualan->id)->with(['creator'])->first();
        $fakturpenjualandetails = FakturPenjualanDetail::with('products')
            ->where('faktur_penjualan_id', '=', $fakturpenjualan->id)->get();

        $bank = Bank::get();
        return view('penjualan.fakturpenjualan.show', compact('title',  'fakturpenjualan', 'fakturpenjualandetails', 'bank'));
    }

    public function print_a4(Request $request, FakturPenjualan $fakturpenjualan)
    {
        $fakturpenjualandetails = FakturPenjualanDetail::with('pengirimanbarangdetail.stokexpdetail.stockExp', 'products.merks')
            ->where('faktur_penjualan_id', '=', $fakturpenjualan->id)->get();


        $bank = Bank::where('id', $request->bank)->first();
        $jmlBaris  = $fakturpenjualandetails->count();
        $perBaris = 13;
        $totalPage = ceil($jmlBaris / $perBaris);
        $tertulis = wordOfNumber(round($fakturpenjualan->grandtotal));
        $data = [
            'totalPage' => $totalPage,
            'perBaris' => $perBaris,
            'date' => date('d/m/Y'),
            'fakturpenjualan' => $fakturpenjualan,
            'fakturpenjualandetails' => $fakturpenjualandetails,
            'tertulis' => $tertulis,
            'bank' => $bank
        ];

        $pdf = PDF::loadView('penjualan.fakturpenjualan.print_a4', $data)->setPaper('a4', 'potrait');;
        return $pdf->download($fakturpenjualan->no_perusahaan . '.pdf');
    }

    public function print_a5(Request $request, FakturPenjualan $fakturpenjualan)
    {
        $fakturpenjualandetails = FakturPenjualanDetail::with('pengirimanbarangdetail.stokexpdetail.stockExp', 'products.merks')
            ->where('faktur_penjualan_id', '=', $fakturpenjualan->id)->get();


        $bank = Bank::where('id', $request->bank)->first();
        $jmlBaris  = $fakturpenjualandetails->count();
        $perBaris = 13;
        $totalPage = ceil($jmlBaris / $perBaris);
        $tertulis = wordOfNumber(round($fakturpenjualan->grandtotal));
        $data = [
            'totalPage' => $totalPage,
            'perBaris' => $perBaris,
            'date' => date('d/m/Y'),
            'fakturpenjualan' => $fakturpenjualan,
            'fakturpenjualandetails' => $fakturpenjualandetails,
            'tertulis' => $tertulis,
            'bank' => $bank

        ];

        $pdf = PDF::loadView('penjualan.fakturpenjualan.print_a4_koma', $data)->setPaper('a5', 'landscape');
        return $pdf->download($fakturpenjualan->no_perusahaan . '.pdf');
    }

    public function editCN(FakturPenjualan $fakturpenjualan)
    {
        $title = "Faktur penjualan Detail";
        $fakturpenjualan = FakturPenjualan::where('id', $fakturpenjualan->id)->with('nopajak')->first();
        $fakturpenjualandetails = FakturPenjualanDetail::with('products')
            ->where('faktur_penjualan_id', '=', $fakturpenjualan->id)->get();


        return view('penjualan.fakturpenjualan.showCN', compact('title',  'fakturpenjualan', 'fakturpenjualandetails'));
    }

    public function createCN(Request $request, FakturPenjualanDetail $fakturpenjualandetail)
    {

        $data = $request->except('_token');

        $harga1 = $request->cn_persen;
        $harga = str_replace(',', '.', $harga1) * 1;


        $subtotal = $fakturpenjualandetail->total;
        $data['cn_rupiah'] = $subtotal * $harga / 100;
        $data['cn_total'] = $data['cn_rupiah'];

        $fakturpenjualandetail->update([
            'cn_persen' => $harga,
            'cn_rupiah' => $data['cn_rupiah'],
            'cn_total' => $data['cn_total']
        ]);

        return back();
    }

    public function updateCN(Request $request, FakturPenjualanDetail $fakturpenjualandetail)
    {

        $harga1 = $request->cn_persen;
        $harga = str_replace(',', '.', $harga1) * 1;

        $data = $request->except('_token');
        $subtotal = $fakturpenjualandetail->subtotal;
        $data['cn_rupiah'] = $subtotal * $harga / 100;


        $data['cn_total'] = $data['cn_rupiah'];

        $fakturpenjualandetail->update([
            'cn_persen' => $harga,
            'cn_rupiah' => $data['cn_rupiah'],
            'cn_total' => $data['cn_total']
        ]);

        $totalCN = FakturPenjualanDetail::where('faktur_penjualan_id', $fakturpenjualandetail->faktur_penjualan_id)->sum('cn_total');


        FakturPenjualan::where('id', $fakturpenjualandetail->faktur_penjualan_id)->update([
            'total_cn' => $totalCN
        ]);

        return back();
    }

    public function editbiaya(Request $request)
    {
        $item = TempBiaya::where('jenis', '=', "FJ")
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
        $biaya = TempBiaya::where('jenis', '=', "FJ")
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
        $biaya = TempBiaya::where('jenis', '=', "FJ")
            ->where('user_id', '=', Auth::user()->id)
            ->first();

        $totalgrandtotal = $biaya->rupiah + $grandtotal;

        if ($totalgrandtotal == 0) {
            return $totalgrandtotal;
        } else {
            return number_format($totalgrandtotal, 2, ',', '.');
        }
    }

    public function showdata($id)
    {
        $fakturpenjualan = FakturPenjualan::where('pajak_id', $id)->with('nopajak')->first();
        $title = "Faktur penjualan Detail";
        // $fakturpenjualan = FakturPenjualan::where('id',$fakturpenjualan->id)->with('nopajak')->first();
        // dd($fakturpenjualan);
        $fakturpenjualandetails = FakturPenjualanDetail::with('products')
            ->where('faktur_penjualan_id', '=', $fakturpenjualan->id)->get();


        return view('penjualan.fakturpenjualan.show', compact('title',  'fakturpenjualan', 'fakturpenjualandetails'));
    }


    public function kwitansi(Request $request, FakturPenjualan $fakturpenjualan)
    {
        $customer = Customer::where('id', $fakturpenjualan->customer_id)->first();
        $text = wordOfNumber(round($fakturpenjualan->grandtotal));
        $responseText = $text . ' Rupiah';


        $pdf = PDF::loadView('penjualan.fakturpenjualan.kwitansi', [
            'faktur' => $fakturpenjualan->no_perusahaan,
            'no_urut' => str_pad($fakturpenjualan->no_urut, 5, '0', STR_PAD_LEFT),
            'text' => $responseText,
            'grandtotal' => $fakturpenjualan->grandtotal,
            'customer' => $customer->nama,
            'tanggal' => Carbon::parse($fakturpenjualan->tanggal)->format('d F Y'),
            'keterangan' => $request->keterangan
        ])->setPaper('a4', 'landscape');


        return $pdf->download('KWIT' . '-' . $fakturpenjualan->no_perusahaan . '.pdf');
    }


    public function tandaTerima(Request $request, $id)
    {
        $img = $request->file('foto_bukti');
        $nameFile = null;
        $tanggal = Carbon::parse($request->tanggal_terima)->format('Y-m-d');

        if ($img) {
            $dataFoto = $img->getClientOriginalName();
            $name = time() . '_' . $dataFoto;

            $img->move(public_path('bukti_tandaterima'), $name);

            $nameFile = $name;
        }

        $tandaterima = FakturPenjualan::where('id', $id)->update([
            'tanggal_diterima' => $tanggal,
            'status_diterima' => $request->status_diterima,
            'foto_bukti' => $nameFile,
            'no_resi' => $request->no_resi
        ]);

        return back();
    }

    public function editTandaTerima(Request $request, $id)
    {
        $img = $request->file('foto_bukti');

        $tanggal = Carbon::parse($request->tanggal_terima)->format('Y-m-d');

        $fakturpenjualan = FakturPenjualan::findOrFail($id);

        $nameFile = $fakturpenjualan->foto_bukti;

        if ($img) {

            // Hapus foto lama
            if ($fakturpenjualan->foto_bukti) {

                $oldFile = public_path('bukti_tandaterima/' . $fakturpenjualan->foto_bukti);

                if (File::exists($oldFile)) {
                    File::delete($oldFile);
                }
            }

            // Upload foto baru
            $nameFile = time() . '_' . $img->getClientOriginalName();

            $img->move(
                public_path('bukti_tandaterima'),
                $nameFile
            );
        }

        $fakturpenjualan->update([
            'tanggal_diterima' => $tanggal,
            'status_diterima' => $request->status_diterima,
            'foto_bukti' => $nameFile,
            'no_resi' => $request->no_resi,
        ]);

        return back();
    }

    public function statusPesanan($id)
    {
        $pesananPenjualan = PesananPenjualan::find($id);

        if ($pesananPenjualan->status_so_id == 4) {
            $fakturPenjualan = FakturPenjualan::where('pesanan_penjualan_id', $id)->first();

            if ($fakturPenjualan) {
                $pesananPenjualan->status_so_id = 5;
            } else {
                $pesananPenjualan->status_so_id = 4;
            }

            $pesananPenjualan->save();
        } elseif ($pesananPenjualan->status_so_id == 5) {
            $fakturPenjualan = FakturPenjualan::where('pesanan_penjualan_id', $id)->first();

            if ($fakturPenjualan) {
                $pesananPenjualan->status_so_id = 5;
            } else {
                $pesananPenjualan->status_so_id = 4;
            }
            $pesananPenjualan->save();
        }
    }

    public function datatabledetail(Request $request)
    {
        $fakturpenjualandetails = FakturPenjualanDetail::with('products')->where('faktur_penjualan_id', '=', $request->id)->get();

        return Datatables::of($fakturpenjualandetails)
            ->addIndexColumn()
            ->editColumn('hargajual', function (FakturPenjualanDetail $sj) {
                return number_format($sj->hargajual, 0, ',', '.');
            })
            ->editColumn('subtotal', function (FakturPenjualanDetail $sj) {
                return number_format($sj->subtotal, 0, ',', '.');
            })
            ->editColumn('total', function (FakturPenjualanDetail $sj) {
                return number_format($sj->total, 0, ',', '.');
            })
            ->editColumn('cn', function (FakturPenjualanDetail $sj) {
                return number_format($sj->cn_persen, 0, ',', '.');
            })
            ->editColumn('cn_total', function (FakturPenjualanDetail $sj) {
                return number_format($sj->cn_total, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                return $id = $row->id;
            })
            ->make(true);
    }


    public function formcn(Request $request)
    {
        $id = $request->id;
        $fakturpenjualandetail = FakturPenjualanDetail::where('id', $id)->first();
        return view('penjualan.fakturpenjualan.partial.formcn', compact('id', 'fakturpenjualandetail'));
    }

    public function inputcn(Request $request)
    {
        $id = $request->id;
        $fakturpenjualandetail = FakturPenjualanDetail::where('id', $id)->first();
        $totalcn = $fakturpenjualandetail->total * $request->cn / 100;
        $fakturpenjualandetail->update([
            'cn_persen' => $request->cn,
            'cn_rupiah' => $totalcn,
            'cn_total' => $totalcn
        ]);

        FakturPenjualan::where('id', $fakturpenjualandetail->faktur_penjualan_id)->update([
            'total_cn' => $totalcn
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ]);
    }

    public function setcn(Request $request)
    {
        $fakturPenjualan = FakturPenjualan::where('id', $request->id)->update([
            'total_cn' => 0
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ]);
    }


    public function simpansj(Request $request)
    {
        $id = $request->ids;
        $pengirimanBarang = PengirimanBarang::whereIn('id', $id)->with('PengirimanBarangDetails.products')->get();


        $namaSession = 'FB' . Auth::user()->id;
        $items = session()->get($namaSession, []);
        foreach ($pengirimanBarang as $pb) {
            foreach ($pb->PengirimanBarangDetails as $detail) {
                if ($detail->qty_sisa > 0) {
                    $items[$detail->product_id] = [
                        'pengiriman_barang_detail_id' => $detail->id,
                        'pengiriman_barang_id' => $detail->pengiriman_barang_id,
                        'product_id' => $detail->product_id,
                        'nama_product' => $detail->products->nama,
                        'qty' => $detail->qty,
                        'satuan' => $detail->satuan,
                        'beda_satuan' => $detail->beda_satuan,
                        'satuan_konversi' => $detail->satuan_konversi,
                        'qty_konversi' => $detail->qty_konversi,
                        'hargajual' => 0,
                        'diskon_persen' => 0,
                        'diskon_rp' => 0,
                        'subtotal' => 0,
                        'total_diskon' => 0,
                        'total' => 0,
                        'ongkir' => 0,
                        'keterangan' => '',
                        'ppn' => 0,
                    ];
                }
            }
        }
        session()->put($namaSession, $items);

        return response()->json([
            'status' => 'success',
            'data'   => $items
        ]);
    }

    public function loadsj()
    {
        $namaSession = 'FB' . Auth::user()->id;
        $items = session()->get($namaSession, []);

        $data = [];

        if (!empty($items)) {
            foreach ($items as $value) {
                if ($value['beda_satuan'] == 'on') {
                    $satuan = $value['satuan_konversi'];
                } else {
                    $satuan = $value['satuan'];
                }

                $data[] = [
                    'id'              => $value['product_id'],
                    'nama'            => $value['nama_product'],
                    'ppn'            => $value['ppn'] . '%',
                    'qty'             => $value['qty'] ?? 0,
                    'satuan'          => $satuan,
                    'hargajual'       => number_format($value['hargajual'] / (1 + ($value['ppn'] / 100)), 2, ',', '.'),
                    'diskon_persen'   => number_format($value['diskon_persen'], 2, ',', '.'),
                    'diskon_rp'       => number_format($value['diskon_rp'], 2, ',', '.'),
                    'subtotal'       => number_format($value['subtotal'], 2, ',', '.'),
                    'total_diskon'   => number_format($value['total_diskon'], 2, ',', '.'),
                    'total'          => number_format($value['total'], 2, ',', '.'),
                ];
            }
        }

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($pb) {
                return '
                        <button type="button" class="btn btn-icon btn-outline-primary btn-sm" onclick="editsj(' . $pb['id'] . ')"><i class="flaticon2-pen"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-danger btn-sm" onclick="hapussj(' . $pb['id'] . ')"><i class="flaticon-delete"></i></button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function editsj(Request $request)
    {
        $namaSession = 'FB' . Auth::user()->id;
        $items = session()->get($namaSession, []);

        $item = $items[$request->id] ?? null;

        return view('penjualan.fakturpenjualan.modal.editsj', compact('item'));
    }

    public function updatesj(Request $request)
    {
        DB::beginTransaction();
        try {
            $namaSession = 'FB' . Auth::user()->id;
            $items = session()->get($namaSession, []);

            if (isset($items[$request->product_id])) {
                $subtotal = $request->qty * ($request->hargajual / (1 + ($request->ppn / 100)));
                $total_diskon = ($subtotal * ($request->diskon_persen / 100)) + $request->diskon_rp;
                $total = $subtotal - $total_diskon;
                $items[$request->product_id]['qty'] = $request->qty;
                $items[$request->product_id]['hargajual'] = $request->hargajual;
                $items[$request->product_id]['diskon_persen'] = $request->diskon_persen;
                $items[$request->product_id]['diskon_rp'] = $request->diskon_rp;
                $items[$request->product_id]['subtotal'] = $subtotal;
                $items[$request->product_id]['total_diskon'] = $total_diskon;
                $items[$request->product_id]['total'] = $total;
                $items[$request->product_id]['ppn'] = $request->ppn;
                $items[$request->product_id]['satuan'] = $request->satuan;
                $items[$request->product_id]['keterangan'] = $request->keterangan;
            }

            session()->put($namaSession, $items);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'data'   => $items
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function hapussj(Request $request)
    {
        $namaSession = 'FB' . Auth::user()->id;
        $items = session()->get($namaSession, []);

        unset($items[$request->id]);
        session()->put($namaSession, $items);

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function caribarang()
    {
        $product = Product::with('merks')->where('status', 'Aktif')->get();
        return Datatables::of($product)
            ->addIndexColumn()
            ->editColumn('nama', function ($pb) {
                return $pb->nama;
            })
            ->editColumn('kode', function ($pb) {
                return $pb->kode;
            })
            ->editColumn('merk', function ($pb) {
                return $pb->merks ? $pb->merks->nama : '-';
            })
            ->editColumn('satuan', function ($pb) {
                return $pb->satuan;
            })
            ->editColumn('stok', function ($pb) {
                return $pb->stok;
            })
            ->addColumn('action', function ($pb) {
                return '<button type="button" class="btn btn-sm btn-outline-primary" onclick="pilihbarang(' . $pb->id . ')">
                    Pilih
                </button>';
            })
            ->make(true);
    }

    public function setbarang(Request $request)
    {
        $product = Product::where('id', $request->id)->select('nama', 'satuan', 'id', 'hargajual')->first();
        $satuan = Satuan::get();
        return view('penjualan.fakturpenjualan.modal.setbarang', compact('product', 'satuan'));
    }

    public function simpanbarang(Request $request)
    {

        $namaSession = 'FB' . Auth::user()->id;
        $items = session()->get($namaSession, []);

        $subtotal = $request->qty * ($request->hargajual / (1 + ($request->ppn / 100)));
        $total_diskon = ($subtotal * ($request->diskon_persen / 100)) + $request->diskon_rp;
        $total = $subtotal - $total_diskon;

        $items[$request->product_id] = [
            'pengiriman_barang_detail_id' => null,
            'pengiriman_barang_id' => null,
            'product_id' => $request->product_id,
            'nama_product' => $request->nama,
            'qty' => $request->qty,
            'satuan' => $request->satuan,
            'beda_satuan' => null,
            'satuan_konversi' => null,
            'qty_konversi' => null,
            'hargajual' => $request->hargajual,
            'diskon_persen' => $request->diskon_persen,
            'diskon_rp' => $request->diskon_rp,
            'subtotal' => $subtotal,
            'total_diskon' => $total_diskon,
            'total' => $total,
            'ongkir' => 0,
            'keterangan' => $request->keterangan,
            'ppn' => $request->ppn,
        ];
        session()->put($namaSession, $items);

        return response()->json([
            'status' => 'success',
            'data'   => $items
        ]);
    }


    public function hitungtotal()
    {
        $namaSession = 'FB' . Auth::user()->id;
        $items = session()->get($namaSession, []);
        $grandtotal = 0;
        $subtotal = 0;
        $total_diskon = 0;
        $ppn = 0;
        $total = 0;

        $namaSessionBiaya = 'FB_BIAYA_' . Auth::user()->id;
        $biaya = session()->get($namaSessionBiaya, []);

        foreach ($items as $item) {
            $subtotal += $item['subtotal'];
            $total_diskon += $item['total_diskon'];
        }

        $total = $subtotal - $total_diskon + ($biaya['ongkir'] ?? 0);
        $ppn = ($total) * 11 / 100;
        $grandtotal = ($total) + $ppn +  ($biaya['materai'] ?? 0);

        return response()->json([
            'status' => 'success',
            'data' => [
                'subtotal' => $subtotal,
                'total_diskon' => $total_diskon,
                'ppn' => $ppn,
                'grandtotal' => $grandtotal,
                'total' => $total,
                'materai' => $biaya['materai'] ?? 0,
                'ongkir' => $biaya['ongkir'] ?? 0,
            ]
        ]);
    }


    public function simpanbiaya(Request $request)
    {
        $namaSession = 'FB_BIAYA_' . Auth::user()->id;
        session()->get($namaSession, []);

        $ongkir = $request->ongkir ?? 0;
        $materai = $request->materai ?? 0;

        session()->put($namaSession, [
            'ongkir' =>  $ongkir,
            'materai' =>  $materai,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'ongkir' => $ongkir,
                'materai' => $materai,
            ]
        ]);
    }

    public function storefj(Request $request)
    {

        DB::beginTransaction();

        try {
            // dd($request->all());
            $namaSessionBiaya = 'FB_BIAYA_' . Auth::user()->id;
            $biaya = session()->get($namaSessionBiaya, []);

            $namaSession = 'FB' . Auth::user()->id;
            $sessionItem = session()->get($namaSession, []);


            $no_urut = null;
            if ($request->no_urut) {
                $no_urut = $request->no_urut;
            }
            $noPerusahaan = $this->kodePerusahaan($no_urut, $request->tanggal);
            $subtotal = 0;
            $total_diskon = 0;
            $total = 0;

            // simpan dulu data faktur penjualan ke db faktur penjualan
            $fakturpenjualan = FakturPenjualan::create([
                'kode' => $this->getKodeTransaksi("faktur_penjualans", "FJ"),
                'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
                'tanggal_jatuh_tempo' => Carbon::parse($request->tanggal_jatuh_tempo)->format('Y-m-d'),
                'customer_id' => $request->customer_id,
                'no_urut' => $noPerusahaan['no_urut'],
                'no_perusahaan' => $noPerusahaan['kode'],
                'sales_id' => $request->sales_id,
                'komoditas_id' => $request->komoditas_id,
                'kategoripesanan_id' => $request->kategori_id,
                'no_sp_customer' => $request->no_sp_customer,
                'tanggal_sp_customer' => $request->tanggal_sp_customer,
                'keterangan' => $request->keterangan,
            ]);
            // looping data yang ada di session item dan simpan ke faktur penjualan detail
            foreach ($sessionItem as $item) {

                $pbid[$item['product_id']] = [
                    'pengirimanbarang_id' => $item['pengiriman_barang_id']
                ];

                FakturPenjualanDetail::create([
                    'faktur_penjualan_id' => $fakturpenjualan->id,
                    'product_id' => $item['product_id'],
                    'pengirimanbarang_id' => $item['pengiriman_barang_id'],
                    'pengiriman_barang_detail_id' => $item['pengiriman_barang_detail_id'],
                    'qty' => $item['qty'],
                    'ppn'  => $item['ppn'],
                    'hargajual' => $item['hargajual'],
                    'diskon_persen' => $item['diskon_persen'],
                    'diskon_rp'  => $item['diskon_rp'],
                    'subtotal' => $item['subtotal'],
                    'total_diskon' => $item['total_diskon'],
                    'total' => $item['total'],
                    'keterangan' => $item['keterangan'],
                    'beda_satuan' => $item['beda_satuan'],
                    'satuan' => $item['satuan'],
                    'satuan_konversi' => $item['satuan_konversi'],
                    'qty_konversi' => $item['qty_konversi'],
                ]);
                $subtotal += $item['subtotal'];
                $total_diskon += $item['total_diskon'];
                $total += $item['total'];

                // ubah qty_sisa yang ada di pengiriman barang 
                if ($item['pengiriman_barang_detail_id']) {
                    $pbdetail = PengirimanBarangDetail::where('id', $item['pengiriman_barang_detail_id'])->first();
                    $stokpb = $pbdetail->qty_sisa;
                    $pbdetail->update([
                        'qty_sisa' => $stokpb - $item['qty']
                    ]);
                }

                Product::where('id', $item['product_id'])->update([
                    'hargajual' => $item['hargajual'],
                    'diskon_persen' => $item['diskon_persen'],
                    'diskon_rp' => $item['diskon_rp'],
                ]);
            }



            // setelah itu hitung grandtotal dan update ke faktur penjualan
            $total = $subtotal - $total_diskon + ($biaya['ongkir'] ?? 0);
            $ppn = ($total) * 11 / 100;
            $grandtotal = ($total) + $ppn +  ($biaya['materai'] ?? 0);

            FakturPenjualan::where('id', $fakturpenjualan->id)->update([
                'subtotal' => $subtotal,
                'total_diskon' => $total_diskon,
                'total' => $total,
                'ongkir' => $biaya['ongkir'] ?? 0,
                'ppn' => $ppn,
                'grandtotal' => $grandtotal,
                'materai' => $biaya['materai'] ?? 0,
            ]);

            // setelah itu cek juga yang ada di surat jalan apakah semuanya sudah terfaktur setiap surat jalan , kalau sudah update surat jalannya menjadi sudah terfaktur / terfaktur sebagian 
            foreach ($pbid as $pb) {
                if ($pb['pengirimanbarang_id']) {
                    $pengirimanbarang = PengirimanBarangDetail::where('pengiriman_barang_id', $pb['pengirimanbarang_id'])->where('qty_sisa', '>=', 1)->first();

                    $status_sj = 3;
                    if (!$pengirimanbarang) {
                        $status_sj = 4;
                    }

                    PengirimanBarang::where('id', $pb['pengirimanbarang_id'])->update([
                        'status_sj_id' => $status_sj
                    ]);
                }
            }

            // setelah itu simpan ke dalam piutang 
            Piutang::create([
                'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
                'customer_id' => $request->customer_id,
                'faktur_penjualan_id' => $fakturpenjualan->id,
                'dpp' => $total,
                'ppn' => $ppn,
                'total' => $grandtotal,
                'dibayar' => 0,
                'status' => 1,
                'tanggal_top' => Carbon::parse($request->tanggal_jatuh_tempo)->format('Y-m-d')
            ]);

            session()->forget($namaSessionBiaya);
            session()->forget($namaSession);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'Message'   =>  'Data Berhasil Di Tambahkan'
            ]);
            // setelah itu hapus session
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updatefj(Request $request)
    {
        DB::beginTransaction();

        try {
            $fakturpenjualan = FakturPenjualan::find($request->id);
            $no_urut = $fakturpenjualan->no_urut;
            if ($request->no_urut) {
                $no_urut = $request->no_urut;
            }

            $noPerusahaan = $this->kodePerusahaan($no_urut, $request->tanggal);

            $fakturpenjualan->update([
                'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
                'tanggal_jatuh_tempo' => Carbon::parse($request->tanggal_jatuh_tempo)->format('Y-m-d'),
                'no_urut' => $noPerusahaan['no_urut'],
                'no_perusahaan' => $noPerusahaan['kode'],
                'sales_id' => $request->sales_id,
                'komoditas_id' => $request->komoditas_id,
                'kategoripesanan_id' => $request->kategori_id,
                'no_sp_customer' => $request->no_sp_customer,
                'tanggal_sp_customer' => $request->tanggal_sp_customer,
                'keterangan' => $request->keterangan,
            ]);

            Piutang::where('faktur_penjualan_id', $request->id)->update([
                'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
                'tanggal_top' => Carbon::parse($request->tanggal_jatuh_tempo)->format('Y-m-d')
            ]);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'Message'   =>  'Data Berhasil Di Update'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function kodePerusahaan($urut, $tanggal)
    {
        DB::beginTransaction();

        $tahun = Carbon::parse($tanggal)->format('y');
        $max = FakturPenjualan::whereYear('tanggal', $tahun)->lockForUpdate()->max('no_urut');

        $next = $max ? $max + 1 : 1;

        if ($urut) {
            $next = $urut;
        }



        $no_perusahaan =  $tahun . '.' . str_pad($next, 5, '0', STR_PAD_LEFT);

        $data = [
            'no_urut' => $next,
            'kode' => $no_perusahaan
        ];


        DB::commit();
        return $data;
    }


    public function ceknomor(Request $request)
    {
        $tanggal = Carbon::parse($request->tanggal)->format('Y');
        if (!$request->no_urut) {
            return response()->json([
                'status' => 'error',
                'message' => 'No urut belum di isi !'
            ], 422);
        }

        if (!$request->tanggal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tanggal belum di isi !'
            ], 422);
        }

        $ceksurat = FakturPenjualan::whereYear('tanggal', $tanggal)
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


    public function loadfj(Request $request)
    {
        $fakturpenjualandet = FakturPenjualanDetail::with(['products:id,nama'])->where('faktur_penjualan_id', '=', $request->id)->get();
        return Datatables::of($fakturpenjualandet)
            ->editColumn('nama', function ($sj) {
                return $sj->products->nama;
            })
            ->editColumn('satuan', function ($sj) {
                if ($sj->beda_satuan == 'on') {
                    return $sj->satuan_konversi;
                } else {
                    return $sj->satuan;
                }
            })
            ->editColumn('hargajual', function ($sj) {
                return number_format($sj->hargajual, 2, ',', '.');
            })
            ->editColumn('diskon_persen', function ($sj) {
                return number_format($sj->diskon_persen, 2, ',', '.');
            })
            ->editColumn('diskon_rp', function ($sj) {
                return number_format($sj->diskon_rp, 2, ',', '.');
            })
            ->editColumn('subtotal', function ($sj) {
                return number_format($sj->subtotal, 2, ',', '.');
            })
            ->editColumn('total_diskon', function ($sj) {
                return number_format($sj->total_diskon, 2, ',', '.');
            })
            ->editColumn('total', function ($sj) {
                return number_format($sj->total, 2, ',', '.');
            })
            ->addColumn('action', function ($row) {
                $pembayaran = PembayaranPiutang::where('faktur_penjualan_id', $row->faktur_penjualan_id)->first();
                if ($pembayaran) {
                    return;
                } else {
                    return '
                        <button type="button" class="btn btn-icon btn-outline-primary btn-sm" onclick="editsj(' . $row->id . ')"><i class="flaticon2-pen"></i></button>
                        <button type="button" class="btn btn-icon btn-outline-danger btn-sm" onclick="hapussj(' . $row->id . ')"><i class="flaticon-delete"></i></button>
                ';
                }
            })
            ->make(true);
    }

    public function editDetail(Request $request)
    {
        $fakturpenjualandet = FakturPenjualanDetail::where('id', $request->id)->with('products')->first();
        $satuan = Satuan::get();
        return view('penjualan.fakturpenjualan.modal.editdetail', compact('fakturpenjualandet', 'satuan'));
    }

    public function updatedetail(Request $request)
    {
        DB::beginTransaction();
        try {
            $subtotal = $request->qty * ($request->hargajual / (1 + ($request->ppn / 100)));
            $total_diskon = ($subtotal * ($request->diskon_persen / 100)) + $request->diskon_rp;
            $total = $subtotal - $total_diskon;

            FakturPenjualanDetail::where('id', $request->id)->update([
                'qty' => $request->qty,
                'satuan' => $request->satuan,
                'hargajual' => $request->hargajual,
                'diskon_persen' => $request->diskon_persen,
                'diskon_rp' => $request->diskon_rp,
                'subtotal' => $subtotal,
                'total_diskon' => $total_diskon,
                'total' => $total,
                'keterangan' => $request->keterangan,
                'ppn' => $request->ppn,
            ]);

            $this->updateGrandtotal($request->faktur_id);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan'
            ]);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function hapusdetail(Request $request)
    {
        DB::beginTransaction();
        try {
            $fpdet = FakturPenjualanDetail::where('id', $request->id)->first();
            if ($fpdet->pengiriman_barang_detail_id !== null) {
                $pbdetail = PengirimanBarangDetail::where('id', $fpdet->pengiriman_barang_detail_id)->first();
                $stokpb = $pbdetail->qty_sisa;
                $pbdetail->update([
                    'qty_sisa' => $stokpb + $fpdet->qty
                ]);

                $pb = PengirimanBarang::where('id', $fpdet->pengirimanbarang_id)->first();
                $pb->update([
                    'status_sj_id' => 3
                ]);
            }

            $fpdet->delete();
            $this->updateGrandtotal($fpdet->faktur_penjualan_id);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function tambahdetail(Request $request)
    {
        DB::beginTransaction();
        try {
            $subtotal = $request->qty * ($request->hargajual / (1 + ($request->ppn / 100)));
            $total_diskon = ($subtotal * ($request->diskon_persen / 100)) + $request->diskon_rp;
            $total = $subtotal - $total_diskon;

            FakturPenjualanDetail::create([
                'faktur_penjualan_id' => $request->faktur_id,
                'pengiriman_barang_detail_id' => null,
                'pengiriman_barang_id' => null,
                'product_id' => $request->product_id,
                'nama_product' => $request->nama,
                'qty' => $request->qty,
                'satuan' => $request->satuan,
                'beda_satuan' => null,
                'satuan_konversi' => null,
                'qty_konversi' => null,
                'hargajual' => $request->hargajual,
                'diskon_persen' => $request->diskon_persen,
                'diskon_rp' => $request->diskon_rp,
                'subtotal' => $subtotal,
                'total_diskon' => $total_diskon,
                'total' => $total,
                'ongkir' => 0,
                'keterangan' => $request->keterangan,
                'ppn' => $request->ppn,
            ]);

            $this->updateGrandtotal($request->faktur_id);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan'
            ]);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function updatebiayadetail(Request $request)
    {
        FakturPenjualan::where('id', $request->faktur_id)->update([
            'ongkir' => $request->ongkir,
            'materai' => $request->materai,
        ]);
        $this->updateGrandtotal($request->faktur_id);
        return response()->json([
            'status' => 'success',
            'message' => 'Biaya berhasil di update'
        ]);
    }

    public function updateGrandtotal($id)
    {
        $fakturpenjualan = FakturPenjualan::where('id', $id)->first();
        $subtotal = FakturPenjualanDetail::where('faktur_penjualan_id', $id)->sum('subtotal');
        $totaldiskon = FakturPenjualanDetail::where('faktur_penjualan_id', $id)->sum('total_diskon');
        $total = $subtotal - $totaldiskon + ($fakturpenjualan->ongkir ?? 0);
        $ppn = $total * 11 / 100;
        $grandtotal = $total + $ppn +  ($fakturpenjualan->materai ?? 0);

        FakturPenjualan::where('id', $id)->update([
            'subtotal' => $subtotal,
            'total_diskon' => $totaldiskon,
            'total' => $total,
            'ppn' => $ppn,
            'grandtotal' => $grandtotal,
        ]);

        // hitung juga untuk piutang
        Piutang::where('faktur_penjualan_id', $id)->update([
            'dpp' => $total,
            'ppn' => $ppn,
            'total' => $grandtotal,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Grand Total berhasil di update'
        ]);
    }

    public function totalDetail(Request $request)
    {
        $fakturpenjualan = FakturPenjualan::where('id', $request->faktur_id)->first();
        return response()->json([
            'status' => 'success',
            'data' => [
                'subtotal' => $fakturpenjualan->subtotal,
                'total_diskon' => $fakturpenjualan->total_diskon,
                'total' => $fakturpenjualan->total,
                'ppn' => $fakturpenjualan->ppn,
                'grandtotal' => $fakturpenjualan->grandtotal,
                'materai' =>  $fakturpenjualan->materai ?? 0,
                'ongkir' => $fakturpenjualan->ongkir ?? 0,
            ]
        ]);
    }
}
