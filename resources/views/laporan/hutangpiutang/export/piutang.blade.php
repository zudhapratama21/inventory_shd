<table >
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal Faktur</th>
            <th>Tanggal</th>
            <th>Tanggal TOP</th>
            <th>Customer</th>
            <th>Kode SO</th>
            <th>Kode SJ</th>
            <th>Kode Faktur</th>
            <th>No KPA</th>
            <th>Total</th>                                        
            <th>Telah Dibayar</th>
            <th>Sisa</th>                                        
            <th>Nominal Toleransi</th>
            <th>Sales</th>
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
                <td>{{$item->tanggal_faktur ? date('d/m/Y', strtotime($item->tanggal_faktur))  : 'tidak ada' }}</td>
                <td>{{$item->tanggal ? date('d/m/Y', strtotime($item->tanggal))  : 'tidak ada' }}</td>
                <td>{{$item->tanggal_top ? date('d/m/Y', strtotime($item->tanggal_top))  : 'tidak ada' }}</td>
                <td>{{$item->nama_customer}}</td>
                <td>{{$item->kode_pp}}</td>
                <td>{{$item->kode_pb}}</td>
                <td>{{$item->kode_fp}}</td>
                <td>{{$item->no_kpa}}</td>                                            
                <td>{{$item->total}}</td>
                <td>{{$item->dibayar}}</td>
                <td>{{$item->total - $item->dibayar}}</td>                                                                                  
                <td>{{$item->nominal_toleransi}}</td>
                <td>{{$item->nama_sales}}</td>                                            
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

    <tr></tr>
    <tr></tr>
    <tr></tr>

    <tr>
        <th><b>Total Piutang</b></th>
        <td><b>{{$totalpiutang}}</b></td>
    </tr>
</table>