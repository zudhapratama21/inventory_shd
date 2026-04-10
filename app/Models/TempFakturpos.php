<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempFakturpos extends Model
{
    use HasFactory;
    protected $fillable = [

        'penerimaan_barang_id',
        'penerimaan_barang_detail_id',
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
        'beda_satuan',
        'qty_konversi',
        'satuan_konversi'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
