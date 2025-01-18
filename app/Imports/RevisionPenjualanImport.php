<?php

namespace App\Imports;

use App\Models\HargaNonExpiredDetail;
use App\Models\Product;
use App\Models\StokExpDetail;
use Maatwebsite\Excel\Concerns\ToModel;

class RevisionPenjualanImport implements ToModel
{
    protected $no=0;
    public function model(array $row)
    {
        if ($this->no !== 0) {
            //    cek jenis expired 
            $harga = str_replace(",", ".", $row[6]);
            $product = Product::where('id',$row[3])->first();
            if ($product->status_exp == 1) {
                StokExpDetail::where('id',$row[1])->update([
                    'harga_beli' => $harga,
                    'diskon_persen_beli' => $row[8],
                    'diskon_rupiah_beli' => $row[9],
                ]);
            }else{
                HargaNonExpiredDetail::where('id',$row[1])->update([
                    'harga_beli' => $harga,
                    'diskon_persen_beli' => $row[8],
                    'diskon_rupiah_beli' => $row[9],
                ]);
            }
        }
        $this->no ++;
        return ;
    }
}
