<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\JenisBiaya;
use App\Models\Keuangan\SubBiaya;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubBiayaController extends Controller
{
  public function index()
  {
    $title = 'Sub Biaya';
    $biaya = JenisBiaya::all();

    return view('keuangan.subbiaya.index', compact('title', 'biaya'));
  }

  public function datatable(Request $request)
  {
    $biaya = SubBiaya::with('jenisbiaya')->orderBy('id', 'desc');
    return DataTables::of($biaya)
      ->addIndexColumn()
      ->editColumn('jenis_biaya', function ($po) {
        return $po->jenisbiaya->nama;
      })
      ->addColumn('action', function ($row) {
        return $row->id;
      })
      ->make(true);
  }
}
