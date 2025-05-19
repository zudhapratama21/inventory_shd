<!-- Modal-->
<div class="modal fade" id="modalcn" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Input CN</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="form-group">
                        <label for="">Persen CN</label>
                        <input type="text" class="form-control" id="persen_cn" name="persen_cn" value="{{$fakturpenjualandetail->cn_persen ? $fakturpenjualandetail->cn_persen : 0 }}" placeholder="Masukkan persen CN">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" onclick="inputcn({{$id}})" class="btn btn-primary font-weight-bold">Save changes</button>
            </div>
        </div>
    </div>
</div>