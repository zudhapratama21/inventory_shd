<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesananPenjualan extends Model
{
    use HasFactory;
    use Blameable;
    use SoftDeletes;

    protected $fillable = [
        'kode',
        'tanggal',
        'customer_id',
        'kategoripesanan_id',
        'komoditas_id',
        'no_so',
        'top',
        'status_so_id',
        'pemesan',
        'ppk',
        'tahun_anggaran',
        'sumber_dana',
        'nama_paket',
        'id_paket',
        'supplier_id',
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
        'sales_id',
        'tanggal_pesanan_customer',
        'keterangan_internal'
    ];
    protected $dates = ['tanggal'];

    public function customers()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function kategoripesanan()
    {
        return $this->belongsTo(Kategoripesanan::class, 'kategoripesanan_id', 'id');
    }

    public function komoditas()
    {
        return $this->belongsTo(Komoditas::class, 'komoditas_id', 'id');
    }

    public function StatusSO()
    {
        return $this->belongsTo(StatusSo::class, 'status_so_id', 'id');
    }

    public function SJ()
    {
        return $this->hasMany(PengirimanBarang::class, 'pesanan_penjualan_id', 'id');
    }

    public function FakturSO()
    {
        return $this->hasMany(FakturPenjualan::class, 'pesanan_penjualan_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id', 'id');
    }

   
    public function pesananpenjualandetail()
    {
        return $this->hasMany(PesananPenjualanDetail::class, 'pesanan_penjualan_id');
    }
}
