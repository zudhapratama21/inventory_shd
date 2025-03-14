<div class="card-body">
    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">Stok Konversi:</label>
        <div class="col-lg-4">
            <input type="text" name="qty_konversi" id="qty_konversi" readonly="readonly" class="form-control"
                value="{{ $temp->qty }}" />

            <input type="hidden" name="konversi_id" value="{{ $temp->id }}">
            <input type="hidden" name="status" value="{{ $status }}">
        </div>

        @if ($status == 1)
            @if ($temp->tanggal != null)
                <label class="col-lg-2 col-form-label text-right">Expired Date:</label>
                <div class="col-lg-4">
                    <div class="input-group date">
                        <input type="text" class="form-control" name="tanggal" readonly
                            value="{{ Carbon\Carbon::parse($temp->exp_date)->format('d-m-Y') }}" id="tgl1" />

                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar"></i>
                            </span>
                        </div>

                    </div>
                </div>
            @endif
        @endif
    </div>
    <div class="row">                 
            <label class="col-lg-1 col-form-label text-right">Nama Product:</label>
            <div class="col-lg-4">
                <input type="text"  readonly="readonly" class="form-control"
                    value="{{ $product }}" />                
            </div>        
    </div>
    <div class="text-right mb-3">
        <a href="javascript:caribarang()" class="btn btn-sm btn-primary"><i class="flaticon2-add"></i>Tambah Barang</a>
    </div>
    <div class="form-group row">
        <div class="col-lg-12">
            <div id="tabel_detil" class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Qty</th>
                            <th>Harga Beli</th>
                            <th>Diskon(%)</th>
                            <th>Diskon (Rp.)</th>
                            <th>-</th>
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
                <textarea class="form-control" name="keterangan" id="keterangan"></textarea>
            </div>

        </div>
        <div class="col-lg-6">

        </div>

    </div>


</div>

</div>
<!--end::Form-->
<div class="card-footer text-right">
    <div class="row">
        <div class="col-lg-12 ">
            <button type="submit" class="btn btn-success font-weight-bold mr-2"><i class="flaticon2-paperplane"></i>
                {{ $submit }}</button>
            <a href="{{ route('penerimaanbarang.listpo') }}" class="btn btn-secondary font-weight-bold mr-2">
                Cancel</a>
        </div>
    </div>
</div>
