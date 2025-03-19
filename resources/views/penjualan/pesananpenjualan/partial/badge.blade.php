@if ($status == 1)
    <span class="badge badge-primary badge-sm">draft</span>
@elseif ($status == 2)
    <span class="badge badge-primary badge-sm">posting</span>
@elseif ($status == 3)
    <span class="badge badge-info badge-sm">terkirim sebagian</span>
@elseif ($status == 4)
    <span class="badge badge-info badge-sm">terkirim seluruhnya</span>
@elseif ($status == 5)
    <span class="badge badge-success badge-sm">sudah terfaktur</span>
@else
    <span class="badge badge-info badge-sm">{{ ucfirst($status) }}</span>
@endif  
