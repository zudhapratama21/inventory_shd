<div class="card-body">


    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">Supplier:</label>
        <div class="col-lg-4">
            @if ($pesananpembelian->status_po_id == 1 || $pesananpembelian->status_po_id == 2)
            <select class="form-control select2" id="supplier_id" name="supplier_id">
                <option value="">Pilih Supplier</option>
                @foreach ($suppliers as $cg)
                    @if ($pesananpembelian->supplier_id == $cg->id)
                        <option selected="selected" value="{{ $cg->id }}">{{ $cg->nama }}</option>
                    @else
                    <option value="{{ $cg->id }}">{{ $cg->nama }}</option>
                    @endif
                @endforeach
            </select>
            @else
            <select name="supplier_id" id="" class="form-control" disabled>
                <option value="{{ $pesananpembelian->supplier_id }}">{{ $pesananpembelian->suppliers->nama }}</option>
            </select>
            @endif
          
            @error('supplier_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <label class="col-lg-2 col-form-label text-right">Tanggal:</label>
        <div class="col-lg-4">
            <div class="input-group date">
                @if($pesananpembelian->tanggal <> null)
                    <input type="text" class="form-control" name="tanggal" readonly
                        value="{{ $pesananpembelian->tanggal->format("d-m-Y") }}" id="tgl1" />
                    @else
                    <input type="text" class="form-control" name="tanggal" readonly value="{{ $tglNow }}" id="tgl1" />
                    @endif

                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="la la-calendar"></i>
                        </span>
                    </div>
            </div>
            <span class="form-text text-muted">Please enter your contact number</span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">Komoditas:</label>
        <div class="col-lg-4">
            <select class="form-control select2" id="komoditas" name="komoditas_id">
                <option value="">Pilih Komoditas</option>
                @foreach ($komoditass as $cg)
                @if ($pesananpembelian->komoditas_id == $cg->id)
                     <option selected="selected" value="{{ $cg->id }}">{{ $cg->nama }}</option>
                @else
                     <option value="{{ $cg->id }}">{{ $cg->nama }}</option>
                @endif

                @endforeach
            </select>
            @error('komoditas_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <label class="col-lg-2 col-form-label text-right">TOP:</label>
        <div class="col-lg-4">
            <input type="number" name="top" id="top" class="form-control" placeholder="Enter Term of Payment"
                value="30" />
            <span class="form-text text-muted">Isi Dalam Satuan Hari, Contoh : 30</span>
        </div>
    </div>
   
    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">Kategori:</label>
        <div class="col-lg-4">
            <select class="form-control select2" id="kategori" name="kategoripesanan_id">
                <option value="">Pilih Kategori</option>
                @foreach ($kategoris as $cg)
                    @if ($pesananpembelian->kategoripesanan_id == $cg->id)
                        <option selected="selected" value="{{ $cg->id }}">{{ $cg->nama }}</option>
                    @else
                        <option value="{{ $cg->id }}">{{ $cg->nama }}</option>
                    @endif
                @endforeach
            </select>
            @error('kategoripesanan_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <label class="col-lg-2 col-form-label text-right">No. KPA:</label>
        <div class="col-lg-4">
            <input type="text" id="no_so" name="no_so" class="form-control"
                placeholder="No. Surat Pesanan Penjualan (Jika Ada)" value="{{$pesananpembelian->no_so}}"/>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right"></label>
        <div class="col-lg-4">
            
        </div>
        <label class="col-lg-2 col-form-label text-right">No. Surat Pesanan Customer :</label>
        <div class="col-lg-4">
            <input type="text" id="no_so_customer" name="no_so_customer" class="form-control"
                placeholder="No. Surat Pesanan Customer " value="{{$pesananpembelian->no_so_customer}}" />
        </div>
    </div>

    @if ($pesananpembelian->status_po_id == 1 || $pesananpembelian->status_po_id == 2)
    <div class="text-right mb-3">
        <a href="javascript:caribarang()" class="btn btn-sm btn-primary"><i class="flaticon2-add"></i>Tambah Barang</a>
    </div>
    @endif

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
                            <th>Diskon(%)</th>
                            <th>Subtotal</th>
                            <th>Keterangan</th>
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
        <div class="col-lg-3">
            <label class="">Keterangan:</label>
            <div class="kt-input-icon kt-input-icon--right">
                <textarea class="form-control" name="keterangan" id="keterangan">{{$pesananpembelian->keterangan}}</textarea>
            </div>

        </div>
        <div class="col-lg-3">
            <label class="">Keterangan Internal: <span class="text-danger" style="font-size: 67%">*(keterangan tidak muncul saat di print)</span> </label>
            <div class="kt-input-icon kt-input-icon--right">
                <textarea class="form-control" name="keterangan_internal" id="keterangan">{{$pesananpembelian->keterangan_internal}}</textarea>
            </div>

        </div>
        <div class="col-lg-6">
            <div id="div_summary">
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Subtotal :</label>
                    <div class="col-lg-5 mb-2">
                        <div id="div_subtotal">
                            <input type="text" id="subtotal" class="form-control text-right" name="subtotal"
                                readonly="readonly" value="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Diskon :</label>
                    <div class="col-lg-5 mb-2">
                        <div class="input-group">
                            <a href="javascript:editdiskon();" class="btn  btn-icon btn-success mr-1">
                                <i class="flaticon-edit"></i>
                            </a>
                            <input type="text" class="form-control text-right" id="diskon" name="diskon" value="0"
                                readonly="readonly">

                        </div>

                    </div>
                </div>
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Ongkir :</label>
                    <div class="col-lg-5 mb-2">
                        <input type="text" id="ongkirheader" readonly="readonly" name="ongkirheader"
                            class="form-control text-right">
                    </div>
                </div>
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Total :</label>
                    <div class="col-lg-5 mb-2">
                        <input type="text" id="total" readonly="readonly" name="total" class="form-control text-right">
                    </div>
                </div>
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">PPN (%) :</label>
                    <div class="col-lg-5 mb-2">
                        <div class="input-group">
                            <a href="javascript:editppn();" class="btn  btn-icon btn-primary mr-1">
                                <i class="flaticon-edit"></i>
                            </a>
                            <input type="text" class="form-control text-right" id="ppn" name="ppn" value="0"
                                readonly="readonly">
                        </div>
                    </div>
                </div>               
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Grand Total :</label>
                    <div class="col-lg-5">
                        <input type="text" id="grandtotal" readonly="readonly" name="grandtotal"
                            class="form-control text-right">
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
            <button type="submit" class="btn btn-success font-weight-bold mr-2"><i class="flaticon2-paperplane"></i>
                {{ $submit }}</button>
            <a href="{{ route('pesananpembelian.index') }}" class="btn btn-secondary font-weight-bold mr-2">
                Cancel</a>
        </div>
    </div>
</div>