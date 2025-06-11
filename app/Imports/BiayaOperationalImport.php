<?php

namespace App\Imports;

use App\Models\BiayaOperational;
use App\Models\Keuangan\SubBiaya;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use DateTime;

class BiayaOperationalImport implements ToModel
{
    protected $no = 0;

    public function model(array $row)
    {
        if ($this->no !== 0) {
            $tanggal = DateTime::createFromFormat('d/m/Y', $row[0])->format('Y-m-d');    

            $jenisbiaya = SubBiaya::where('id',$row[2])->first();       
            BiayaOperational::create([
                'tanggal' => $tanggal,
                'kode' => $row[1],
                'jenis_biaya_id' => $jenisbiaya->jenisbiaya_id,
                'subjenis_biaya_id' => $jenisbiaya->id,
                'nominal' => $row[3],
                'karyawan_id' => $row[4],
                'bank_id' => $row[5],
                'verified' => 'terima',
                'verified_by' => 3,
                'keterangan' =>$row[6] ,             
            ]);
        }
        $this->no++;
        return;
    }
}
