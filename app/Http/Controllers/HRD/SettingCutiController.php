<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\HRD\SettingCuti;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SettingCutiController extends Controller
{
    public function index ()
    {
        $title = 'Setting Cuti';
        $settingcuti = SettingCuti::orderBy('id','desc')->get();

        return view('hrd.settingcuti.index',compact('title','settingcuti'));
    }

    public function store (Request $request)
    {
        SettingCuti::create([
            'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
            'keterangan' => $request->keterangan
        ]);

        return back();
    }

    public function update (Request $request,$id)
    {
        SettingCuti::where('id',$id)->update([
            'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
            'keterangan' => $request->keterangan
        ]);

        return back();
    }

    public function destroy ($id)
    {
        SettingCuti::where('id',$id)->delete();
        return back();
    }

    
}
