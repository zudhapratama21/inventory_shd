<?php

namespace App\Models;

use App\Blameable;
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
        'nominal',        
        'sales_id',
        'bank_id',
        'verified',
        'verified_by',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];


    public function jenisbiaya()
    {
        return $this->belongsTo(JenisBiaya::class, 'jenis_biaya_id', 'id');
    }

  
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }

   
    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id', 'id');
    }
   

  
 
}
