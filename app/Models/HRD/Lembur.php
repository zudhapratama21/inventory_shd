<?php

namespace App\Models\HRD;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lembur extends Model
{
    use HasFactory,SoftDeletes,Blameable;
    protected $table = 'lembur';
    protected $fillable = [
        'karyawan_id',
        'penanggungjawab_id',
        'tugas',
        'tanggal',
        'nominal_gaji',
        'jumlah_jam'
    ];

   
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }

    public function penanggungjawab()
    {
        return $this->belongsTo(Karyawan::class, 'penanggungjawab_id', 'id');
    }    


}
