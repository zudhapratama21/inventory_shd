<?php

namespace App\Models\HRD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeFasilitas extends Model
{
    use HasFactory;
    protected $table = 'tipe_fasilitas';
    protected $fillable = [
        'nama'
    ];

    
}
