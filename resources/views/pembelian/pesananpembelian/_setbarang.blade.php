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
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_2_3">
                                    <span class="nav-icon"><i class="flaticon2-drop"></i></span>
                                    <span class="nav-text">LIST HARGA JUAL</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_2_4">
                                    <span class="nav-icon"><i class="flaticon2-drop"></i></span>
                                    <span class="nav-text">LIST HARGA BELI</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body" style="height: 400px;">
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
                                        <label class="col-lg-2 col-form-label">Harga</label>
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control" id="hargabeli" name="hargabeli"
                                                value="{{ $product->hargabeli }}" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">PPN (%) * (KHUSUS E-KATALOG)</label>
                                        <div class="col-lg-10">
                                            <input type="number" class="form-control" id="ppnprice" name="ppnprice"
                                                value="0" />
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
                                        <label class="col-lg-2 col-form-label">Ongkir(Rp.)</label>
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control" id="ongkir" name="ongkir"
                                                value="0" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Keterangan</label>
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control" id="keterangan"
                                                name="keterangan" value="" />
                                        </div>
                                    </div>

                                    <button type="button" onclick="javascript:submitItem();"
                                        class="btn btn-success mr-2 btn-block">Submit</button>


                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="kt_tab_pane_2_3" role="tabpanel"
                            aria-labelledby="kt_tab_pane_2_3">
                            <table class="table table-separate table-head-custom table-checkable" id="kt_datatable1">
                                <thead class="datatable-head">
                                    <tr>
                                        <th>Customer</th>
                                        <th>Harga Jual</th>
                                        <th>Diskon Persen</th>
                                        <th>Diskon Rupiah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penjualan as $item)
                                        <tr>
                                            <td>{{ $item->fakturpenjualan->customers->nama }}</td>
                                            <td>{{ number_format($item->hargajual, 0, ',', '.') }}</td>
                                            <td>{{ $item->diskon_persen }}</td>
                                            <td>{{ $item->diskon_rp }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="kt_tab_pane_2_4" role="tabpanel"
                            aria-labelledby="kt_tab_pane_2_3">
                            <table class="table table-separate table-head-custom table-checkable" id="kt_datatable1">
                                <thead class="datatable-head">
                                    <tr>
                                        <th>Supplier</th>
                                        <th>Harga Beli</th>
                                        <th>Diskon Persen</th>
                                        <th>Diskon Rupiah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembelian as $item)
                                        <tr>
                                            <td>{{ $item->fakturpembelian->suppliers->nama }}</td>
                                            <td>{{ number_format($item->hargabeli, 0, ',', '.') }}</td>
                                            <td>{{ $item->diskon_persen }}</td>
                                            <td>{{ $item->diskon_rp }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>





                    </div>
                    <div class="modal-footer">



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
                    <div class="modal-body" style="height: 400px;">

                        <form class="form">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Nama Barang</label>
                                    <div class="col-lg-10">
                                        <input type="text" readonly="readonly" disabled="disabled"
                                            class="form-control form-control-solid" name="nama" id="nama"
                                            value="{{ $product_name }}" />
                                        <input type="hidden" id="product_id" name="product_id"
                                            value="{{ $item->product_id }}" >
                                        <input type="hidden" id="id" name="id"
                                            value="{{ $item->id }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Qty</label>
                                    <div class="col-lg-2">
                                        <input type="number" class="form-control" id="qty" name="qty"
                                            value="{{ $item->qty }}" {{ $status ? ($status > 2 ? 'disabled' : '') : '' }}/>
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
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Harga</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="hargabeli" name="hargabeli"
                                            value="{{ number_format($item->hargabeli, 12, ',', '') }}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">PPN (%) * (KHUSUS E-KATALOG)</label>
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
                                    <label class="col-lg-2 col-form-label">Ongkir(Rp.)</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="ongkir" name="ongkir"
                                            value="{{ number_format($item->ongkir, 2, ',', '') }}" />
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
