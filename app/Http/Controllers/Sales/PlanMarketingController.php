<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\Outlet;
use App\Models\PlanMarketing;
use App\Models\PLanMarketingDetail;
use App\Traits\CodeTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Days;
use Yajra\DataTables\Facades\DataTables;

class PlanMarketingController extends Controller
{
    use CodeTrait;

    public function index()
    {

        $title = "Plan Marketing";
        $day = Day::get();
        $bulan =  [];
        for ($i = 1; $i <= 12; $i++) {
            $databulan = '1-' . $i . '-2023';
            $bulan[] = [
                'nama' => Carbon::parse($databulan)->format('F'),
                'id' => $i
            ];
        }

        $outlet = Outlet::get();
        return view('sales.planmarketing.index', compact('title', 'bulan', 'outlet', 'day'));
    }

    public function datatable(Request $request)
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
            $outlet = $planmarketing->where('outlet_id', $request->outlet);
        } else {
            $outlet =  $planmarketing;
        }

        if ($request->tahun) {
            $year = $outlet->where('tahun', $request->tahun);
        }

        if ($request->bulan) {
            $bulan = $year->where('bulan', $request->bulan);
        }
        $bulan->where('user_id', auth()->user()->id)
            ->orderBy('id', 'desc');

        return DataTables::of($planmarketing)
            ->addIndexColumn()
            ->addColumn('waktu', function (PlanMarketing $ot) {
                $tanggal = '11-' . $ot->bulan . '-' . $ot->tahun;
                $waktu = Carbon::parse($tanggal)->format('F - Y');
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
            ->addColumn('action', function ($row) {
                $id = $row->id;
                return view('sales.planmarketing.partial._action', compact('id'));
            })
            ->make(true);
    }

    public function create(Request $request)
    {
        // $title = 'Plan Marketing';
        $outlet = Outlet::get();
        
        return view('sales.planmarketing.partial.modal', compact('outlet','request'));
    }

    public function list (Request $request)
    {
      $start = Carbon::parse($request->start)->format('Y-m-d');
      $end = Carbon::parse($request->end)->format('Y-m-d');
      

      $planmarketing = PlanMarketing::with('outlet')->where('tanggal','>=',$start)->where('tanggal','<=',$end)
                        ->where('user_id',auth()->user()->id)
                        ->get()
                        ->map(fn($item) => [
                            'id' => $item->id,
                            'start' => $item->tanggal,
                            'title' => $item->outlet->nama,
                            'className' => 'fc-event-primary fc-event-solid-success',                            
                        ]);

      return response()->json($planmarketing);
    }


    public function store(Request $request)
    {                
        $planmarketing = PlanMarketing::create([
            'tanggal' => $request->tanggal,        
            'outlet_id' => $request->outlet_id,
            'user_id' => auth()->user()->id,            
        ]);

        return response()->json('Data Berhasil Ditambahkan');
    }


    public function edit($id)
    {      

        $planmarketing = PlanMarketing::with('outlet')->where('id',$id)->first();  
        $outlet = Outlet::get();      
        return view('sales.planmarketing.partial.modaledit', compact('planmarketing','outlet'));
    }

    public function update(Request $request)
    {
      
        
        $update = PlanMarketing::where('id',$request->data_id)->update([
                'tanggal' => $request->tanggal,
                'outlet_id' => $request->outlet_id
        ]);

        return response()->json('Data Berhasil di Update');
        
    }


    public function destroy(Request $request)
    {
        $planmarketing = PlanMarketing::where('id', $request->data_id)->first();
        $planmarketing->delete();

        return response()->json('Data Berhasil Dihapus');
    }

    public function remind(Request $request)
    {
        $planmarketing = DB::table('plan_marketings as pm')
            ->join('plan_marketings_detail as pmd', 'pmd.planmarketing_id', '=', 'pm.id')
            ->join('outlets as o', 'pm.outlet_id', '=', 'o.id')
            ->join('days as d', 'pmd.day_id', '=', 'd.id')
            ->where('pm.bulan', $request->bulan)
            ->where('pm.tahun', $request->tahun)
            ->where('pm.user_id', auth()->user()->id)
            ->where('pmd.minggu', $request->minggu)
            ->where('pmd.day_id', $request->hari)
            ->where('pm.deleted_at', '=', null)
            ->where('pmd.deleted_at', '=', null)
            ->select('o.nama as nama_outlet', 'd.nama as nama_hari', 'pm.bulan as nama_bulan', 'pm.tahun as nama_tahun', 'pmd.minggu as nama_minggu')
            ->get();

        $text = 'Berikut List Kunjungan Pada Hari ' . ucfirst($planmarketing[0]->nama_hari) . ' Minggu ke - ' . ucfirst($planmarketing[0]->nama_minggu) . ' Bulan ' . ucfirst(Carbon::parse('01-' . $planmarketing[0]->nama_bulan . '-2023')->format('F')) . ' Tahun ' . ucfirst($planmarketing[0]->nama_tahun) . '%0A====================================%0A';
        foreach ($planmarketing as $key => $value) {
            $text = $text . $value->nama_outlet . '%0A';
        }


        $phone = auth()->user()->sales->phone;
        return view('sales.planmarketing.partial._remind', compact('planmarketing', 'text', 'phone'));
    }
}
