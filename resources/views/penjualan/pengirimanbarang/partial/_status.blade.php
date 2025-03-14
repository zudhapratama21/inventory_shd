<div style="text-align:center;">
    <div class="d-flex flex-nowrap">
       @if ($status_pengiriman == 1)
            @if (count($dataSj->PengirimanBarangDetails) > 0 )
                <span class="badge badge-danger">Belum Input Expired</span>    
            @else
                <span class="badge badge-primary">Belum Difaktur</span>
            @endif            
       @elseif($status_pengiriman == 2 )
           <span class="badge badge-success">Sudah Terfaktur</span>
       @endif
    </div>
</div>