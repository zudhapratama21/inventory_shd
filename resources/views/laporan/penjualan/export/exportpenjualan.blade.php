<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Kode Faktur</th>
            <th>No Perusahaan</th>                        
            <th>Customer</th>                                                    
            <th>Subtotal</th>
            <th>Total Diskon</th>            
            <th>Total</th>
            <th>Ongkir</th>
            <th>PPN</th>            
            <th>Materai</th>
            <th>Grand Total</th>                                                                
            <th>Sales</th>
            <th>Pembuat</th>
            <th>Keterangan</th>                                        
        </tr>
    </thead>
    <tbody>
        @php
            $no=1;
        @endphp
        @foreach ($penjualan as $item)
            <tr>
                <td>{{$no++}}</td>
                <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                <td>{{$item->kode}}</td>
                <td>{{ "\u{200B}" .   $item->no_perusahaan}}</td>                
                <td>{{$item->nama_customer}}</td>                
                <td>{{$item->subtotal}}</td>
                <td>{{$item->total_diskon}}</td>                                
                <td>{{$item->total}}</td>       
                <td>{{$item->ongkir}}</td>                
                <td>{{$item->ppn}}</td>                                                     
                <td>{{$item->materai}}</td>                  
                <td>{{$item->grandtotal}}</td>                                  
                <td>{{$item->nama_sales}}</td>
                <td>{{$item->nama_pembuat}}</td>
                <td>{{$item->keterangan}}</td>                                            
            </tr>
        @endforeach
    </tbody>
</table>