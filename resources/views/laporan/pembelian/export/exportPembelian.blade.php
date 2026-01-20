<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Kode Faktur</th>
            <th>Kode PO</th>
            <th>Kode PB</th>    
            <th>Kode BSB</th> 
            <th>No Faktur Supplier</th>                                    
            <th>Supplier</th>                                        
            <th>Diskon Rupiah</th>
            <th>Diskon Persen</th>
            <th>Subtotal</th>
            <th>Total Diskon Detail</th>
            <th>Total Diskon Header</th>
            <th>Total</th>
            <th>Ongkir</th>  
            <th>PPN</th>            
            <th>Grand Total</th>                                                                                                                     
            <th>Pembuat</th>
            <th>Keterangan</th>                                        
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
                <td>'{{Str::ucfirst($item->no_faktur_supplier)}}</td>    
                <td>{{$item->nama_supplier}}</td>
                <td>{{$item->diskon_rupiah}}</td>
                <td>{{$item->diskon_persen}}</td>
                <td>{{$item->subtotal}}</td>
                <td>{{$item->total_diskon_detail}}</td>
                <td>{{$item->total_diskon_header}}</td>
                <td>{{$item->total}}</td>       
                <td>{{$item->ongkir}}</td>   
                <td>{{$item->ppn}}</td>                                                  
                <td>{{$item->grandtotal}}</td>                                                                                         
                <td>{{$item->nama_pembuat}}</td>
                <td>{{$item->keterangan}}</td>                                            
            </tr>
        @endforeach
    </tbody>
</table>