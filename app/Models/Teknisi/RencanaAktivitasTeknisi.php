<?php

namespace App\Models\Teknisi;

use App\Blameable;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RencanaAktivitasTeknisi extends Model
{
    use HasFactory,SoftDeletes,Blameable;

    protected $table = 'rencana_aktivitas_teknisi';
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

    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }  
    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id', 'id');
    }

}
