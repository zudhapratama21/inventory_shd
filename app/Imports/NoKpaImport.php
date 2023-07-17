<?php

namespace App\Imports;

use App\Models\NoKPA;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;

class NoKpaImport implements ToModel
{
    protected $no=0;
    public function model(array $row)
    {
        if ($this->no !== 0) {
            $now = now()->format('y');
            $nopajak = NoKPA::create([
                'no_kpa' => $row[0].'-'.$now.'/BSB',
                'status' => 'Aktif',                
            ]);
        }
        $this->no ++;
        return ;
    }
}
