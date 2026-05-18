<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <style type="text/css">
        .tabel {
            border-collapse: collapse;
            border-style: double;
        }

        .tabel td,
        th,
        tr {
            border-style: double;
        }

        @media print {
            .tabpage {
                page-break-after: always
            }
        }
    </style>
</head>

<body style="font-family: sans-serif; ">
    <table class="tabel" width="100%" style="font-size:90%;margin-top:2px;">
        <tr>
            <td width="15%" style="text-align:center; vertical-align:middle; overflow:hidden;">

                <img src="{{ public_path('ttd/KOPSURAT.png') }}"
                    style="
                        height: 19%;                        
                        max-height: 500px;
                        transform: rotate(-90deg);
                    ">

            </td>
            <td width="70%" style="padding-left:10px">

                <div>
                    <table style="margin-top:5px;">
                        <tr>
                            <td width="70%" style="border: 1px double white">
                                <h3 style="font-weight: normal;">Kwitansi No. {{ $no_urut }}</h3> <br>
                            </td>
                        </tr>
                    </table>
                </div>
                <div>
                    <table width="100%" style="margin-top:10px;">
                        <tr>
                            <td width="30%" style="border: 1px double white">
                                <h3 style="font-weight: normal;">Sudah Terima Dari </h3>
                            </td>
                            <td width="1%" style="border: 1px double white">
                                <h3>:</h3>
                            </td>
                            <td width="78%" style="border: 1px double white">
                                <h3>{{ $customer }}.</h3>
                            </td>

                        </tr>

                    </table>
                </div>
                <div>
                    <table width="100%" style="margin-top:10px">
                        <tr>
                            <td width="30%" style="border: 1px double white; vertical-align: top;">
                                <h3 style="font-weight: normal; margin: 0;">Jumlah Uang</h3>
                            </td>
                            <td width="1%" style="border: 1px double white; vertical-align: top;">
                                <h3 style="margin: 0;">:</h3>
                            </td>
                            <td width="78%" style="border: 1px double white; vertical-align: top;">
                                <h3 style="margin: 0;">
                                    <i>{{ $text }}</i>
                                </h3>
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="margin-bottom: 60px">
                    <table width="100%" style="margin-top:10px;margin-bottom:50px;">
                        <tr style="vertical-align: top">
                            <td width="30%" style="border: 1px double white">
                                <h3 style="font-weight: normal;">Untuk Pembayaran</h3>
                            </td>
                            <td width="1%" style="border: 1px double white">
                                <h3>:</h3>
                            </td>
                            <td width="78%" style="border: 1px double white">
                                <h3>Pembelian sesuai Atas Faktur No. {{ $faktur }} , Sebagaimana Terlampir : </h3>
                            </td>
                        </tr>
                    </table>
                </div>
                <br><br><br>
                <div style="margin-top: 15px">
                    <table width="100%">
                        <tr>
                            <td width="50%" style="border:1px white">
                                <table width="100%" style="border-top:1px solid black;border-bottom:1px solid black">
                                    <tr>
                                        <td width="45%" style="font-size:90%;border:1px white">
                                            <h2> Terbilang : </h2>
                                        </td>
                                        <td width="55%" style="text-align: left;border:1px white">
                                            <h1>Rp. {{ number_format($grandtotal, 0, ',', '.') }}</h1>
                                        </td>
                                    </tr>
                                </table>

                            </td>
                            <td width="10%" style="border: 1px double white">

                            </td>
                            <td width="40%" style=' text-align:center; vertical-align:top;border: 1px double white'>
                                <h3 style="margin-top: -10px">Denpasar {{ $tanggal }}</h3>
                                <p style="margin-top: -10px">Yang menerima, </p>
                                <h3 style="margin-top: -10px">PT SYAHID HUSADA DEWATA</h3>
                                <br><br><br><br><br><br>
                                <h3>
                                    <u>M. Taufik Krisdianto</u> <br />
                                    Direktur
                                </h3>
                            </td>
                        </tr>
                    </table>

                </div>

            </td>
        </tr>


    </table>


</body>

</html>
