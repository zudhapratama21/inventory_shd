<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Barang</th>            
            <th>Merk</th>
            <th>Satuan</th>            
            <th>Categori Produk</th>
            <th>Sub Kategori</th>
            <th>Jenis</th>
            <th>Stok</th>
            <th>Tipe</th>
            <th>Ukuran</th>
            <th>Kemasan</th>
            <th>Katalog</th>
            <th>Asal Negara</th>
            <th>Pabrikan</th>
            <th>No Ijin Edar</th>
            <th>Exp Ijin Edar</th>
            <th>Harga Jual</th>            
            <th>Harga Beli</th> 
            <th>HPP</th>      
            <th>Diskon Persen</th>           
            <th>Diskon Rupiah</th>        
            <th>Status Expired</th>                                         
            <th>Status Produk</th>                                         
            <th>Keterangan</th>                                     
        </tr>
    </thead>
    <tbody>
        @php
            $no=1;
        @endphp
        @foreach ($product as $item)
            <tr>
                <td>{{$no++}}</td>
                <td>{{$item->kode}}</td>
                <td>{{$item->nama}}</td>
                <td>{{$item->merks->nama}}</td>
                <td>{{$item->satuan}}</td>   
                <td>{{$item->categories->nama}}</td>   
                <td>{{$item->subcategories->nama}}</td>   
                <td>{{$item->jenis}}</td>   
                <td>{{$item->stok}}</td>   
                <td>{{$item->tipe}}</td>   
                <td>{{$item->ukuran}}</td>   
                <td>{{$item->kemasan}}</td>   
                <td>{{$item->katalog}}</td>   
                <td>{{$item->asal_negara}}</td>   
                <td>{{$item->pabrikan}}</td>   
                <td>{{$item->no_ijinedar}}</td>   
                <td>{{$item->exp_ijinedar}}</td>   
                <td>{{$item->hargajual}}</td>   
                <td>{{$item->hargabeli}}</td>   
                <td>{{$item->hpp}}</td>   
                <td>{{$item->diskon_persen}}</td>   
                <td>{{$item->diskon_rp}}</td>                   
                <td>
                    @if ($item->status_exp == true)
                        Iya
                    @else
                        Tidak
                    @endif
                </td>     
                <td>{{$item->status}}</td>                                         
                <td>{{$item->keterangan}}</td> 
            </tr>
        @endforeach        
    </tbody>
</table>