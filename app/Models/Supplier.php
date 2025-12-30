<?php

namespace App\Models;

use App\Blameable;
use App\Models\Supplier_category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

class Supplier extends Model
{
    use HasFactory;
    use Blameable;
    use SoftDeletes;

    protected $fillable = [
        'kode',
        'nama',
        'alamat',
        'blok',
        'nomor',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'kodepos',
        'tlp',
        'email',
        'npwp',
        'kategori_id',
        'keterangan'
    ];

    public function kategori()
    {
        return $this->belongsTo(Supplier_category::class, 'kategori_id', 'id');
    }

    public function kelurahans()
    {
        return $this->belongsTo(Village::class, 'kelurahan', 'id');
    }

    public function kecamatans()
    {
        return $this->belongsTo(District::class, 'kecamatan', 'id');
    }

    public function namakota()
    {
        return $this->belongsTo(City::class, 'kota', 'id');
    }


    public function prov()
    {
        return $this->belongsTo(Province::class, 'provinsi', 'id');
    }

    public function pesananpembelian()
    {
        return $this->hasMany(PesananPembelian::class, 'supplier_id', 'id');
    }

    public function penerimaanbarang()
    {
        return $this->hasMany(PenerimaanBarang::class, 'supplier_id', 'id');
    }

    public function fakturpembelian()
    {
        return $this->hasMany(FakturPembelian::class, 'supplier_id', 'id');
    }

    public function hutangs()
    {
        return $this->hasMany(Hutang::class, 'supplier_id');
    }

   
    public function merk()
    {
        return $this->hasMany(Merk::class, 'supplier_id');
    }
}
