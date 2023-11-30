<?php

namespace App\Imports;

use App\Models\Outlet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class OutletImport implements ToModel
{
    
    protected $no=0;
    public function model(array $row)
    {
        if ($this->no !== 0) {
            $nopajak = Outlet::create([
                'nama' => $row[0],
                'area' => $row[1],                
                'sales_id' => $row[2]
            ]);
        }
        $this->no ++;
        return ;
    }
}
