@if (count($week) > 0)
    @foreach ($week as $item)
        @if ($item->day)
            <span class="badge badge-primary mt-2">{{ucfirst($item->day->nama)}}</span>     
        @endif       
    @endforeach
@endif
