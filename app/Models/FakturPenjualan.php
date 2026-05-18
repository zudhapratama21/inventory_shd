<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FakturPenjualan extends Model
{
    use HasFactory;
    use Blameable;
    use SoftDeletes;

    protected $fillable = [
        'kode',
        'tanggal',
        'tanggal_jatuh_tempo',
        'customer_id',  
        'no_urut',
        'no_perusahaan',        
        'sales_id',
        'komoditas_id',
        'kategoripesanan_id',
        'no_sp_customer',
        'tanggal_sp_customer',       
        'keterangan',
        'subtotal',        
        'total_diskon',
        'total',
        'ongkir',
        'ppn',
        'grandtotal',           
        'cn',
        'total_cn',
        'materai',
        'tanggal_diterima',
        'status_diterima',
        'foto_bukti',
        'no_resi',        
    ];    

    public function customers()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function fakturpenjualandetail()
    {
        return $this->hasMany(FakturPenjualanDetail::class, 'faktur_penjualan_id');
    }

    public function piutang()
    {
        return $this->hasMany(Piutang::class, 'faktur_penjualan_id');
    }
}
