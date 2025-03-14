<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesananPembelian extends Model
{
    use HasFactory;
    use Blameable;
    use SoftDeletes;

    protected $fillable = [
        'kode',
        'tanggal',
        'supplier_id',
        'kategoripesanan_id',
        'komoditas_id',
        'no_so',
        'top',
        'status_po_id',
        'keterangan',
        'subtotal',
        'diskon_rupiah',
        'diskon_persen',
        'total_diskon_header',
        'total_diskon_detail',
        'total',
        'ongkir',
        'ppn',
        'grandtotal',
        'no_so_customer',
        'keterangan_internal'
    ];
    protected $dates = ['tanggal'];

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function kategoripesanan()
    {
        return $this->belongsTo(Kategoripesanan::class, 'kategoripesanan_id', 'id');
    }

    public function komoditas()
    {
        return $this->belongsTo(Komoditas::class, 'komoditas_id', 'id');
    }

    public function StatusPO()
    {
        return $this->belongsTo(StatusPo::class, 'status_po_id', 'id');
    }

    public function PB()
    {
        return $this->hasMany(PenerimaanBarang::class, 'pesanan_pembelian_id', 'id');
    }

    public function FakturPO()
    {
        return $this->hasMany(FakturPembelian::class, 'pesanan_pembelian_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
   
    public function pesananpembeliandetail()
    {
        return $this->hasMany(PesananPembelianDetail::class, 'pesanan_pembelian_id');
    }
}
