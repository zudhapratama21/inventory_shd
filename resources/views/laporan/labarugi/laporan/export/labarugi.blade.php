<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Bulan</th>
            <th>No KPA</th>
            <th>Pengiriman Barang ID</th>
            <th>Customer</th>
            <th>Products ID</th>
            <th>Products</th>
            <th>Merk</th>
            <th>Supplier</th>
            <th>QTY</th>
            <th>Harga Jual</th>
            <th>Diskon Jual (%)</th>
            <th>Diskon Jual (Rp.)</th>
            <th>Total Diskon</th>
            <th>Total</th>
            <th>PPH (1.5%)</th>                                                                        
            <th>CN</th>
            <th>Harga Jual Nett</th>
            <th>Harga Beli</th>
            <th>Diskon Beli (%)</th>
            <th>Diskon Beli (Rp.)</th>
            <th>Total Diskon Beli</th>
            <th>HPP</th>
            <th>Laba Kotor</th>
            <th>Sales</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($labarugi as $item)
            <tr>
                <td>{{ $item['tanggal'] }}</td>
                <td>{{ $item['bulan'] }}</td>                
                <td>{{ $item['no_kpa'] }}</td>
                <td>{{ $item['pengirimanbarangdetail_id'] }}</td>
                <td>{{ $item['customer'] }}</td>
                <td>{{ $item['products_id'] }}</td>
                <td>{{ $item['products'] }}</td>

                <td>{{ $item['merk'] }}</td>
                <td>{{ $item['supplier'] }}</td>
                <td>{{ $item['qty'] * -1  }}</td>
                <td>{{ $item['hargajual'] }}</td>
                <td>{{ $item['diskon_persen'] }}</td>
                <td>{{ $item['diskon_rp'] }}</td>
                <td>{{ $item['total_diskon'] }}</td>                                                                               
                <td>{{ $item['total'] }}</td>
                <td>{{ $item['pph'] }}</td>
                <td>{{ $item['cn_rupiah'] }}</td>
                <td>{{ $item['nett'] }}</td>
                <td>{{ $item['harga_beli'] }}</td>
                <td>{{ $item['diskon_beli_persen'] }}</td>
                <td>{{ $item['diskon_beli_rupiah'] }}</td>
                <td>{{ $item['total_diskon_beli'] }}</td>
                <td>{{ $item['hpp'] }}</td>
                <td>{{ $item['laba_kotor'] }}</td>
                <td>{{ $item['sales'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>