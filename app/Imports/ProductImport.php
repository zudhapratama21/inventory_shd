<?php

namespace App\Imports;

use App\Models\AdjustmentStok;
use App\Models\HargaNonExpired;
use App\Models\HargaNonExpiredDetail;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Traits\CodeTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Str;

class ProductImport implements ToModel
{
    protected $no = 0;
    use CodeTrait;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        DB::beginTransaction();
        try {
            if ($this->no !== 0) {
                // cek produk berdasarkan kode 
                $product = Product::where('kode', $row[0])->first();
                // cek stok berdasarkan qty
                // kurangi dari qty inputan - stok 
                $stok = $product->stok;
                $tanda = 1;
                if ($stok < 0) {
                    $tanda = -1;
                }

                $tahun = Carbon::now()->format('y');
                $bulan = Carbon::now()->format('m');

                $kode = 'AJS' . $tahun . $bulan . rand(1000, 9999);

                if ($product->status_exp == 0) {

                    if ($this->no == 1) {
                        HargaNonExpired::where('product_id', $product->id)->update([
                            'qty' => 0
                        ]);
                    }

                    $harganonexpired = HargaNonExpired::create([
                        'product_id' => $product->id,
                        'qty' => $row[2],
                        'harga_beli' => $row[3],
                        'diskon_persen' => $row[4],
                        'diskon_rupiah' => $row[5],
                        'tanggal_transaksi' => now()->format('Y-m-d'),
                    ])->id;

                    HargaNonExpiredDetail::create([
                        'tanggal' => now()->format('Y-m-d'),
                        'harganonexpired_id' => $harganonexpired,
                        'product_id' => $product->id,
                        'qty' => $row[2],                        
                        'harga_beli' => $row[3],                        
                        'diskon_persen_beli' => $row[4],
                        'diskon_rupiah_beli' => $row[5]
                    ]);



                    // save di tabel adjustmen stok
                    $ajs = AdjustmentStok::create([
                        'product_id' => $product->id,
                        'qty' => $stok,
                        'jenis' => 'nonexpired',
                        'kode' => $kode
                    ]);

                    // perubahan simpan di inventory transaction
                    $inv = InventoryTransaction::create([
                        'tanggal' => Carbon::now()->format('Y-m-d'),
                        'product_id' => $product->id,
                        'qty' => $stok * $tanda,
                        'stok' => $row[2],
                        'hpp' => $product->hpp,
                        'jenis' => 'AJS',
                        'jenis_id' => $kode,
                    ]);

                    $product->update([
                        'stok' => $row[2]
                    ]);
                }
            }

            DB::commit();

            $this->no++;

            return;
        } catch (Exception $th) {
            DB::rollBack();
        }
    }
}
