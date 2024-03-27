<?php

namespace App\Models\HRD;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cuti extends Model
{
    use HasFactory;
    use Blameable;
    use SoftDeletes;
    
    protected $table = 'cuti';
    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'alasan',
        'bulan',
        'tahun',
        'status'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }
    
    
}
