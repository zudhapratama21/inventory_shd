<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubBiaya extends Model
{
    use HasFactory;
    protected $table = 'sub_biayas';
    protected $fillable = [
        'nama',        
        'keterangan',
        'jenisbiaya_id'
    ];

}


