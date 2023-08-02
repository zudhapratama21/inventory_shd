<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokExp extends Model
{
    use HasFactory;
    use Blameable;
    use SoftDeletes;
    protected $fillable = [
        'tanggal',
        'product_id',
        'qty',
        'lot',
        'ppn',
        'harga_beli',
        'diskon_persen',
        'diskon_rupiah'
    ];

    protected $dates = ['tanggal'];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

   
    public function stokExpDetail()
    {
        return $this->hasMany(StokExpDetail::class, 'stok_exp_id', 'id');
    }
}
