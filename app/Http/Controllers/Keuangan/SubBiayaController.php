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
      ->editColumn('jenisbiaya', function ($po) {
        return $po->jenisbiaya->nama;
      })
      ->addColumn('action', function ($row) {
        return $row->id;
      })
      ->make(true);
  }

  public function store(Request $request)
  {
      SubBiaya::create([
        'nama' => $request->nama,
        'keterangan' => $request->keterangan,
        'no_akun' => $request->no_akun,
        'jenisbiaya_id' => $request->jenisbiaya
      ]);

      return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan']);
  }

  public function delete(Request $request)
  {
     SubBiaya::destroy($request->id);
     return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
  }
}
