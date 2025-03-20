<table>
    <thead>
        <tr style="background-color: turquoise">
            <th style="background-color: turquoise ; border : 1px solid black">No</th>
            <th style="background-color: turquoise ; border : 1px solid black">Tanggal</th>            
            <th style="background-color: turquoise ; border : 1px solid black">Sales</th>
            <th style="background-color: turquoise ; border : 1px solid black">Outlet</th>
            <th style="background-color: turquoise ; border : 1px solid black">Aktivitas</th>
            
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
                <td>{{$item->nama_sales}}</td>
                <td>{{$item->nama_outlet}}</td>
                <td>{!! $item->aktivitas !!}</td>                   
            </tr>
        @endforeach
    </tbody>
</table>