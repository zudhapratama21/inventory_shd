<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempKonversiDet extends Model
{
    use HasFactory;
    
    protected $table = 'temp_konversi_dets';
    protected $fillable= [
        'temp_konversi_id',
        'product_id',
        'tanggal',
        'qty',
        'satuan',
        'exp_date',
        'user_id',
        'lot',
        'keterangan',
        'harga_beli',
        'diskon_persen',
        'diskon_rupiah'
    ];


    
    public function tempKonversi()
    {
        return $this->belongsTo(TempKonversi::class, 'konversi_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
