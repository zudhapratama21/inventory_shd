<div class="modal fade" id="setBarangModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Surat Jalan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Nama Barang</label>
                            <div class="col-lg-10">
                                <input type="text" readonly="readonly" disabled="disabled"
                                    class="form-control form-control-solid" name="nama_product" id="nama_product"
                                    value="{{ $item['nama_product'] }}" />
                                <input type="hidden" id="product_id" name="product_id" value="{{ $item['product_id'] }}" /`>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Qty</label>
                            <div class="col-lg-2">
                                <input type="number" class="form-control" id="qty" name="qty"
                                    value="{{ $item['qty'] }}"
                                    @if ($item['pengiriman_barang_id'] != null) readonly="readonly" disabled="disabled"                                                                                                                
                                    @endif
                                    />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Satuan </label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control form-control-solid text-left" readonly="readonly"
                                    id="satuan" name="satuan" value="@if ($item['beda_satuan'] == 'on') {{ $item['satuan_konversi'] }} @else {{ $item['satuan'] }} @endif " />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Harga</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="hargajual" name="hargajual"
                                    value="{{ $item['hargajual'] }}" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">PPn (%) ? </label>
                            <div class="col-lg-10">
                                <input type="number" class="form-control" id="ppn" name="ppn"
                                    value="{{ $item['ppn'] }}" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Diskon(%)</label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control" id="diskon_persen" name="diskon_persen"
                                    value="{{ $item['diskon_persen'] }}" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Diskon(Rp.)</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="diskon_rp" name="diskon_rp"
                                    value="{{ $item['diskon_rp'] }}" />
                            </div>
                        </div>                                            

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Keterangan</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="keterangan" name="keterangan"
                                    value="{{ $item['keterangan'] }}" />
                            </div>
                        </div>                    

                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" onclick="updatesj()" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
