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
        $bulan =  [];
        for ($i = 1; $i <=12; $i++) {
            $databulan = '1-'.$i.'-2023';
            $bulan[] = [
                'nama' => Carbon::parse($databulan)->format('F') ,
                'id' => $i
            ];         
        }

        $outlet = Outlet::get();
        $sales = User::whereNotNull('sales_id')->get();
        
        $title = 'Laporan Plan Marketing';
        return view('laporan.sales.planmarketing.index',compact('title','bulan','outlet','sales'));
    }

    public function  datatable(Request $request)
    {
        $planmarketing = PlanMarketing::with(['planmarketingdetailminggu1' => function ($query) {
            $query->where('minggu', 1)->with('day');
        }])
            ->with(['planmarketingdetailminggu2' => function ($query) {
                $query->where('minggu', 2)->with('day');
            }])
            ->with(['planmarketingdetailminggu3' => function ($query) {
                $query->where('minggu', 3)->with('day');
            }])
            ->with(['planmarketingdetailminggu4' => function ($query) {
                $query->where('minggu', 4)->with('day');
            }])
            ->with(['planmarketingdetailminggu5' => function ($query) {
                $query->where('minggu', 5)->with('day');
            }])
            ->with('outlet')
            ->with('user');

            if ($request->outlet !== 'all') {
                $outlet = $planmarketing->where('outlet_id',$request->outlet);
            }else{
                $outlet =  $planmarketing;
            }

            if ($request->tahun) {
                $year = $outlet->where('tahun',$request->tahun);
            }

            if ($request->bulan) {
                $bulan = $year->where('bulan',$request->bulan);
            }
            
            if ($request->sales !== 'all') {
                $sales = $year->where('user_id',$request->sales);
            }else{
                $sales = $bulan;
            }

            $sales->orderBy('id', 'asc');

        return DataTables::of($sales)
            ->addIndexColumn()
            ->addColumn('waktu', function (PlanMarketing $ot) {
                $tanggal = '11-'.$ot->bulan.'-'.$ot->tahun;
                $waktu = Carbon::parse($tanggal)->format('F/Y');
                return $waktu;
            })
            ->editColumn('outlet', function (PlanMarketing $ot) {
                return $ot->outlet->nama;
            })
            ->editColumn('week1', function (PlanMarketing $ot) {
                $week = $ot->planmarketingdetailminggu1;
                return view('sales.planmarketing.partial._week', compact('week'));
            })
            ->editColumn('week2', function (PlanMarketing $ot) {
                $week = $ot->planmarketingdetailminggu2;
                return view('sales.planmarketing.partial._week', compact('week'));
            })
            ->editColumn('week3', function (PlanMarketing $ot) {
                $week = $ot->planmarketingdetailminggu3;
                return view('sales.planmarketing.partial._week', compact('week'));
            })
            ->editColumn('week4', function (PlanMarketing $ot) {
                $week = $ot->planmarketingdetailminggu4;
                return view('sales.planmarketing.partial._week', compact('week'));
            })
            ->editColumn('week5', function (PlanMarketing $ot) {
                $week = $ot->planmarketingdetailminggu5;
                return view('sales.planmarketing.partial._week', compact('week'));
            })   
            ->editColumn('userName', function (PlanMarketing $ot) {                
                return $ot->user->name;
            })          
            ->make(true);
    }



}
