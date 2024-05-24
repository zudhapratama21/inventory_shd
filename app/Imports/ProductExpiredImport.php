<?php

namespace App\Imports;

use App\Models\AdjustmentStok;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\StokExp;
use App\Models\StokExpDetail;
use App\Traits\CodeTrait;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductExpiredImport implements ToModel
{
    use CodeTrait;
    protected $no=0;
    protected $id= [];
    protected $now = 0;


   

    public function model(array $row)
    {
        if ($this->no !== 0) {
            $this->now = Carbon::parse(now())->subMinutes(1)->format('H:i:s');
            
            // ngecek apakah expired datenya ada 
            // cek produk
            
              $product = Product::where('kode',$row[0])->first();                    
              if ($product) {
            
                    // stok exp berdasarkan exp nya 
                    // insert masing-masing exp stok dan exp date sesuai dengan product id dan tanggal 
                    // cek jika ada tanggal yang sama maka ditambah
                    $tanggal = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4]))->format('Y-m-d');    
                    // $now = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(now()->subMinutes(1)))->format('H-i-s');                                  
                   
                    
                    // ubah stok yang lama menjadi 0
                    if ($this->no == 1) {
                        $sukses = StokExp::where('product_id',$product->id)                                                     
                            ->update([
                            'qty' => 0
                            ]); 
                    }                      
                    

                    // dd($sukses);

                    

                    //tidak ada data, harus insert stok
                    $datas['tanggal'] = $tanggal;
                    $datas['product_id'] = $product->id;
                    $datas['qty'] = $row[2];                    
                    $datas['lot'] = $row[3];
                    $datas['harga_beli'] = $row[6];
                    $datas['diskon_persen'] = $row[7];
                    $datas['diskon_rupiah'] = $row[8];
                    $id_stokExp = StokExp::create($datas)->id;                    
                    
                    //insert detail;
                    $stokExpDetail = new StokExpDetail;
                    $stokExpDetail->tanggal = $tanggal;
                    $stokExpDetail->stok_exp_id = $id_stokExp;
                    $stokExpDetail->product_id = $product->id;
                    $stokExpDetail->qty = $row[2];  
                    $stokExpDetail->harga_beli = $row[6];                                        
                    $stokExpDetail->diskon_persen_beli = $row[7];                                        
                    $stokExpDetail->diskon_rupiah_beli = $row[8];                                                            
                    $stokExpDetail->save();         
                    
                    // cek stok berdasarkan     qty
                    // kurangi dari qty inputan - stok 
                    $stok = $row[2];  
                    $tahun = Carbon::now()->format('y');
                    $bulan = Carbon::now()->format('m');
        
                    $kode = 'AJS'.$tahun.$bulan.rand(1000,9999);

                    // save di tabel adjustmen stok
                    $ajs = AdjustmentStok::create([
                        'product_id' => $product->id,
                        'qty' => $stok,                        
                        'jenis' => 'expired',
                        'kode' => $kode
                    ]);
            
                    // perubahan simpan di inventory transaction
                    $inv = InventoryTransaction::create([
                        'tanggal' => Carbon::now()->format('Y-m-d'),
                        'product_id' => $product->id,
                        'qty' => $row[2],
                        'stok' => $row[5],
                        'hpp' => $product->hpp,
                        'jenis' => 'AJS',
                        'jenis_id' => $kode,
                    ]);

                    $product->update([
                        'stok' => $row[5]
                    ]);
              }
                                   
        }
        $this->no++;
        return ;
    }  
}






