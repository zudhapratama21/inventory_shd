<!-- Modal-->
<div class="modal fade" id="setBarangModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Terima Barang</h5>
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
                                    value="{{ $product->products->nama }}" />
                                <input type="hidden" id="detail_id" name="detail_id" value="{{ $product->id }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Qty Pesanan</label>
                            <div class="col-lg-2">
                                <input type="text" readonly="readonly" class="form-control" id="qty_pesanan"
                                    name="qty_pesanan" value="{{ $product->qty }}" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Qty Sisa</label>
                            <div class="col-lg-2">
                                <input type="text" readonly="readonly" class="form-control" id="qty_sisa"
                                    name="qty_sisa" value="{{ $product->qty_sisa }}" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Qty Diterima</label>
                            <div class="col-lg-2">
                                <input type="number" class="form-control" id="qty" name="qty" value="0" />
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
                                <input type="text" class="form-control" id="keterangan" name="keterangan" value="" />
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