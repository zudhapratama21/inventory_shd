<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Kode Barang</th>
            <th>Tgl Expired</th>
            <th>Lot</th>
            <th>Stok</th>           
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($stok as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item->nama_produk }}</td>
                <td>{{ $item->kode_item }}</td>
                <td>
                    @if ($item->status_exp == 1)
                        {{ Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $item->lot }}</td>
                <td>{{ $item->qty }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
