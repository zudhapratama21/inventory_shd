<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenerimaanBarang extends Model
{
    use HasFactory;
    use Blameable;
    use SoftDeletes;
    protected $fillable = [
        'kode',
        'tanggal',
        'pesanan_pembelian_id',
        'sj_customer',
        'supplier_id',
        'status_pb_id',
        'status_exp',
        'keterangan',
    ];

    protected $dates = ['tanggal'];

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function StatusPB()
    {
        return $this->belongsTo(StatusPb::class, 'status_pb_id', 'id');
    }

    public function PO()
    {
        return $this->belongsTo(PesananPembelian::class, 'pesanan_pembelian_id', 'id');
    }

    public function FakturPO()
    {
        return $this->hasMany(FakturPembelian::class, 'pesanan_pembelian_id', 'id');
    }

    public function PenerimaanBarangDetails()
    {
        return $this->hasMany(PenerimaanBarangDetail::class, 'penerimaan_barang_id', 'id');
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
