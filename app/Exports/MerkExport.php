<?php

namespace App\Exports;

use App\Models\Merk;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MerkExport implements FromView
{
  
    public function view() : View
    {
        $merk = Merk::get();
        
        return view('master.merk.laporan.print',[
            'merk' => $merk
        ]);        
    }
}
