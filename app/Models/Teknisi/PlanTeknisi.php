<?php

namespace App\Models\Teknisi;

use App\Blameable;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanTeknisi extends Model
{
    use HasFactory, SoftDeletes, Blameable;
    protected $table = 'plan_teknisi';
    protected $fillable = [
        'tanggal',
        'outlet_id',
        'user_id',
        'created_by',
        'deleted_by',
        'updated_by'
    ];

  
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

  
    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id', 'id');
    }
    
}
