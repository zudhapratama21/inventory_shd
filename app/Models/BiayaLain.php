<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiayaLain extends Model
{
    use HasFactory,Blameable,SoftDeletes;
    protected $table = 'biaya_lains';
    protected $fillable = [
        'jenisbiaya_id',
        'fakturpenjualan_id',
        'nominal',
        'request',
        'keterangan',
        'pengurangan_cn'
    ];

    public function fakturpenjualan()
    {
        return $this->belongsTo(FakturPenjualan::class, 'fakturpenjualan_id', 'id');
    }

   
    public function jenisbiaya()
    {
        return $this->belongsTo(JenisBiaya::class, 'jenisbiaya_id', 'id');
    }
    
}
