<?php

namespace App\Models;

use App\Blameable;
use App\Models\HRD\Divisi;
use App\Models\HRD\Pembuat;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, Blameable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'sales_id',
        'phone',
        'email_verified_at',
        'sales_id',
        'divisi_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


   
    public function tempbiaya()
    {
        return $this->hasMany(TempBiaya::class, 'id');
    }

    
    public function kunjungan()
    {
        return $this->hasMany(KunjunganSales::class, 'user_id');
    }

   
    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }
    
    public function kunjunganteknisi()
    {
        return $this->hasMany(KunjunganTeknisi::class, 'user_id');
    }

    public function rencanakunjungan()
    {
        return $this->hasMany(RencanaKunjungan::class, 'user_id');
    }

   
    public function pembuat()
    {
        return $this->hasOne(Pembuat::class, 'user_id');
    }

 
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id', 'id');
    }
   
}
