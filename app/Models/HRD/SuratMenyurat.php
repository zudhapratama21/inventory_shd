<?php

namespace App\Models\HRD;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratMenyurat extends Model
{
    use HasFactory,SoftDeletes,Blameable;

    protected $table = 'surat_menyurat';
    protected $fillable = [
        'tanggal',
        'kode',
        'pembuat_id',
        'tipesurat_id',
        'kepada',
        'isi',
        'status',
        'file',
        'request'
    ];

  
    public function pembuat()
    {
        return $this->belongsTo(Pembuat::class, 'pembuat_id', 'id');
    }

    public function tipesurat()
    {
        return $this->belongsTo(TipeSurat::class, 'tipesurat_id', 'id');
    }
    
}
