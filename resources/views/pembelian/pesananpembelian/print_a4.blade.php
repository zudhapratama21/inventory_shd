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
        <hr>
    </div>

    <div class="title"><u>Surat Pesanan</u></div>
    <div class="capt">No. {{ $pesananpembelian->no_so }}</div>

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
                    <td class="item-group text-center">{{  ($item->products->merks ? $item->products->merks->nama : '') }}</td>
                    <td class="text-center item-group" >{{ $item->qty }}</td>
                    <td class="item-group text-center" >
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

            @for ($i = 1; $i < 24 - $jumlahproduk; $i++)
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
                    <tr>
                        <td width="99.9%">- {{ $pesananpembelian->keterangan }}</td>
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
                            {{ number_format($pesananpembelian->ppn, 0, ',', '.') }}
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

    <table class="mt-2">
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
