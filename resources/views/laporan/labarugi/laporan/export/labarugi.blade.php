<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Bulan</th>
            <th>No KPA</th>
            <th>Customer</th>
            <th>Products</th>
            <th>QTY</th>
            <th>Harga Jual</th>
            <th>Diskon Jual (%)</th>
            <th>Diskon Jual (Rp.)</th>
            <th>Total Diskon</th>
            <th>Total</th>                                                                        
            <th>CN</th>
            <th>Harga Jual Nett</th>
            <th>Harga Beli</th>
            <th>Diskon Beli (%)</th>
            <th>Diskon Beli (Rp.)</th>
            <th>Total Diskon Beli</th>
            <th>HPP</th>
            <th>Laba Kotor</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($labarugi as $item)
            <tr>
                <td>{{ $item['tanggal'] }}</td>
                <td>{{ $item['bulan'] }}</td>                
                <td>{{ $item['no_kpa'] }}</td>
                <td>{{ $item['customer'] }}</td>
                <td>{{ $item['products'] }}</td>
                <td>{{ $item['qty'] }}</td>
                <td>{{ number_format($item['hargajual'], 2, ',', '.') }}</td>
                <td>{{ $item['diskon_persen'] }}</td>
                <td>{{ $item['diskon_rp'] }}</td>
                <td>{{ number_format($item['total_diskon'], 2, ',', '.') }}</td>                                                                               
                <td>{{ number_format($item['total'], 2, ',', '.') }}</td>
                <td>{{ number_format($item['cn_rupiah'], 2, ',', '.') }}</td>
                <td>{{ number_format($item['nett'], 2, ',', '.') }}</td>
                <td>{{ number_format($item['harga_beli'], 2, ',', '.') }}</td>
                <td>{{ number_format($item['diskon_beli_persen'], 2, ',', '.') }}</td>
                <td>{{ number_format($item['diskon_beli_rupiah'], 2, ',', '.') }}</td>
                <td>{{ number_format($item['total_diskon_beli'], 2, ',', '.') }}</td>
                <td>{{ number_format($item['hpp'], 2, ',', '.') }}</td>
                <td>{{ number_format($item['laba_kotor'], 2, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>