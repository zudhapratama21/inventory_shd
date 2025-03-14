<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CanvassingPesananDetail extends Model
{
    use HasFactory,Blameable,SoftDeletes;

    protected $table ='canvassing_pesanan_details';

    protected $fillable = [
        'canvassing_pesanan_id',
        'product_id',
        'tanggal',
        'qty',
        'qty_sisa',
        'keterangan',
        'status_data'
    ];

    
    public function canvassing()
    {
        return $this->belongsTo(CanvassingPesanan::class, 'canvassing_pesanan_id', 'id');
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

    public function tempcanvaskembali()
    {
        return $this->hasOne(TempCanvasPengembalian::class, 'canvassing_pesanan_id');
    }

   
    
    public function canvassingpengembaliandet()
    {
        return $this->hasMany(CanvassingPesananDetail::class, 'canvassing_pesanan_detail_id');
    }
    
    
}
