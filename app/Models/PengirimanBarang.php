<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengirimanBarang extends Model
{
    use HasFactory;
    use Blameable;
    use SoftDeletes;

    protected $fillable = [
        'kode',
        'tanggal',
        'pesanan_penjualan_id',
        'customer_id',
        'status_sj_id',
        'status_exp',
        'keterangan',
    ];

    protected $dates = ['tanggal'];

    public function customers()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function StatusSJ()
    {
        return $this->belongsTo(StatusSj::class, 'status_sj_id', 'id');
    }

    public function SO()
    {
        return $this->belongsTo(PesananPenjualan::class, 'pesanan_penjualan_id', 'id');
    }

    public function FakturSO()
    {
        return $this->hasMany(FakturPenjualan::class, 'pengiriman_barang_id');
    }

    public function PengirimanBarangDetails()
    {
        return $this->hasMany(PengirimanBarangDetail::class, 'pengiriman_barang_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
