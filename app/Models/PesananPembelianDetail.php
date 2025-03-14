<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesananPembelianDetail extends Model
{
    use HasFactory;
    use Blameable;
    use SoftDeletes;

    protected $fillable = [
        'pesanan_pembelian_id',
        'tanggal',
        'product_id',
        'qty',
        'qty_sisa',
        'satuan',
        'hargabeli',
        'diskon_persen',
        'diskon_rp',
        'subtotal',
        'total_diskon',
        'total',
        'ongkir',
        'keterangan',
        'ppn',
        'hargabeli_ppn',
    ];

    protected $dates = ['tanggal'];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    
    public function pesananpembelian()
    {
        return $this->belongsTo(PesananPembelian::class, 'pesanan_pembelian_id', 'id');
    }

   
    public function temppb()
    {
        return $this->hasOne(TempPb::class, 'pesanan_pembelian_detail_id');
    }
}
