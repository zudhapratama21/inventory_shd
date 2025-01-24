<div id="xcontohmodal">
    <div class="modal fade" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Outlet</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-action">
                        <div class="form-group">
                            <label for="">Tanggal</label>
                            <input type="date" id="tanggal" class="form-control" value="{{ $planmarketing->tanggal }}" readonly>
                        </div>

                        <input type="hidden" id="data_id" value="{{$planmarketing->id}}">

                        <div class="form-group">
                            <label for="">Outlet</label> <br>
                            <select name="" id="kt_select2_4" class="form-control" required>                               
                                @foreach ($outlet as $item)
                                    @if ($planmarketing->outlet_id == $item->id)
                                        <option value="{{ $item->id }}" selected>{{ $item->nama }}</option>    
                                    @else
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>    
                                    @endif
                                    
                                @endforeach
                            </select>   
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-sm" >Simpan</button>
                            <button type="button" class="btn btn-light-primary font-weight-bold"
                                data-dismiss="modal">Close</button>
                            <button  type="button" id="delete-btn" class="btn btn-light-danger font-weight-bold"><i class="flaticon2-trash"></i>Hapus</button>
                        </div>
                    <form>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
