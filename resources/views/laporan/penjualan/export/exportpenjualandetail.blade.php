<table>
    <thead>
        <tr>
            <th>No</th>
            <th>ID Faktur</th>
            <th>ID Produk</th>
            <th>ID Pengiriman</th>
            <th>Tanggal</th>
            <th>Kode Faktur</th>
            <th>No KPA</th>
            <th>Kode SO</th>
            <th>Kode SJ</th>
            <th>No Pajak</th>
            <th>Komoditas</th>
            <th>Kategori Pesanan</th>
            <th>Customer</th>    
            <th>Kategori Customer</th>                                    
            <th>Diskon Rupiah Faktur</th>
            <th>Diskon Persen Faktur</th>
            <th>Subtotal Faktur</th>
            <th>Total Diskon Detail Faktur</th>
            <th>Total Diskon Header Faktur</th>
            <th>Total Faktur</th>
            <th>Ongkir Faktur</th>  
            <th>PPN Faktur</th>                                                                                          
            <th>Grand Total Faktur</th>                    
            <th>Nama Produk</th> 
            <th>Kode Produk</th>  
            <th>Merk</th> 
            <th>Qty</th>
            <th>Satuan</th>
            <th>Diskon Persen Produk</th>
            <th>Diskon Rupiah Produk</th>
            <th>Harga Jual Produk</th>
            <th>Subtotal Produk</th>
            <th>Total Diskon Produk</th>
            <th>PPN(11%)</th>
            <th>Disc CN</th>
            <th>Total Produk</th>
            <th>Ongkir Produk</th>
            <th>Harga Bersih</th>
            <th>Sales</th>
            <th>Pembuat</th>                                        
            <th>Keterangan</th>                                          
            <th>Keterangan Produk</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no=1;
        @endphp
        @foreach ($penjualan as $item)
            <tr>
                <td>{{$no++}}</td>
                <th>{{$item->id}}</th>
                <th>{{$item->id_product}}</th>
                <th>{{$item->id_pengiriman}}</th>
                <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                <td>{{$item->kode}}</td>
                <td>{{$item->no_kpa}}</td>
                <td>{{$item->kode_SP}}</td>
                <td>{{$item->kode_SJ}}</td>
                <td>{{$item->no_seri_pajak ? $item->no_seri_pajak : ''}} - {{ $item->no_pajak ? $item->no_pajak : ''}}</td>
                <td>{{$item->nama_komoditas}}</td>
                <td>{{$item->nama_kategori_pesanan}}</td>
                <td>{{$item->nama_customer}}</td>
                <td>{{$item->nama_kategori_customer}}</td>
                <td>{{$item->diskon_rupiah}}</td>
                <td>{{$item->diskon_persen}}</td>
                <td>{{$item->subtotal}}</td>
                <td>{{$item->total_diskon_detail}}</td>
                <td>{{$item->total_diskon_header}}</td>
                <td>{{$item->total}}</td>       
                <td>{{$item->ongkir}}</td>  
                <td>{{$item->ppn}}</td>                                                   
                <td>{{$item->grandtotal}}</td>                  
                <td>{{$item->nama_produk}}</td>
                <td>{{$item->kode_produk}}</td>
                <td>{{$item->nama_merk}}</td>
                <td>{{$item->qty_det}}</td>
                <td>{{$item->satuan_det}}</td>
                <td>{{$item->dikson_persen_det}}</td>
                <td>{{$item->diskon_rp_det}}</td>
                <td>{{$item->hargajual_det}}</td>
                <td>{{$item->subtotal_det}}</td>
                <td>{{$item->total_diskon_det}}</td>
                <td>{{11/100 * $item->total_det}}</td>
                <td>{{$item->cn_total ? $item->cn_total : 0 }}</td>
                <td>{{$item->total_det + (11/100 * $item->total_det)}}</td>
                <td>{{$item->ongkir_det}}</td>
                <td>{{$item->total_det - (11/100 * $item->total_det) - ($item->cn_total ? $item->cn_total : 0)}}</td>
                <td>{{$item->nama_sales}}</td>
                <td>{{$item->nama_pembuat}}</td>
                <td>{{$item->keterangan}}</td>                                            
                <td>{{$item->keterangan_det}}</td>
            </tr>
        @endforeach

        <tr>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td><u><b>GRANDTOTAL</b></u></td>
            <td><b>{{$totHargaJual}}</b></td>
            <td><b>{{$totSubtotal}}</b></td>
            <td><b>{{$totDiskon}}</b></td>
            <td><b>{{11/100 * $totTotal}}</b></td>
            <td><b>{{ $totCN ? $totCN : 0 }}</b></td>
            <td><b>{{$totTotal + (11/100 * $totTotal)}}</b></td>
            <td></td>
            <td><b>{{$totTotal + (11/100 * $totTotal) - ($totCN ? $totCN : 0)}}</b></td>
            <td></td>
        </tr>

        
       
    </tbody>
</table>