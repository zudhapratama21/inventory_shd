<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'product_id',
        'qty',
        'stok',
        'hpp',
        'jenis',
        'jenis_id',
        'customer',
        'supplier',
        'keterangan'
    ];
    protected $dates = ['tanggal'];

   
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
