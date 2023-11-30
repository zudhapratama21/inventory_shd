<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;
    protected $table = 'outlets';
    protected $fillable = [
        'nama',        
        'area',
        'sales_id'        
    ];

   
    public function user()
    {
        return $this->belongsTo(User::class, 'sales_id', 'id');
    }
    
    public function planmarketing()
    {
        return $this->hasMany(PlanMarketing::class, 'outlet_id');
    }

    
}
