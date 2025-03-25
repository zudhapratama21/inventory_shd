<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\JenisBiaya;
use Illuminate\Http\Request;

class SubBiayaController extends Controller
{
    public function index ()
    {
      $title = 'Sub Biaya';
      $biaya = JenisBiaya::all();

      return view('keuangan.subbiaya.index', compact('title', 'biaya'));
    }
}
