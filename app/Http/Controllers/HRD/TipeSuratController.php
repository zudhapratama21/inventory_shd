<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\HRD\TipeSurat;
use Illuminate\Http\Request;

class TipeSuratController extends Controller
{
    public function index ()
    {
        $title = 'Tipe Surat';
        $tipesurat = TipeSurat::get();
        return view('hrd.tipesurat.index',compact('title','tipesurat'));
    } 

    public function store (Request $request)
    {
       $tipesurat =  TipeSurat::create([
            'nama' => $request->nama,
            'kode' => $request->kode
       ]);

       return back();
    }

    public function update (Request $request , $id)
    {
        $tipesurat = TipeSurat::where('id',$id)->update([
            'nama' => $request->nama,
            'kode' => $request->kode
        ]);

        return back();
    }

    public function delete ($id)
    {
        TipeSurat::where('id',$id)->delete();

        return back();
    }
}
