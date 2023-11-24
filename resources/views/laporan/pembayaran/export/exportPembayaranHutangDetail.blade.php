<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal Hutang</th>
            <th>Tanggal Pembayaran</th>
            <th>No KPA</th>
            <th>No SO Supplier</th>
            <th>No Faktur Supplier</th>
            <th>Supplier</th>
            <th>Kode SO</th>
            <th>Kode SJ</th>
            <th>Kode Faktur</th>
            <th>DPP</th>                                        
            <th>PPN</th>
            <th>Total</th>                                        
            <th>Telah Dibayar</th>
            <th>Sisa</th>
            <th>Bank</th>
            <th>Nominal</th>
            <th>Status</th>                                                                                
        </tr>
    </thead>
    <tbody>
        @php
            $no=1;
        @endphp
        @foreach ($hutang as $item)
            <tr>
                <td>{{$no++}}</td>
                <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                <td>{{ date('d/m/Y', strtotime($item->tanggal_pembayaran)) }}</td>
                <td>{{$item->no_so}}</td>
                <td>{{$item->no_so_customer}}</td>
                <td>{{$item->no_faktur_supplier}}</td>
                <td>{{$item->nama_supplier}}</td>
                <td>{{$item->kode_pp}}</td>
                <td>{{$item->kode_pb}}</td>
                <td>{{$item->kode_fp}}</td>                                            
                <td>{{$item->dpp}}</td>
                <td>{{$item->ppn}}</td>
                <td>{{$item->total}}</td>
                <td>{{$item->dibayar}}</td>
                <td>{{$item->total - $item->dibayar}}</td>                                            
                <td>{{$item->nama_bank}}</td>
                <td>{{$item->nominal_pembayaran}}</td>
                <td>
                    @if ($item->status == 1)
                        Belum Lunas
                    @else
                        Lunas
                    @endif
                </td>    
                <td>{{$item->keterangan_pembayaran}}</td>                                    
            </tr>
        @endforeach
    </tbody>
</table>