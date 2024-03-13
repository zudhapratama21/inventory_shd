<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KunjunganSales extends Model
{
    use HasFactory,Blameable,SoftDeletes;

    protected $table = 'kunjungan_sales';
    protected $fillable = [
        'customer',
        'tanggal',
        'aktifitas',
        'ttd',
        'image',
        'user_id',
        'jam_buat',
        'hari_buat'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    
}
