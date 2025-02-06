<div>
    <div style="text-align:center;">
        <div class="d-flex flex-nowrap">
            @can('evaluasi-edit')
                <a href="javascript:edit({{ $id }})" class="btn btn-icon btn-light btn-hover-primary btn-sm mr-3">
                    <i class="flaticon2-pen text-info"></i>
                </a>
            @endcan
            
            @can('evaluasi-delete')
                <a href="javascript:destroy({{ $id }})"
                    class="btn btn-icon btn-light btn-hover-primary btn-sm mr-3"> 
                    <i class="flaticon2-trash text-danger"></i>
                </a>
            @endcan

        </div>
    </div>
</div>
