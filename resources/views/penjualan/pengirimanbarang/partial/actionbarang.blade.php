<div class="d-flex">
    @can('dataexpired-list')
        <button class="btn btn-outline-primary btn-sm mr-2" onclick="editExp({{$id}})">edit</button>    
    @endcan    
        <button class="btn btn-outline-danger btn-sm" onclick="hapusbarang({{$id}})"><i class="fas fa-trash"></i></button>
</div>
