<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Evaluasi;
use App\Models\Sales;
use App\Traits\CodeTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EvaluasiController extends Controller
{
    use CodeTrait;
    public function index()
    {
        $title = 'Evaluasi Sales';
        $sales = Sales::get();
        return view('sales.evaluasi.index', compact('title', 'sales'));
    }

    public function store(Request $request)
    {
        Evaluasi::create([
            'sales_id' => $request->sales,
            'tanggal' => now()->format('Y-m-d'),
            'evaluasi' => $request->evaluasi,
            'saran' => $request->saran
        ]);

        return response()->json('Data Berhasil Ditambahkan');
    }

    public function datatable(Request $request)
    {
        $evaluasi = Evaluasi::with('sales','pembuat')->orderBy('id','desc');
        return DataTables::of($evaluasi)
            ->addIndexColumn()
            ->editColumn('tanggal', function (Evaluasi $ot) {
                return Carbon::parse($ot->tanggal)->format('d F Y');
            })
            ->editColumn('pembuat', function (Evaluasi $ot) {
                return $ot->pembuat->name;
            })
            ->editColumn('sales', function (Evaluasi $ot) {
                return $ot->sales->nama;
            })
            ->editColumn('evaluasi', function (Evaluasi $ot) {
                $text = $ot->evaluasi;
                return view('sales.evaluasi.partial.text',compact('text'));
            })
            ->editColumn('saran', function (Evaluasi $ot) {
                $text = $ot->saran;
                return view('sales.evaluasi.partial.text',compact('text'));
            })
            ->addColumn('action', function ($row) {
                $id = $row->id;
                return view('sales.evaluasi.partial.action', compact('id'));
            })
            ->make(true);
    }

    public function edit (Request $request)
    {
       $sales = Sales::get();
       $evaluasi = Evaluasi::where('id',$request->id)->first();

       return view('sales.evaluasi.partial.modaledit',compact('sales','evaluasi'));
    
    }

    public function update (Request $request)
    {
        Evaluasi::where('id',$request->id)->update([
            'sales_id' => $request->sales,
            'tanggal' => now()->format('Y-m-d'),
            'evaluasi' => $request->evaluasi,
            'saran' => $request->saran
        ]);

       return response()->json('Data Berhasil diubah');
    }

    public function destroy (Request $request)
    {
       Evaluasi::where('id',$request->id)->delete();
       return response()->json('Data Berhasil Ditambahkan');

    }
}
