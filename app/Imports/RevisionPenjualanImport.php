<?php

namespace App\Imports;

use App\Models\FakturPenjualan;
use App\Models\HargaNonExpiredDetail;
use App\Models\Piutang;
use App\Models\Product;
use App\Models\StokExpDetail;
use Maatwebsite\Excel\Concerns\ToModel;

class RevisionPenjualanImport implements ToModel
{
    protected $no = 0;
    public function model(array $row)
    {
        if ($this->no !== 0) {
            //    cek jenis expired                               
            $faktur = FakturPenjualan::where('kode', $row[0])->first();            
            if ($faktur) {
                $piutang = Piutang::where('faktur_penjualan_id', $faktur->id)->update([
                    'status' => 1
                ]);              
            }

        }else{
            $piutang = Piutang::whereYear('tanggal', 2024)->update([
                'status' => 2
            ]);

            $piutang = Piutang::whereYear('tanggal', 2023)->update([
                'status' => 2
            ]);
        }
        $this->no++;
        return;
    }
}


