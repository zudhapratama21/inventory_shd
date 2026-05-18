<div class="card-body">


    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">Supplier:</label>
        <div class="col-lg-4">
            <input type="text" class="form-control form-control-solid" name="supplier" readonly
                value="{{ $fakturpembelian->suppliers->nama }}" id="supplier" />
        </div>
        <label class="col-lg-2 col-form-label text-right">Tanggal:</label>
        <div class="col-lg-4">
            <div class="input-group date">
                @if ($fakturpembelian->tanggal != null)
                    <input type="text" class="form-control " name="tanggal"
                        value="{{ $fakturpembelian->tanggal->format('d-m-Y') }}" id="tgl1" />
                @else
                    <input type="text" class="form-control" name="tanggal" value="{{ $tglNow }}"
                        id="tgl1" />
                @endif

                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-calendar"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">Surat Pesanan:</label>
        <div class="col-lg-4">
            <input type="text" class="form-control form-control-solid" name="pesanan_pembelian" readonly
                value="{{ $fakturpembelian->po->no_so }}" id="pesanan_pembelian" />
            @error('pesanan_pembelian')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <label class="col-lg-2 col-form-label text-right">Penerimaan Barang:</label>
        <div class="col-lg-4">
            <input type="text" class="form-control form-control-solid" name="penerimaan_barang" readonly
                value="{{ $fakturpembelian->PB->kode }}" id="penerimaan_barang" />
            @error('penerimaan_barang')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">Tanggal Jatuh Tempo:</label>
        <div class="col-lg-4">
            <div class="input-group date">
                <input type="text" class="form-control" name="jatuhtempo" value="{{ \Carbon\Carbon::parse($fakturpembelian->hutangs->tanggal_top)->format('d-m-Y') }}"
                    id="tgl2" />

                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-calendar"></i>
                    </span>
                </div>
            </div>
        </div>
        <label class="col-lg-2 col-form-label text-right">No Faktur Supplier :</label>
        <div class="col-lg-4">
            <input type="text" class="form-control" name="no_faktur_supplier" id="penerimaan_barang"
                value="{{ $fakturpembelian->no_faktur_supplier }}" />
            @error('no_faktur_supplier')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>


    <div class="form-group row">
        <div class="col-lg-12">
            <div id="tabel_detil" class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Ongkir</th>
                            <th>Diskon(%)</th>
                            <th>Diskon(Rp)</th>
                            <th>Subtotal</th>
                            <th>Total Diskon</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fakturpembelian->detail as $item)
                            <tr>
                                <td>{{ $item->products->kode }}</td>
                                <td>{{ $item->products->nama }}</td>
                                <td>
                                    @if ($item->beda_satuan == 'on')
                                        {{ $item->satuan_konversi }}
                                </td>
                            @else
                                {{ $item->satuan }}</td>
                        @endif

                        <td>{{ $item->qty }}</td>
                        <td>{{ number_format($item->hargabeli, 2, ',', '.') }}</td>
                        <td>{{ number_format($item->ongkir, 2, ',', '.') }}</td>
                        <td>{{ $item->diskon_persen }}</td>
                        <td>{{ $item->diskon_rp }}</td>
                        <td>{{ number_format($item->subtotal, 2, ',', '.') }}</td>
                        <td>{{ number_format($item->total_diskon, 2, ',', '.') }}</td>
                        <td>{{ number_format($item->total, 2, ',', '.') }}</td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="separator separator-dashed my-5"></div>
    <div class="form-group row">
        <div class="col-lg-6">
            <label class="">Keterangan:</label>
            <div class="kt-input-icon kt-input-icon--right">
                <textarea class="form-control" name="keterangan" id="keterangan">{{ $fakturpembelian->keterangan }}</textarea>
            </div>

        </div>
        <div class="col-lg-6">
            <div id="div_summary">
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Subtotal :</label>
                    <div class="col-lg-5 mb-2">
                        <div id="div_subtotal">
                            <input type="text" id="subtotal" class="form-control text-right" name="subtotal"
                                readonly="readonly"
                                value="{{ number_format($fakturpembelian->subtotal, 2, ',', '.') }}">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Diskon :</label>
                    <div class="col-lg-5 mb-2">
                        <div class="input-group">

                            <input type="text" class="form-control text-right" id="diskon" name="diskon"
                                value="{{ number_format($fakturpembelian->total_diskon_header, 2, ',', '.') }}"
                                readonly="readonly">

                        </div>

                    </div>
                </div>
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Total :</label>
                    <div class="col-lg-5 mb-2">
                        <input type="text" id="total" readonly="readonly"
                            value="{{ number_format($fakturpembelian->total, 2, ',', '.') }}" name="total"
                            class="form-control text-right">
                    </div>
                </div>
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Ongkir :</label>
                    <div class="col-lg-5 mb-2">
                        <input type="text" id="ongkirheader"
                            value="{{ number_format($fakturpembelian->ongkir, 2, ',', '.') }}" readonly="readonly"
                            name="ongkirheader" class="form-control text-right">
                    </div>
                </div>

                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">PPN (%) :</label>
                    <div class="col-lg-5 mb-2">
                        <div class="input-group">

                            <input type="text" class="form-control text-right" id="ppn" name="ppn"
                                value="{{ number_format($fakturpembelian->ppn, 2, ',', '.') }}" readonly="readonly">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Grand Total :</label>
                    <div class="col-lg-5">
                        <input type="text" id="grandtotal" readonly="readonly" name="grandtotal"
                            class="form-control text-right"
                            value="{{ number_format($fakturpembelian->grandtotal, 2, ',', '.') }}">
                    </div>
                </div>
            </div>

        </div>

    </div>


</div>

<div class="card-footer text-right">
    <div class="row">
        <div class="col-lg-12 ">
            <button type="submit" class="btn btn-success font-weight-bold mr-2"><i class="flaticon2-paperplane"></i>
                {{ $submit }}</button>
            <a href="{{ route('pesananpembelian.index') }}" class="btn btn-secondary font-weight-bold mr-2">
                Cancel</a>
        </div>
    </div>
</div>
