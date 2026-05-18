<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Faktur Penjualan</title>

    <style>
        @page {
            size: A5 landscape;
            margin: 0;
        }


        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
        }

        html,
        body {
            margin: 0px;
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
            padding: 2px;
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
            font-size: 20px;
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
            padding: 1px;
        }

        .signature {
            height: 70px;
        }

        .bordertop {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        .border-top-none {
            border-top: none;
            border-bottom: none;
        }

        .item-group {
            border-bottom: none !important;
            border-top: none !important;

        }

        .page-frame {
            border: 1px solid #000;
            padding: 2px;
        }
    </style>
</head>

<body>

    <div class="page-frame">


        <table class="border">
            <tr>
                <td width="48%">
                    <table class="no-border">
                        <tr>
                            <td width="50%" class="text-left">
                                <b>Faktur Penjualan</b>
                            </td>
                            <td width="50%" class="text-right">
                                No Faktur : {{ $fakturpenjualan->no_perusahaan }}
                            </td>
                        </tr>
                    </table>

                </td>
                <td width="52%">
                    <table class="no-border">
                        <tr>
                            <td width="50%" class="text-left">
                                Tanggal : {{ \Carbon\Carbon::parse($fakturpenjualan->tanggal)->format('d/m/Y') }}
                            </td>
                            <td width="50%" class="text-right">
                                Jatuh Tempo :
                                {{ \Carbon\Carbon::parse($fakturpenjualan->tanggal_jatuh_tempo)->format('d/m/Y') }}
                            </td>
                        </tr>
                    </table>
                </td>


            </tr>
            <tr>
                <td>
                    <b>PT. SYAHID HUSADA DEWATA</b><br>
                    Jl. Padang Indah II/16 Denpasar<br>
                    NPWP : 002.152.666.0-901.000
                </td>
                <td>
                    <table class="no-border">
                        <tr>
                            <td width="100%"><b>PEMBELI / PENERIMA JASA :</b><br></td>
                        </tr>
                    </table>
                    <table class="no-border">

                        <tr>
                            <td width="8%">Nama</td>
                            <td width="2%">:</td>
                            <td width="90%">{{ $fakturpenjualan->customers->nama }}</td>
                        </tr>

                        <tr>
                            <td width="8%">Alamat</td>
                            <td width="2%">:</td>
                            <td width="90%">{{ $fakturpenjualan->customers->alamat }}</td>
                        </tr>

                        <tr>
                            <td width="8%">NPWP</td>
                            <td width="2%">:</td>
                            <td width="90%">{{ $fakturpenjualan->customers->npwp }}</td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
        <table class="border">
            <thead>
                <tr class="text-center bold">
                    <th width="6%">Qty</th>
                    <th width="7%">Kode</th>
                    <th width="34%">Nama Barang</th>
                    <th width="10%">EXP</th>
                    <th width="12%">SN / LOT</th>
                    <th width="10%">Harga</th>
                    <td width="5%"><b>Disc</b></td>
                    <th width="14%">Jumlah</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $no = 0;
                ?>

                @foreach ($fakturpenjualandetails as $item)
                    <?php
                    if ($item->products->merks) {
                        $merks = $item->products->merks->nama;
                    } else {
                        $merks = '';
                    }
                    ?>
                    @if ($item->pengirimanbarang_id != null)
                        @foreach ($item->pengirimanbarangdetail->stokexpdetail as $exp)
                            {{ $no++ }}
                            <tr>
                                <td class="text-left item-group">{{ $exp->qty * -1 }} {{ $item->satuan }}</td>
                                <td class="item-group">{{ $item->products->kode_item }}</td>
                                <td class="item-group">
                                    {{ $merks }} {{ $item->products->nama }}<br>
                                    {{ $item->products->no_ijinedar }}
                                </td>
                                <td class="text-center item-group">
                                    @if ($item->products->status_exp == 1)
                                        {{ \Carbon\Carbon::parse($exp->stockExp->tanggal)->format('F Y') }}    
                                    @else
                                        <span>----</span>
                                    @endif
                                    
                                </td>
                                <td class="text-center item-group">{{ $exp->stockExp->lot }}</td>
                                <td class="item-group">
                                    <table width="100%" class="no-border">
                                        <tr>
                                            <td style="width: 30%" class="text-left">Rp.</td>
                                            <td style="width: 50%" class="text-right">
                                                {{ number_format($item->hargajual, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="text-center item-group">
                                    {{ number_format($item->diskon_persen, 0, ',', '.') }} %</td>
                                <td class="item-group">
                                    <table width="100%" class="no-border">
                                        <tr>
                                            <td style="width: 30%" class="text-left">Rp.</td>
                                            <td style="width: 50%" class="text-right">
                                                {{ number_format($item->hargajual * ($exp->qty * -1), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        {{ $no++ }}
                        <tr>
                            <td class="text-left item-group">{{ $item->qty }} {{ $item->satuan }}</td>
                            <td class="item-group">{{ $item->products->kode_item }}</td>
                            <td class="item-group">
                                {{ $merks }} {{ $item->products->nama }}<br>
                                {{ $item->products->no_ijinedar }}
                            </td>
                            <td class="text-center item-group">-----</td>
                            <td class="text-center item-group">-----</td>
                            <td class="item-group">
                                <table width="100%" class="no-border">
                                    <tr>
                                        <td style="width: 30%" class="text-left">Rp.</td>
                                        <td style="width: 50%" class="text-right">
                                            {{ number_format($item->hargajual, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="text-center item-group"> {{ number_format($item->diskon_persen, 0, ',', '.') }}
                                %</td>
                            <td class="item-group">
                                <table width="100%" class="no-border">
                                    <tr>
                                        <td style="width: 30%" class="text-left">Rp.</td>
                                        <td style="width: 50%" class="text-right">
                                            {{ number_format($item->hargajual * $item->qty, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    @endif
                @endforeach


                <!-- baris kosong tapi tetap ada BORDER -->
                @for ($i = 1; $i < 7 - $no; $i++)
                    <tr>
                        <td class="text-center item-group">&nbsp;</td>
                        <td class="item-group"></td>
                        <td class="item-group">
                            <br>
                        </td>
                        <td class="text-center item-group"></td>
                        <td class="item-group"></td>
                        <td class="text-right item-group"></td>
                        <td class="item-group"></td>
                        <td class="text-right item-group"></td>
                    </tr>
                @endfor

            </tbody>
        </table>

        <table class="border">
            <tr>

                <td width="58.15%">
                    <table class="no-border">
                        <tr>
                            <b>Terbilang :</b><br>
                        </tr>
                        <td height="20px" style="font-size:10px">
                            {{ $tertulis }} Rupiah
                        </td>
                    </table>

                    <table class="no-border">
                        <tr>
                            <b>Keterangan :</b><br>
                        </tr>
                        <td height="30px">
                            {{ $fakturpenjualan->keterangan }}
                        </td>
                    </table>

                </td>
                <td width="41.85%">
                    <table class="no-border">
                        <tr>
                            <td width="66%">Jumlah</td>
                            <td width="14%" class="text-left">:Rp. </td>
                            <td width="20%" class="text-right">
                                {{ number_format($fakturpenjualan->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td width="66%">Discount</td>
                            <td width="14%" class="text-left">:Rp. </td>
                            <td width="20%" class="text-right">
                                {{ number_format($fakturpenjualan->total_diskon, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td width="66%">DPP (11/12 X Subtotal)</td>
                            <td width="14%" class="text-left">:Rp. </td>
                            <td width="20%" class="text-right">
                                {{ number_format(($fakturpenjualan->subtotal * 11) / 12, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td width="66%">PPN 12%</td>
                            <td width="14%" class="text-left">:Rp. </td>
                            <td width="20%" class="text-right">
                                {{ number_format($fakturpenjualan->ppn, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td width="66%">Materai</td>
                            <td width="14%" class="text-left">:Rp. </td>
                            <td width="20%" class="text-right">
                                {{ number_format($fakturpenjualan->materai, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="bold">
                            <td width="66%"><b>Jumlah yang harus dibayar</b></td>
                            <td width="14%" class="text-left">:Rp. </td>
                            <td width="20%" class="text-right">
                                {{ number_format($fakturpenjualan->grandtotal, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="no-border">
            <tr>
                <td width="58.15%" style="font-size:10px">
                    PO Customer : {{ $fakturpenjualan->no_sp_customer }}
                </td>
                <td width="41.85%" style="font-size:10px">Tanggal PO Customer. :
                    {{ $fakturpenjualan->tanggal_sp_customer }}
                </td>
            </tr>
        </table>

        <table class="no-border text-center" style="margin-top:30px">
            <tr>
                <td>Penerima</td>
                <td>Penanggung Jawab Teknis</td>
                <td>Hormat Kami</td>
            </tr>
            <tr>
                <td height="70px"></td>
                <td>
                    <img src="{{ public_path('ttd/ttdpjt.png') }}" alt="" height="70px">
                </td>
                <td>
                </td>
            </tr>

            <tr class="bold">
                <td>(_______________________)</td>
                <td>( Febrian Nurrohman )</td>
                <td> <u>M. Taufik Krisdianto</u><br><span class="small">Direktur</span></td>
            </tr>
        </table>

        <div style="border: 0.5px solid black;width: 100%;line-height:90%;margin-bottom:12.2px">
            <p style="font-size:90%;text-align:center"><b> Pembayaran dapat ditransfer ke Rek : <b>{{ $bank->nama }}
                        No. {{ $bank->nomor }}
                    </b> a/n PT Syahid Husada Dewata </b></p>
        </div>
    </div>


</body>

</html>
