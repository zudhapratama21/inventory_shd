<?php

namespace App\Http\Controllers\Laporan;

use App\Exports\LaporanPembayaranHutangExport;
use App\Exports\LaporanPembayaranHutangExportDetail;
use App\Exports\LaporanPembayaranPiutangDetailExport;
use App\Exports\LaporanPembayaranPiutangExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\LogToleransi;
use App\Models\Sales;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPembayaranController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:pembayaran-list');
    }


    public function index()
    {
        return view('laporan.pembayaran.index');
    }

    public function filterHutangDetail()
    {
        $title = "Laporan Pembayaran Hutang Detail";
        $supplier = Supplier::with('namakota')->select('id', 'nama', 'kota')->get();

        return view('laporan.pembayaran.pembayaranHutangDetail.filterHutangDetail', [
            'suppliers' => $supplier,
            'title' => $title
        ]);
    }

    public function filterDataHutangDetail(Request $request)
    {
        $title = 'Laporan Pembayaran Hutang Detail';
        $data  = $request->all();

        // karena pasti ada
        $tgl1 = Carbon::parse($request->tgl1)->format('Y-m-d');
        $tgl2 = Carbon::parse($request->tgl2)->format('Y-m-d');

        $query = DB::table('pembayaran_hutangs as ph')
            ->join('hutangs as h', 'h.id', '=', 'ph.hutang_id')
            ->join('pesanan_pembelians as pp', 'h.pesanan_pembelian_id', '=', 'pp.id')
            ->join('banks as b', 'ph.bank_id', '=', 'b.id')
            ->join('penerimaan_barangs as pb', 'h.penerimaan_barang_id', '=', 'pb.id')
            ->join('suppliers as s', 'h.supplier_id', '=', 's.id')
            ->join('faktur_pembelians as fb', 'h.faktur_pembelian_id', '=', 'fb.id')

            // 🔥 tanggal pasti dipakai
            ->whereBetween('ph.tanggal', [$tgl1, $tgl2])
            ->where('pp.deleted_at',null);;

        // 🔥 supplier
        if ($request->supplier !== 'all') {
            $query->where('s.id', $request->supplier);
        }

        // 🔥 no faktur (opsional)
        if (!empty($request->no_faktur)) {
            $query->where('fb.kode', $request->no_faktur);
        }

        // 🔥 ambil data
        $datafilter = $query->select(
            's.nama as nama_supplier',
            'pp.kode as kode_pp',
            'pp.no_so',
            'pb.kode as kode_pb',
            'fb.kode as kode_fp',
            'h.*',
            'b.nama as nama_bank',
            'ph.nominal as nominal_pembayaran'
        )->get();

        // 🔥 validasi
        if ($datafilter->isEmpty()) {
            return redirect()->back()->with('status_danger', 'Data tidak ditemukan atau belum melakukan pembayaran');
        }

        // 🔥 view
        return view('laporan.pembayaran.pembayaranHutangDetail.filterPembayaranResult', [
            'title' => $title,
            'hutang' => $datafilter,
            'form' => $data
        ]);
    }

    public function exportPembayaranHutangDetail(Request $request)
    {
        $data = $request->all();
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LaporanPembayaranHutangExportDetail($data), 'laporanpembayaranhutangdetail-' . $now . '.xlsx');
    }

    public function filterPiutangDetail()
    {
        $title = "Laporan Pembayaran Piutang Detail";
        $customer = Customer::with('namakota')->select('id', 'nama', 'kota')->get();
        $sales = Sales::select('id', 'nama')->get();

        return view('laporan.pembayaran.pembayaranPiutangDetail.filterPiutangDetail', [
            'customer' => $customer,
            'sales' => $sales,
            'title' => $title
        ]);
    }


    public function filterPembayaranPiutangDetail(Request $request)
    {
        $title = 'Laporan Pembayaran Piutang';
        $data  = $request->all();

        // tanggal (anggap pasti ada)
        $tgl1 = Carbon::parse($data['tgl1'])->format('Y-m-d');
        $tgl2 = Carbon::parse($data['tgl2'])->format('Y-m-d');

        // base query
        $query = DB::table('pembayaran_piutangs as pp')
            ->join('piutangs as p', 'pp.piutang_id', '=', 'p.id')
            ->join('faktur_penjualans as fp', 'p.faktur_penjualan_id', '=', 'fp.id')
            ->join('customers as c', 'p.customer_id', '=', 'c.id')
            ->join('sales as s', 'fp.sales_id', '=', 's.id')
            ->join('banks as b', 'pp.bank_id', '=', 'b.id')

            // 🔥 filter tanggal
            ->whereBetween('pp.tanggal', [$tgl1, $tgl2])
            ->where('pp.deleted_at',null);

        // 🔥 filter customer
        if ($data['customer'] !== 'all') {
            $query->where('fp.customer_id', $data['customer']);
        }

        // 🔥 filter no faktur (opsional)
        if (!empty($data['no_faktur'])) {
            $query->where('fp.no_perusahaan', $data['no_faktur']);
        }

        // 🔥 filter sales (FIXED 🔥)
        if ($data['sales'] !== 'all') {
            $query->where('fp.sales_id', $data['sales']);
        }

        // 🔥 ambil data
        $datafilter = $query->select(
            'c.nama as nama_customer',
            'fp.kode as kode_fp',
            'fp.no_perusahaan',
            'p.*',
            'pp.nominal as nominal_pembayaran',
            'pp.keterangan',
            's.nama as nama_sales',
            'b.nama as nama_bank'
        )->get();

        // 🔥 validasi
        if ($datafilter->isEmpty()) {
            return redirect()->back()->with('status_danger', 'Data tidak ditemukan atau belum melakukan pembayaran');
        }

        // 🔥 return
        return view('laporan.pembayaran.pembayaranPiutangDetail.filterPembayaranResult', [
            'title' => $title,
            'hutang' => $datafilter,
            'form' => $data
        ]);
    }

    public function exportPembayaranPiutangDetail(Request $request)
    {        
        $data = $request->all();
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LaporanPembayaranPiutangDetailExport($data), 'laporanpembayaranpiutangdetail-' . $now . '.xlsx');
    }
}
