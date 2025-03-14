<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KonversiDetail extends Model
{
    use HasFactory,Blameable,SoftDeletes;

    protected $table = 'konversi_details';

    protected $fillable= [
        'konversi_id',
        'product_id',
        'tanggal',
        'qty',
        'satuan',
        'exp_date',
        'lot',
        'user_id',
        'harga_beli',
        'diskon_persen',
        'diskon_rupiah'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
