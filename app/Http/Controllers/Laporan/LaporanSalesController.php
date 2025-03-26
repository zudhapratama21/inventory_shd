<?php

namespace App\Http\Controllers\Laporan;

use App\Exports\LaporanKunjunganSales;
use App\Http\Controllers\Controller;
use App\Models\KunjunganSales;
use App\Models\Outlet;
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
        $outlet = Outlet::get();

        $bulan =  [];
        for ($i = 1; $i <= 12; $i++) {
            $databulan = '1-' . $i . '-2023';
            $bulan[] = [
                'nama' => Carbon::parse($databulan)->format('F'),
                'id' => $i
            ];
        }

        return view('laporan.sales.index', compact('title', 'sales', 'bulan', 'outlet'));
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

        $sales = KunjunganSales::with(['user', 'outlet'])
            ->whereBetween('tanggal', [$start, $end])
            ->when($request->sales !== 'All', fn($query) => $query->where('user_id', $request->sales))
            ->when($request->outlet !== 'All', fn($query) => $query->where('outlet_id', $request->outlet))
            ->get();

        $kunjungansales = $sales->map(function ($item) {
            $planmarketing = PlanMarketing::where([
                'tanggal' => $item->tanggal,
                'user_id' => $item->user_id,
                'outlet_id' => $item->outlet_id,
            ])->exists();

            $rencanakunjungan = RencanaKunjungan::where([
                'tanggal' => $item->tanggal,
                'user_id' => $item->user_id,
                'outlet_id' => $item->outlet_id,
            ])->exists();

            $statusCount = $planmarketing + $rencanakunjungan;

            // Cek apakah kunjungan ini mengisi PlanMarketing dan RencanaKunjungan
            $mengisiPlan = PlanMarketing::where([
                'tanggal' => $item->tanggal,
                'user_id' => $item->user_id,
            ])->exists();

            $mengisiRencana = RencanaKunjungan::where([
                'tanggal' => $item->tanggal,
                'user_id' => $item->user_id,
            ])->exists();

            $mengisiPlanRencana = $mengisiPlan + $mengisiRencana;

            $classNames = [
                0 => 'fc-event-primary fc-event-solid-danger', // Merah (tidak ada plan & rencana)
                1 => 'fc-event-primary fc-event-solid-info', // Hijau (salah satu ada)
                2 => 'fc-event-primary fc-event-solid-success', // Biru (keduanya ada)
            ];
            $januari = '2025-01-31'; // Pastikan format tanggal benar (YYYY-MM-DD)

            // Tambahkan kondisi jika mengisi Plan & Rencana hari itu, maka jadi biru
            $className = $classNames[$statusCount] ?? 'fc-event-primary fc-event-solid-danger';
            if ($statusCount == 0 && $mengisiPlanRencana == 2) {
                $className = 'fc-event-danger fc-event-solid-primary'; // Biru
            } elseif ($statusCount == 0 && $mengisiPlanRencana == 1) {
                $className = 'fc-event-danger fc-event-solid-info';
            }

            // Cek apakah hari ini setelah 31 Januari 2025
            if (Carbon::parse($item->tanggal)->lessThan(Carbon::create(2025, 2, 1))) {
                $className = 'fc-event-danger fc-event-solid-success';
            }

            return [
                'id' => $item->id,
                'start' => $item->tanggal,
                'title' => $item->outlet->nama ?? $item->customer,
                'description' => $item->aktivitas,
                'className' => $className,
            ];
        });

        return response()->json($kunjungansales);
    }

    public function print(Request $request)
    {
        $start = '2025-02-27';
        $end = '2025-03-26';

        $sales = KunjunganSales::with(['user', 'outlet'])
            ->whereBetween('tanggal', [$start, $end])            
            ->get();

        $absensi = collect();

        $sales->groupBy('user_id')->each(function ($salesByUser, $userId) use (&$absensi) {
            $userAbsensi = collect();
            $userName = $salesByUser->first()->user->name ?? 'Unknown';
        
            $salesByUser->groupBy('tanggal')->each(function ($kunjungans, $tanggal) use (&$userAbsensi) {
                // Lewati jika hari adalah Sabtu (6) atau Minggu (0)
                if (Carbon::parse($tanggal)->isWeekend()) {
                    return;
                }
        
                // Cek jika ada kunjungan dengan className yang valid (bukan fc-event-primary fc-event-solid-danger)
                $hadir = $kunjungans->first(function ($item) {
                    $statusCount = PlanMarketing::where([
                        'tanggal' => $item->tanggal,
                        'user_id' => $item->user_id,
                        'outlet_id' => $item->outlet_id,
                    ])->exists() + RencanaKunjungan::where([
                        'tanggal' => $item->tanggal,
                        'user_id' => $item->user_id,
                        'outlet_id' => $item->outlet_id,
                    ])->exists();
        
                    $classNames = [
                        0 => 'fc-event-primary fc-event-solid-danger',
                        1 => 'fc-event-primary fc-event-solid-info',
                        2 => 'fc-event-primary fc-event-solid-success',
                    ];
        
                    $className = $classNames[$statusCount] ?? 'fc-event-primary fc-event-solid-danger';
        
                    return $className !== 'fc-event-primary fc-event-solid-danger';
                });
        
                if ($hadir) {
                    $userAbsensi->push($tanggal);
                }
            });
        
            $absensi->push([
                'user' => $userName,
                'total_hari_kerja' => $userAbsensi->count(),
                'hari_kerja' => $userAbsensi->values()
            ]);
        });

        // return response()->json($absensi);
        dd($absensi);
    }

    public function show($id)
    {
        $kunjungansales = KunjunganSales::with('outlet', 'user')->where('id', $id)->first();
        $planmarketing = PlanMarketing::with('outlet')
            ->where('user_id', $kunjungansales->user_id)
            ->where('tanggal', $kunjungansales->tanggal)
            ->get();

        // dd($planmarketing);
        $rencanakunjungan = RencanaKunjungan::with('outlet', 'user')
            ->where('user_id', $kunjungansales->user_id)
            ->where('tanggal', $kunjungansales->tanggal)
            ->get();
        $text = strip_tags($kunjungansales->aktifitas);

        return view('laporan.sales.partial.modal', compact('kunjungansales', 'planmarketing', 'rencanakunjungan', 'text'));
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
            ->where('kunjungan_sales.outlet_id', $request->outlet)
            ->select('users.name as user', 'kunjungan_sales.tanggal as tanggal', 'kunjungan_sales.jam_buat as jam_buat', 'kunjungan_sales.aktifitas as aktifitas');


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
