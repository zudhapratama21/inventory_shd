<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Evaluasi;
use App\Models\Sales;
use App\Traits\CodeTrait;
use Illuminate\Http\Request;

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
}
