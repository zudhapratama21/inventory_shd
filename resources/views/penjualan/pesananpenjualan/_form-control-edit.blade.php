<div class="card-body">
    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">Customer:</label>
        <div class="col-lg-4">
            <select class="form-control select2" id="customer_id" name="customer_id" required>
                <option value="">Pilih Customer</option>
                @foreach ($customers as $cg)
                @if ($pesananpenjualan->customer_id == $cg->id)
                <option selected="selected" value="{{ $cg->id }}">{{ $cg->nama }}</option>
                @else
                <option value="{{ $cg->id }}">{{ $cg->nama }}</option>
                @endif

                @endforeach
            </select>
            @error('customer_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <label class="col-lg-2 col-form-label text-right">Tanggal:</label>
        <div class="col-lg-4">
            <div class="input-group date">
                @if($pesananpenjualan->tanggal <> null)
                    <input type="text" class="form-control" name="tanggal" readonly
                        value="{{ $pesananpenjualan->tanggal->format("d-m-Y") }}" id="tgl1" required/>
                    @else
                    <input type="text" class="form-control" name="tanggal" readonly value="{{ $tglNow }}" id="tgl1" required/>
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
            <select class="form-control select2" id="komoditas" name="komoditas_id" required>
                <option value="">Pilih Komoditas</option>
                @foreach ($komoditass as $cg)
                @if ($pesananpenjualan->komoditas_id == $cg->id)
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
                value="30"  />
            <span class="form-text text-muted">Isi Dalam Satuan Hari, Contoh : 30</span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">Kategori:</label>
        <div class="col-lg-4">
            <select class="form-control select2" id="kategori" name="kategoripesanan_id" required>
                <option value="">Pilih Kategori</option>
                @foreach ($kategoris as $cg)
                    @if ($pesananpenjualan->kategoripesanan_id == $cg->id)
                    <option value="{{ $cg->id }}" selected>{{ $cg->nama }}</option>
                    @else
                    <option value="{{ $cg->id }}">{{ $cg->nama }}</option>
                    @endif
                @endforeach
            </select>
            @error('kategoripesanan_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <label class="col-lg-2 col-form-label text-right">No. Surat Pesanan Cust.:</label>
        <div class="col-lg-4">
            <input type="text" id="no_so" name="no_so" class="form-control"
                placeholder="No. Surat Pesanan Penjualan (Jika Ada)" value="{{$pesananpenjualan->no_so}}" />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">ID Paket:</label>
        <div class="col-lg-4">
            <input type="text" id="id_paket" name="id_paket" class="form-control"
                placeholder="Khusus e-Katalog (Jika Ada)" value="{{$pesananpenjualan->id_paket}}"/>
        </div>

        <label class="col-lg-2 col-form-label text-right">Tanggal. Surat Pesanan Cust.:</label>
        <div class="col-lg-4">
            <div class="input-group date">
                {{-- @dd(Carbon\Carbon::parse($pesananpenjualan->tanggal_pesanan_customer)->format("d-m-Y")) --}}
                @if($pesananpenjualan->tanggal_pesanan_customer <> null)                            
                     <input type="text" name="tanggal_pesanan_customer" class="form-control" value="{{Carbon\Carbon::parse($pesananpenjualan->tanggal_pesanan_customer)->format("d/m/Y")}}" id="kt_datepicker_3"> 
                 @else                       
                       <input type="text" name="tanggal_pesanan_customer" class="form-control" value="{{$tglNow}}"  id="kt_datepicker_3"/>
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
        <label class="col-lg-1 col-form-label text-right">Sumber Dana:</label>
        <div class="col-lg-4">
            <input type="text" id="sumber_dana" name="sumber_dana" class="form-control"
                placeholder="Khusus e-Katalog (Jika Ada)" value="{{$pesananpenjualan->nama_paket}}"/>
        </div>

        <label class="col-lg-2 col-form-label text-right">Nama Paket :</label>
        <div class="col-lg-4">
            <input type="text" id="nama_paket" name="nama_paket" class="form-control"
                placeholder="Khusus e-Katalog (Jika Ada)" value="{{$pesananpenjualan->nama_paket}}"/>
        </div>
       
    </div>
    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">Pemesan:</label>
        <div class="col-lg-4">
            <input type="text" id="pemesan" name="pemesan" class="form-control"
                placeholder="Khusus e-Katalog (Jika Ada)" value="{{$pesananpenjualan->pemesan}}"/>
        </div>

        <label class="col-lg-2 col-form-label text-right">Tahun Anggaran :</label>
        <div class="col-lg-4">
            <input type="text" id="tahun_anggaran" name="tahun_anggaran" class="form-control"
                placeholder="Khusus e-Katalog (Jika Ada)" value="{{$pesananpenjualan->tahun_anggaran}}" />
        </div>
       
    </div>
    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">Sales:</label>
        <div class="col-lg-4">
            <select class="form-control select2" id="sales_id" name="sales_id" required>
                <option value="">Pilih Sales</option>
                @foreach ($saless as $cg)
                @if ($pesananpenjualan->sales_id == $cg->id)
                <option selected="selected" value="{{ $cg->id }}">{{ $cg->nama }}</option>
                @else
                <option value="{{ $cg->id }}">{{ $cg->nama }}</option>
                @endif

                @endforeach
            </select>
            @error('sales_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <label class="col-lg-2 col-form-label text-right">PPK :</label>
        <div class="col-lg-4">
            <input type="text" id="ppk" name="ppk" class="form-control" placeholder="Khusus e-Katalog (Jika Ada)" value="{{$pesananpenjualan->ppk}}"/>
        </div>


    </div>
    <div class="text-right mb-3">
        <a href="javascript:caribarang({{$pesananpenjualan->id}})" class="btn btn-sm btn-primary"><i class="flaticon2-add"></i>Tambah Barang</a>
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
        <div class="col-lg-6">
            <label class="">Keterangan:</label>
            <div class="kt-input-icon kt-input-icon--right">
                <textarea class="form-control" name="keterangan" id="keterangan">{{$pesananpenjualan->keterangan}}</textarea>
            </div>

        </div>
        <div class="col-lg-6">
            <div id="div_summary">
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Subtotal :</label>
                    <div class="col-lg-5 mb-2">
                        <div id="div_subtotal">
                            <input type="text" id="subtotal" class="form-control text-right" 
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
                            <input type="text" class="form-control text-right" id="diskon"  value="0"
                                readonly="readonly">
                        </div>                        
                    </div>
                </div>

                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Ongkir :</label>
                    <div class="col-lg-5 mb-2">
                        <input type="text" id="ongkirheader" readonly="readonly" 
                            class="form-control text-right">
                    </div>
                </div>
                
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Total :</label>
                    <div class="col-lg-5 mb-2">
                        <input type="text" id="total" readonly="readonly"  class="form-control text-right">
                    </div>
                </div>
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">PPN (%) :</label>
                    <div class="col-lg-5 mb-2">
                        <div class="input-group">
                            <a href="javascript:editppn();" class="btn  btn-icon btn-primary mr-1">
                                <i class="flaticon-edit"></i>
                            </a>
                            <input type="text" class="form-control text-right" id="ppn"  value="0"
                                readonly="readonly">
                        </div>
                    </div>
                </div>               
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Grand Total :</label>
                    <div class="col-lg-5">
                        <input type="text" id="grandtotal" readonly="readonly" 
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
            <a href="{{ route('pesananpenjualan.index') }}" class="btn btn-secondary font-weight-bold mr-2">
                Cancel</a>
        </div>
    </div>
</div>