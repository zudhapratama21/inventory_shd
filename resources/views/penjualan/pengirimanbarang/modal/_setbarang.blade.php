<div class="modal fade" id="setBarangModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Opsi Barang</h5>            
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
                                    value="{{ $product->nama }}" />
                                <input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Stok</label>
                            <div class="col-lg-2">
                                <input type="number" class="form-control" 
                                    value="{{$product->stok}}" disabled />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Qty</label>
                            <div class="col-lg-2">
                                <input type="number" class="form-control" id="qty" name="qty"
                                    value="1" />
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
                            <label class="col-lg-2 col-form-label">Beda Satuan ?</label>
                            <div class="col-3">
                                <span class="switch switch-outline switch-icon switch-success">
                                    <label>
                                        <input type="checkbox" name="select" id="beda_satuan"
                                            onclick="satuanFunction()" value="off" />
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Satuan Konversi</label>
                            <div class="col-lg-4">
                                <select name="satuan" class="form-control" id="satuan_konversi" disabled>
                                    @foreach ($satuan as $item)
                                        <option value="{{ $item->nama }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <label class="col-lg-2 col-form-label">Qty Konversi</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" id="qty_konversi" name="qty_konversi"
                                    value="0" disabled />
                                <p class="text-danger" style="font-size: 65%">(*) Qty akan di kali dengan
                                    qty konversi</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Keterangan</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="keterangan" name="keterangan"
                                    value="" />
                            </div>
                        </div>

                        <button type="button" onclick="javascript:submitItem();"
                            class="btn btn-success mr-2 btn-block">Submit</button>


                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">Close</button>
            </div>
        </div>


    </div>

</div>
