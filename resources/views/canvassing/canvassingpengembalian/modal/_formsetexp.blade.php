<!-- Modal-->
<div class="modal fade" id="formsetexp" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Kembali</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Produk</label>
                    <input type="text" class="form-control" id="produk" name="produk" value="{{
                        $status == 1 ? $stok->products->nama : $stok->product->nama
                    }}" readonly>
                    <input type="hidden" id="status_exp" value="{{$status}}">
                </div>

                <div class="form-group">
                    <label for="">Tanggal exp</label>
                    <input type="text" class="form-control" id="produk" name="produk" value="{{\Carbon\Carbon::parse($stok->tanggal)->format('d-m-Y') }}" readonly>
                </div>

                <div class="form-group">
                    <label for="">Stok</label>
                    <input type="text" class="form-control" id="jumlah" name="jumlah" value="{{$stok->qty * -1 }}" readonly>
                </div>

                <div class="form-group">
                    <label for="">Qty yang di kirim ?</label>
                    <input type="number" max="{{$stok->qty}}" class="form-control" id="qty" name="qty" value="0">
                    <span class="text-danger" >*Qty tidak boleh melebihi stok</span> 
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary font-weight-bold" onclick="inputexp({{$stok->id}})">Save</button>
            </div>
        </div>
    </div>
</div>