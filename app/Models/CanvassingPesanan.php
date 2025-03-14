<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CanvassingPesanan extends Model
{
    use HasFactory,Blameable,SoftDeletes;
    protected $table ='canvassing_pesanans';

    protected $fillable=[
        'kode',
        'kode_pesanan',
        'tanggal',
        'customer_id',   
        'qty',
        'status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    
    public function canvassingdetail()
    {
        return $this->hasMany(CanvassingPesananDetail::class, 'canvassing_pesanan_id');
    }

    
    
}
