<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>           
            <th>Jam Kunjungan</th>
            <th>Nama Sales</th>
            <th>Plan Marketing</th>
            <th>Rencana Kunjungan</th>
            <th>Realisasi</th>
            <th>Fasilitas</th>
            <th>Hasil Kunjungan</th>
            <th>Tindak Lanjut</th>
            <th>Foto</th>                                     
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
                <td>{{$item->nama_sales}}</td>
                <td>
                    @if ($item->planmarketing)
                        @foreach ($item->planmarketing as $plan)
                            <span class="badge badge-primary">{{ $plan->outlet->nama }} ,</span>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if ($item->rencanakunjungan)
                        @foreach ($item->rencanakunjungan as $rencana)
                            <span class="badge badge-success"> ( {{ $rencana->outlet->nama }} -{!! $rencana->aktivitas !!} ) </span>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
                <td>{{$item->nama_outlet}}</td>
                <td></td>                
                <td>{!! $item->aktifitas !!}</td>    
                <td></td>               
                <td><a href="https://bsb.briliansuksesberkah.com/storage/kunjungan/{{$item->image}}">https://bsb.briliansuksesberkah.com/storage/kunjungan/{{$item->image}}</a> </td>
            </tr>
        @endforeach
    </tbody>
</table>