<table >
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Tanggal TOP</th>
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
            <th>Nominal Toleransi</th>
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
                <td>{{ $item->tanggal ? date('d/m/Y', strtotime($item->tanggal)) : 'Tidak ada'}}</td>
                <td>{{ $item->tanggal_top ? date('d/m/Y', strtotime($item->tanggal_top)) : 'Tidak ada'}}</td>
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
                <td>{{$item->nominal_toleransi}}</td>                                            
                <td>
                    @if ($item->status == 1)
                        Belum Lunas
                    @else
                        Lunas
                    @endif
                </td>                                        
            </tr>
        @endforeach
    </tbody>

</table>