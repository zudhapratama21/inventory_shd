<?php

namespace App\Http\Controllers\Pembayaran;

use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Hutang;
use App\Traits\CodeTrait;
use Illuminate\Http\Request;
use App\Models\PembayaranHutang;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\FakturPembelian;
use App\Models\LogToleransi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembayaranHutangController extends Controller
{
    use CodeTrait;
    function __construct()
    {
        $this->middleware('permission:pembayaranhutang-list');
        $this->middleware('permission:pembayaranhutang-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:pembayaranhutang-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:pembayaranhutang-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = "Pembayaran Hutang";
        $pembayaranhutang = PembayaranHutang::with(['suppliers',  'hutangs', 'banks', 'FakturPO.PO'])->orderBy('id', 'desc');

        if (request()->ajax()) {
            return Datatables::of($pembayaranhutang)
                ->addIndexColumn()
                ->addColumn('suppliers', function (PembayaranHutang $ph) {
                    return $ph->suppliers->nama;
                })
                ->addColumn('faktur_po', function (PembayaranHutang $ph) {
                    return $ph->FakturPO->kode;
                })
                ->addColumn('no_faktur_supplier', function (PembayaranHutang $ph) {
                    return $ph->FakturPO->no_faktur_supplier;
                })
                ->addColumn('no_so', function (PembayaranHutang $ph) {
                    return $ph->FakturPO->PO->no_so;
                })
                ->addColumn('banks', function (PembayaranHutang $ph) {
                    return $ph->banks->nama;
                })
                ->editColumn('nominal', function (PembayaranHutang $ph) {
                    return $ph->nominal ? with(number_format($ph->nominal, 0, ',', '.')) : '';
                })
                ->editColumn('tanggal', function (PembayaranHutang $ph) {
                    return $ph->tanggal ? with(new Carbon($ph->tanggal))->format('d-m-Y') : '';
                })
                ->addColumn('action', function ($row) {
                    //$editUrl = route('fakturpembelian.edit', ['fakturpembelian' => $row->id]);
                    //$showUrl = route('pembayaranhutang.show', ['pembayaranhutang' => $row->id]);
                    $showUrl = "";
                    $id = $row->id;
                    $status = "";
                    return view('pembayaran.pembayaranhutang._formAction', compact('id', 'status', 'showUrl'));
                })
                ->make(true);
        }


        return view('pembayaran.pembayaranhutang.index', compact('title'));
    }

    public function listhutang()
    {
        $title = "Daftar Hutang";
        $hutangs =  Hutang::where('status', '=', '1')
            ->with(['suppliers:id,nama', 'FakturPO.PO'])
            ->orderBy('hutangs.id', 'desc');

        if (request()->ajax()) {
            return Datatables::of($hutangs)
                ->addIndexColumn()
                ->addColumn('nama_supplier', function (Hutang $pb) {
                    return $pb->suppliers->nama;
                })
                ->filterColumn('kode', function ($query, $keyword) {
                    $query->whereHas('FakturPO', function ($q) use ($keyword) {
                        $q->where('kode', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn(
                    'no_so',
                    fn($pb) =>
                    optional(optional($pb->FakturPO)->PO)->no_so ?? '-'
                )
                ->filterColumn('no_so', function ($query, $keyword) {
                    $query->whereHas('FakturPO.PO', function ($q) use ($keyword) {
                        $q->where('no_so', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('no_faktur_supplier', function ($query, $keyword) {
                    $query->whereHas('FakturPO', function ($q) use ($keyword) {
                        $q->where('no_faktur_supplier', 'like', "%{$keyword}%");
                    });
                })
                ->editColumn('tanggal', function (Hutang $pb) {
                    return $pb->tanggal ? with(new Carbon($pb->tanggal))->format('d-m-Y') : '';
                })
                ->editColumn('total', function (Hutang $pb) {
                    return $pb->total ? with(number_format($pb->total, 0, ',', '.')) : '';
                })
                ->editColumn('dibayar', function (Hutang $pb) {
                    return $pb->dibayar ? with(number_format($pb->dibayar, 0, ',', '.')) : '0';
                })
                ->addColumn('sisa', function (Hutang $pb) {
                    $sisa = $pb->total - $pb->dibayar;
                    return $sisa ? with(number_format($sisa, 0, ',', '.')) : '0';
                })
                ->editColumn('tanggal_top', function (Hutang $pb) {
                    return $pb->tanggal_top ? with(new Carbon($pb->tanggal_top))->format('d-m-Y') : '';
                })
                ->addColumn('action', function (Hutang $row) {
                    $pilihUrl = route('pembayaranhutang.create', ['hutang' => $row->id]);
                    $id = $row->id;
                    return view('pembayaran.pembayaranhutang._pilihAction', compact('pilihUrl', 'id'));
                })
                ->make(true);
        }

        //dd($pesananpembelian);
        return view('pembayaran.pembayaranhutang.listhutang', compact('title', 'hutangs'));
    }
    public function create(Hutang $hutang)
    {


        $title = "Faktur Pembelian";
        $pembayaranhutang = new PembayaranHutang;
        $banks = Bank::get();
        return view('pembayaran.pembayaranhutang.create', compact('title', 'pembayaranhutang', 'hutang', 'banks'));
    }

    public function store(Request $request, Hutang $hutang)
    {
        $request->validate([
            'tanggal' => ['required'],
            'nominal' => ['required'],
            'bank_id' => ['required'],
        ]);

        $datas = $request->all();
        $tanggal = $request->tanggal;
        if ($tanggal <> null) {
            $tanggal = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
        }

        $total_hutang   = $hutang->total;
        $dibayar        = $hutang->dibayar;
        $sisa           = $total_hutang - $dibayar;

        $nominal = str_replace('.', '', $request->nominal) * 1;
        $dibayar_baru = $dibayar + $nominal;

        $toleransi = $total_hutang - $dibayar_baru;



        if ($toleransi >= -500 && $toleransi <= 500) {
            $status = '2';
        } else {
            $status = '1';
        }

        if ($toleransi < -500) {
            return back()->with('error', 'Nominal tidak boleh melebihi sisa hutang');
        }


        //insert pembayaran
        $datas['tanggal'] = $tanggal;
        $datas['supplier_id'] = $hutang->supplier_id;
        $datas['faktur_pembelian_id'] = $hutang->faktur_pembelian_id;
        $datas['hutang_id'] = $hutang->id;
        $datas['bank_id'] = $request->bank_id;
        $datas['nominal'] = $nominal;
        $datas['keterangan'] = $request->keterangan;
        PembayaranHutang::create($datas);

        //update Hutang
        $datahutang = Hutang::find($hutang->id);
        $datahutang->status = $status;
        $datahutang->dibayar = $dibayar_baru;
        $datahutang->nominal_toleransi = $toleransi;
        $datahutang->save();

        $faktur = FakturPembelian::where('id', $hutang->faktur_pembelian_id)->first();

        if ($status == '2') {
            LogToleransi::create([
                'tanggal' => $tanggal,
                'rupiah' => $toleransi,
                'jenis' => 'Hutang',
                'jenis_id' => $faktur->kode,
            ]);
        }


        return redirect()->route('pembayaranhutang.index')->with('status', 'Pembayaran Hutang Berhasil Dibuat !');
    }

    public function show(Request $request)
    {
        $pembayaranhutang = PembayaranHutang::with('FakturPO', 'suppliers', 'banks')
            ->where('id', '=', $request->id)->get()->first();

        return view('pembayaran.pembayaranhutang._showDetail', compact('pembayaranhutang'));
    }

    public function delete(Request $request)
    {
        $data = PembayaranHutang::where('id', '=', $request->id)->get()->first();
        $id = $request->id;
        return view('pembayaran.pembayaranhutang._confirmDelete', compact('id'));
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $pembayaranhutang = PembayaranHutang::find($id);
        $hutang_id = $pembayaranhutang->hutang_id;
        $nominal = $pembayaranhutang->nominal;

        $data_hutang = Hutang::find($hutang_id);
        $data_hutang->dibayar -= $nominal;
        $data_hutang->status = "1";
        $data_hutang->save();

        $pembayaranhutang->deleted_by = Auth::user()->id;
        $pembayaranhutang->save();
        PembayaranHutang::destroy($request->id);

        return redirect()->route('pembayaranhutang.index')->with('status', 'Pembayaran Hutang Berhasil Dihapus !');
    }
}
