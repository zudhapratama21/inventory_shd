<?php

namespace App\Models\HRD;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembuat extends Model
{
    use HasFactory;

    protected $table = 'pembuat';
    protected $fillable = [
        'user_id',
        'inisial'
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
