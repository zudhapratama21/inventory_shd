<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\HRD\Pembuat;
use App\Models\User;
use Illuminate\Http\Request;

class PembuatController extends Controller
{
    public function index ()
    {
        $title = 'Pembuat';
        $pembuat  = Pembuat::with('user')->get();

        // dd($pembuat);
        $user = User::get();
        return view('hrd.pembuat.index',compact('title','pembuat','user'));
    }  
    
    public function create ()
    {
        $title = 'Pembuat';
        return view('hrd.pembuat.create',compact('title'));
    }

    public function store (Request $request)
    {
        
        $data = Pembuat::create([
            'user_id' => $request->user_id,
            'inisial' => $request->inisial
        ]);

        return back();
    }

    public function update (Request $request,$id)
    {
        
        $data = Pembuat::where('id',$id)->update([
            'user_id' => $request->user_id,
            'inisial' => $request->inisial
        ]);

        return back();
    }


    public function delete ($id)
    {
        Pembuat::where('id',$id)->delete();

        return back();

    }
}
