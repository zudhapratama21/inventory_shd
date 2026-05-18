<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piutang extends Model
{
    use HasFactory;
    protected $table= 'piutangs';
    
    protected $fillable = [
        'tanggal',
        'customer_id',                
        'faktur_penjualan_id',
        'dpp',
        'ppn',
        'total',
        'dibayar',
        'status',
        'nominal_toleransi',
        'tanggal_top'
    ];

    protected $dates = ['tanggal'];

    public function customers()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function fakturpenjualan()
    {
        return $this->belongsTo(FakturPenjualan::class, 'faktur_penjualan_id', 'id');
    }   
}
