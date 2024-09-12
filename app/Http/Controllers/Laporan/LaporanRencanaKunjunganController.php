<?php

namespace App\Http\Controllers\Laporan;

use App\Exports\LaporanRencanaKunjunganExport;
use App\Http\Controllers\Controller;
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
        $title = "Laporan Kunjungan Sales";
        $sales = User::whereNotNull('sales_id')->get();        
       
        return view('laporan.sales.rencanakunjungan.index',compact('title','sales'));
    }


    public function datatable(Request $request)
    {
        $sales = RencanaKunjungan::with(['user','outlet']);

        if ($request->tanggalMulai) {
           $sales->where('tanggal','>=',$request->tanggalMulai);
        }

        if ($request->tanggalSelesai) {
            $sales->where('tanggal','<=',$request->tanggalSelesai);
        }

        if ($request->sales !== 'all') {
            $sales->where('user_id',$request->sales);
        }

        $datasales = $sales->orderBy('id','desc');

        if (request()->ajax()) {
            return DataTables::of($datasales)
                ->addIndexColumn()
                ->editColumn('tanggal', function (RencanaKunjungan $sj) {
                    return $sj->tanggal ? with(new Carbon($sj->tanggal))->format('d-m-Y') : '';
                })
                ->editColumn('created_at', function (RencanaKunjungan $sj) {
                    return $sj->jam_buat ? with(new Carbon($sj->jam_buat))->format('H:i') : with(new Carbon($sj->created_at))->format('H:i');
                })
                ->editColumn('user', function (RencanaKunjungan $sj) {
                    return $sj->user->name;
                })
                ->addColumn('aktivitas', function (RencanaKunjungan $kj) {
                    return view('laporan.sales.partial.text',[
                        'text' => $kj->aktivitas
                    ]);
                })
                ->addColumn('action', function ($row) {
                    $id = $row->id;
                    $tanggal = Carbon::parse($row->tanggal)->format('d-F-Y');
                    return view('laporan.sales.partial._form-action', [
                        'id' => $id,
                        'tanggal' => $tanggal,
                        'aktifitas' => $row->aktifitas,
                        'customer' => $row->customer,
                        'nomor' => $row->user->phone
                    ]);
                })
                ->make(true);
         }
    }

    public function print(Request $request)
    {
        $data = $request->all();   
        // dd($data);     
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new  LaporanRencanaKunjunganExport($data), 'laporanrencanakunjungan-'.$now.'.xlsx');
    }
}
