<?php

namespace App\Models\HRD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;
    protected $table = 'topic';
    protected $fillable = [
        'nama'
    ];


    public function pengumuman()
    {
        return $this->hasMany(Pengumuman::class, 'topic_id');
    }
}
