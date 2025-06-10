<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Karyawan</th>
            <th>Divisi</th>
            <th>Status</th>
            <th>Jenis Biaya</th>
            <th>Keterangan</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Saldo</th>

        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach ($data as $item)
            @php
                $biaya = $item->biayaoperational;

            @endphp

            @php
                $saldo = $item->nominal; // set saldo awal untuk setiap CashAdvance
            @endphp
            
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $item->karyawan->nama ?? '' }}</td>
                <td>{{ $item->karyawan->posisi->divisi->nama ?? '' }}</td>
                <td>
                    @if ($item->status == 1)
                        <span>Lunas</span>
                    @else
                        <span>Belum Lunas</span>
                    @endif
                </td>
                <td>{{ '' }}</td>
                <td>{{ $item->keterangan }}</td>
                <td>{{ $item->nominal }}</td>
                <td></td>
                <td></td>
            </tr>
            @foreach ($biaya as $key => $b)
                @php
                    $saldo -= $b->nominal; // kurangi saldo secara bertahap
                @endphp
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ $b->subbiaya->nama ?? '' }}</td>
                    <td>{{ $b->keterangan }}</td>
                    <td></td>
                    <td>{{ $b->nominal }}</td>
                    <td>{{ $saldo }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
