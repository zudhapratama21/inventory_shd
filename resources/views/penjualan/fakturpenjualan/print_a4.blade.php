<!DOCTYPE html>
<html>

<head>

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

    @for ($i = 1; $i <= $totalPage; $i++)
        <table width=" 100%" style="margin-top: 0px;">
            <tr>
                <td colspan="4" style=" border-bottom: 1px solid black;">FAKTUR PENJUALAN</td>
                <td colspan="2" style=" border-bottom: 1px solid black; text-align:right">No. Faktur :
                    {{ $fakturpenjualan->no_kpa }}</td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: center; border-bottom: 1px solid black;">
                    <h1 style="margin-top: 5px; margin-bottom: 10px;">PT BRILIAN SUKSES BERKAH</h1>
                    <h5 style="margin-top: 0px; margin-bottom: 5px;">Juanda Regency Blok H-06, JL. Raya Bypass Juanda
                        NO.11, Pabean-Sedati, Sidoarjo , Kode Pos 61253</h5>
                    <h5 style="margin-top: 0px;margin-bottom: 5px;">IDAK : 13102201284910012 | NPWP :
                        61.097.970.0-643.000</h5>
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
                            <td style="font-size: 75%;">{{ $fakturpenjualan->customers->nama }}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 75%;width:10%">Alamat</td>
                            <td style="font-size: 75%; width:5%">:</td>
                            <td style="font-size: 75%;">{{ $fakturpenjualan->customers->alamat }}, Blok
                                {{ $fakturpenjualan->customers->blok }}, No. {{ $fakturpenjualan->customers->nomor }},
                                {{ $fakturpenjualan->customers->namakota->name }}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 75%;width:10%">NPWP</td>
                            <td style="font-size: 75%; width:5%"> :</td>
                            <td style="font-size: 75%;">{{ $fakturpenjualan->customers->npwp }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="vertical-align: top; ">
                    <div class="isi" style="height: 400px;">
                        <table border="0" class="xyz" style="width:100%; ">
                            <tr>
                                <td colspan="7">
                                    <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 1px 0px 0px;">
                                </td>
                            </tr>
                            <tr style="">
                                <td style="font-size: 65%; border:none; width:3%">NO</td>
                                <td style="font-size: 65%; border:none; width:7%;">KWANTUM</td>
                                <td style="font-size: 65%; border:none;">NAMA BARANG</td>
                                <td style="font-size: 65%; border:none; width:10%;text-align:right">HARGA</td>
                                <td style="font-size: 65%; border:none; width:15%;text-align:right">SUBTOTAL</td>
                                <td style="font-size: 65%; border:none; width:10%;text-align:right">DISKON</td>
                                <td style=" font-size: 65%; border:none; width:15%;text-align:right">JUMLAH</td>
                            </tr>
                            <tr>
                                <td colspan="7">
                                    <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 0.3px 0px 0px;">
                                </td>
                            </tr>

                            @php
                                $n = 1;
                                $j = 1;
                            @endphp
                            @foreach ($fakturpenjualandetails as $a)
                                @if ($n > ($i - 1) * $perBaris && $n <= $i * $perBaris)
                                    <tr class="" style="vertical-align:top">
                                        <td style="font-size: 62%;">{{ $j }}.</td>
                                        <td style="font-size: 62%; ">{{ $a->qty }} {{ $a->satuan }}</td>
                                        <td
                                            style="font-size: 67%;font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                            {{ $a->products->nama }}</td>
                                        <td style="font-size: 62%; text-align:right;">
                                            {{ number_format($a->hargajual, 0, ',', '.') }}</td>
                                        <td style="font-size: 62%; text-align:right;">
                                            {{ number_format($a->subtotal, 0, ',', '.') }}</td>
                                        <td style="font-size: 62%; text-align:right;">
                                            {{ number_format($a->total_diskon, 0, ',', '.') }}
                                        </td>
                                        <td style="font-size: 62%; text-align:right;">
                                            {{ number_format($a->total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                                @php
                                    $n++;
                                    $j++;
                                @endphp
                            @endforeach
                        </table>
                    </div>
                </td>
            </tr>

        </table>
        <br /><br />


        <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 1px 0px 0px;">
        <table width="100%">
            <tr>
                @if ($i == $totalPage)
                    <td style="text-align: right">
                        <table width="100%">
                            <tr>
                                <td style='font-size: 70%; width: 75%; line-height:90%'><b>Total Jumlah</b></td>
                                <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                                <td style='font-size: 70%; line-height:90%; text-align:right'><b>
                                        {{ number_format($fakturpenjualan->subtotal, 0, ',', '.') }}
                                    </b></td>
                            </tr>
                            <tr>
                                <td style='font-size: 70%; width: 75%; line-height:90%'><b>Potongan Harga</b></td>
                                <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                                <td style='font-size: 70%; line-height:90%; text-align:right'><b>
                                        {{ number_format($fakturpenjualan->total_diskon_header, 0, ',', '.') }}
                                    </b></td>
                            </tr>
                            <tr>
                                <td style='font-size: 70%; width: 75%; line-height:90%'><b>Dasar Pengenaan Pajak</b>
                                </td>
                                <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                                <td style='font-size: 70%; line-height:90%; text-align:right'><b>
                                        {{ number_format($fakturpenjualan->total, 0, ',', '.') }}
                                    </b></td>
                            </tr>
                            <tr>
                                <td style='font-size: 70%; width: 75%;'><b>PPN</b></td>
                                <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                                <td style='font-size: 70%; text-align:right'><b>
                                        {{ number_format($fakturpenjualan->ppn, 0, ',', '.') }}
                                    </b></td>
                            </tr>
                            <tr>
                                <td style='font-size: 70%; width: 75%;'><b>Biaya Pengiriman</b></td>
                                <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                                <td style='font-size: 70%; text-align:right'><b>
                                        {{ number_format($fakturpenjualan->ongkir, 0, ',', '.') }}
                                    </b></td>
                            </tr>
                            <tr>
                                <td style='font-size: 70%; width: 75%;'><b>Biaya Lain-Lain</b></td>
                                <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                                <td style='font-size: 70%; text-align:right'><b>
                                        {{ number_format($fakturpenjualan->biaya_lain, 0, ',', '.') }}
                                    </b></td>
                            </tr>
                            <tr>
                                <td style='font-size: 70%; width: 25%;'><b>Jumlah Yang Harus Dibayar</b></td>
                                <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                                <td style='font-size: 70%; text-align:right'><b>
                                        {{ number_format($fakturpenjualan->grandtotal, 0, ',', '.') }}
                                    </b></td>
                            </tr>
                        </table>


                    </td>
                @else
                    <td style="text-align: right;page-break-after:always;">
                        <table width="100%">
                            <tr>
                                <td style='font-size: 70%; width: 25%;text-align:center'><i>( HALAMAN SELANJUTNYA )</i>
                                </td>
                            </tr>
                        </table>

                    </td>
                @endif


            </tr>
        </table>
        <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 1px 0px 0px;">
        <table>
            <tr>
                <td style="font-size: 60%;">SO Cust. :
                    {{ $fakturpenjualan->SO->no_so ? $fakturpenjualan->SO->no_so : '-' }}</td>
                <td></td>
                <td></td>
                <td style="font-size: 60%;">Tanggal SO Cust. :
                    {{ $fakturpenjualan->SO->tanggal_pesanan_customer ? \Carbon\Carbon::parse($fakturpenjualan->SO->tanggal_pesanan_customer)->format('d/m/Y') : '-' }}
                </td>
            </tr>
        </table>        
        <table width="100%">
            <tr>
                <td style='font-size: 70%; width: 15%; line-height:90%; vertical-align:top'>PENERIMA,

                    <br /><br /> <br /><br /> <br /> <br />
                    <u>(...............................)</u> <br />
                    <br />
                    <i>Hal. :
                        {{ $i }}/{{ $totalPage }}<br />
                        User : {{ $fakturpenjualan->creator->name }}</i>
                </td>
                <td style='font-size: 70%; width: 55%; line-height:90%; vertical-align:top'><b>KETERANGAN :
                        <br />{{ $fakturpenjualan->keterangan }}</b>
                    <br /> <br /><br /> <br /> <br /><br /><br /> <br /><br /> <br /> <br />
                </td>

                <td style='font-size: 70%; text-align:center; vertical-align:top'>Sidoarjo,
                    {{ $fakturpenjualan->tanggal->format("d
                                        F Y") }}

                    <br /><br /> <br /><br /> <br /> <br />
                    <u>HEPPY WAHYU PURNOMO</u> <br />
                    Direktur
                </td>
            </tr>
        </table>
        <div style="border: 0.5px solid black;width: 100%;line-height:90%">
            <p style="font-size:55%;text-align:center"><b> Pembayaran dapat ditransfer ke Rek : <b>Bank BCA No.675 222
                        2289
                    </b> , <b>Bank JATIM No. 066 1292 229</b> a/n PT.BRILIAN SUKSES BERKAH</b></p>
        </div>



        @if ($totalPage != $i)
            <div style="page-break-after: always;"></div>
        @endif



    @endfor

</body>

</html>
