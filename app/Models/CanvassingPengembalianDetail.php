<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CanvassingPengembalianDetail extends Model
{
    use HasFactory,Blameable,SoftDeletes;

    protected $table = 'canvassing_pengembalian_details';

    protected $fillable  = [
        'canvassing_kembali_id',
        'canvassing_pesanan_detail_id',
        'product_id',
        'tanggal',
        'qty',
        'qty_sisa',
        'qty_kirim',       
        'status_data'
    ];


    
    public function canvassingpengembalian()
    {
        return $this->belongsTo(CanvassingPengembalian::class, 'canvassing_pengembalian_id', 'id');
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

  
    public function canvassingpesanandetail()
    {
        return $this->belongsTo(CanvassingPesananDetail::class, 'canvassing_pesanan_detail_id', 'id');
    }


}
