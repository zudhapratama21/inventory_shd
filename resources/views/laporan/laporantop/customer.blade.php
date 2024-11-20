<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal Transaksi</th>
            <th>Nama Customer</th>
            <th>Nama Produk</th>
            <th>Qty</th>
            <th>Total Penjualan</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no=1;
        @endphp
        @foreach ($data as $item)
            <tr>
                <td>{{$no++}}</td>
                <td>{{ date('d/m/Y', strtotime($item->tanggal_penjualan))}}</td>
                <td>{{$item->nama}}</td>
                <td>{{$item->nama_produk}}</td>
                <td>{{$item->stok_produk}}</td>
                <td>{{$item->total_penjualan - $item->total_cn}}</td>
                
            </tr>
        @endforeach
    </tbody>
</table>