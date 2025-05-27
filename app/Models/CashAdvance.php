<?php

namespace App\Models;

use App\Blameable;
use App\Models\HRD\Karyawan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashAdvance extends Model
{
    use HasFactory,SoftDeletes,Blameable;
    protected $table = 'cash_advance';
    protected $fillable = [
        'tanggal',
        'kode',
        'karyawan_id',
        'keterangan',
        'nominal',
        'status',        
    ];
    
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }
    public function biayaoperational()
    {
        return $this->hasMany(BiayaOperational::class, 'cashadvance_id', 'id');
    }
}
