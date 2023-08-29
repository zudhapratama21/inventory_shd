
<!DOCTYPE html>
<html>
<head>
    {{-- <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> --}}

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
    @for($i = 1; $i <= $totalPage; $i++) 
    <table width="100%" style="margin-top: 0px; border-collapse:collapse">
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
                <table >
                    <tr style="padding:0px;">
                        <td style="padding:0px;font-size: 70%; " colspan="3"><br />Sidoarjo, 
                           {{$pesananpembelian->tanggal->format('d F Y')}}
                        </td>                            
                    </tr>
                    <tr style="padding:0px;">
                        <td style="padding:0px;font-size: 70%; ">KEPADA :</td>
                        <td style="padding:0px;font-size: 70%; "></td>
                        <td style="padding:0px;font-size: 70%; ">

                        </td>
                    </tr>
                    <tr style="padding:0px;">
                        <td style="padding:0px;font-size: 70%; " colspan="3">{{ $pesananpembelian->suppliers->nama
                            }}</td>

                    </tr>
                    <tr style="padding:0px;">
                        <td style="padding:0px;font-size: 70%; " colspan="3">{{ $pesananpembelian->suppliers->alamat
                            }}, Blok {{ $pesananpembelian->suppliers->blok
                            }}, No. {{ $pesananpembelian->suppliers->nomor
                            }}, {{ $pesananpembelian->suppliers->namakota->name
                            }}</td>

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

        <tr>
            <td colspan="6" style="vertical-align: top;display: flex">
                <div class="isi" style="height: 450px;">
                    <table style="width:100%" border="0" class="xyz" >
                        <tr>
                            <td colspan="10">
                                <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 1px 0px 0px;">
                            </td>
                        </tr>
                        <tr style="">
                            {{-- <td style="font-size: 75%; border:none; width:10%;">KODE</td> --}}
                            <td style="font-size: 60%; border:none; width:3%;text-align:left">NO</td>
                            <td style="font-size: 60%; border:none; width:7%;text-align:left">KEBUTUHAN</td>
                            <td style="font-size: 60%; border:none; width:24%;text-align:left">NAMA BARANG</td>
                            <td style="font-size: 60%; border:none; width:8%;text-align:right">MERK</td>
                            <td style="font-size: 60%; border:none; width:12%;text-align:right">HARGA</td>
                            <td style="font-size: 60%; border:none; width:6%;text-align:center">DISK.(%)</td>
                            <td style="font-size: 60%; border:none; width:10%;text-align:left">DISK.(RP)</td>
                            <td style="font-size: 60%; border:none; width:10%;text-align:right">SUBTOTAL</td>
                            <td style="font-size: 60%; border:none; width:10%;text-align:right">TOTAL DISC</td>
                            <td style=" font-size: 60%;border:none; width:10%;text-align:right">TOTAL</td>
                        </tr>
                        <tr>
                            <td colspan="10">
                                <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 1px 0px 0px;">
                            </td>
                        </tr>

                        @php
                        $n=1;
                        $j=1;
                        @endphp
                        @foreach($pesananpembeliandetail as $a)
                        @if($n > (($i-1)*$perBaris) && $n <= ($i)*$perBaris)<tr class="" style="vertical-align: top"> 
                                <td style="font-size: 70%; ">{{ $j++ }}</td>
                                <td style="font-size: 70%; ">{{ $a->qty }} {{ $a->satuan }}</td>
                                <td style="font-size: 70%;font-family: DejaVu Sans; sans-serif; ">{{ $a->products->nama }}</td>
                                <td style="font-size: 70%; text-align:right;">{{ $a->products->merks->nama }}</td>
                                <td style="font-size: 70%; text-align:right">{{ number_format($a->hargabeli, 0, ',', '.')
                                    }}</td>
                                <td style="font-size: 70%; text-align:center">{{ number_format($a->diskon_persen, 0, ',', '.')
                                    }}</td>
                                <td style="font-size: 70%; text-align:left">{{ number_format($a->diskon_rp, 0, ',', '.')
                                    }}</td>
                                <td style="font-size: 70%; text-align:right">{{ number_format($a->subtotal, 0, ',', '.')
                                    }}</td>
                                <td style="font-size: 70%; text-align:right">{{ number_format($a->total_diskon, 0, ',',
                                    '.') }}</td>
                                <td style="font-size: 70%; text-align:right">{{ number_format($a->total, 0, ',',
                                    '.') }}</td>            

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


        <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 1px 0px 0px;display: flex;">
        <table width="100%" >
            <tr>
                @if ($i == $totalPage)
                <td style="text-align: right">
                    <table width="100%" >
                        <tr>
                            <td style='font-size: 70%; width: 75%; line-height:90%'><b>Total Jumlah</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; line-height:90%; text-align:right'><b>
                                    {{ number_format(floor($pesananpembelian->subtotal), 0, ',', '.') }}
                                </b></td>
                        </tr>
                        <tr>
                            <td style='font-size: 70%; width: 75%; line-height:90%'><b>Discount</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; line-height:90%; text-align:right'><b>
                                    {{ number_format(floor($pesananpembelian->total_diskon_header), 0, ',', '.') }}
                                </b></td>
                        </tr>
                        <tr>
                            <td style='font-size: 70%; width: 75%; line-height:90%'><b>Total Harga</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; line-height:90%; text-align:right'><b>
                                    {{ number_format(floor($pesananpembelian->total), 0, ',', '.') }}
                                </b></td>
                        </tr>
                        <tr>
                            <td style='font-size: 70%; width: 75%;'><b>PPN ({{$pesananpembelian->ppn}}) %</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; text-align:right'><b>
                                    {{ number_format(floor($pesananpembelian->total * $pesananpembelian->ppn/100), 0, ',', '.') }}
                                </b></td>
                        </tr>
                        {{-- <tr>
                            <td style='font-size: 70%; width: 75%;'><b>Biaya Pengiriman</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; text-align:right'><b>
                                    {{ number_format(floor($pesananpembelian->ongkir), 0, ',', '.') }}
                                </b></td>
                        </tr> --}}
                        <tr>
                            <td style='font-size: 70%; width: 25%;'><b>Jumlah Yang Harus Dibayar</b></td>
                            <td style='font-size: 70%; width: 5%;'><b>: Rp.</b></td>
                            <td style='font-size: 70%; text-align:right'><b>
                                    {{ number_format(floor($pesananpembelian->grandtotal), 0, ',', '.') }}
                                </b></td>
                        </tr>
                    </table>

                    <hr style="margin-bottom: 0px; margin-top: 0px; border-width: 1px 0px 0px;position: relative;">
                    <table>
                        <tr>
                            <td style="font-size: 70%;">SO Cust. : {{ $pesananpembelian->no_so_customer ? $pesananpembelian->no_so_customer : '-' }}</td>
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
                                    $pesananpembelian->keterangan }}</b>
                                <br /> <br /><br /> <br /> <br /><br /><br /> <br /><br /> <br /> <br />


                            </td>

                            <td style='font-size: 70%; text-align:center; vertical-align:top'>Sidoarjo, {{
                                $pesananpembelian->tanggal->format("d
                                F Y")
                                }}

                                <br /><br /> <br /><br /> <br /> <br />
                                <u>HEPPY WAHYU</u> <br />
                                Direktur
                            </td>
                        </tr>
                    </table>



                </td>
                @else
                <td style="text-align: right;page-break-after:always;">
                </td>
                @endif
              

            </tr>
        </table>
        
        @if($totalPage <> $i)
            <div style="page-break-after: always;"></div> 
            @endif



            @endfor

</body>

</html>