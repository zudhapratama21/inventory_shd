<?php

namespace App\Models\HRD;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Absensi extends Model
{
    use HasFactory,Blameable,SoftDeletes;

    protected $table ='absensi';
    protected $fillable = [
        'karyawan_id',
        'clock_in',
        'clock_out',
        'work_time',
        'tanggal',
        'status',
        'keterangan'
    ];

   
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }


}
