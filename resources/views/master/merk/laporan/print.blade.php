<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Supplier</th>                       
        </tr>
    </thead>
    <tbody>
        {{-- @dd($data) --}}
        @php
            $no=1;
        @endphp
        @foreach ($merk as $item)
            <tr>
                <td>{{$item->id}}</td>                               
                <td>{{$item->nama}}</td>
                <td></td>                                                                                          
            </tr>
        @endforeach
    </tbody>
</table>