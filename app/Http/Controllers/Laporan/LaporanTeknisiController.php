<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\KunjunganTeknisi;
use App\Models\Outlet;
use App\Models\Teknisi\PlanTeknisi;
use App\Models\Teknisi\RencanaAktivitasTeknisi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LaporanTeknisiController extends Controller
{
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

        return view('laporan.teknisi.index', compact('title', 'sales', 'bulan', 'outlet'));
    }


    public function datatable(Request $request)
    {
        $sales =  DB::table('kunjungan_teknisi')
            ->join('outlets', 'kunjungan_teknisi.outlet_id', '=', 'outlets.id')
            ->groupBy('kunjungan_teknisi.outlet_id', 'kunjungan_teknisi.user_id')
            ->orderBy('kunjungan_teknisi.id', 'desc')
            ->where('kunjungan_teknisi.deleted_at', '=', null)
            ->where('kunjungan_teknisi.outlet_id', '<>', null)
            ->when($request->tahun !== 'All', fn($query) => $query->whereYear('tanggal', $request->tahun))
            ->when($request->bulan !== 'All', fn($query) => $query->whereMonth('tanggal', $request->bulan))
            ->when($request->sales !== 'All', fn($query) => $query->where('user_id', $request->sales))
            ->select(
                'outlets.id as id_outlet',
                'outlets.nama as outlet',
                DB::raw('COUNT(kunjungan_teknisi.user_id) as users')
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

        $sales = KunjunganTeknisi::with(['user', 'outlet'])
            ->whereBetween('tanggal', [$start, $end])
            ->when($request->sales !== 'All', fn($query) => $query->where('user_id', $request->sales))
            ->when($request->outlet !== 'All', fn($query) => $query->where('outlet_id', $request->outlet))
            ->get();

        $kunjungansales = $sales->map(function ($item) {
            $planmarketing = PlanTeknisi::where([
                'tanggal' => $item->tanggal,
                'user_id' => $item->user_id,
                'outlet_id' => $item->outlet_id,
            ])->exists();

            $rencanakunjungan = RencanaAktivitasTeknisi::where([
                'tanggal' => $item->tanggal,
                'user_id' => $item->user_id,
                'outlet_id' => $item->outlet_id,
            ])->exists();

            $statusCount = $planmarketing + $rencanakunjungan;

            // Cek apakah kunjungan ini mengisi PlanTeknisi dan RencanaAktivitasTeknisi
            $mengisiPlan = PlanTeknisi::where([
                'tanggal' => $item->tanggal,
                'user_id' => $item->user_id,
            ])->exists();

            $mengisiRencana = RencanaAktivitasTeknisi::where([
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

    // public function print(Request $request)
    // {
    //     $data = $request->all();
    //     $now = Carbon::parse(now())->format('Y-m-d');
    //     return Excel::download(new LaporanKunjunganSales($data), 'laporankunjungansales-' . $now . '.xlsx');
    // }

    public function show($id)
    {
        $kunjungansales = KunjunganTeknisi::with('outlet', 'user')->where('id', $id)->first();
        $planmarketing = PlanTeknisi::with('outlet')
            ->where('user_id', $kunjungansales->user_id)
            ->where('tanggal', $kunjungansales->tanggal)
            ->get();

        // dd($planmarketing);
        $rencanakunjungan = RencanaAktivitasTeknisi::with('outlet', 'user')
            ->where('user_id', $kunjungansales->user_id)
            ->where('tanggal', $kunjungansales->tanggal)
            ->get();
        $text = strip_tags($kunjungansales->aktifitas);

        return view('laporan.teknisi.partial.modal', compact('kunjungansales', 'planmarketing', 'rencanakunjungan', 'text'));
    }

    public function datatablesales(Request $request)
    {
        $sales = DB::table('kunjungan_teknisi')
            ->join('outlets', 'kunjungan_teknisi.outlet_id', '=', 'outlets.id')
            ->join('users', 'kunjungan_teknisi.user_id', '=', 'users.id')
            ->where('kunjungan_teknisi.deleted_at', '=', null)
            ->when($request->tahun !== 'All', fn($query) => $query->whereYear('tanggal', $request->tahun))
            ->when($request->bulan !== 'All', fn($query) => $query->whereMonth('tanggal', $request->bulan))
            ->when($request->sales !== 'All', fn($query) => $query->where('user_id', $request->sales))
            ->where('kunjungan_teknisi.outlet_id', $request->outlet)
            ->select('users.name as user', 'kunjungan_teknisi.tanggal as tanggal', 'kunjungan_teknisi.jam_buat as jam_buat', 'kunjungan_teknisi.aktifitas as aktifitas');


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
                return view('laporan.teknisi.partial.text', compact('aktifitas'));
            })
            ->make(true);
    }
}
