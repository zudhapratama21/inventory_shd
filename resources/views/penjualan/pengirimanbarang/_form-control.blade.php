<div class="card-body">


    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">Customer:</label>
        <div class="col-lg-4">
            <input type="text" name="customer" id="customer" readonly="readonly" class="form-control"
                value="{{ $pesananpenjualan->customers->nama }}" />
        </div>
        <label class="col-lg-2 col-form-label text-right">Tanggal:</label>
        <div class="col-lg-4">
            <div class="input-group date">
                @if($pesananpenjualan->tanggal <> null)
                    <input type="text" class="form-control" name="tanggal" readonly
                        value="{{ $pesananpenjualan->tanggal->format("d-m-Y") }}" id="tgl1" />
                    @else
                    <input type="text" class="form-control" name="tanggal" readonly value="{{ $tglNow }}" id="tgl1" />
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
        <label class="col-lg-1 col-form-label text-right">No. SO:</label>
        <div class="col-lg-4">
            <input type="text" name="no_so" id="no_so" readonly="readonly" class="form-control"
                value="{{ $pesananpenjualan->kode }}" />
        </div>
    </div>   
    <h5><span class="badge badge-info">Daftar Produk</span></h5>
    <div class="form-group row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table yajra-datatable-pesanan collapsed">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Qty Pesanan</th>
                            <th>Qty Sisa</th>  
                            <th>Status</th>                                               
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <h5><span class="badge badge-info">Daftar Produk Terpilih</span></h5>

    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table yajra-datatable-daftar collapsed">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Qty Kirim</th>                           
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
            <a href="{{ route('pengirimanbarang.listso') }}" class="btn btn-secondary font-weight-bold mr-2">
                Cancel</a>
        </div>
    </div>
</div>