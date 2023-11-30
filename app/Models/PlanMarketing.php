<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanMarketing extends Model
{
    use HasFactory,SoftDeletes,Blameable;
    protected $table = 'plan_marketings';
    protected $fillable = [
        'tahun',
        'bulan',
        'outlet_id',
        'user_id',
        'created_by',
        'deleted_by',
        'updated_by'
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id', 'id');
    }

   
    public function planmarketingdetailminggu1()
    {
        return $this->hasMany(PLanMarketingDetail::class, 'planmarketing_id');
    }

    public function planmarketingdetailminggu2()
    {
        return $this->hasMany(PLanMarketingDetail::class, 'planmarketing_id');
    }

    public function planmarketingdetailminggu3()
    {
        return $this->hasMany(PLanMarketingDetail::class, 'planmarketing_id');
    }

    public function planmarketingdetailminggu4()
    {
        return $this->hasMany(PLanMarketingDetail::class, 'planmarketing_id');
    }

    public function planmarketingdetailminggu5()
    {
        return $this->hasMany(PLanMarketingDetail::class, 'planmarketing_id');
    }

   
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
