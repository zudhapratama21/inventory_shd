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
            ->orderBy('id', 'asc');

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

    public function create()
    {
        $bulan =  [];
        for ($i = 1; $i <= 12; $i++) {
            $databulan = '1-' . $i . '-2023';
            $bulan[] = [
                'nama' => Carbon::parse($databulan)->format('F'),
                'id' => $i
            ];
        }


        $title = 'Plan Marketing';
        $outlet = Outlet::get();
        $day = Day::get();
        return view('sales.planmarketing.create', compact('outlet', 'title', 'day', 'bulan'));
    }


    public function store(Request $request)
    {
    //     DB::beginTransaction();        
    //     try {
            $planmarketing = PlanMarketing::create([
                'tahun' => $request->tahun,
                'bulan' => $request->bulan,
                'outlet_id' => $request->outlet_id,
                'user_id' => auth()->user()->id
            ]);

            // looping minggu ke 1 
            if ($request->day_minggu1) {
                foreach ($request->day_minggu1 as $item) {
                    PLanMarketingDetail::create([
                        'planmarketing_id' => $planmarketing->id,
                        'day_id' => $item,
                        'minggu' => 1
                    ]);
                }
            }

            if ($request->day_minggu2) {
                // looping minggu ke 2 
                foreach ($request->day_minggu2 as $item) {
                    PLanMarketingDetail::create([
                        'planmarketing_id' => $planmarketing->id,
                        'day_id' => $item,
                        'minggu' => 2
                    ]);
                }
            }

            if ($request->day_minggu3) {
                // looping minggu ke 3
                foreach ($request->day_minggu3 as $item) {
                    PLanMarketingDetail::create([
                        'planmarketing_id' => $planmarketing->id,
                        'day_id' => $item,
                        'minggu' => 3
                    ]);
                }
            }

            if ($request->day_minggu4) {
                // looping minggu ke 4 
                foreach ($request->day_minggu4 as $item) {
                    PLanMarketingDetail::create([
                        'planmarketing_id' => $planmarketing->id,
                        'day_id' => $item,
                        'minggu' => 4
                    ]);
                }
            }

            if ($request->day_minggu5) {
                // looping minggu ke 5 
                foreach ($request->day_minggu5 as $item) {
                    PLanMarketingDetail::create([
                        'planmarketing_id' => $planmarketing->id,
                        'day_id' => $item,
                        'minggu' => 5
                    ]);
                }
            }

            // DB::commit();

            return redirect()->back()->with('success-create', 'Data Berhasil Ditambahkan');
        // } catch (Exception $th) {
        //     return redirect()->back()->with('error', $th->getMessage());
        // }
    }


    public function edit($id)
    {
        $title = 'Edit Plan Marketing';
        $outlet = Outlet::get();
        $day = Day::get();
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
            ->where('id', $id)
            ->first();

        return view('sales.planmarketing.edit', compact('planmarketing', 'title', 'outlet', 'day'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $planmarketing = PlanMarketing::where('id', $id)->first();
            $planmarketing->planmarketingdetailminggu1()->delete();
            $planmarketing->planmarketingdetailminggu2()->delete();
            $planmarketing->planmarketingdetailminggu3()->delete();
            $planmarketing->planmarketingdetailminggu4()->delete();
            $planmarketing->planmarketingdetailminggu5()->delete();

            $planmarketing->update([
                'tahun' => $request->tahun,
                'bulan' => $request->bulan,
                'outlet_id' => $request->outlet_id,
            ]);

            // looping minggu ke 1 
            foreach ($request->day_minggu1 as $item) {
                PLanMarketingDetail::create([
                    'planmarketing_id' => $planmarketing->id,
                    'day_id' => $item,
                    'minggu' => 1
                ]);
            }

            // looping minggu ke 2 
            foreach ($request->day_minggu2 as $item) {
                PLanMarketingDetail::create([
                    'planmarketing_id' => $planmarketing->id,
                    'day_id' => $item,
                    'minggu' => 2
                ]);
            }

            // looping minggu ke 3
            foreach ($request->day_minggu3 as $item) {
                PLanMarketingDetail::create([
                    'planmarketing_id' => $planmarketing->id,
                    'day_id' => $item,
                    'minggu' => 3
                ]);
            }

            // looping minggu ke 4 
            foreach ($request->day_minggu4 as $item) {
                PLanMarketingDetail::create([
                    'planmarketing_id' => $planmarketing->id,
                    'day_id' => $item,
                    'minggu' => 4
                ]);
            }

            // looping minggu ke 5 
            foreach ($request->day_minggu5 as $item) {
                PLanMarketingDetail::create([
                    'planmarketing_id' => $planmarketing->id,
                    'day_id' => $item,
                    'minggu' => 5
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success-create', 'Data Berhasil Ditambahkan');
        } catch (Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function destroy(Request $request)
    {
        $planmarketing = PlanMarketing::where('id', $request->id)->first();
        $planmarketing->planmarketingdetailminggu1()->delete();

        $planmarketing->delete();

        return response()->json('Data Berhasil Dihapus');
    }
}
