    <div class="modal fade" id="formbarang" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
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
                            <label for="">Tanggal Exp</label>
                            <input type="text" value="{{$stok->tanggal ? $stok->tanggal : 'Non Exp'}}" class="form-control" readonly disabled>
                            <input type="hidden" id="stok_id" value="{{$stok->id}}">
                        </div>
                        <div class="form-group">
                            <label for="">Lot</label> 
                            <input type="text" value="{{$stok->lot ? $stok->lot : 'Non Exp'}}" class="form-control" readonly disabled>
                        </div>
                        <div class="form-group">
                            <label for="">Qty</label> 
                            <input type="text" value="{{$stok->qty}}" class="form-control" readonly disabled>
                        </div>

                        <div class="form-group">
                            <label for="">Qty Kirim</label>
                            <input type="number" id="qty_kirim" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="javascript:submitbarang();" class="btn btn-success mr-2">Submit</button>
                    <button type="button" class="btn btn-light-primary font-weight-bold"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>

    </div>
