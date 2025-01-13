<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FakturPenjualanDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id']; 

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

   
    public function fakturpenjualan()
    {
        return $this->belongsTo(FakturPenjualan::class, 'faktur_penjualan_id', 'id');
    }

    
    public function pengirimanbarangdetail()
    {
        return $this->belongsTo(PengirimanBarangDetail::class, 'pengiriman_barang_detail_id', 'id');
    }
}
