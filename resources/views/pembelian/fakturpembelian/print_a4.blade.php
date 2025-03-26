
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <style type="text/css">
        .tabel {
            border-collapse: collapse;
        }

        .tabel td,
        th,
        tr {
            border: 1px solid black;
        }
        
        @media print {
            .tabpage {
                page-break-after: always
            }
        }
    </style>
</head>

<body style="font-family: sans-serif;">
    @for($i = 1; $i <= $totalPage; $i++) <table width=" 100%" style="margin-top: 0px;">
        <tr>
            <td colspan="4" style=" border-bottom: 1px solid black;">FAKTUR PEMBELIAN</td>
            <td colspan="2" style=" border-bottom: 1px solid black; text-align:right">No. Faktur :
                {{ $fakturpembelian->kode }}</td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center; border-bottom: 1px solid black;">
                <h1 style="margin-top: 5px; margin-bottom: 10px;">PT BRILIAN SUKSES BERKAH</h1>
                <h5 style="margin-top: 0px; margin-bottom: 5px;">Juanda Regency Blok H-06, JL. Raya Bypass Juanda NO.11, Pabean-Sedati, Sidoarjo , Kode Pos 61253</h5>                
                <h5 style="margin-top: 0px;margin-bottom: 5px;">IDAK : 13102201284910012 | NPWP : 61.097.970.0-643.000</h5>
            </td>

        </tr>
        <tr>
            <td colspan="6" style="border-bottom: 1px solid black;">
                <table border="0" width="100%">
                    <tr>
                        <td style="font-size: 80%;" colspan="3">PEMBELI BKP</td>
                    </tr>
                    <tr>
                        <td style="font-size: 75%; width:10%">Nama</td>
                        <td style="font-size: 75%; width:5%">:</td>
                        <td style="font-size: 75%;">{{ $fakturpembelian->suppliers->nama }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 75%;width:10%">Alamat</td>
                        <td style="font-size: 75%; width:5%">:</td>
                        <td style="font-size: 75%;">{{ $fakturpembelian->suppliers->alamat }}, Blok {{
                            $fakturpembelian->suppliers->blok
                            }}, No. {{ $fakturpembelian->suppliers->nomor
                            }}, {{ $fakturpembelian->suppliers->namakota->name
                            }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 75%;width:10%">NPWP</td>
                        <td style="font-size: 75%; width:5%"> :</td>
                        <td style="font-size: 75%;">{{ $fakturpembelian->suppliers->npwp }}</td>
                    </tr>  
                    
                    <tr>
                        <td style="font-size: 75%;width:10%">No Faktur Supplier</td>
                        <td style="font-size: 75%; width:5%"> :</td>
                        <td style="font-size: 75%;">{{ $fakturpembelian->no_faktur_supplier }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="vertical-align: top; ">
                <div class="isi" style="height: 400px;">
                    <table border="0" class="xyz" style="width:100%; ">
                        <tr>
                            <td colspan="6">
                                <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 1px 0px 0px;">
                            </td>
                        </tr>
                        <tr style="">
                            <td style="font-size: 75%; border:none; width:10%;">KWANTUM</td>
                            <td style="font-size: 75%; border:none;">NAMA BARANG</td>
                            <td style="font-size: 75%; border:none; width:10%;text-align:right">HARGA</td>
                            <td style="font-size: 75%; border:none; width:15%;text-align:right">SUBTOTAL</td>
                            <td style="font-size: 75%; border:none; width:10%;text-align:right">DISKON</td>
                            <td style=" font-size: 75%; border:none; width:15%;text-align:right">JUMLAH</td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 0.3px 0px 0px;">
                            </td>
                        </tr>

                        @php
                        $n=1;
                        @endphp
                        @foreach($fakturpembeliandetail as $a)
                        @if($n > (($i-1)*$perBaris) && $n <= ($i)*$perBaris) <tr class="">
                            <td style="font-size: 65%; ">{{ $a->qty }} {{ $a->satuan }}</td>
                            <td style="font-size: 65%;font-family: DejaVu Sans; sans-serif; ">{{ $a->products->nama }}</td>
                            <td style="font-size: 65%; text-align:right">{{ number_format(floor($a->hargabeli), 2, ',', '.')
                                }}</td>
                            <td style="font-size: 65%; text-align:right">{{ number_format(floor($a->subtotal), 2, ',', '.')
                                }}</td>
                            <td style="font-size: 65%; text-align:right">{{ number_format(floor($a->total_diskon), 2, ',',
                                '.') }}</td>
                            <td style="font-size: 65%; text-align:right">{{ number_format(floor($a->total), 2, ',',
                                '.') }}</td>


        </tr>

        @endif
        @php
        $n++;
        @endphp
        @endforeach
        </table>
        </div>
        </td>
        </tr>

        </table>
        <br /><br /><br />


        <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 1px 0px 0px;">
        <table width="100%">
            <tr>
                @if ($i==$totalPage)
                <td style="text-align: right">
                    <table width="100%">
                        <tr>
                            <td style='font-size: 70%; width: 75%; line-height:90%'><b>Total Jumlah</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; line-height:90%; text-align:right'><b>
                                    {{ number_format(floor($fakturpembelian->subtotal), 2, ',', '.') }}
                                </b></td>
                        </tr>
                        <tr>
                            <td style='font-size: 70%; width: 75%; line-height:90%'><b>Potongan Harga</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; line-height:90%; text-align:right'><b>
                                    {{ number_format(floor($fakturpembelian->total_diskon_header), 2, ',', '.') }}
                                </b></td>
                        </tr>
                        <tr>
                            <td style='font-size: 70%; width: 75%; line-height:90%'><b>Dasar Pengenaan Pajak</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; line-height:90%; text-align:right'><b>
                                    {{ number_format(floor($fakturpembelian->total), 2, ',', '.') }}
                                </b></td>
                        </tr>
                        <tr>
                            <td style='font-size: 70%; width: 75%;'><b>PPN</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; text-align:right'><b>
                                    {{ number_format(floor($fakturpembelian->ppn), 2, ',', '.') }}
                                </b></td>
                        </tr>
                        <tr>
                            <td style='font-size: 70%; width: 75%;'><b>Biaya Pengiriman</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; text-align:right'><b>
                                    {{ number_format(floor($fakturpembelian->ongkir), 2, ',', '.') }}
                                </b></td>
                        </tr>
                        <tr>
                            <td style='font-size: 70%; width: 75%;'><b>Biaya Lain-Lain</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; text-align:right'><b>
                                    {{ number_format(floor($fakturpembelian->biaya_lain), 2, ',', '.') }}
                                </b></td>
                        </tr>
                        <tr>
                            <td style='font-size: 70%; width: 25%;'><b>Jumlah Yang Harus Dibayar</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; text-align:right'><b>
                                    {{ number_format(floor($fakturpembelian->grandtotal), 2, ',', '.') }}
                                </b></td>
                        </tr>
                    </table>


                </td>
                @else
                <td style="text-align: right">
                    <table width="100%">
                        {{-- <tr>
                            <td style='font-size: 70%; width: 75%; line-height:90%'><b>Total Jumlah</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; line-height:90%; text-align:right'><b>
                                    -
                                </b></td>
                        </tr>
                        <tr>
                            <td style='font-size: 70%; width: 75%; line-height:90%'><b>Potongan Harga</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; line-height:90%; text-align:right'><b>
                                    -
                                </b></td>
                        </tr>
                        <tr>
                            <td style='font-size: 70%; width: 75%; line-height:90%'><b>Dasar Pengenaan Pajak</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; line-height:90%; text-align:right'><b>
                                   -
                                </b></td>
                        </tr>
                        <tr>
                            <td style='font-size: 70%; width: 75%;'><b>PPN</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; text-align:right'><b>
                                   -
                                </b></td>
                        </tr>
                        <tr>
                            <td style='font-size: 70%; width: 75%;'><b>Biaya Pengiriman</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; text-align:right'><b>
                                 -
                                </b></td>
                        </tr>
                        <tr>
                            <td style='font-size: 70%; width: 25%;'><b>Jumlah Yang Harus Dibayar</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; text-align:right'><b>
                                -
                                </b></td>
                        </tr> --}}

                        <tr>
                            <td style='font-size: 70%; width: 25%;text-align:center'><i>( HALAMAN SELANJUTNYA )</i></td>
                        </tr>
                    </table>


                </td>
                @endif
              

            </tr>
        </table>
        <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 1px 0px 0px;">
        <table>
            <tr>
                <td style="font-size: 70%;">SO Cust. : {{ $fakturpembelian->PO->no_so ? $fakturpembelian->PO->no_so : '-' }}</td>
            </tr>
        </table>
        <br />    
        <table width="100%">
            <tr>
                <td style='font-size: 70%; width: 15%; line-height:90%; vertical-align:top'>PENERIMA,

                    <br /><br /> <br /><br /> <br /> <br />
                    <u>(...............................)</u> <br />
                    <br />
                    <i>Hal. :
                        {{ $i }}
                        {{ $totalPage }}<br />
                        User : {{ Auth::user()->name }}</i>
                </td>
                <td style='font-size: 70%; width: 55%; line-height:90%; vertical-align:top'><b>KETERANGAN : <br />{{
                        $fakturpembelian->keterangan }}</b>
                    <br /> <br /><br /> <br /> <br /><br /><br /> <br /><br /> <br /> <br />


                </td>

                <td style='font-size: 70%; text-align:center; vertical-align:top'>Sidoarjo, {{
                    $fakturpembelian->tanggal->format("d
                    F Y")
                    }}

                    <br /><br /> <br /><br /> <br /> <br />
                    <u>HEPPY WAHYU PURNOMO</u> <br />
                    Direktur
                </td>
            </tr>
        </table>
      

        @if($totalPage <> $i)
            <div style="page-break-after: always;"></div>
            @endif



            @endfor

</body>

</html>