<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use Blameable;
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'kode',
        'productgroup_id',
        'jenis',
        'merk_id',
        'tipe',
        'ukuran',
        'kemasan',
        'satuan',
        'katalog',
        'asal_negara',
        'pabrikan',
        'no_ijinedar',
        'exp_ijinedar',
        'productcategory_id',
        'productsubcategory_id',
        'hargajual',
        'hargabeli',
        'hpp',
        'diskon_persen',
        'diskon_rp',
        'stok',
        'keterangan',
        'status',
        'status_exp',
        'stok_canvassing',
        'hpp_baru'
    ];
    protected $dates = ['exp_ijinedar'];


    public function merks()
    {
        return $this->belongsTo(Merk::class, 'merk_id', 'id');
    }

    public function groups()
    {
        return $this->belongsTo(Productgroup::class, 'productgroup_id', 'id');
    }

    public function categories()
    {
        return $this->belongsTo(Productcategory::class, 'productcategory_id', 'id');
    }

    public function subcategories()
    {
        return $this->belongsTo(Productsubcategory::class, 'productsubcategory_id', 'id');
    }

    public function temppo()
    {
        return $this->hasMany(TempPo::class, 'product_id', 'id');
    }

    public function podetails()
    {
        return $this->hasMany(PesananPembelianDetail::class, 'product_id', 'id');
    }
    
    public function penerimaanBarang()
    {
        return $this->hasMany(PenerimaanBarangDetail::class, 'product_id');
    }

   
    public function pengirimanBarang()
    {
        return $this->hasMany(PengirimanBarangDetail::class, 'product_id');
    }

    public function pesananPenjualan()
    {
        return $this->hasMany(PesananPenjualanDetail::class, 'product_id');
    }


    public function temppb()
    {
        return $this->hasMany(TempPb::class, 'product_id', 'id');
    }

   
    public function stokExp()
    {
        return $this->hasMany(StokExp::class, 'product_id');
    }

   
    public function harganonexpired()
    {
        return $this->hasMany(HargaNonExpired::class, 'product_id');
    }


    
    public function fakturpenjualandetail()
    {
        return $this->hasMany(FakturPenjualanDetail::class, 'product_id');
    }
}
