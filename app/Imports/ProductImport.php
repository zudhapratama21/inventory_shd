<?php

namespace App\Imports;

use App\Models\AdjustmentStok;
use App\Models\HargaNonExpired;
use App\Models\HargaNonExpiredDetail;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\StokExp;
use App\Models\StokExpDetail;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductImport implements ToCollection
{
   public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {

            // =========================================
            // AMBIL SEMUA PRODUCT ID DARI FILE EXCEL
            // =========================================
            $productIds = [];

            foreach ($rows as $key => $row) {

                // skip header
                if ($key == 0) {
                    continue;
                }

                $product = Product::where('kode', $row[0])->first();

                if ($product) {
                    $productIds[] = $product->id;
                }
            }

            $productIds = collect($productIds)->unique()->toArray();

            // =========================================
            // RESET STOK PRODUCT
            // =========================================
            Product::whereIn('id', $productIds)
                ->update([
                    'stok' => 0
                ]);

            // =========================================
            // RESET STOK EXP
            // =========================================
            StokExp::whereIn('product_id', $productIds)
                ->update([
                    'qty' => 0
                ]);

            // =========================================
            // LOOP INSERT DATA BARU
            // =========================================
            foreach ($rows as $key => $row) {

                // skip header
                if ($key == 0) {
                    continue;
                }

                $product = Product::where('kode', $row[0])->first();                

                if (!$product) {
                    continue;
                }               

                // =========================================
                // INSERT STOK EXP
                // =========================================
                $stokExp = StokExp::create([
                    'tanggal'     => Carbon::now()->format('Y-m-d'),                    
                    'product_id'  => $product->id,
                    'qty'         => $row[2],
                    'lot'         => $row[3],
                ]);

                // =========================================
                // INSERT DETAIL STOK EXP
                // =========================================
                StokExpDetail::create([
                    'tanggal'     => Carbon::now()->format('Y-m-d'),
                    'stok_exp_id' => $stokExp->id,
                    'product_id'  => $product->id,
                    'qty'         => $row[2],
                ]);

                // =========================================
                // GENERATE KODE AJS
                // =========================================
                $kode = 'AJS'
                    . Carbon::now()->format('ym')
                    . rand(1000, 9999);

                // =========================================
                // INSERT ADJUSTMENT STOK
                // =========================================
                AdjustmentStok::create([
                    'product_id' => $product->id,
                    'qty'        => $row[2],
                    'jenis'      => 'nonexpired',
                    'kode'       => $kode
                ]);

                // =========================================
                // INSERT INVENTORY TRANSACTION
                // =========================================
                InventoryTransaction::create([
                    'tanggal'   => now()->format('Y-m-d'),
                    'product_id' => $product->id,
                    'qty'       => $row[2],
                    'stok'      => $row[4],
                    'hpp'       => '-',
                    'jenis'     => 'AJS',
                    'jenis_id'  => $kode,
                ]);

                // =========================================
                // UPDATE STOK PRODUCT
                // =========================================
                $product->update([
                    'stok' => $row[4]
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {

            DB::rollback();

            throw $e;
        }
    }
}
