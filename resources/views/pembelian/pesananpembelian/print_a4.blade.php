{{-- <!DOCTYPE html>
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


<body style="font-family: sans-serif;position: relative;">
    @for ($i = 1; $i <= $totalPage; $i++)
        <table width="100%" style="margin-top: 0px">
            <tr>
                <td width="40%" style="font-size: 70%; vertical-align: top;">
                    <h3>PT BRILIAN SUKSES BERKAH</h3>
                    <p>
                        Juanda Regency Blok H-06, JL. Raya Bypass Juanda NO.11, Pabean-Sedati, Sidoarjo, Kode Pos 61253.
                    </p>
                    <p style="margin-top: 0px;margin-bottom: 0px;">NPWP : 61.097.970.0-643.000</p>
                    <p style="margin-top: 0px;margin-bottom: 3px;">IDAK : 13102201284910012</p>
                </td>
                <td width="20%" style="font-size: 75%; vertical-align: top; text-align: center;">
                    <center><b></b></center>
                    <center><b>

                        </b></center>
                </td>
                <td width="40%" style="vertical-align: top; text-align: left; font-family: sans-serif">
                    <table>
                        <tr style="padding:0px;">
                            <td style="padding:0px;font-size: 70%; " colspan="3"><br />Sidoarjo,
                                {{ $pesananpembelian->tanggal->format('d F Y') }}
                            </td>
                        </tr>
                        <tr style="padding:0px;">
                            <td style="padding:0px;font-size: 70%; ">KEPADA :</td>
                            <td style="padding:0px;font-size: 70%; "></td>
                            <td style="padding:0px;font-size: 70%; ">

                            </td>
                        </tr>
                        <tr style="padding:0px;">
                            <td style="padding:0px;font-size: 70%; " colspan="3">
                                {{ $pesananpembelian->suppliers->nama }}</td>

                        </tr>
                        <tr style="padding:0px;">
                            <td style="padding:0px;font-size: 70%; " colspan="3">
                                {{ $pesananpembelian->suppliers->alamat }}, Blok
                                {{ $pesananpembelian->suppliers->blok }}, No. {{ $pesananpembelian->suppliers->nomor }},
                                {{ $pesananpembelian->suppliers->namakota->name }}</td>

                        </tr>

                    </table>


                </td>

            </tr>
            <tr>
                <td width="20%" style="font-size: 70%; vertical-align: top;">
                    Surat Pesanan : {{ $pesananpembelian->no_so }}
                </td>
                <td width="25%" style="font-size: 75%; vertical-align: top; text-align: center;">
                    <center><b></b></center>
                    <center><b>
                        </b></center>
                </td>

            </tr>



        </table>

        <table width="100%">
            <tr>
                <td colspan="6" style="vertical-align: top;display: flex">
                    <div class="isi" style="height: 450px;">
                        <table style="width:100%" border="0" class="xyz">
                            <tr>
                                <td colspan="10">
                                    <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 1px 0px 0px;">
                                </td>
                            </tr>
                            <tr style="">
                                <td style="font-size: 60%; border:none; width:3%;text-align:left">NO</td>
                                <td style="font-size: 60%; border:none; width:7%;text-align:left">KEBUTUHAN</td>
                                <td style="font-size: 60%; border:none; width:24%;text-align:left">NAMA BARANG</td>
                                <td style="font-size: 60%; border:none; width:8%;text-align:right">MERK</td>
                                <td style="font-size: 60%; border:none; width:12%;text-align:right">HARGA</td>
                                <td style="font-size: 60%; border:none; width:6%;text-align:center">DISK.(%)</td>
                                <td style="font-size: 60%; border:none; width:10%;text-align:left">DISK.(RP)</td>
                                <td style="font-size: 60%; border:none; width:10%;text-align:left">SUBTOTAL</td>
                                <td style="font-size: 60%; border:none; width:10%;text-align:right">TOTAL DISC</td>
                                <td style="font-size: 60%;border:none; width:10%;text-align:right">TOTAL</td>
                            </tr>
                            <tr>
                                <td colspan="10">
                                    <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 1px 0px 0px;">
                                </td>
                            </tr>

                            @php
                                $n = 1;
                                $j = 1;
                            @endphp
                            @foreach ($pesananpembeliandetail as $a)
                                @if ($n > ($i - 1) * $perBaris && $n <= $i * $perBaris)
                                    <tr class="" style="vertical-align: top">
                                        <td style="font-size: 70%; ">{{ $j++ }}</td>
                                        <td style="font-size: 70%; ">{{ $a->qty }} {{ $a->satuan }}</td>
                                        <td style="font-size: 70%;font-family: DejaVu Sans; sans-serif;">
                                            {{ $a->products->nama }}</td>
                                        <td style="font-size: 70%; text-align:right;">{{ $a->products->merks->nama }}
                                        </td>
                                        <td style="font-size: 70%; text-align:right">
                                            {{ number_format($a->hargabeli, 2, ',', '.') }}</td>
                                        <td style="font-size: 70%; text-align:center">
                                            {{ number_format($a->diskon_persen, 2, ',', '.') }}</td>
                                        <td style="font-size: 70%; text-align:left">
                                            {{ number_format($a->diskon_rp, 2, ',', '.') }}</td>
                                        <td style="font-size: 70%; text-align:left">
                                            {{ number_format($a->subtotal, 2, ',', '.') }}</td>
                                        <td style="font-size: 70%; text-align:right">
                                            {{ number_format($a->total_diskon, 2, ',', '.') }}
                                        </td>
                                        <td style="font-size: 70%; text-align:right">
                                            {{ number_format($a->total, 2, ',', '.') }}
                                        </td>
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
        <br /><br /><br /> <br> <br> <br> <br>

        <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 1px 0px 0px;display: flex;">
        <table width="100%">
            <tr>
                @if ($i == $totalPage)
                    <td style="text-align: right">
                        <table width="100%">
                            <tr>
                                <td style='font-size: 70%; width: 75%; line-height:90%'><b>Total Jumlah</b></td>
                                <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                                <td style='font-size: 70%; line-height:90%; text-align:right'><b>
                                        {{ number_format(floor($pesananpembelian->subtotal), 2, ',', '.') }}
                                    </b></td>
                            </tr>
                            <tr>
                                <td style='font-size: 70%; width: 75%; line-height:90%'><b>Discount</b></td>
                                <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                                <td style='font-size: 70%; line-height:90%; text-align:right'><b>
                                        {{ number_format(floor($pesananpembelian->total_diskon_header), 2, ',', '.') }}
                                    </b></td>
                            </tr>
                            <tr>
                                <td style='font-size: 70%; width: 75%; line-height:90%'><b>Total Harga</b></td>
                                <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                                <td style='font-size: 70%; line-height:90%; text-align:right'><b>
                                        {{ number_format(floor($pesananpembelian->total), 2, ',', '.') }}
                                    </b></td>
                            </tr>
                            <tr>
                                <td style='font-size: 70%; width: 75%;'><b>PPN ({{ $pesananpembelian->ppn }}) %</b>
                                </td>
                                <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                                <td style='font-size: 70%; text-align:right'><b>
                                        {{ number_format(floor(($pesananpembelian->total * $pesananpembelian->ppn) / 100), 2, ',', '.') }}
                                    </b></td>
                            </tr>
                            <tr>
                                <td style='font-size: 70%; width: 75%;'><b>Biaya Pengiriman</b></td>
                                <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                                <td style='font-size: 70%; text-align:right'><b>
                                        {{ number_format(floor($pesananpembelian->ongkir), 0, ',', '.') }}
                                    </b></td>
                            </tr>
                            <tr>
                                <td style='font-size: 70%; width: 25%;'><b>Jumlah Yang Harus Dibayar</b></td>
                                <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                                <td style='font-size: 70%; text-align:right'><b>
                                        {{ number_format(floor($pesananpembelian->grandtotal), 2, ',', '.') }}
                                    </b></td>
                            </tr>
                        </table>

                        <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 1px 0px 0px;position: relative;">
                        <table>
                            <tr>
                                <td style="font-size: 70%;">SO Cust. :
                                    {{ $pesananpembelian->no_so_customer ? $pesananpembelian->no_so_customer : '-' }}
                                </td>
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
                                <td style='font-size: 70%; width: 55%; line-height:90%; vertical-align:top'>
                                    <b>KETERANGAN : <br />{{ $pesananpembelian->keterangan }}</b>
                                    <br /> <br /><br /> <br /> <br /><br /><br /> <br /><br /> <br /> <br />


                                </td>

                                <td style='font-size: 70%; text-align:center; vertical-align:top'>Sidoarjo,
                                    {{ $pesananpembelian->tanggal->format("d
                                                                                                                                                                                                                                                                                            F Y") }}

                                    <br /><br /> <br /><br /> <br /> <br />
                                    <u>HEPPY WAHYU PURNOMO</u> <br />
                                    Direktur
                                </td>
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

        @if ($totalPage != $i)
            <div style="page-break-after: always;"></div>
        @endif
    @endfor

</body>

</html> --}}

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pesanan Pembelian</title>

    <style>
        @page {
            size: A4;
            margin: 0;
        }


        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
        }

        html,
        body {
            margin: 2px;
            padding: 0;
        }


        table {
            width: 100%;
            border-collapse: collapse;
        }

        .border {
            border: 1px solid #000;
        }

        .border th,
        .border td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-bold {
            font-weight: bold;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            font-size: 16px;
        }

        .capt {
            text-align: center;
            font-size: 12px;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .no-border td {
            border: none;
            padding: 2px;
        }

        .signature {
            height: 70px;
        }

        .bordertop {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        .item-group {
            border-bottom: none !important;
            border-top: none !important;

        }
    </style>
</head>

<body>

    <div>
        <img src="{{ public_path('ttd/KOPSURAT.png') }}" alt="" width="100%" height="100px">
    </div>

    <div class="title mt-10"><u>Surat Pesanan</u></div>
    <div class="capt mt-2">No. {{ $pesananpembelian->no_so }}</div>

    <table class="no-border">
        <tr>
            <td width="60%"></td>
            <td width="40%">
                <table>
                    <td width="40%" class="text-right">Tanggal</td>
                    <td width="2%"></td>
                    <td width="58%">: {{ $pesananpembelian->tanggal->format('d F Y') }}</td>
                </table>
            </td>
        </tr>
        <tr>
            <td width="60%"></td>
            <td width="40%">
                <table>
                    <td width="40%" class="text-right">Pemesan / PIC</td>
                    <td width="2%"></td>
                    <td width="58%">: {{ Str::ucfirst(Auth::user()->name) }}</td>
                </table>
            </td>
        </tr>
    </table>
    <table class="no-border">
        <tr>
            <td width="100%">Kepada Yth</td>
        </tr>
        <tr>
            <td width="100%"><b>{{ $pesananpembelian->suppliers->nama }}</b> ,
                {{ $pesananpembelian->suppliers->alamat }}</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td width="100%">Dengan Hormat, Bersama ini kami bermaksud memesan barang-barang sebagai berikut :</td>
        </tr>
    </table>

    <table class="border mt-10">
        <thead>
            <tr class="text-center text-bold">
                <th width="5%">No</th>
                <th width="40%">Nama Barang/Type/Katalog/No.Izin Edar</th>
                <th width="7%">Merk</th>
                <th width="5%">Qty</th>
                <th width="5%">Satuan</th>
                <th width="11%">Harga Satuan</th>
                <th width="8%">Disc</th>
                <th width="16%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            ?>
            @foreach ($pesananpembeliandetail as $item)
                <tr>
                    <td class="text-center item-group">{{ $no++ }}</td>
                    <td class="item-group">
                        {{ $item->products->nama }}<br>
                        {{ $item->products->no_ijinedar }}
                    </td>
                    <td class="item-group" class="text-center">{{ $item->products->merks->nama }}</td>
                    <td class="text-center item-group">{{ $item->qty }}</td>
                    <td class="item-group">
                        @if ($item->beda_satuan == 'on')
                            {{ $item->satuan_konversi }}
                        @else
                            {{ $item->satuan }}
                        @endif
                    </td>
                    <td class="text-right item-group">
                        <table class="no-border">
                            <tr>
                                <td width="20%" class="text-left">Rp.</td>
                                <td width="80%" class="text-right">{{ number_format($item->hargabeli, 0, ',', '.') }}</td>
                            </tr>
                        </table>                                              
                    </td>
                    <td class="text-center item-group">{{ $item->diskon_persen }} %</td>
                    <td class="text-right item-group">
                          <table class="no-border">
                            <tr>
                                <td width="20%" class="text-left">Rp.</td>
                                <td width="80%" class="text-right">{{ number_format($item->total, 0, ',', '.') }}</td>
                            </tr>
                        </table>      
                        
                    </td>
                </tr>
            @endforeach

            @for ($i = 1; $i < 27 - $jumlahproduk; $i++)
                <tr>
                    <td class="text-center item-group"></td>
                    <td class="item-group">
                        <br>
                    </td>
                    <td class="item-group"></td>
                    <td class="text-center item-group"></td>
                    <td class="item-group"></td>
                    <td class="text-right item-group"></td>
                    <td class="text-center item-group"></td>
                    <td class="text-right item-group"></td>
                </tr>
            @endfor
        </tbody>
    </table>

    <!-- TOTAL -->
    <table class="border">
        <tr>
            <td width="58.8%">
                <table class="no-border">
                    <tr><b>Keterangan :</b></tr>
                    <tr>
                        <td width="99.9%">- Mohon Konfirmasi stock , expired , dan estimasi pengiriman</td>
                    </tr>
                    <tr>
                        <td width="99.9%">- Setelah Surat pesanan diterima mohon ditandatangani , distempel , dan
                            diberi nama terang lalu di email kembali</td>
                    </tr>
                </table>

            </td>
            <td width="41.2%">
                <table class="no-border">
                    <tr>
                        <td width="66%">Subtotal</td>
                        <td width="14%" class="text-left">:Rp. </td>
                        <td width="20%" class="text-right">
                             {{ number_format($pesananpembelian->total, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td width="66%">DPP (11/12 X Subtotal)</td>
                        <td width="14%" class="text-left">:Rp. </td>
                        <td width="20%" class="text-right"> {{ number_format($pesananpembelian->total * 11/12, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td width="66%">PPN 12%</td>
                        <td width="14%" class="text-left">:Rp. </td>
                        <td width="20%" class="text-right">
                            {{ number_format($pesananpembelian->total * $pesananpembelian->ppn / 100, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="bold">
                        <td width="66%"><b>Grandtotal</b></td>
                        <td width="14%" class="text-left"><b>:Rp. </b> </td>
                        <td width="20%" class="text-right"><b>{{ number_format($pesananpembelian->grandtotal, 0, ',', '.') }}</b></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="mt-10">
        <tr>
            <td>Demikian Surat Pesanan kami , atas perhatianya kami sampaikan terima kasih .</td>
        </tr>
    </table>

    <!-- TANDA TANGAN -->
    <table class="mt-20 no-border text-center">
        <tr>
            <td width="33%" style="text-left">Hormat Kami,</td>
            <td width="34%">Penanggung Jawab Teknis</td>
            <td width="33%">Konfirmasi Penjual,</td>
        </tr>
        <tr>
            <td>
                <img src="{{ public_path('ttd/ttd.png') }}" alt="" height="70px">
            </td>
            <td>
                <img src="{{ public_path('ttd/ttdpjt.png') }}" alt="" height="70px">
            </td>
            <td>

            </td>
        </tr>

        <tr>
            <td>
                <b><u>M. Taufik Krisdianto</u></b><br>
                Direktur
            </td>
            <td><b>( Febrian Nurrohman )</b></td>
            <td>(___________________)</td>
        </tr>
    </table>

</body>

</html>
