<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\PlanMarketing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class laporanPlanMarketingController extends Controller
{
    public function index ()
    {        
        $outlet = Outlet::get();
        $sales = User::whereNotNull('sales_id')->get();
        
        $title = 'Laporan Plan Marketing';
        return view('laporan.sales.planmarketing.index',compact('title','outlet','sales'));
    }

    public function  list(Request $request)
    {
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');
        
  
        $planmarketing = PlanMarketing::with('outlet')->where('tanggal','>=',$start)->where('tanggal','<=',$end)
                          ->when($request->sales !== 'All', fn($query) => $query->where('user_id', $request->sales))
                          ->when($request->outlet !== 'All', fn($query) => $query->where('outlet_id', $request->outlet))
                          ->where('user_id',auth()->user()->id)
                          ->get()
                          ->map(fn($item) => [
                              'id' => $item->id,
                              'start' => $item->tanggal,
                              'title' => $item->outlet->nama,
                              'className' => 'fc-event-primary fc-event-solid-success',
                              'description' =>$item->outlet->nama                           
                          ]);
  
        return response()->json($planmarketing);
    }



}
