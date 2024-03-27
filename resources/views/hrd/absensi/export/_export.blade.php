@foreach ($data as $key => $value)
    <table>
        <thead>
            <tr style="background-color: turquoise">
                <th colspan="6" style="background-color: turquoise;text-align: center">{{$key}}</th>
            </tr>
            <tr style="background-color: turquoise">                
                <th style="background-color: yellow ; border : 1px solid black">Nama</th>
                <th style="background-color: yellow ; border : 1px solid black">Tanggal</th>
                <th style="background-color: yellow ; border : 1px solid black">Clock In</th>
                <th style="background-color: yellow ; border : 1px solid black">Clock Out</th>
                <th style="background-color: yellow ; border : 1px solid black">Work Time</th>                              
                <th style="background-color: yellow ; border : 1px solid black">Status</th>  
            </tr>
        </thead>
        <tbody>            
            @foreach ($value as $item)
                <tr>
                    <td style="border : 1px solid black">{{ ucfirst($item['nama'])}}</td>
                    <td style="border : 1px solid black">{{\Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y') }}</td>
                    <td style="border : 1px solid black">{{\Carbon\Carbon::parse($item['clock_in'])->format('H:i') }}</td>
                    <td style="border : 1px solid black">{{\Carbon\Carbon::parse($item['clock_out'])->format('H:i') }}</td>
                    <td style="border : 1px solid black">{{\Carbon\Carbon::parse($item['work_time'])->format('H:i') }}</td>
                    <td style="border : 1px solid black">{{ ucfirst($item['status'])}}</td>
                </tr>
            @endforeach
            <tr></tr>
        </tbody>
    </table>
@endforeach

