<table class="table table-separate table-head-custom table-checkable" id="kt_datatable1">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Tanggal TOP</th>
            <th>Tanggal Pembayaran</th>
            <th>Selisih Pembayaran</th>
            <th>Customer</th>
            <th>Kode SO</th>
            <th>Kode SJ</th>
            <th>Kode Faktur</th>
            <th>DPP</th>                                        
            <th>PPN</th>
            <th>Total</th>                                        
            <th>Telah Dibayar</th>
            <th>Sisa</th>
            <th>Nama Bank</th>
            <th>Nominal Pembayaran</th>
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
                <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                <td>{{ date('d/m/Y', strtotime($item->tanggal_top)) }}</td>
                <td>{{ date('d/m/Y', strtotime($item->tanggal_pembayaran)) }}</td>
                <td>
                    @php
                        $tglHutang = strtotime($item->tanggal);
                        $tglPembayaran = strtotime($item->tanggal_pembayaran);

                        $selisih = ($tglPembayaran - $tglHutang ) / 86400;                                                
                    @endphp

                    <span>{{$selisih}}</span>
                </td>
                
                <td>{{$item->nama_customer}}</td>
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
                <td>{{$item->nama_sales}}</td>                
                <td>
                    @if ($item->status == 1)
                        Belum Lunas
                    @else
                        Lunas
                    @endif
                </td>      
                <td>{{$item->keterangan}}</td>                                  
            </tr>
        @endforeach
    </tbody>
</table>
<!--end: Datatable-->