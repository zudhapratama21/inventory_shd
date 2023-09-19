<table >
    <thead>
        <tr>
            <th>No</th>     
            <th>ID Produk</th>       
            <th>Nama Produk</th>
            <th>Kode Barang</th>           
            <th>Stok</th>
            <th>Supplier</th>
            <th>Harga Beli</th>
            <th>Diskon (%) Beli</th>
            <th>Diskon (Rp.) Beli</th>            
        </tr>
    </thead>
    <tbody>
        @php
            $no=1;
        @endphp
        @foreach ($stoknonexp as $item)
            @if (count($item->harganonexpired) > 0)
                @foreach ($item->harganonexpired as $nonexp)
                    <tr>
                        <td>{{$no++}}</td>
                        <td>{{$item->id}}</td>                    
                        <td>{{$item->nama}}</td>
                        <td>{{$item->kode}}</td>
                        <td>{{$item->stok}}</td>                                            
                        <td>{{$nonexp->supplier->nama}}</td>                                                                                  
                        <td>{{$nonexp->harga_beli}}</td>
                        <td>{{$nonexp->diskon_persen}}</td>
                        <td>{{$nonexp->diskon_rupiah}}</td>
                    </tr>    
                @endforeach               
            @else
            <tr>
                <td>{{$no++}}</td>
                <td>{{$item->id}}</td>
                <td>{{$item->nama}}</td>
                <td>{{$item->kode}}</td>
                <td>{{$item->stok}}</td>                                            
                <td></td>
                <td></td>                                                                                 
                <td></td>
                <td></td>
            </tr>       
            @endif               
        @endforeach
    </tbody>
</table>