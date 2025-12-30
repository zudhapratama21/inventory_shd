<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FakturPembelian extends Model
{
    use HasFactory;
    use Blameable;
    use SoftDeletes;

    protected $fillable = [
        'kode',
        'tanggal',
        'supplier_id',
        'pesanan_pembelian_id',
        'penerimaan_barang_id',
        'status_fakturpo_id',
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
        'biaya_lain',
        'no_faktur_supplier'
    ];

    protected $dates = ['tanggal'];

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function StatusFB()
    {
        return $this->belongsTo(StatusFakturpos::class, 'status_fakturpo_id', 'id');
    }

    public function PO()
    {
        return $this->belongsTo(PesananPembelian::class, 'pesanan_pembelian_id', 'id');
    }
    public function PB()
    {
        return $this->belongsTo(PenerimaanBarang::class, 'penerimaan_barang_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    
    public function hutangs()
    {
        return $this->hasMany(Hutang::class, 'faktur_pembelian_id');
    }
}
