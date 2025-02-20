<?php

namespace App\Models\HRD;

use App\Blameable;
use App\Models\Sales;
use App\Models\User;
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
        'request',
        'publish',
        'keterangan'
    ];

  
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function tipesurat()
    {
        return $this->belongsTo(TipeSurat::class, 'tipesurat_id', 'id');
    }

   
    public function request()
    {
        return $this->belongsTo(User::class, 'request', 'id');
    }
    
}
