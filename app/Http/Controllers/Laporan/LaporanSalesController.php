<?php

namespace App\Http\Controllers\Laporan;

use App\Exports\LaporanKunjunganSales;
use App\Http\Controllers\Controller;
use App\Models\KunjunganSales;
use App\Models\PlanMarketing;
use App\Models\RencanaKunjungan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class LaporanSalesController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $title = "Laporan Kunjungan Sales";
        $sales = User::whereNotNull('sales_id')->get();

        $bulan =  [];
        for ($i = 1; $i <= 12; $i++) {
            $databulan = '1-' . $i . '-2023';
            $bulan[] = [
                'nama' => Carbon::parse($databulan)->format('F'),
                'id' => $i
            ];
        }

        return view('laporan.sales.index', compact('title', 'sales', 'bulan'));
    }


    public function datatable(Request $request)
    {
        $sales =    DB::table('kunjungan_sales')
            ->join('outlets', 'kunjungan_sales.outlet_id', '=', 'outlets.id')
            ->groupBy('kunjungan_sales.outlet_id', 'kunjungan_sales.user_id')
            ->orderBy('kunjungan_sales.id', 'desc')
            ->where('kunjungan_sales.deleted_at', '=', null)
            ->where('kunjungan_sales.outlet_id', '<>', null)
            ->when($request->tahun !== 'All', fn($query) => $query->whereYear('tanggal', $request->tahun))
            ->when($request->bulan !== 'All', fn($query) => $query->whereMonth('tanggal', $request->bulan))
            ->when($request->sales !== 'All', fn($query) => $query->where('user_id', $request->sales))
            ->select(
                'outlets.id as id_outlet',
                'outlets.nama as outlet',
                DB::raw('COUNT(kunjungan_sales.user_id) as users')
            )->get();

        return DataTables::of($sales)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $id = $row->id_outlet;
                return view('laporan.sales.partial.action', compact('id'));
            })
            ->make(true);
    }

    public function list(Request $request)
    {
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');

        $sales = KunjunganSales::with('user', 'outlet')
            ->whereBetween('tanggal', [$start, $end])
            ->when($request->sales !== 'All', fn($query) => $query->where('user_id', $request->sales))
            ->get();

        $kunjungansales = [];

        foreach ($sales as $item) {
            $angka = 0;
            $planmarketing = PlanMarketing::where([
                'tanggal' => $item->tanggal,
                'user_id' => $item->user_id,
                'outlet_id' => $item->outlet_id,
            ])->first();

            $rencanakunjungan = RencanaKunjungan::where([
                'tanggal' => $item->tanggal,
                'user_id' => $item->user_id,
                'outlet_id' => $item->outlet_id,
            ])->first();


            $className = 'fc-event-primary fc-event-solid-danger';

            if ($planmarketing) {
                $angka += 1;
            }

            if ($rencanakunjungan) {
                $className = 'fc-event-primary fc-event-solid-success';
                $angka += 1;
            }

            if ($angka == 2) {
                $className = 'fc-event-primary fc-event-solid-success';
            } elseif ($angka == 1) {
                $className = 'fc-event-primary fc-event-solid-info';
            } else {
                $className = 'fc-event-primary fc-event-solid-danger';
            }

            $kunjungansales[] = [
                'id' => $item->id,
                'start' => $item->tanggal,
                'title' => $item->outlet ? $item->outlet->nama : $item->customer,
                'description' => $item->aktivitas,
                'className' => $className
            ];
        }

        return response()->json($kunjungansales);
    }

    public function print(Request $request)
    {
        $data = $request->all();
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LaporanKunjunganSales($data), 'laporankunjungansales-' . $now . '.xlsx');
    }

    public function show($id)
    {
        $kunjungansales = KunjunganSales::with('outlet')->where('id', $id)->first();
        $planmarketing = PlanMarketing::with('outlet')->where('user_id', $kunjungansales->user_id)
            ->where('tanggal', $kunjungansales->tanggal)
            ->get();
        $rencanakunjungan = RencanaKunjungan::with('outlet', 'user')->where('user_id', $kunjungansales->user_id)
            ->where('tanggal', $kunjungansales->tanggal)
            ->get();

        return view('laporan.sales.partial.modal', compact('kunjungansales', 'planmarketing', 'rencanakunjungan'));
    }

    public function datatablesales(Request $request)
    {        
        $sales = DB::table('kunjungan_sales')
            ->join('outlets', 'kunjungan_sales.outlet_id', '=', 'outlets.id')            
            ->join('users', 'kunjungan_sales.user_id', '=', 'users.id')
            ->where('kunjungan_sales.deleted_at', '=', null)            
            ->when($request->tahun !== 'All', fn($query) => $query->whereYear('tanggal', $request->tahun))
            ->when($request->bulan !== 'All', fn($query) => $query->whereMonth('tanggal', $request->bulan))
            ->when($request->sales !== 'All', fn($query) => $query->where('user_id', $request->sales))
            ->where('kunjungan_sales.outlet_id',$request->outlet)
            ->select('users.name as user','kunjungan_sales.tanggal as tanggal','kunjungan_sales.jam_buat as jam_buat','kunjungan_sales.aktifitas as aktifitas');            
                

        return DataTables::of($sales)
                    ->addIndexColumn()
                    ->editColumn('user', function ($row) {
                        return $row->user;
                    })
                    ->editColumn('tanggal', function ($row) {
                        return Carbon::parse($row->tanggal)->format('d-m-Y');
                    })
                    ->editColumn('jam_buat', function ($row) {
                        return Carbon::parse($row->jam_buat)->format('H:i');
                    })
                    ->editColumn('aktifitas', function ($row) {
                        $aktifitas = $row->aktifitas;
                        return view('laporan.sales.partial.text', compact('aktifitas'));
                    })
                    ->make(true);
    }
}
