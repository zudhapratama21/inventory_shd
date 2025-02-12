<?php

namespace App\Models\HRD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BisaLihat extends Model
{
    use HasFactory;
    protected $table = 'bisalihat';
    protected $fillable = [
        'pengumuman_id',
        'divisi_id'
    ];

    public function pengumuman()
    {
        return $this->belongsTo(Pengumuman::class, 'pengumuman_id', 'id');
    }

   
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id', 'id');
    }

    
}
