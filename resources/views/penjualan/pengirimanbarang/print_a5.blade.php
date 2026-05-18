<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Jalan</title>

    <style>
        @page {
            size: A5 landscape;
            margin: 0;
        }

        .page-frame {
            border: 1px solid #000;
        }


        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
        }

        html,
        body {
            margin: 1px;
            padding: 0;
        }


        table {
            width: 100%;
            border-collapse: collapse;
        }

        .border {
            border: 1px solid #000;
            padding: 0px 20px 0px 20px;
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
            padding: 10px;
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
    </style>
</head>

<body>
    <div class="page-frame">
        <table class="no-border">
            <tr>
                <td width="60%" style="vertical-align: top;">
                    <table>
                        <tr>
                            <td>
                                <span style="font-size:15px"><b>PT SYAHID HUSADA DEWATA</b></span>
                                <br>
                                <span style="font-size:12px"><u>Jl. Padang Indah II/16 Denpasar</u></span>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <span style="font-size: 15px"><b>SURAT JALAN</b></span> <br>
                                Kami kirimkan barang-barang tersebut dibawah ini :
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="40%" style="vertical-align: top;">
                    <table>
                        <tr>
                            <td>Denpasar, {{ \Carbon\Carbon::parse($pengirimanbarang->tanggal)->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td>
                                Kepada Yth <br>
                                <b>{{ $pengirimanbarang->customers->nama }}</b><br>
                                {{ $pengirimanbarang->customers->alamat }} <br>
                                {{ ucfirst($pengirimanbarang->customers->prov->name) }}
                            </td>

                        </tr>
                    </table>
                </td>
            </tr>

        </table>

        <div>
            <table class="border" style="height: 200px">
                <thead>
                    <tr class="text-center">
                        <th width="10%">Banyaknya</th>
                        <th width="45%">Nama Barang</th>
                        <th width="15%">Exp Date</th>
                        <th width="30%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listExp as $item)
                        <tr>
                            <td class="text-center item-group">{{ $item->qty * -1 }} @if ($item->pengirimandetail->beda_satuan == 'on')
                                    {{ strtolower($item->pengirimandetail->satuan_konversi) }}
                                @else
                                    {{ strtolower($item->pengirimandetail->satuan) }}
                                @endif
                            </td>
                            <td class="item-group">
                                {{ $item->products->nama }}
                            </td>
                            <td class="text-center item-group">
                                @if ($item->products->status_exp == 0)
                                    <span>-</span>
                                @else
                                    {{ \Carbon\Carbon::parse($item->stockExp->exp_date)->format('M-Y') }}
                                @endif

                            </td>
                            <td class="item-group">Lot : {{ $item->stockExp->lot }}</td>
                        </tr>
                    @endforeach

                    @for ($i = 1; $i <= 12 - $list; $i++)
                        <tr>
                            <td class="text-center item-group">&nbsp;</td>
                            <td class="item-group"></td>
                            <td class="text-center item-group"></td>
                            <td class="item-group"></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>


        <!-- TANDA TANGAN -->
        <table class="mt-10 no-border text-center">
            <tr>
                <td width="33%" style="text-center">Penerima</td>
                <td width="34%"></td>
                <td width="33%">Hormat Kami,</td>
            </tr>
            <tr>
                <td height="35px"></td>
                <td></td>
                <td>
                </td>
            </tr>

            <tr>
                <td>(_________________________)</td>
                <td></td>
                <td>
                    <b><u>M. Taufik Krisdianto</u></b><br>
                    Direktur
                </td>
            </tr>
        </table>
        <br>
    </div>
</body>

</html>
