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

        .fsSubmitButton {
            padding: 10px 15px 11px !important;
            font-size: 18px !important;
            background-color: #57d6c7;
            font-weight: bold;
            text-shadow: 1px 1px #57D6C7;
            color: #ffffff;
            border-radius: 5px;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border: 1px solid #57D6C7;
            cursor: pointer;
            box-shadow: 0 1px 0 rgba(255, 255, 255, 0.5) inset;
            -moz-box-shadow: 0 1px 0 rgba(255, 255, 255, 0.5) inset;
            -webkit-box-shadow: 0 1px 0 rgba(255, 255, 255, 0.5) inset;
        }
    </style>
    <script language="JavaScript">
        var gAutoPrint = true; // Tells whether to automatically call the print function
            
            function printSpecial()
            {
                    if (document.getElementById != null)
                {
                var html = '<HTML>\n<HEAD>\n';
            
                if (document.getElementsByTagName != null)
                {
                    var headTags = document.getElementsByTagName("head");
                    if (headTags.length > 0)
                    html += headTags[0].innerHTML;
                }
            
                html += '\n</HE>\n<BODY onload="Child.close();" >\n';
            
                var printReadyElem = document.getElementById("printReady");
            
                if (printReadyElem != null)
                {
                    html += printReadyElem.innerHTML;
                }
                    else
                {
                alert("Could not find the printReady function");
                return;
            }
            
            html += '\n</BO>\n</HT>';
            
            var printWin = window.open("","printSpecial");
            //childWindows = printWin;
            printWin.document.open();
            printWin.document.write(html);
           
            
            if (gAutoPrint)
                printWin.print();
                 printWin.close();
               // window.onfocus=function(){ window.close();}
            }
            else
                {
                    alert("The print ready feature is only available if you are using an browser. Please update your browswer.");
                }
            }

    </script>
</head>

<body style="font-family: sans-serif; margin-top:-15px;">
    <div id="printReady">
       
        @for($i = 1; $i <= $totalPage; $i++) 
        <table border="0" width="100%">
            <tr>
                <td width="20%" style="font-size: 70%; vertical-align: top;">
                    <h3>PT BRILIAN SUKSES BERKAH</h3>
                    <p>Juanda Regency Blok H-06, JL. Raya Bypass Juanda NO.11, Pabean-Sedati, Sidoarjo , Kode Pos 61253.
                    </p>
                </td>
                <td width="25%" style="font-size: 75%; vertical-align: top; text-align: center;">
                    <center><b></b></center>
                    <center><b>

                        </b></center>
                </td>
                <td width="20%" style="vertical-align: top; text-align: left; font-family: sans-serif">
                    <table >
                        <tr style="padding:0px;">
                            <td style="padding:0px;font-size: 70%; " colspan="3"><br />Sidoarjo, {{\Carbon\Carbon::parse($canvassing->tanggal)->format("d F Y")}}</td>                            
                        </tr>
                        <tr style="padding:0px;">
                            <td style="padding:0px;font-size: 70%; ">KEPADA :</td>
                            <td style="padding:0px;font-size: 70%; "></td>
                            <td style="padding:0px;font-size: 70%; ">

                            </td>
                        </tr>
                        <tr style="padding:0px;">
                            <td style="padding:0px;font-size: 70%; " colspan="3">{{ $canvassing->customer->nama
                                }}</td>

                        </tr>
                        <tr style="padding:0px;">
                            <td style="padding:0px;font-size: 70%; " colspan="3">{{ $canvassing->customer->alamat
                                }}, Blok {{ $canvassing->customer->blok
                                }}, No. {{ $canvassing->customer->nomor
                                }}, {{ $canvassing->customer->namakota->name
                                }}</td>
                        </tr>
                    </table>
                </td>               
            </tr>
            <tr>
                <td width="20%" style="font-size: 70%; vertical-align: top;">
                    Surat Jalan : {{ $canvassing->kode }}
                </td>
                <td width="25%" style="font-size: 75%; vertical-align: top; text-align: center;">
                    <center><b></b></center>
                    <center><b>
                    </b></center>
                </td>
                {{-- <td width="20%" style="font-size: 70%; vertical-align: top;">
                    SO Cust. : {{ $canvassing->SO->no_so }}
                </td>                --}}
            </tr>
            

            <tr>
                <td width="20%" style="font-size: 70%; vertical-align: top;">
                </td>
                <td width="25%" style="font-size: 75%; vertical-align: top; text-align: center;">
                    <center><b></b></center>
                    <center><b>
                    </b></center>
                </td>
                {{-- <td width="20%" style="font-size: 70%; vertical-align: top;">
                    Tanggal SO Cust. : {{  $canvassingdetails[0]->canvassing->tanggal ? \Carbon\Carbon::parse($canvassingdetails[0]->canvassing->tanggal)->format('d/m/Y') :'-' }}
                </td>                --}}
            </tr>

        </table>          
           
            <div style="height:200px;">
                <table width="100%" >
                    <tr>
                        <td colspan="5">
                            <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 0.3px 0px 0px;">
                        </td>
                    </tr>
                    <tr style="">
                        <td style="font-size: 70%; width:3%;">NO</td>
                        <td style="font-size: 70%; width:57%">BARANG</td>
                        <td style="font-size: 70%; width:10%">SATUAN</td>
                        <td style="font-size: 70%; width:10%">QTY</td>
                        <td style="font-size: 70%; width:20%">KET.</td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 0.3px 0px 0px;">
                        </td>
                    </tr>
                    @php
                    $n=1;
                    $j=1;
                    @endphp
                    @foreach($canvassingdetails as $a)
                    @if($n > (($i-1)*$perBaris) && $n <= ($i)*$perBaris) <tr class="" style="vertical-align: top">
                        <td style="font-size: 70%;" class="text-left">{{ $j }}.</td>
                        <td style="font-size: 70%;" class="text-left">{{ $a->product->nama }}</td>
                        <td style="font-size: 70%;" class="text-left">{{ $a->satuan }}</td>
                        <td style="font-size: 70%;" class="text-left">{{ $a->qty }}</td>
                        <td style="font-size: 60%;" class="text-left">{{ $a->keterangan }} ED: 
                            @foreach($listExp as $x)
                                @if($a->product_id == $x->product_id) {{ $x->tanggal->format("m/y") }}({{ $x->qty * -1 }})-{{$x->stockExp->lot}} 
                                @endif
                            @endforeach
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
            <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 0.3px 0px 0px;">
            <font style="font-size: 70%; ">KETERANGAN :
                {{ $canvassing->keterangan }}
            </font>
            <br />
            <table width="100%">
                <tr>
                    <td>
                        <table class="tabelxx">
                            <tr>
                                <td style="font-size: 70%;  text-align: center; "> HORMAT KAMI </td>
                                <td style="font-size: 70%; text-align: center;"> GUDANG </td>
                                <td style="font-size: 70%;text-align: center;"> EKSPEDISI </td>
                                <td style="font-size: 70%; text-align: center;"> PENERIMA </td>

                            </tr>
                            <tr>
                                <td style="font-size: 75%; vertical-align: top; text-align: center; ">
                                    <br /><br />( . . . . . . . . . . . . . . . . )
                                </td>
                                <td style="font-size: 75%; vertical-align: top; text-align: center;">
                                    <br /><br />
                                    ( . . . . . . . . . . . . . . . . )


                                </td>
                                <td style="font-size: 75%; vertical-align: top; text-align: center;">
                                    <br /><br />( . . . . . . . . . . . . . . . . )
                                </td>
                                <td style="font-size: 75%; vertical-align: top; text-align: center;">
                                    <br /><br />( . . . . . . . . . . . . . . . . )
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="font-size: 70%; vertical-align: bottom; text-align: left;">
                        Hal :
                        {{ $i }} /
                        {{ $totalPage }}<br />
                        User : {{ $canvassing->creator->name }}
                    </td>
                </tr>
            </table>

            @if($totalPage <> $i)
                <div style="page-break-after: always;"></div>
                @endif
                @endfor

    </div>

</body>

</html>