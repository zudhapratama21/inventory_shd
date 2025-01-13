<?php

namespace App\Exports;

use App\Models\FakturPenjualanDetail;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LabaRugiExport implements FromView
{

    protected $data ;

    public function __construct($data)
    {
        $this->data = $data;

    }
    public function view(): View
    {   

        $tanggalAwal = Carbon::parse($this->data['tanggal_awal'])->format('Y-m-d');
        $tanggalAkhir = Carbon::parse($this->data['tanggal_akhir'])->format('Y-m-d');
       
        $fakturpenjualan =  FakturPenjualanDetail::with('fakturpenjualan.customers')
                                    ->with('products')
                                    ->with('pengirimanbarangdetail.stokexpdetail')
                                    ->with('pengirimanbarangdetail.harganonexpireddetail')
                                    // ->whereHas('fakturpenjualan',function($q){
                                    //         $q->where('customer_id',615);
                                    // })                                    
                                    ->get();

            // dd($fakturpenjualan);
        foreach ($fakturpenjualan as $item) {            

            if ($item->products->status_exp == 0) {
                foreach ($item->pengirimanbarangdetail->harganonexpireddetail as $nonexpired) {

                    $subtotal = $nonexpired->qty * $nonexpired->harga_beli * -1;
                    $total_diskon = ($nonexpired->diskon_persen_beli * $subtotal/100) + $nonexpired->diskon_rupiah_beli;
                    $hpp = ($subtotal - $total_diskon) * 1.11;

                    //  penjualan
                    $totalJual = $nonexpired->qty * $item->hargajual * -1;
                    $subtotalPenjualan  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                    $cn = $subtotalPenjualan * $item->cn_persen/100;
                    $nett = $subtotalPenjualan - $cn;

                    $labarugi[] = array(
                            'tanggal' => Carbon::parse($item->fakturpenjualan->tanggal)->format('d/m/Y'),
                            'bulan' => Carbon::parse($item->fakturpenjualan->tanggal)->format('m'),
                            'no_kpa' => $item->fakturpenjualan->no_kpa,
                            'products' => $item->products->nama,
                            'customer' => $item->fakturpenjualan->customers->nama,
                            'qty' => $nonexpired->qty,
                            'hargajual' => $item->hargajual,
                            'diskon_persen' => $item->diskon_persen,
                            'diskon_rp' => $item->diskon_rp,
                            'total_diskon' => $item->total_diskon,
                            'subtotal' => $subtotalPenjualan,                       
                            'total' => $subtotalPenjualan,
                            'cn_rupiah' => $cn,
                            'nett' => $subtotalPenjualan - $cn,
                            'harga_beli' => $nonexpired->harga_beli,
                            'diskon_beli_persen' => $nonexpired->diskon_persen_beli,
                            'diskon_beli_rupiah' => $nonexpired->diskon_rupiah_beli,
                            'total_diskon_beli' => $total_diskon,
                            'hpp' => $hpp,
                            'laba_kotor' =>  $nett - $hpp
                            );
                }
            } else {
                foreach ($item->pengirimanbarangdetail->stokexpdetail as $expired) {
                    $subtotalexpired = $expired->qty * $expired->harga_beli * -1;
                    $total_diskon_expired = ($expired->diskon_persen_beli * $subtotalexpired/100) + $expired->diskon_rupiah_beli;
                    $hpp_expired = ($subtotalexpired - $total_diskon_expired) * 1.11;

                      //  penjualan
                      $totalJual = $expired->qty * $item->hargajual * -1;
                      $subtotalPenjualanExpired  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                      $cnExpired = $subtotalPenjualanExpired * $item->cn_persen/100;
                      $nettExpired = $subtotalPenjualanExpired - $cnExpired;
                    $labarugi[] = array(
                        'tanggal' => Carbon::parse($item->fakturpenjualan->tanggal)->format('d/m/Y'),
                        'bulan' => Carbon::parse($item->fakturpenjualan->tanggal)->format('m'),
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'products' => $item->products->nama,
                        'customer' => $item->fakturpenjualan->customers->nama,
                        'qty' => $expired->qty,
                        'hargajual' => $item->hargajual,
                        'diskon_persen' => $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => $item->total_diskon,
                        'subtotal' => $subtotalPenjualanExpired,                        
                        'total' => $subtotalPenjualanExpired,
                        'cn_rupiah' => $cnExpired,
                        'nett' => $nettExpired,
                        'harga_beli' => $expired->harga_beli,
                        'diskon_beli_persen' => $expired->diskon_persen_beli,
                        'diskon_beli_rupiah' => $expired->diskon_rupiah_beli,
                        'total_diskon_beli' => $total_diskon_expired,
                        'hpp' => $hpp_expired,
                        'laba_kotor' =>  $nettExpired - $hpp_expired
                    );
                }
            }
        }

        return  view('laporan.labarugi.laporan.export.labarugi',compact('labarugi'));
    }
}
