<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PLanMarketingDetail extends Model
{
    use HasFactory,Blameable,SoftDeletes;
    protected $table = 'plan_marketings_detail';
    protected $fillable = [
        'planmarketing_id',
        'day_id',
        'minggu'
    ];
     
    public function planmarketing()
    {
        return $this->belongsTo(PlanMarketing::class, 'planmarketing_id','id');
    }

    
    public function day()
    {
        return $this->belongsTo(Day::class, 'day_id', 'id');
    }
}
