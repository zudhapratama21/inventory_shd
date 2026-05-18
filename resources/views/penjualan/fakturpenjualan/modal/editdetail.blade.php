<div class="modal fade" id="setbarang" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
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
                                    value="{{ $fakturpenjualandet->products->nama }}" />
                                <input type="hidden" id="product_id" name="product_id"
                                    value="{{ $fakturpenjualandet->product_id }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Qty</label>
                            <div class="col-lg-2">
                                <input type="number" class="form-control" id="qty" name="qty"
                                    value="{{ $fakturpenjualandet->qty }}"
                                    @if ($fakturpenjualandet->pengirimanbarang_id != null) readonly @endif />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Satuan </label>
                            <div class="col-lg-10">
                                @if ($fakturpenjualandet->pengirimanbarang_id != null)
                                    <input type="text" class="form-control" id="satuan" name="satuan"
                                        value="{{ $fakturpenjualandet->satuan }}" readonly>
                                @else
                                    <select name="satuan" class="form-control" id="satuan">
                                        @foreach ($satuan as $item)
                                            @if ($item->nama == $fakturpenjualandet->satuan)
                                                <option value="{{ $item->nama }}" selected>{{ $item->nama }}
                                                </option>
                                            @else
                                                <option value="{{ $item->nama }}">{{ $item->nama }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                @endif

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Harga</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="hargajual" name="hargajual"
                                    value="{{ $fakturpenjualandet->hargajual }}" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">PPn (%) ? </label>
                            <div class="col-lg-10">
                                <input type="number" class="form-control" id="ppn" name="ppn"
                                    value="{{ $fakturpenjualandet->ppn }}" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Diskon(%)</label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control" id="diskon_persen" name="diskon_persen"
                                    value="{{ $fakturpenjualandet->diskon_persen }}" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Diskon(Rp.)</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="diskon_rp" name="diskon_rp"
                                    value=" {{ $fakturpenjualandet->diskon_rp }} " />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Keterangan</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="keterangan" name="keterangan"
                                    value="  {{ $fakturpenjualandet->keterangan }} " />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">Close</button>
                <button type="button" onclick="updatesj({{ $fakturpenjualandet->id }});"
                    class="btn btn-success ">Submit</button>
            </div>
        </div>


    </div>

</div>
