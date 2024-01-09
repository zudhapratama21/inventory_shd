<?php

namespace App\Imports;

use App\Models\Merk;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class MerkImport implements ToModel
{
    protected $no=0;
    public function model(array $row)
    {
        if ($this->no !== 0) {
            Merk::where('id',$row[0])->update([
                'supplier_id' => $row[1]
            ]);
        }                
        $this->no ++;
        return ;
    }
}
