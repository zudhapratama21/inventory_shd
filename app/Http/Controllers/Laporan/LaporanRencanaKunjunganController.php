<?php

namespace App\Http\Controllers\Laporan;

use App\Exports\LaporanRencanaKunjunganExport;
use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\PlanMarketing;
use App\Models\RencanaKunjungan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class LaporanRencanaKunjunganController extends Controller
{
    public function index()
    {
        $title = "Laporan Rencanna Aktivitas";
        $sales = User::whereNotNull('sales_id')->get();
        $outlet = Outlet::get();

        return view('laporan.sales.rencanakunjungan.index', compact('title', 'sales', 'outlet'));
    }


    public function list(Request $request)
    {
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');
        $rencanakunjungan = RencanaKunjungan::with(['user', 'outlet'])
                        ->where('tanggal', '>=', $start)
                        ->where('tanggal', '<=', $end)
                        ->when($request->sales !== 'All', fn($query) => $query->where('user_id', $request->sales))
                        ->when($request->outlet !== 'All', fn($query) => $query->where('outlet_id', $request->outlet))
                        ->orderBy('id', 'desc')                        
                        ->get()
                        ->map(fn($item) => [
                            'id' => $item->id,
                            'start' => $item->tanggal,
                            'title' => $item->outlet->nama,
                            'description' => $item->outlet->nama,
                            'className' => 'fc-event-primary fc-event-solid-success'
                        ]);
        return response()->json($rencanakunjungan);
    }

    public function show ($id)
    {
        $rencanakunjungan = RencanaKunjungan::where('id',$id)->first();
        $kunjungan = RencanaKunjungan::with('user')->where('user_id',$rencanakunjungan->user_id)->where('tanggal',$rencanakunjungan->tanggal)->get();
        $planmarketing = PlanMarketing::with('user')->where('user_id',$rencanakunjungan->user_id)->where('tanggal',$rencanakunjungan->tanggal)->get();

        return view('laporan.sales.rencanakunjungan.partial.modal',compact('kunjungan','planmarketing'));
    }

    public function print(Request $request)
    {
        $data = $request->all();
        // dd($data);     
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new  LaporanRencanaKunjunganExport($data), 'laporanrencanakunjungan-' . $now . '.xlsx');
    }
}
