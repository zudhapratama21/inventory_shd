<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Faktur Penjualan</title>

    <style>
        @page {
            size: A4;
            margin: 0;
            border: 1px solid #000;
        }


        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10px;
            color: #000;
        }

        html,
        body {
            margin: 0.1px;
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
            line-height: 1;
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

        /* .page-frame {
            border: 1px solid #000;
            padding: 2px;
        } */
    </style>
</head>

<body>

    <div class="page-frame">
        <!-- HEADER -->
        <div class="title">FAKTUR PENJUALAN</div>
        <div class="subtitle text-bold">PT. SYAHID HUSADA DEWATA</div>
        <div class="capt">Jl. Padang Indah II No. 16 Denpasar</div>
        <div class="capt">NPWP : 002.152.666.0.901.000</div>

        <!-- INFO FAKTUR -->
        <table class="mt-10 bordertop">
            <tr>
                <td width="42%">No. Faktur : <b>{{ $fakturpenjualan->no_perusahaan }}</b></td>
                <td width="30%" style="border-left: none">Tanggal :
                    <b>{{ \Carbon\Carbon::parse($fakturpenjualan->tanggal)->format('d/m/Y') }}</b>
                </td>
                <td width="28%" class="text-right">Jatuh Tempo :
                    <b>{{ \Carbon\Carbon::parse($fakturpenjualan->jatuh_tempo)->format('d/m/Y') }}</b>
                </td>
            </tr>
        </table>

        <!-- DATA PEMBELI -->
        <table class="no-border">
            <tr>
                <td width="15%"><b>Pembeli BKP</b></td>
                <td width="2%">:</td>
                <td width="83%"></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><b>{{ $fakturpenjualan->customers->nama }}</b></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $fakturpenjualan->customers->alamat }}</td>
            </tr>
            <tr>
                <td>NPWP</td>
                <td>:</td>
                <td>{{ $fakturpenjualan->customers->npwp }}</td>
            </tr>
        </table>

        <!-- TABEL BARANG -->
        <table class="border mt-10">
            <thead style="font-size: 10px">
                <tr class="text-center text-bold">
                    <th width="6%">Kuantum</th>
                    <th width="8%">Kode Item</th>
                    <th width="34%">Nama Barang</th>
                    <th width="12%">EXP</th>
                    <th width="12%">SN / LOT</th>
                    <th width="10%">Harga</th>
                    <th width="5%">Disc</th>
                    <th width="13%">Jumlah</th>
                </tr>
            </thead>
            <tbody style="font-size: 10px">
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
                                <td class="text-center item-group">{{ $exp->qty * -1 }} {{ $item->satuan }}</td>
                                <td class="item-group text-center">{{ $item->products->kode_item }}</td>
                                <td class="item-group">
                                    {{ $merks }} {{ $item->products->nama }}
                                    @if ($item->products->no_ijinedar)
                                        {{ $no++ }}
                                        <br>{{ $item->products->no_ijinedar }}
                                    @endif
                                </td>
                                <td class="text-center item-group">
                                    @if ($item->products->status_exp == 1)
                                        {{ \Carbon\Carbon::parse($exp->stockExp->tanggal)->format('F Y') }}
                                    @else
                                        <span>-</span>
                                    @endif

                                </td>
                                <td class="text-center item-group">{{ $exp->stockExp->lot }}</td>
                                <td class="item-group">
                                    <span style="float:left">Rp.</span>
                                    <span style="float:right">
                                        {{ number_format($item->hargajual, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="text-center item-group">
                                    {{ number_format($item->diskon_persen, 0, ',', '.') }} %
                                </td>

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
                            <td class="text-center item-group">{{ $item->qty }} {{ $item->satuan }}</td>
                            <td class="text-center item-group">{{ $item->products->kode_item }}</td>
                            <td class="item-group">
                                {{ $merks }} {{ $item->products->nama }}
                                @if ($item->products->no_ijinedar)
                                    {{ $no++ }}
                                    <br>{{ $item->products->no_ijinedar }}
                                @endif
                            </td>
                            <td class="text-center item-group">-</td>
                            <td class="text-center item-group">-</td>
                            <td class="item-group">
                                <span style="float:left">Rp.</span>
                                <span style="float:right">
                                    {{ number_format($item->hargajual, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-center item-group"> {{ number_format($item->diskon_persen, 0, ',', '.') }}%
                            </td>
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
                @for ($i = 1; $i < 24 - $no; $i++)
                    <tr>
                        <td class="text-center item-group">
                            &nbsp;
                        </td>
                        <td class="item-group">&nbsp;</td>
                        <td class="item-group">&nbsp;</td>
                        <td class="text-center item-group">&nbsp; <br> &nbsp;</td>
                        <td class="item-group">&nbsp;</td>
                        <td class="text-right item-group">&nbsp;</td>  
                        <td class="item-group">&nbsp;</td>
                        <td class="text-right item-group">&nbsp;</td>
                    </tr>
                @endfor

            </tbody>
        </table>

        <!-- TOTAL -->
        <table width="100%" class="border">
            <tr>
                <!-- kiri kosong -->
                <td width="50%">
                    <table class="no-border">
                        <tr>
                            <b>Terbilang :</b><br>
                        </tr>
                        <tr>
                            <td height="20px">
                                {{ $tertulis }} Rupiah
                            </td>
                        </tr>
                    </table>

                    <table class="no-border">
                        <tr>
                            <b>Keterangan :</b><br>
                        </tr>
                        <tr>
                            <td height="30px">
                                {{ $fakturpenjualan->keterangan }}
                            </td>
                        </tr>

                    </table>
                </td>

                <!-- kanan total -->
                <td width="50%">
                    <table class="no-border">
                        <tr>
                            <td width="70%">
                                <table width="100%" class="no-border">
                                    <tr>
                                        <td>Total Jumlah</td>
                                    </tr>
                                    <tr>
                                        <td>Dikurangi Potongan Harga</td>
                                    </tr>
                                    <tr>
                                        <td>Dasar Pengenaan Pajak (11/12 x Sub Total)</td>
                                    </tr>
                                    <tr>
                                        <td>PPN = 12% x Dasar Pengenaan Pajak</td>
                                    </tr>
                                    <tr>
                                        <td>Materai</td>
                                    </tr>
                                    <tr class="text-bold">
                                        <td>Jumlah Harus Dibayar</td>
                                    </tr>
                                </table>
                            </td>
                            <td width="30%">
                                <table width="100%" class="no-border">
                                    <tr>
                                        <td style="width: 30%" class="text-left">Rp.</td>
                                        <td style="width: 50%" class="text-right">
                                            {{ number_format($fakturpenjualan->subtotal, 0, ',', '.') }} </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%" class="text-left">Rp.</td>
                                        <td style="width: 50%" class="text-right">
                                            {{ number_format($fakturpenjualan->total_diskon, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%" class="text-left">Rp.</td>
                                        <td style="width: 50%" class="text-right">
                                            {{ number_format(($fakturpenjualan->subtotal * 11) / 12, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%" class="text-left">Rp.</td>
                                        <td style="width: 50%" class="text-right">
                                            {{ number_format($fakturpenjualan->ppn, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%" class="text-left">Rp.</td>
                                        <td style="width: 50%" class="text-right">
                                            {{ number_format($fakturpenjualan->materai, 0, ',', '.') }} </td>
                                    </tr>
                                    <tr class="text-bold">
                                        <td style="width: 30%" class="text-left">Rp.</td>
                                        <td style="width: 50%" class="text-right">
                                            {{ number_format($fakturpenjualan->grandtotal, 0, ',', '.') }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>
        <table class="no-border">
            <tr>
                <td width="56%" style="font-size:10px">
                    PO Customer : {{ $fakturpenjualan->no_sp_customer }}
                </td>
                <td width="44%" style="font-size:10px">Tanggal PO Customer. :
                    {{ $fakturpenjualan->tanggal_sp_customer }}
                </td>
            </tr>
        </table>

        <!-- TANDA TANGAN -->
        <table class="no-border text-center" style="margin-top:30px">
            <tr>
                <td width="33%" style="text-left">Penerima,</td>
                <td width="34%">Penanggung Jawab Teknis</td>
                <td width="33%">Hormat Kami,</td>
            </tr>
            <tr>
                <td height="50px"></td>
                <td>
                    {{-- <img src="{{ public_path('ttd/ttdpjt.png') }}" alt="" height="50px"> --}}
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>(___________________)</td>
                <td><b>( Febrian Nurrohman )</b></td>
                <td>
                    <b><u>M. Taufik Krisdianto</u></b><br>
                    Direktur
                </td>
            </tr>
        </table>

        <div style="border: 0.5px solid black;width: 100%;line-height:90% ; margin-bottom:11px">
            <p style="font-size:90%;text-align:center"><b> Pembayaran dapat ditransfer ke Rek : <b>{{ $bank->nama }}
                        No. {{ $bank->nomor }}
                    </b> a/n PT Syahid Husada Dewata </b></p>
        </div>

    </div>
</body>

</html>
