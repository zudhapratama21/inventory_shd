<?php

namespace App\Models\Keuangan;

use App\Models\PembayaranPiutang;
use App\Models\Piutang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPiutangLain extends Model
{
    protected $table = 'pembayaran_piutang_lain';
    protected $fillable = [
        'jenisbiaya_id',
        'pembayaranpiutang_id',
        'piutang_id',
        'nominal',
        'keterangan',
        'keterangan',
        'user_id'
    ];

    
    public function jenisbiaya()
    {
        return $this->belongsTo(SubBiaya::class, 'jenisbiaya_id', 'id');
    }

   
    public function pembayaranpiutang()
    {
        return $this->belongsTo(PembayaranPiutang::class, 'pembayaranpiutang_id', 'id');
    }
    
    public function piutang()
    {
        return $this->belongsTo(Piutang::class, 'piutang_id', 'id');
    }
}
