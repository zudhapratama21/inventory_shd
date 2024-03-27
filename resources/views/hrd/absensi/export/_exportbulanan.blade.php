@foreach ($data as $key => $value)
    <table>
        <thead>
            <tr style="background-color: turquoise">
                <th colspan="7" style="background-color: turquoise;text-align: center; border : 1px solid black">{{$key}}</th>
            </tr>
            <tr style="background-color: turquoise">                
                <th style="background-color: yellow ; border : 1px solid black">Nama</th>
                <th style="background-color: yellow; border : 1px solid black">Ijin / Sakit</th>
                <th style="background-color: yellow; border : 1px solid black">Abstain</th>
                <th style="background-color: yellow; border : 1px solid black">Error</th>
                <th style="background-color: yellow; border : 1px solid black">Terlambat</th>
                <th style="background-color: yellow; border : 1px solid black">Pengurangan</th>
                <th style="background-color: yellow; border : 1px solid black">Lembur (Jam)</th>         
                <th style="background-color: yellow; border : 1px solid black">Total Hari Kerja</th>                                  
                <th style="background-color: yellow; border : 1px solid blacks">Sisa Total Hari Kerja</th>
                <th style="background-color: yellow; border : 1px solid blacks">Sisa Kerja dan Pengurangan Terlambat</th>
            </tr>
        </thead>
        <tbody>            
            @foreach ($value as $item)
                <tr>
                    <td style="border : 1px solid black">{{ ucfirst($item['nama'])}}</td>
                    <td style="border : 1px solid black">{{ $item['ijin']}}</td>
                    <td style="border : 1px solid black">{{ $item['tidak_hadir']}}</td>
                    <td style="border : 1px solid black">{{ $item['error']}}</td>
                    <td style="border : 1px solid black">{{ $item['terlambat']}}</td>
                    <td style="border : 1px solid black">{{ $item['pengurangan']}}</td>
                    <td style="border : 1px solid black">{{ $item['lembur']}}</td>                    
                    <td style="border : 1px solid black">{{ $item['ijin'] + $item['ontime'] + $item['tidak_hadir'] + $item['terlambat'] + $item['error']}}</td>
                    <td style="border : 1px solid black">{{ $item['ontime'] + $item['terlambat'] + $item['error']  }}</td>
                    <td style="border : 1px solid black">{{ $item['ontime'] + $item['terlambat'] + $item['error'] -   $item['pengurangan'] }}</td>
                </tr>
            @endforeach
            <tr></tr>
        </tbody>
    </table>
@endforeach

