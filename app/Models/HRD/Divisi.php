<?php

namespace App\Models\HRD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;
    protected $table = 'divisi';
    protected $fillable = [
        'nama'
    ];

    
    public function posisi()
    {
        return $this->hasMany(Posisi::class, 'posisi_id');
    }

}
