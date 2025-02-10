<?php

namespace App\Models\HRD;

use App\Blameable;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengumuman extends Model
{
    use Blameable,SoftDeletes;
    protected $table = 'pengumuman';
    protected $fillable = [
        'topic_id',
        'subject',
        'description',
        'file',        
    ];
    
    public function bisalihat()
    {
        return $this->hasMany(BisaLihat::class, 'pengumuman_id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'id');
    }

    
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
