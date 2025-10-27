<?php

namespace App\Models\Keuangan;

use App\Models\Piutang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempBiayaLain extends Model
{
    protected $table = 'temp_biayalain';
    protected $fillable = [
        'id',
        'jenisbiaya_id',
        'piutang_id',        
        'nominal',
        'keterangan',
        'user_id'
    ];

  
    public function piutang()
    {
        return $this->belongsTo(Piutang::class, 'piutang_id', 'id');
    }

    public function jenisbiaya()
    {
        return $this->belongsTo(SubBiaya::class, 'jenisbiaya_id', 'id');
    }
    
}
