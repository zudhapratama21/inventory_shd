<!-- Modal-->
@if ($mode == 'new')
    <div class="modal fade" id="setBarangModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Opsi Barang</h5>
                    <div class="card-toolbar">
                        <ul class="nav nav-tabs nav-bold nav-tabs-line">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#kt_tab_pane_1_3">
                                    <span class="nav-icon"><i class="flaticon2-chat-1"></i></span>
                                    <span class="nav-text">FORM</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body" style="height: 700px;">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="kt_tab_pane_1_3" role="tabpanel"
                            aria-labelledby="kt_tab_pane_1_3">

                            <form class="form">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Nama Barang</label>
                                        <div class="col-lg-10">
                                            <input type="text" readonly="readonly" disabled="disabled"
                                                class="form-control form-control-solid" name="nama" id="nama"
                                                value="{{ $product->nama }}" />
                                            <input type="hidden" id="product_id" name="product_id"
                                                value="{{ $product->id }}">
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
                                            <input type="text" class="form-control form-control-solid"
                                                readonly="readonly" id="satuan" name="satuan"
                                                value="{{ $product->satuan }}" />
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
                                            <input type="text" class="form-control" id="qty_konversi"
                                                name="qty_konversi" value="0" disabled />
                                            <p class="text-danger" style="font-size: 65%">(*) Qty akan di kali dengan
                                                qty konversi</p>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Harga</label>
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control" id="hargabeli" name="hargabeli"
                                                value="{{ $product->hargabeli }}" />
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Kena PPn ?</label>
                                        <div class="col-lg-10">
                                            <input type="number" class="form-control" id="ppnprice"
                                                name="ppnprice" value="0" />
                                            <p class="text-danger" style="font-size: 60%">(*) Untuk Harga Kena Pajak
                                                Isi Pajak / PPn</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Diskon(%)</label>
                                        <div class="col-lg-2">
                                            <input type="text" class="form-control" id="diskon_persen"
                                                name="diskon_persen" value="0" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Diskon(Rp.)</label>
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control" id="diskon_rp"
                                                name="diskon_rp" value="0" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Keterangan</label>
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control" id="keterangan"
                                                name="keterangan" value="" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="javascript:submitItem();"
                            class="btn btn-success mr-2">Save</button>
                        <button type="button" class="btn btn-light-primary font-weight-bold"
                            data-dismiss="modal">Close</button>
                    </div>
                </div>


            </div>

        </div>
    @else
        <div class="modal fade" id="setBarangModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body" style="height: 700px;">

                        <form class="form">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Nama Barang</label>
                                    <div class="col-lg-10">
                                        <input type="text" readonly="readonly" disabled="disabled"
                                            class="form-control form-control-solid" name="nama" id="nama"
                                            value="{{ $product_name }}" />
                                        <input type="hidden" id="product_id" name="product_id"
                                            value="{{ $item->product_id }}">
                                        <input type="hidden" id="id" name="id"
                                            value="{{ $item->id }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Qty</label>
                                    <div class="col-lg-2">
                                        <input type="number" class="form-control" id="qty" name="qty"
                                            value="{{ $item->qty }}"
                                            {{ $status ? ($status > 2 ? 'disabled' : '') : '' }} />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Satuan </label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control form-control-solid"
                                            readonly="readonly" id="satuan" name="satuan"
                                            value="{{ $item->satuan }}" />
                                    </div>
                                </div>

                                @if ($item->pesananpembelian->status_po_id < 3)
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Ubah Satuan ?</label>
                                        <div class="col-3">
                                            <span class="switch switch-outline switch-icon switch-success">
                                                <label>
                                                    <input type="checkbox" name="select" id="beda_satuan"
                                                        onclick="satuanFunction()"
                                                        value="{{ $item->beda_satuan }}" />
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                @endif


                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Satuan Konversi</label>
                                    <div class="col-lg-4">
                                        <select name="satuan" class="form-control" id="satuan_konversi" disabled>
                                            @foreach ($satuan as $data)
                                                @if ($data->nama == $item->satuan_konversi)
                                                    <option value="{{ $data->nama }}" selected>{{ $data->nama }}
                                                    </option>
                                                @else
                                                    <option value="{{ $data->nama }}">{{ $data->nama }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <label class="col-lg-2 col-form-label">Qty Konversi</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control" id="qty_konversi"
                                            name="qty_konversi" value="{{ $item->qty_konversi }}" disabled />
                                        <p class="text-danger" style="font-size: 65%">(*) Qty akan di kali dengan qty
                                            konversi</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Harga</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="hargabeli" name="hargabeli"
                                            value="{{ $item->hargabeli }}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Kena PPn ?</label>
                                    <div class="col-lg-10">
                                        <input type="number" class="form-control" id="ppnprice" name="ppnprice"
                                            value="{{ $item->ppn ? $item->ppn : 0 }}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Diskon(%)</label>
                                    <div class="col-lg-2">
                                        <input type="text" class="form-control" id="diskon_persen"
                                            name="diskon_persen" value="{{ $item->diskon_persen }}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Diskon(Rp.)</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="diskon_rp" name="diskon_rp"
                                            value="{{ $item->diskon_rp }}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Keterangan</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="keterangan" name="keterangan"
                                            value="{{ $item->keterangan }}" />
                                    </div>
                                </div>

                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold"
                            data-dismiss="modal">Close</button>
                        <button type="button" onclick="javascript:updateItem();"
                            class="btn btn-success mr-2">Update</button>

                    </div>
                </div>
            </div>

        </div>
@endif
<!-- Modal-->
