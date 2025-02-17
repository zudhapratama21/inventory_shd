<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\Teknisi\PlanTeknisi;
use App\Models\Teknisi\RencanaAktivitasTeknisi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RencanaAktivitasTeknisiController extends Controller
{
    public function index()
    {

        $title = 'Rencana Kunjungan';
        return view('teknisi.rencana_aktivitas.index', compact('title'));
    }

    public function list(Request $request)
    {
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');
        $rencanakunjungan = RencanaAktivitasTeknisi::with(['user', 'outlet'])
            ->where('tanggal', '>=', $start)
            ->where('tanggal', '<=', $end)
            ->orderBy('id', 'desc')->where('user_id', auth()->user()->id)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'start' => $item->tanggal,
                'title' => $item->outlet->nama,
                'description' => $item->aktivitas,
                'className' => 'fc-event-primary fc-event-solid-success'
            ]);
        return response()->json($rencanakunjungan);
    }

    public function create(Request $request)
    {
        $planteknisi = PlanTeknisi::with('outlet')->where('tanggal', $request->start_date)->where('user_id', auth()->user()->id)->get();
        
        $outlet = Outlet::get();

        $terlambat = 0;
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        if ($start_date < now()->format('Y-m-d')) {
            $terlambat = 1;
        }

        $terlambat = 0;


        return view('teknisi.rencana_aktivitas.partial.modal', compact('outlet', 'request', 'planteknisi', 'terlambat'));
    }

    public function store(Request $request)
    {
        RencanaAktivitasTeknisi::create([
            'outlet_id' => $request->outlet_id,
            'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
            'aktivitas' => $request->aktivitas,
            'jam_buat' =>  Carbon::parse(now())->format('H:i'),
            'user_id' => auth()->user()->id
        ]);

        return response()->json('Data Berhasil Ditambahkan');
    }

    public function edit($id)
    {       
        $rencanaaktivitasteknisi = RencanaAktivitasTeknisi::where('id', $id)->first();
        $planteknisi = PlanTeknisi::where('tanggal', $rencanaaktivitasteknisi->tanggal)->where('user_id', auth()->user()->id)->get();
        $outlet = Outlet::get();

        $terlambat = 0;
        $start_date = Carbon::parse($rencanaaktivitasteknisi->tanggal)->format('Y-m-d');
        if ($start_date < now()->format('Y-m-d')) {
            $terlambat = 1;
        }

        $terlambat = 0;

        return view('teknisi.rencana_aktivitas.partial.modaledit', compact('planteknisi', 'outlet', 'rencanaaktivitasteknisi', 'terlambat'));
    }

    public function update(Request $request)
    {
        $rencanakunjungan = RencanaAktivitasTeknisi::where('id', $request->data_id)->update([
            'outlet_id' => $request->outlet_id,
            'aktivitas' => $request->aktivitas,
        ]);

        return response()->json('Data Berhasil Ditambahkan');
    }

    public function delete(Request $request)
    {
        RencanaAktivitasTeknisi::where('id', $request->data_id)->delete();

        return response()->json('Data Berhasil Dihapus');
    }
}
