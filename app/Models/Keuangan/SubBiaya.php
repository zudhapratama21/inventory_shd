<?php

namespace App\Models\Keuangan;

use App\Models\JenisBiaya;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubBiaya extends Model
{
    use HasFactory;
    protected $table = 'sub_biaya';
    protected $fillable = [
        'nama',        
        'keterangan',
        'no_akun',
        'jenisbiaya_id'
    ];

    public function jenisbiaya()
    {
        return $this->belongsTo(JenisBiaya::class, 'jenisbiaya_id', 'id');
    }

}


