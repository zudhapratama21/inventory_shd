<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Kode Faktur</th>
            <th>Kode PO</th>
            <th>Kode PB</th>
            <th>Kode BSB</th>
            <th>Supplier</th>   
            <th>Nama Produk</th>  
            <th>Kode Produk</th>    
            <th>Merk</th>                                                                  
            <th>Qty Produk</th>     
            <th>Satuan</th>                                                                          
            <th>Diskon Rupiah</th>
            <th>Diskon Persen</th>
            <th>Subtotal</th>
            <th>Total Diskon Detail</th>
            <th>Total Diskon Header</th>
            <th>Total</th>
            <th>PPN</th>
            <th>Ongkir</th>  
            <th>Grand Total</th>                                          
            <th>Harga Beli Produk</th>
            <th>Diskon Persen Produk</th>
            <th>Diskon Rupiah Produk</th>
            <th>Subtotal Produk</th>
            <th>Total Diskon Produk</th>
            <th>Total Produk</th>
            <th>Ongkir Produk</th>
            <th>Pembuat</th>
            <th>Keterangan</th>                                        
            <th>Keterangan Produk</th>                                        
        </tr>
    </thead>
    <tbody>
        @php
            $no=1;
        @endphp
        @foreach ($pembelian as $item)
            <tr>
                <td>{{$no++}}</td>
                <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                <td>{{$item->kode}}</td>
                <td>{{$item->kode_SP}}</td>
                <td>{{$item->kode_SJ}}</td>   
                <td>{{$item->no_pesanan}}</td>   
                <td>{{$item->nama_supplier}}</td>
                <td>{{$item->nama_produk}}</td>
                <td>{{$item->kode_produk}}</td>
                <td>{{$item->nama_merk}}</td>
                <td>{{$item->qty_produk}}</td>
                <td>{{$item->satuan_produk}}</td>
                <td>{{$item->diskon_rupiah}}</td>
                <td>{{$item->diskon_persen}}</td>
                <td>{{$item->subtotal}}</td>
                <td>{{$item->total_diskon_detail}}</td>
                <td>{{$item->total_diskon_header}}</td>
                <td>{{$item->total}}</td>       
                <td>{{$item->ppn}}</td>
                <td>{{$item->ongkir}}</td>                                     
                <td>{{$item->grandtotal}}</td>  
                <td>{{$item->hargabeli_produk}}</td>  
                <td>{{$item->diskon_persen_produk}}</td>  
                <td>{{$item->diskon_rp_produk}}</td>  
                <td>{{$item->subtotal_produk}}</td>  
                <td>{{$item->total_diskon_produk}}</td>  
                <td>{{$item->total_produk}}</td>  
                <td>{{$item->ongkir_produk}}</td>  
                <td>{{$item->nama_pembuat}}</td>
                <td>{{$item->keterangan}}</td>                                            
                <td>{{$item->keterangan_produk}}</td> 
            </tr>
        @endforeach
    </tbody>
</table>
