<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RencanaKunjungan extends Model
{
    use HasFactory,SoftDeletes,Blameable;

    protected $table = 'rencana_kunjungan';
    protected $fillable = [
        'outlet_id',
        'tanggal',
        'aktivitas',
        'updated_by',
        'deleted_by',
        'created_by',
        'user_id',
        'jam_buat'
    ];

   
    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
}
