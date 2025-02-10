<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluasi extends Model
{
    use HasFactory,Blameable,SoftDeletes;
    protected $table = 'evaluasi';
    protected $fillable = [
        'sales_id',
        'tanggal',
        'evaluasi',
        'saran'
    ];
   
    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id', 'id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
