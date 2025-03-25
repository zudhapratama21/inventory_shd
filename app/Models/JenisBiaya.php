<?php

namespace App\Models;

use App\Models\Keuangan\SubBiaya;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBiaya extends Model
{
    use HasFactory;

    protected $table = 'jenis_biayas';
    protected $fillable = [
        'nama',
        'no_akun',
        'keterangan'
    ];

  
    public function biayalain()
    {
        return $this->hasMany(BiayaLain::class, 'jenisbiaya_id');
    }

    
    public function biayaoperational()
    {
        return $this->hasMany(BiayaOperational::class, 'jenis_biaya_id');
    }

    
    public function subjenisbiaya()
    {
        return $this->hasMany(SubBiaya::class, 'jenisbiaya_id');
    }
    


}
