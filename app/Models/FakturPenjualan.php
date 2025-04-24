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
        'customer_id',
        'pesanan_penjualan_id',
        'pengiriman_barang_id',
        'status_fakturso_id',
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
        'no_kpa',
        'pajak_id',
        'total_cn',
        'biaya_lain',
        'no_seri_pajak',
        'no_pajak',
        'tanggal_diterima',
        'status_diterima',
        'foto_bukti',
        'no_resi',
        'status_tanggaltop'
    ];

    protected $dates = ['tanggal'];

    public function customers()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function StatusFJ()
    {
        return $this->belongsTo(StatusFaktursos::class, 'status_fakturso_id', 'id');
    }

    public function SO()
    {
        return $this->belongsTo(PesananPenjualan::class, 'pesanan_penjualan_id', 'id');
    }
    public function SJ()
    {
        return $this->belongsTo(PengirimanBarang::class, 'pengiriman_barang_id', 'id');
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


    public function nopajak()
    {
        return $this->belongsTo(NoFakturPajak::class, 'pajak_id', 'id');
    }


    public function piutang()
    {
        return $this->hasMany(Piutang::class, 'faktur_penjualan_id');
    }
}
