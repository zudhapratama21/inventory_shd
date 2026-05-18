<table>
    <thead>
        <tr>
            <th>No</th>            
            <th>Tanggal</th>            
            <th>Kode</th>
            <th>No Perusahaan</th>            
            <th>Komoditas</th>
            <th>Kategori Pesanan</th>
            <th>Customer</th>
            <th>Kategori Customer</th>            
            <th>Subtotal Faktur</th>
            <th>Total Diskon Faktur</th>            
            <th>Total Faktur</th>
            <th>Ongkir</th>
            <th>PPN </th>
            <th>Materai </th>
            <th>Grand Total </th>
            <th>Nama Produk</th>
            <th>Kode Produk</th>
            <th>Merk</th>
            <th>Satuan</th>            
            <th>Qty</th>          
            <th>Harga Jual</th>  
            <th>Diskon Persen</th>
            <th>Diskon Rupiah</th>            
            <th>Subtotal</th>
            <th>Total Diskon</th>                                    
            <th>Total Produk</th>                        
            <th>Sales</th>
            <th>Pembuat</th>
            <th>Keterangan</th>
            <th>Keterangan Produk</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($penjualan as $item)
            <tr>
                <td>{{ $no++ }}</td>                        
                <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>                
                <td>{{ $item->kode }}</td>
                <td>{{ $item->no_perusahaan }}</td>                                
                <td>{{ $item->nama_komoditas }}</td>
                <td>{{ $item->nama_kategori_pesanan }}</td>
                <td>{{ $item->nama_customer }}</td>
                <td>{{ $item->nama_kategori_customer }}</td>                
                <td>{{ $item->subtotal }}</td>
                <td>{{ $item->total_diskon }}</td>                
                <td>{{ $item->total }}</td>
                <td>{{ $item->ongkir }}</td>
                <td>{{ $item->ppn }}</td>
                <td>{{ $item->materai }}</td>
                <td>{{ $item->grandtotal }}</td>
                <td>{{ $item->nama_produk }}</td>
                <td>{{ $item->kode_produk }}</td>
                <td>{{ $item->nama_merk }}</td>
                <td>{{ $item->satuan_det }}</td>                
                <td>{{ $item->qty_det }}</td>      
                <td>{{ $item->hargajual_det }}</td>                          
                <td>{{ $item->diskon_persen_det }}</td>
                <td>{{ $item->diskon_rp_det }}</td>   
                <td>{{ $item->subtotal_det }}</td>                             
                <td>{{ $item->total_diskon_det }}</td>                                                                
                <td>{{ $item->total_det }}</td>                                                                
                <td>{{ $item->nama_sales }}</td>
                <td>{{ $item->nama_pembuat }}</td>
                <td>{{ $item->keterangan }}</td>
                <td>{{ $item->keterangan_det }}</td>
            </tr>
        @endforeach



    </tbody>
</table>
