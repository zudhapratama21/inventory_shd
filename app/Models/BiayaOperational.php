<?php

namespace App\Models;

use App\Blameable;
use App\Models\HRD\Karyawan;
use App\Models\Keuangan\SubBiaya;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiayaOperational extends Model
{
    use HasFactory,Blameable,SoftDeletes;

    protected $table = 'biaya_operationals';            
    protected $fillable = [
        'tanggal',
        'kode',
        'jenis_biaya_id',
        'subjenis_biaya_id',
        'nominal',        
        'karyawan_id',
        'bank_id',
        'verified',
        'verified_by',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by',
        'cashadvance_id',
    ];


    public function jenisbiaya()
    {
        return $this->belongsTo(JenisBiaya::class, 'jenis_biaya_id', 'id');
    }

    public function subbiaya()
    {
        return $this->belongsTo(SubBiaya::class, 'subjenis_biaya_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }

   
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }

     public function cashadvance()
    {
        return $this->belongsTo(CashAdvance::class, 'cashadvance_id', 'id');
    }

}
