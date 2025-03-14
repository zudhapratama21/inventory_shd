<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempSj extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'pesanan_penjualan_detail_id',
        'qty',
        'qty_sisa',
        'qty_pesanan',
        'satuan',
        'keterangan',
        'user_id',
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
   
    public function sodet()
    {
        return $this->belongsTo(PesananPenjualanDetail::class, 'pesanan_penjualan_detail_id', 'id');
    }
}
