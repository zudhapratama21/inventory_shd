<?php

namespace App\Exports;

use App\Models\FakturPenjualanDetail;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LabaRugiExport implements FromView
{

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function view(): View
    {
        $request = $this->data;
        $requesttanggal = [
            'tanggalawal' => $this->data['tanggal_awal'] ? Carbon::parse($this->data['tanggal_awal'])->format('Y-m-d') : null,
            'tanggalakhir' => $this->data['tanggal_akhir'] ? Carbon::parse($this->data['tanggal_akhir'])->format('Y-m-d') : null
        ];         
            

        $fakturpenjualan =  FakturPenjualanDetail::whereHas('fakturpenjualan.SO', function ($q) use ($request) {
                                        if ($request['sales'] !== 'All') {
                                            $q->where('sales_id', $request['sales']);
                                        }
                                        if ($request['kategori'] !== 'All') {
                                            $q->where('kategoripesanan_id', $request['kategori']);
                                        }

                                        if ($request['customer'] !== 'All') {
                                            $q->where('customer_id', $request['customer']);
                                        }
                                })
                                ->whereHas('products.merks.supplier', function ($q) use ($request) {
                                    if ($request['supplier'] !== 'All') {
                                        $q->where('id', $request['supplier']);
                                    }
                                }) 
                                ->with('pengirimanbarangdetail.stokexpdetail')
                                ->with('pengirimanbarangdetail.harganonexpireddetail')
                                ->whereHas('fakturpenjualan', function ($q) use ($requesttanggal) {
                                    if (isset($requesttanggal['tanggalawal'])) {
                                        $q->where('tanggal','>=',$requesttanggal['tanggalawal']);    
                                    }

                                    if (isset($requesttanggal['tanggalakhir'])) {
                                        $q->where('tanggal','<=',$requesttanggal['tanggalakhir']);    
                                    }
                                    
                                })->with('fakturpenjualan.SO.sales')
                                ->get();
        
        //  dd($fakturpenjualan);

        // dd($fakturpenjualan);
        foreach ($fakturpenjualan as $item) {

            if ($item->products->status_exp == 0) {
                foreach ($item->pengirimanbarangdetail->harganonexpireddetail as $nonexpired) {

                    $subtotal = $nonexpired->qty * $nonexpired->harga_beli * -1;
                    $total_diskon = ($nonexpired->diskon_persen_beli * $subtotal / 100) + $nonexpired->diskon_rupiah_beli;
                    $hpp = ($subtotal - $total_diskon) * 1.11;

                    //  penjualan
                    $totalJual = $nonexpired->qty * $item->hargajual * -1;
                    $subtotalPenjualan  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;

                    if ($item->pph) {
                        $pph = $subtotalPenjualan * $item->pph / 100;
                    } else {
                        $pph = 0;
                    }

                    $cn = ($subtotalPenjualan-$pph) * $item->cn_persen / 100;
                    $nett = $subtotalPenjualan - $cn - $pph;

                    $labarugi[] = array(
                        'tanggal' => Carbon::parse($item->fakturpenjualan->tanggal)->format('d/m/Y'),
                        'bulan' => Carbon::parse($item->fakturpenjualan->tanggal)->format('m'),
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'id' => $nonexpired->id,
                        'products' => $item->products->nama,
                        'products_id' => $item->products->id,
                        'merk' => $item->products->merks->nama,
                        'supplier' => $item->products->merks->supplier->nama,
                        'customer' => $item->fakturpenjualan->customers->nama,
                        'qty' => $nonexpired->qty,
                        'hargajual' => $item->hargajual,
                        'diskon_persen' => $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => $item->total_diskon,
                        'subtotal' => $subtotalPenjualan,
                        'total' => $subtotalPenjualan,
                        'pph' => $pph,
                        'cn_rupiah' => $cn,
                        'nett' => $nett,
                        'harga_beli' => $nonexpired->harga_beli,
                        'diskon_beli_persen' => $nonexpired->diskon_persen_beli,
                        'diskon_beli_rupiah' => $nonexpired->diskon_rupiah_beli,
                        'total_diskon_beli' => $total_diskon,
                        'ppn_beli' => ($subtotal - $total_diskon) * 11/100,
                        'hpp' => $hpp,
                        'laba_kotor' =>  $nett - $hpp,
                        'sales' => $item->fakturpenjualan->SO->sales->nama
                    );
                }
            } else {
                foreach ($item->pengirimanbarangdetail->stokexpdetail as $expired) {
                    $subtotalexpired = $expired->qty * $expired->harga_beli * -1;
                    $total_diskon_expired = ($expired->diskon_persen_beli * $subtotalexpired / 100) + $expired->diskon_rupiah_beli;
                    $hpp_expired = ($subtotalexpired - $total_diskon_expired) * 1.11;

                    //  penjualan
                    $totalJual = $expired->qty * $item->hargajual * -1;
                    $subtotalPenjualanExpired  = $totalJual - ($totalJual * $item->diskon_persen / 100) - $item->diskon_rp;
                    if ($item->pph) {
                        $pph = $subtotalPenjualanExpired * $item->pph / 100;
                    } else {
                        $pph = 0;
                    }

                    $cnExpired = ($subtotalPenjualanExpired-$pph) * $item->cn_persen / 100;
                    $nettExpired = $subtotalPenjualanExpired - $cnExpired - $pph;
                    $labarugi[] = array(
                        'tanggal' => Carbon::parse($item->fakturpenjualan->tanggal)->format('d/m/Y'),
                        'bulan' => Carbon::parse($item->fakturpenjualan->tanggal)->format('m'),
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'id' => $expired->id,
                        'products' => $item->products->nama,
                        'products_id' => $item->products->id,
                        'merk' => $item->products->merks->nama,
                        'supplier' => $item->products->merks->supplier->nama,
                        'customer' => $item->fakturpenjualan->customers->nama,
                        'qty' => $expired->qty,
                        'hargajual' => $item->hargajual,
                        'diskon_persen' => $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => $item->total_diskon,
                        'subtotal' => $subtotalPenjualanExpired,
                        'total' => $subtotalPenjualanExpired,
                        'pph' => $pph,
                        'cn_rupiah' => $cnExpired,
                        'nett' => $nettExpired,
                        'harga_beli' => $expired->harga_beli,
                        'diskon_beli_persen' => $expired->diskon_persen_beli,
                        'diskon_beli_rupiah' => $expired->diskon_rupiah_beli,
                        'total_diskon_beli' => $total_diskon_expired,
                        'ppn_beli' => ($subtotalPenjualanExpired - $total_diskon_expired) * 11/100,
                        'hpp' => $hpp_expired,
                        'laba_kotor' =>  $nettExpired - $hpp_expired,
                        'sales' => $item->fakturpenjualan->SO->sales->nama
                    );
                }
            }
        }

        usort($labarugi, function ($a, $b) {
            return strcmp($a['no_kpa'], $b['no_kpa']);
        });

        return  view('laporan.labarugi.laporan.export.labarugi', compact('labarugi'));
    }
}
