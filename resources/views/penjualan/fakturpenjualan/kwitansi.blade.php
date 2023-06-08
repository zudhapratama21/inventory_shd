
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> --}}

    <style type="text/css">
        .tabel {
            border-collapse: collapse;
            border-style: double;
        }

        .tabel td,
        th,
        tr {
            /* border: 1px  double black; */
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
            <td width="15%" style="text-align: center;">
                <div style="transform: rotate(270deg);position: fixed;margin-left:-200px;width: 600px;margin-top:240px;">
                   <h1 style="margin-top: 5px; margin-bottom: 10px;color:rgb(5, 2, 186)">PT BRILIAN SUKSES BERKAH</h1>
                   <h3 style="margin-top: 0px; margin-bottom: 3px;">Juanda Regency Blok H-06, JL. Raya Bypass Juanda NO.11
                    Rai <br> Pabean-Sedati, Sidoarjo
                    Kode Pos : 61253</h3>
                   {{-- <h3 style="margin-top: 0px;margin-bottom: 5px;">NPWP : 03.113.119.6-615.000</h3> --}}
                </div>
            </td>
            <td width="70%" style="padding-left:10px">

                <div>
                   <table style="margin-top:5px;">
                        <tr >
                            <td width="70%" style="padding-left : 10px">
                                <h3>Kwitansi No. {{$faktur}}</h3> <br>
                            </td>
                        </tr>
                   </table>
                </div>  
                <div >
                    <table width="100%" style="margin-top:10px;">
                        <tr>
                            <td width="30%"  style="border: 1px double white">
                                <h3>Sudah Terima Dari </h3>
                            </td>
                            <td  width="1%" style="border: 1px double white">
                                <h3>:</h3>
                            </td>
                            <td width="78%" style="border: 1px double white">
                                <h3>{{$customer}}.</h3>
                            </td>
                           
                        </tr>

                    </table>
                </div > 
                <div  >
                    <table width="100%" style="margin-top:10px;">
                        <tr>
                            <td width="30%"  style="border: 1px double white">
                                <h3>Yang Sejumlah</h3>
                            </td>
                            <td  width="1%" style="border: 1px double white">
                                <h3>:</h3>
                            </td>
                            <td width="78%" style="border: 1px double white">
                                <h3> <i>{{$text}}</i></h3>
                            </td>
                        </tr>

                    </table>
                </div> 
                <div style="margin-bottom: 60px">
                    <table width="100%" style="margin-top:10px;margin-bottom:50px">
                        <tr>
                            <td width="30%"  style="border: 1px double white">
                                <h3>Untuk Pembayaran</h3>
                            </td>
                            <td  width="1%" style="border: 1px double white">
                                <h3>:</h3>
                            </td>
                            <td width="78%" style="border: 1px double white">
                                <h3>Atas Faktur No. {{$faktur}} , Sebagaimana Terlampir  : </h3>
                            </td>
                        </tr>
                    </table>
                </div> 
                <div style="margin-top: 20px">
                    <table width="100%" style="margin-top:10px;">
                        <tr>
                            <td width="50%"  style="border-left: 1px double white;border-right : 1px double white">
                                <table width="100%" style="margin-top:5px;">
                                    <tr>
                                        <td width="45%" style="font-size:90%;border:1px white">
                                            <h2> Terbilang Rp.</h2> 
                                        </td>
                                        <td width="55%" style="text-align: center;padding-right:12px">
                                            <h1>{{  number_format($grandtotal, 0, ',', '.')}}</h1>
                                        </td>
                                    </tr>
                                </table>
                                
                            </td>
                            <td width="10%" style="border: 1px double white">

                            </td>
                            <td width="40%" style=' text-align:center; vertical-align:top;border: 1px double white'>
                                <h3 style="margin-top: -10px">Surabaya, {{ \Carbon\Carbon::now()->format("d F Y") }}</h3>
                                <br /><br /> <br /><br /> <br /> <br />
                                <h3>                                                                       
                                    <u>HEPPY WAHYU</u> <br />
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