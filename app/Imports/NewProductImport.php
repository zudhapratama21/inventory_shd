<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\TempProduct;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class NewProductImport implements ToModel
{
    protected $no=0;
    
    
    public function model(array $row)
    {
        
        if ($this->no !== 0) {                           
              $product = Product::where('kode', $row[0])->update([
                'kode_item' => $row[2]
              ]);
        }
        $this->no++;

        return ;
    }
}
