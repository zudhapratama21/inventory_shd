<?php

namespace App\Models\HRD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingCuti extends Model
{
    use HasFactory;
    protected $table = 'setting_cuti';
    protected $fillable = [
        'tanggal',
        'keterangan'
    ];
}
