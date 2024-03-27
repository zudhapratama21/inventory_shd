<?php

namespace App\Models\HRD;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';
    protected $fillable = [
        'nip',
        'no_emp',
        'nama',
        'posisi_id',
        'jabatan_id',    
        'email',
        'hp',
        'tanggal_masuk',
        'gaji_pokok',
        'insentif',
        'alamat',
        'rekening',
        'bank',
        'atas_nama',
        'no_ktp',
        'statuskaryawan_id',
        'tempat_lahir',
        'tanggal_lahir',
        'foto_profil',
        'foto_ktp'
    ];

  
    public function posisi()
    {
        return $this->belongsTo(Posisi::class, 'posisi_id', 'id');
    }
   
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id', 'id');
    }

    public function statuskaryawan()
    {
        return $this->belongsTo(StatusKaryawan::class, 'statuskaryawan_id', 'id');
    }

   
    public function user()
    {
        return $this->hasMany(User::class, 'karyawan_id');
    }

  
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'karyawan_id');
    }

}
