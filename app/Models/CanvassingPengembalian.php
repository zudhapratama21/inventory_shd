<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CanvassingPengembalian extends Model
{
    use HasFactory,Blameable,SoftDeletes;

    protected $table = 'canvassing_pengembalians';
    protected $fillable = [
        'kode',
        'canvassing_pesanan_id',
        'tanggal',
        'customer_id',
        'keterangan',
        'status'
    ];

   
    public function canvassing()
    {
        return $this->belongsTo(CanvassingPesanan::class, 'canvassing_pesanan_id', 'id');
    }

   
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    
    public function canvassingpengembaliandetail()
    {
        return $this->hasMany(CanvassingPengembalian::class, 'canvassing_pengembalian_id');
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
