<div class="modal fade" id="formexp" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" style="height: 400px;">
                <form action="">                    
                    <div class="form-group">
                        <label for="">Harga Beli</label> 
                        <input type="number" id="harga_beli" value="{{$stok->harga_beli}}" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label for="">Diskon (%)</label> 
                        <input type="number" id="diskon_persen" value="{{$stok->diskon_persen_beli}}" class="form-control" >
                    </div>

                    <div class="form-group">
                        <label for="">Diskon (Rp.)</label>
                        <input type="number" id="diskon_rupiah" value="{{$stok->diskon_rupiah_beli}}" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="javascript:submitexp({{$stok->id}});" class="btn btn-success mr-2">Submit</button>
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>
