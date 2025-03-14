@if ($hapusexp)
<span>
    <button class="btn btn-outline-danger btn-sm" onclick="hapusexp({{$id}},{{$status}})"><i class="fas fa-trash"></i></button>
</span>
@else
<span>
    <button class="btn btn-primary btn-sm" onclick="formsetexp({{$id}},{{$status}})">Pilih</button>
</span>
@endif



