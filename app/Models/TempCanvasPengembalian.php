<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempCanvasPengembalian extends Model
{
    use HasFactory;

    protected $table = 'temp_canvas_pengembalians';
    protected $fillable = [
        'canvassing_pesanan_id',  
        'canvassing_pesanan_detail_id',      
        'product_id',
        'tanggal',
        'qty',
        'qty_sisa',
        'qty_kirim',
        'user_id',
        'keterangan'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }



}
