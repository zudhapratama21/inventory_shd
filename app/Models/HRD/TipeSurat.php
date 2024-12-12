<?php

namespace App\Models\HRD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeSurat extends Model
{
    use HasFactory;
    protected $table = 'tipe_surat';
    protected $fillable = [
        'nama',
        'kode'
    ];
}
