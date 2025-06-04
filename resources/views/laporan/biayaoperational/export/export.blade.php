<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Karyawan</th>
            <th>Divisi</th>            
            <th>Jenis Biaya</th>
            <th>Sub Jenis Biaya</th>
            <th>Nominal</th>
            <th>Sumber Dana</th>            
            <th>Keterangan</th>
            
        </tr>
    </thead>
    <tbody>
        {{-- @dd($data) --}}
        @php
            $no=1;
        @endphp
        @foreach ($data as $item)
            <tr>
                <td>{{$no++}}</td>
                <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                <td>{{$item->karyawan}}</td>
                <td>{{$item->divisi}}</td>
                <td>{{$item->jenis_biaya}}</td>
                <td>{{$item->subjenis_biaya}}</td>
                <td>{{$item->total_biaya}}</td>  
                <td>{{$item->bank}}</td>                                
                <td>{{$item->keterangan}}</td>                                                                                
            </tr>
        @endforeach
    </tbody>
</table>