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
            <th>Kredit</th>
            <th>Debit</th>
            
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach ($data as $item)
            @php
                $biaya = $item->biayaoperational;

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
                <td>{{ number_format($item->nominal, 0, ',', '.') }}</td>
                <td></td>
            </tr>
            @foreach ($biaya as $key => $b)
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ $b->subbiaya->nama ?? '' }}</td>
                    <td>{{ $b->keterangan }}</td>
                    <td></td>
                    <td>{{ number_format($b->nominal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
