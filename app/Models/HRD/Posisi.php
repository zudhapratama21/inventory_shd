<?php

namespace App\Models\HRD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posisi extends Model
{
    use HasFactory;
    protected $table = 'posisi';
    
    protected $fillable = [
        'nama',
        'divisi_id'
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id','id');
    }
    
    
}
