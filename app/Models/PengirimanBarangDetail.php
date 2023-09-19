<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengirimanBarangDetail extends Model
{
    use HasFactory;
    use Blameable;
    use SoftDeletes;

    protected $fillable = [
        'tanggal',
        'pengiriman_barang_id',
        'pesanan_penjualan_id',
        'pesanan_penjualan_detail_id',
        'product_id',
        'qty',
        'qty_sisa',
        'qty_pesanan',
        'satuan',
        'keterangan',
        'status_exp'
    ];
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function PengirimanBarangs()
    {
        return $this->belongsTo(PengirimanBarang::class, 'pengiriman_barang_id', 'id');
    }

    
    public function pesananpenjualan()
    {
        return $this->belongsTo(PesananPenjualan::class, 'pesanan_penjualan_id', 'id');
    }

    
    public function faktupenjualandetail()
    {
        return $this->hasOne(FakturPenjualanDetail::class, 'pengiriman_barang_detail_id');
    }

   
   
    public function stokexpdetail()
    {
        return $this->hasMany(StokExpDetail::class, 'id_sj_detail');
    }

  
    public function harganonexpireddetail()
    {
        return $this->hasMany(HargaNonExpiredDetail::class, 'id_sj_detail');
    }
}
