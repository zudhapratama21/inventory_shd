<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FakturPembelianDetail extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

   
    public function fakturpembelian()
    {
        return $this->belongsTo(FakturPembelian::class, 'faktur_pembelian_id', 'id');
    }
}
