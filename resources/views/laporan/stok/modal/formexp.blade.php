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
                <form>
                    <div class="form-group">
                        <label for="">Tanggal Exp</label>
                        <input type="date" id="tanggal" value="{{$stok->tanggal ? \Carbon\Carbon::parse($stok->tanggal)->format('Y-m-d') : now()->format('Y-m-d')}}" class="form-control" >
                        <input type="hidden" id="stok_id" value="{{$stok->id}}">
                    </div>
                    <div class="form-group">
                        <label for="">Lot</label> 
                        <input type="text" value="{{$stok->lot ? $stok->lot : 'Non Exp'}}" id="lot" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label for="">Qty</label> 
                        <input type="text" value="{{$stok->qty}}" id="qty" class="form-control" >
                    </div>
                    
                    <div class="form-group">
                        <label for="">Harga Beli</label> 
                        <input type="text" value="{{$stok->harga_beli}}" id="harga_beli" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label for="">Diskon (%)</label> 
                        <input type="text" value="{{$stok->diskon_persen}}" id="diskon_persen" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label for="">Diskon (Rp.)</label> 
                        <input type="text" value="{{$stok->diskon_rupiah}}" id="diskon_rupiah" class="form-control" >
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="submitbarang()" class="btn btn-success mr-2">Submit</button>
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>
