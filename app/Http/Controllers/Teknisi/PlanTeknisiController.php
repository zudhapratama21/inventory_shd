<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\Teknisi\PlanTeknisi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlanTeknisiController extends Controller
{
    public function index()
    {
        $title = 'Plan Teknisi';

        return view('teknisi.plan_teknisi.index', compact('title'));
    }

    public function list(Request $request)
    {

        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');

        $planteknisi = PlanTeknisi::with('outlet')->where('tanggal', '>=', $start)->where('tanggal', '<=', $end)
            ->where('user_id', auth()->user()->id)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'start' => $item->tanggal,
                'title' => $item->outlet ? $item->outlet->nama : '-',
                'className' => 'fc-event-primary fc-event-solid-success',
            ]);

        return response()->json($planteknisi);
    }

    public function create(Request $request)
    {
        $outlet = Outlet::get();
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $now = Carbon::parse(now())->format('Y-m-d');
        $bulan = Carbon::parse($request->start_date)->format('m');
        $tahun = Carbon::parse($request->start_date)->format('Y');
        $terlambat = 0;
        $tanggal2 = $tahun . '-' . $bulan . '-' . '02';
        $tanggalakhir = $tahun . '-' . $bulan . '-' . '31';

        if ($now <= $tanggal2) {
            $terlambat = 0;
        } else {
            if ($start_date <= $tanggal2) {
                $terlambat = 1;
            } elseif ($start_date >= $tanggal2 && $start_date <= $tanggalakhir) {
                $terlambat = 1;
            }
        }

        $terlambat = 0;

        return view('teknisi.plan_teknisi.partial.modal', compact('outlet', 'request', 'terlambat'));
    }

    public function store(Request $request)
    {
        $outlet = json_decode($request->outlet_id);
        foreach ($outlet as $key) {
            $planteknisi = PlanTeknisi::create([
                'tanggal' => $request->tanggal,
                'outlet_id' => $key,
                'user_id' => auth()->user()->id,
            ]);
        }

        return response()->json('Data Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        $planteknisi = PlanTeknisi::with('outlet')->where('id', $id)->first();
        $outlet = Outlet::get();
        $start_date = Carbon::parse($planteknisi->tanggal)->format('Y-m-d');
        $bulan = Carbon::parse($planteknisi->tanggal)->format('m');
        $tahun = Carbon::parse($planteknisi->tanggal)->format('Y');

        $terlambat = 0;
        $tanggal2 = $tahun . '-' . $bulan . '-' . '02';
        $tanggalakhir = $tahun . '-' . $bulan . '-' . '31';
        $now = Carbon::parse(now())->format('Y-m-d');

        if ($now <= $tanggal2) {
            $terlambat = 0;
        } else {
            if ($start_date <= $tanggal2) {
                $terlambat = 1;
            } elseif ($start_date >= $tanggal2 && $start_date <= $tanggalakhir) {
                $terlambat = 1;
            }
        }
        $terlambat = 0;

        return view('teknisi.plan_teknisi.partial.modaledit', compact('planteknisi', 'outlet', 'terlambat'));
    }

    public function update(Request $request)
    {        
        $update = PlanTeknisi::where('id', $request->data_id)->update([
            'tanggal' => $request->tanggal,
            'outlet_id' => $request->outlet_id
        ]);

        return response()->json('Data Berhasil di Update');
    }

    public function delete(Request $request)
    {
        PlanTeknisi::where('id', $request->id)->delete();
        return response()->json('Data Berhasil Dihapus');
    }
}
