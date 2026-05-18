<?php

namespace App\Http\Controllers\Laporan;

use App\Exports\LaporanHutangExport;
use App\Exports\LaporanPiutangExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Sales;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanHutangPiutangController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:laporanhutang-list');
    }

    public function index()
    {
        return view('laporan.hutangpiutang.index');
    }


    public function hutang()
    {
        $title = "Laporan  Hutang";
        $supplier = Supplier::with('namakota')->select('id', 'nama', 'kota')->get();

        // dd($supplier[0]);
        return view('laporan.hutangpiutang.hutang.filterHutang', [
            'supplier' => $supplier,
            'title' => $title
        ]);
    }

    public function piutang()
    {
        $title = "Laporan Piutang";
        $customer = Customer::with('namakota')->select('id', 'nama', 'kota')->get();
        $sales = Sales::select('id', 'nama')->get();

        return view('laporan.hutangpiutang.piutang.filterPiutang', [
            'customer' => $customer,
            'sales' => $sales,
            'title' => $title
        ]);
    }

    public function filterHutang(Request $request)
    {
        $title = 'Laporan Hutang';
        $data = $request->all();
        $tgl1 = Carbon::parse($data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($data['tgl2'])->format('Y-m-d');

        // base query
        $query = DB::table('hutangs as h')
            ->join('pesanan_pembelians as pp', 'h.pesanan_pembelian_id', '=', 'pp.id')
            ->join('penerimaan_barangs as pb', 'h.penerimaan_barang_id', '=', 'pb.id')
            ->join('suppliers as s', 'h.supplier_id', '=', 's.id')
            ->join('faktur_pembelians as fb', 'h.faktur_pembelian_id', '=', 'fb.id')
            ->whereBetween('h.tanggal_top', [$tgl1, $tgl2]);

        // 🔥 filter supplier
        $query->when($request->supplier !== 'all', function ($q) use ($request) {
            $q->where('s.id', $request->supplier);
        });

        // 🔥 filter no faktur
        $query->when(!empty($request->no_faktur), function ($q) use ($request) {
            $q->where('fb.kode', $request->no_faktur);
        });

        // 🔥 filter status
        $query->when($data['status'] !== 'all', function ($q) use ($data) {
            $q->where('h.status', $data['status']);
        });

        // 🔥 ambil data
        $datafilter = $query->select(
            's.nama as nama_supplier',
            'pp.kode as kode_pp',
            'pb.kode as kode_pb',
            'fb.kode as kode_fp',
            'h.*'
        )->get();

        // 🔥 validasi
        if ($datafilter->isEmpty()) {
            return redirect()->back()->with('status_danger', 'Data tidak ditemukan');
        }

        // 🔥 return view
        return view('laporan.hutangpiutang.hutang.filterHutangResult', [
            'title' => $title,
            'hutang' => $datafilter,
            'form'   => $data
        ]);
    }

    public function exportHutang(Request $request)
    {
        $data = $request->all();
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LaporanHutangExport($data), 'laporanhutang-' . $now . '.xlsx');
    }

    public function filterPiutang(Request $request)
    {
        $title = 'Laporan Piutang';
        $data = $request->all();

        $tgl1 = Carbon::parse($data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($data['tgl2'])->format('Y-m-d');

        // tanggal
        $tgl1 = Carbon::parse($data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($data['tgl2'])->format('Y-m-d');

        // base query
        $query = DB::table('piutangs as p')                        
            ->join('customers as c', 'p.customer_id', '=', 'c.id')
            ->join('faktur_penjualans as fp', 'p.faktur_penjualan_id', '=', 'fp.id')
            ->join('sales as s', 'fp.sales_id', '=', 's.id')

            // filter tanggal
            ->whereBetween('p.tanggal_top', [$tgl1, $tgl2]);

        // 🔥 filter customer
        $query->when($data['customer'] !== 'all', function ($q) use ($data) {
            $q->where('c.id', $data['customer']);
        });

        // 🔥 filter no faktur
        $query->when(!empty($data['no_faktur']), function ($q) use ($data) {
            $q->where('fp.kode', $data['no_faktur']);
        });

        // 🔥 filter sales
        $query->when($data['sales'] !== 'all', function ($q) use ($data) {
            $q->where('pp.sales_id', $data['sales']);
        });

        // 🔥 filter status
        $query->when($data['status'] !== 'all', function ($q) use ($data) {
            $q->where('p.status', $data['status']);
        });

        // 🔥 ambil data
        $datafilter = $query->select(
            'c.nama as nama_customer',                        
            'fp.kode as kode_fp',
            'fp.no_perusahaan',
            's.nama as nama_sales',
            'p.*'
        )->get();

        // 🔥 validasi data kosong
        if ($datafilter->isEmpty()) {
            return redirect()->back()->with(
                'status_danger',
                'Data tidak ditemukan atau belum melakukan pembayaran'
            );
        }

        // 🔥 return view
        return view('laporan.hutangpiutang.piutang.filterPiutangResult', [
            'title' => $title,
            'hutang' => $datafilter,
            'form' => $data
        ]);
    }

    public function exportPiutang(Request $request)
    {
        $data = $request->all();
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LaporanPiutangExport($data), 'laporanpiutang-' . $now . '.xlsx');
    }
}
