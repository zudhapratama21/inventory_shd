<div class="card-body">
    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">Customers:</label>
        <div class="col-lg-4">
            <input type="text" class="form-control" value="{{ $fakturpenjualan->customers->nama }}" readonly>
        </div>
        <label class="col-lg-2 col-form-label text-right">Tanggal:</label>
        <div class="col-lg-4">
            <div class="input-group date">
                <input type="text" class="form-control" name="tanggal"
                    value="{{ \Carbon\Carbon::parse($fakturpenjualan->tanggal)->format('d-m-Y') }}" id="tgl1" />

                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-calendar"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">No. Perusahaan:</label>
        <div class="col-lg-1">
            <input type="number" id="no_urut" name="no_urut" class="form-control" />
        </div>
        <div class="col-lg-1">
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="cekNomor()"> <i
                    class="flaticon2-refresh text-success"></i> Cek</button>
        </div>
        <div class="col-lg-2">
            <input type="text" class="form-control" value="{{ $fakturpenjualan->no_perusahaan }}" readonly />
        </div>

        <label class="col-lg-2 col-form-label text-right">Tanggal Jatuh Tempo:</label>
        <div class="col-lg-4">
            <div class="input-group date">
                <input type="text" class="form-control" name="tanggal"
                    value="{{ \Carbon\Carbon::parse($fakturpenjualan->tanggal_jatuh_tempo)->format('d-m-Y') }}"
                    id="tgl2" />
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-calendar"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">Sales:</label>
        <div class="col-lg-4">
            <select class="form-control select2" id="sales_id" name="sales_id">
                <option value="">Pilih Sales</option>
                @foreach ($sales as $s)
                    @if ($fakturpenjualan->sales_id == $s->id)
                        <option value="{{ $s->id }}" selected>{{ $s->nama }}</option>
                    @else
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <label class="col-lg-2 col-form-label text-right">Komoditas:</label>
        <div class="col-lg-4">
            <select class="form-control" id="komoditas_id" name="komoditas_id">
                <option value="">Pilih Komoditas</option>
                @foreach ($komoditas as $k)
                    @if ($fakturpenjualan->komoditas_id == $k->id)
                        <option value="{{ $k->id }}" selected>{{ $k->nama }}</option>
                    @else
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">Kategori Pesanan:</label>
        <div class="col-lg-4">
            <select class="form-control select2" id="kategori_id" name="kategori_id">
                <option value="">Pilih Kategori</option>
                @foreach ($kategori as $s)
                    @if ($fakturpenjualan->kategoripesanan_id == $s->id)
                        <option value="{{ $s->id }}" selected>{{ $s->nama }}</option>
                    @else
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <label class="col-lg-2 col-form-label text-right"></label>
        <div class="col-lg-4">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">No SP Customer:</label>
        <div class="col-lg-4">
            <textarea class="form-control" name="no_sp_customer" id="no_sp_customer" cols="30" rows="5">{{ $fakturpenjualan->no_sp_customer }}</textarea>
        </div>
        <label class="col-lg-2 col-form-label text-right">Tanggal SP Customer</label>
        <div class="col-lg-4">
            <textarea class="form-control" name="tanggal_sp_customer" id="tanggal_sp_customer" cols="30" rows="5">{{ $fakturpenjualan->tanggal_sp_customer }}</textarea>
        </div>
    </div>

    @if ($status == 0)
        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-sm btn-outline-primary mb-2" onclick="tambahbarang()"><i
                    class="flaticon-add-circular-button"></i> Tambah Barang</button>
        </div>
    @endif
    
    <div class="form-group row">
        <div class="col-lg-12">
            <div id="tabel_detil" class="table table-bordered ">
                <table class="table yajra-datatable-databarang">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Qty</th>
                            <th>Kena PPn ?</th>
                            <th>Harga</th>
                            <th>Diskon(%)</th>
                            <th>Diskon(Rp)</th>
                            <th>Subtotal</th>
                            <th>Total Diskon</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
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
                <textarea class="form-control" name="keteranganfj" id="keteranganfj">{{ $fakturpenjualan->keterangan }}</textarea>
            </div>

        </div>
        <div class="col-lg-6">
            <div id="div_summary">
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Subtotal :</label>
                    <div class="col-lg-5 mb-2">
                        <div id="div_subtotal">
                            <input type="text" id="subtotal" class="form-control text-right" name="subtotal"
                                readonly="readonly" value="0">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Diskon :</label>
                    <div class="col-lg-5 mb-2">
                        <div class="input-group">
                            <input type="text" class="form-control text-right" id="diskon" name="diskon"
                                value="0" readonly="readonly">

                        </div>

                    </div>
                </div>

                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Ongkir :</label>
                    <div class="col-lg-5 mb-2">
                        <input type="text" id="ongkirtotal" value="0" readonly="readonly"
                            name="ongkirtotal" class="form-control text-right">
                    </div>
                </div>

                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Total :</label>
                    <div class="col-lg-5 mb-2">
                        <input type="text" id="total" readonly="readonly" value="0" name="total"
                            class="form-control text-right">
                    </div>
                </div>
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">PPn (%) :</label>
                    <div class="col-lg-5 mb-2">
                        <div class="input-group">

                            <input type="text" class="form-control text-right" id="totalppn" name="ppn"
                                value="0" readonly="readonly">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Materai :</label>
                    <div class="col-lg-5 mb-2">
                        <div class="input-group">
                            <button type="button" onclick="tambahbiaya();"
                                class="btn  btn-icon btn-outline-primary mr-1">
                                <i class="flaticon-coins"></i>
                            </button>
                            <input type="text" class="form-control text-right" id="materaitotal"
                                name="materaitotal" value="0" readonly="readonly">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Grand Total :</label>
                    <div class="col-lg-5">
                        <input type="text" id="grandtotal" readonly="readonly" name="grandtotal"
                            class="form-control text-right" value="0">
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

</div>
<!--end::Form-->
<div class="card-footer text-right">
    <div class="row">
        <div class="col-lg-12 ">
            <button type="button" class="btn btn-success font-weight-bold mr-2" onclick="submitfj()"><i
                    class="flaticon2-paperplane"></i>
                {{ $submit }}</button>
            <a href="{{ route('fakturpenjualan.index') }}" class="btn btn-secondary font-weight-bold mr-2">
                Cancel</a>
        </div>
    </div>
</div>
