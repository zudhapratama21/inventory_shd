<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempPo extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'qty',
        'satuan',
        'hargabeli',
        'diskon_persen',
        'diskon_rp',
        'subtotal',
        'total_diskon',
        'total',
        'ongkir',
        'keterangan',
        'user_id',
        'ppn',
        'beda_satuan',
        'qty_konversi',
        'satuan_konversi'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
