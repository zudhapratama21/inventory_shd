<table >
    <thead>
        <tr>
            <th>No</th>               
            <th>Nama Produk</th>
            <th>Kode Barang</th>            
            <th>Tgl Expired</th>           
            <th>Lot</th>
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
        @foreach ($stokexp as $item)
           
                @foreach ($item->stokExp as $exp)
                    <tr>
                        <td>{{$no++}}</td>                                  
                        <td>{{$item->nama}}</td>
                        <td>{{$item->kode}}</td>                                 
                        <td>
                            @if ($item->status_exp == 1)
                                {{ Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                            @else
                                -
                            @endif                            
                        </td>                                                   
                        <td>{{$exp->lot}}</td>
                        <td>{{$exp->qty}}</td>
                        <td>{{$exp->supplier ? $exp->supplier->nama : '-' }}</td>                                                                                  
                        <td>{{$exp->harga_beli ? $exp->harga_beli : '-' }}</td>
                        <td>{{$exp->diskon_persen ? $exp->diskon_persen : '-' }}</td>
                        <td>{{$exp->diskon_rupiah ? $exp->diskon_rupiah : '-'}}</td>
                    </tr>    
                @endforeach                              
        @endforeach
    </tbody>
</table>