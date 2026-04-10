<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenerimaanBarangDetail extends Model
{
    use HasFactory;
    use Blameable;
    use SoftDeletes;
    protected $fillable = [
        'tanggal',
        'penerimaan_barang_id',
        'pesanan_pembelian_id',
        'pesanan_pembelian_detail_id',
        'product_id',
        'qty',
        'qty_sisa',
        'qty_pesanan',
        'satuan',
        'keterangan',
        'status_exp',
        'beda_satuan',
        'satuan_konversi',
        'qty_konversi'
    ];

    protected $dates = ['tanggal'];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function PenerimaanBarangs()
    {
        return $this->belongsTo(PenerimaanBarang::class, 'penerimaan_barang_id', 'id');
    }
}
