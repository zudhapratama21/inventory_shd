<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Sales</th>
            <th>Customer</th>
            <th>Aktivitas</th>                                       
        </tr>
    </thead>
    <tbody>
        @php
            $no=1;
        @endphp
        @foreach ($data as $item)
            <tr>
                <td>{{$no++}}</td>
                <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                <td>{{ date('H:i', strtotime($item->jam_buat)) }}</td>
                <td>{{$item->user->name}}</td>
                <td>{{$item->outlet ? $item->outlet->nama : $item->customer  }}</td>
                <td>{{ $item->aktifitas }}</td>                   
            </tr>
        @endforeach
    </tbody>
</table>