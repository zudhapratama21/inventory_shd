<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\BiayaLain;
use App\Models\FakturPenjualan;
use App\Models\FakturPenjualanDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabaRugiController extends Controller
{
    public function  show($id)
    {
        $totalHpp = 0;
        $totalHargaBeli = 0;
        $totalPpnBeli = 0;
        $totalLaba = 0;

        $title = "Laba Rugi Detail";
        $fakturpenjualan =  FakturPenjualanDetail::where('faktur_penjualan_id', $id)
            ->with('fakturpenjualan.customers')
            ->with('products')
            ->with('pengirimanbarangdetail.stokexpdetail')
            ->with('pengirimanbarangdetail.harganonexpireddetail')
            ->get();

        $labakotor = 0;
        foreach ($fakturpenjualan as $item) {

            $total =  $item->subtotal - $item->total_diskon;
            $nett = $total - $item->cn_rupiah;


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
                    $cn = $subtotalPenjualan * $item->cn_persen / 100;
                    $nett = $subtotalPenjualan - $cn - $pph;
                    $labakotorNonExpired = $nett - $hpp;
                    $labakotor += $labakotorNonExpired;

                    $labarugi[] = array(
                        'tanggal' => Carbon::parse($item->fakturpenjualan->tanggal)->format('d/m/Y'),
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'products' => $item->products->nama,
                        'customer' => $item->fakturpenjualan->customers->nama,
                        'qty' => $nonexpired->qty * -1,
                        'pph' => $pph,
                        'hargajual' => $item->hargajual,
                        'diskon_persen' => $item->diskon_persen,
                        'diskon_rp' => $item->diskon_rp,
                        'total_diskon' => $item->total_diskon,
                        'subtotal' => $subtotalPenjualan,
                        'total' => $subtotalPenjualan,
                        'cn_rupiah' => $cn,
                        'nett' => $nett,
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
                    $cnExpired = $subtotalPenjualanExpired * $item->cn_persen / 100;
                    $nettExpired = $subtotalPenjualanExpired - $cnExpired - $pph;

                    $labakotorExpired =  $nettExpired - $hpp_expired;
                    $labakotor += $labakotorExpired;
                    $labarugi[] = array(
                        'tanggal' => Carbon::parse($item->fakturpenjualan->tanggal)->format('d/m/Y'),
                        'no_kpa' => $item->fakturpenjualan->no_kpa,
                        'products' => $item->products->nama,
                        'customer' => $item->fakturpenjualan->customers->nama,
                        'qty' => $expired->qty * -1,
                        'pph' => $pph,
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

        return view('penjualan.fakturpenjualan.laporan.labarugi', [
            'title' => $title,
            'labarugi' => $labarugi,
            'labakotor' =>  number_format($labakotor, 2, ',', '.')
        ]);
    }
}
