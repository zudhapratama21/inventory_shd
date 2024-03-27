@if ($status == 'ontime')
    <span class="badge badge-success">{{ucfirst($status)}}</span>    
@endif

@if ($status == 'terlambat')
    <span class="badge badge-danger">{{ucfirst($status)}}</span>    
@endif

@if ($status == 'tidak hadir')
    <span class="badge badge-warning">{{ucfirst($status)}}</span>    
@endif

@if ($status == 'weekend')
    <span class="badge badge-info">{{ucfirst($status)}}</span>    
@endif

@if ($status == 'cuti bersama')
    <span class="badge badge-info">{{ucfirst($status)}}</span>    
@endif

@if ($status == 'ijin')
    <span class="badge badge-warning">{{ucfirst($status)}}</span>    
@endif

@if ($status == 'error')
    <span class="badge badge-warning">{{ucfirst($status)}}</span>    
@endif