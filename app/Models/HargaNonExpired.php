<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HargaNonExpired extends Model
{
    use HasFactory,Blameable,SoftDeletes;
    protected $table = 'harga_non_expired';

    protected $fillable = [
        'product_id',
        'qty',
        'harga_beli',
        'ppn',
        'diskon_persen',
        'diskon_rupiah',
        'tanggal_transaksi',
        'supplier_id',
        'penerimaanbarang_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function penerimaanbarang()
    {
        return $this->belongsTo(PenerimaanBarang::class, 'penerimaanbarang_id', 'id');
    }

   
    public function harganonexpireddetail()
    {
        return $this->hasMany(HargaNonExpiredDetail::class, 'harganonexpired_id');
    }
    
}
