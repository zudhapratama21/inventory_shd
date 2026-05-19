<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FakturPenjualanDetail extends Model
{
    use HasFactory;
        
    protected $table = 'faktur_penjualan_details';
    protected $fillable = [
        'faktur_penjualan_id',
        'product_id',
        'pengirimanbarang_id',
        'pengiriman_barang_detail_id',
        'qty',
        'ppn',
        'hargajual',
        'diskon_persen',
        'diskon_rp',
        'subtotal',
        'total_diskon',
        'total',
        'keterangan',
        'beda_satuan',
        'satuan',
        'satuan_konversi',
        'qty_konversi',        
    ];

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
