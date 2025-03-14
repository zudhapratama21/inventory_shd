<!-- Modal-->
@if($mode == "new")
<div class="modal fade" id="modal-barang" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Canvassing Pengembalian Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" style="height: 400px;">
                <input type="hidden" id="canvassingdetail_id" value="{{$canvas->id}}">
                <form class="form">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Nama Barang</label>
                            <div class="col-lg-10">
                                <input type="text" readonly="readonly" disabled="disabled"
                                    class="form-control form-control-solid" name="nama" id="nama"
                                    value="{{ $canvas->product->nama }}" />                                
                                
                            </div>
                        </div>                      
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Qty Canvassing</label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control" id="qty_canvassing"
                                    name="qty_canvassing" value="{{$canvas->qty}}"  min="0" readonly/>
                            </div>
                        </div> 

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Qty Sisa</label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control" id="qty_canvassing"
                                    name="qty_canvassing" value="{{$canvas->qty_sisa}}"  min="0" readonly/>
                            </div>
                        </div> 
                        
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Qty Kembali</label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control" id="qty_kembali"
                                    name="qty_kembali" />
                            </div>
                        </div>                        
                    </div>
                </form>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" onclick="javascript:submitItem();" class="btn btn-success mr-2">Submit</button>

            </div>
        </div>
    </div>

</div>
@else
<div class="modal fade" id="modal-barang" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" style="height: 400px;">

                <form class="form">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Nama Barang</label>
                            <div class="col-lg-10">
                                <input type="text" readonly="readonly" disabled="disabled"
                                    class="form-control form-control-solid" name="nama" id="nama"
                                    value="{{ $product_name }}" />
                                
                                <input type="hidden" id="id" name="id" value="{{ $canvas->id }}">
                                <input type="hidden" id="product_id" name="product_id" value="{{ $canvas->product_id }}">

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Qty </label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control" id="qty"
                                    name="qty" value="{{$canvas->qty}}"  max="{{$product->stok}}" min="0" readonly/>
                            </div>
                        </div> 

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Qty Sisa</label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control" id="qty_sisa"
                                    name="qty_sisa" value="{{$canvas->qty_sisa}}"  max="{{$product->stok}}" min="0" readonly/>
                            </div>
                        </div> 
                        
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Qty Kembali</label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control" id="qty_kembali"
                                    name="qty_kembali" value="{{$canvas->qty_kirim}}"/>
                            </div>
                        </div>                 
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Satuan </label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control form-control-solid" readonly="readonly"
                                    id="satuan" name="satuan" value="{{ $product->satuan }}" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Keterangan</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="keterangan" name="keterangan"
                                    value="{{ $canvas->keterangan }}" />
                            </div>
                        </div>

                    </div>
                </form>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" onclick="javascript:updateItem();" class="btn btn-success mr-2">Update</button>

            </div>
        </div>
    </div>

</div>
@endif
<!-- Modal-->