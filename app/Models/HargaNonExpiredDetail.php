<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HargaNonExpiredDetail extends Model
{
    use HasFactory,Blameable,SoftDeletes; 
    protected $table = 'harga_non_expired_detail';
    protected $fillable = [
        'tanggal',
        'harganonexpired_id',
        'product_id',
        'qty',
        'id_pb',
        'id_pb_detail',
        'id_sj',
        'id_sj_detail',
        'konversi_id',
        'konversi_detail_id',
        'canvassing_id',
        'canvassing_detail_id',
        'canvassingkembali_id',
        'canvassingkembali_detail_id',
        'harga_jual',
        'harga_beli',
        'diskon_persen_jual',
        'diskon_rupiah_jual',
        'diskon_persen_beli',
        'diskon_rupiah_beli',
        'harganonexpdetail_id'
    ];
 
    public function harganonexpired()
    {
        return $this->belongsTo(HargaNonExpired::class, 'harganonexpired_id', 'id');
    }

 
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

   
    public function penerimaanbarang()
    {
        return $this->belongsTo(PenerimaanBarang::class, 'id_pb', 'id');
    }

    public function penerimaanbarangdetail()
    {
        return $this->belongsTo(PenerimaanBarangDetail::class, 'id_pb_detail', 'id');
    }

    public function pengirimanbarang()
    {
        return $this->belongsTo(PengirimanBarang::class, 'id_sj', 'id');
    }

    public function pengirimanbarangdetail()
    {
        return $this->belongsTo(PengirimanBarangDetail::class, 'id_sj_detail', 'id');
    }



    
}
