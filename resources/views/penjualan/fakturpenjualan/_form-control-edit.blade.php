<div class="card-body">

    {{-- @dd($fakturpenjualan) --}}
    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">Customers:</label>
        <div class="col-lg-4">
            <input type="text" class="form-control form-control-solid" name="customer" readonly
                value="{{ $fakturpenjualan->customers->nama }}" id="customer" />
        </div>
        <label class="col-lg-2 col-form-label text-right">Tanggal:</label>
        <div class="col-lg-4">
            <div class="input-group date">
                @if($fakturpenjualan->tanggal <> null)
                    <input type="text" class="form-control form-control-solid" name="tanggal" readonly
                        value="{{ $fakturpenjualan->tanggal->format(" d-m-Y") }}" id="tgl1" />
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
        <label class="col-lg-1 col-form-label text-right">No. SP:</label>
        <div class="col-lg-4">
            <input type="text" class="form-control form-control-solid" name="pesanan_penjualan" readonly
                value="{{ $fakturpenjualan->so->kode }}" id="pesanan_penjualan" />
            @error('pesanan_penjualan')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <label class="col-lg-2 col-form-label text-right">Pengiriman Barang:</label>
        <div class="col-lg-4">
            <input type="text" class="form-control form-control-solid" name="pengiriman_barang" readonly
                value="{{ $fakturpenjualan->sj->kode }}" id="pengiriman_barang" />

            @error('penerimaan_barang')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">No. KPA:</label>
        <div class="col-lg-4">
            <select name="kpa_id" id="kt_select2_2" class="form-control nokpa" required>
                <option value="{{$fakturpenjualan->no_kpa}}">{{$fakturpenjualan->no_kpa}}</option>                    

                @foreach ($nokpa as $item)                
                        <option value="{{$item->no_kpa}}">{{$item->no_kpa}}</option>                    
                @endforeach
            </select>

            @error('no_kpa')
               <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>     
        
        <label class="col-lg-2 col-form-label text-right">No. Seri Pajak:</label>
        <div class="col-lg-4">
            <input type="text" name="no_seri_pajak" class="form-control form-control-solid" value="{{$fakturpenjualan->no_seri_pajak}}"   id="no__seri_pajak" />

            @error('no_seri_pajak')
               <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right"></label>
        <div class="col-lg-4">           
        </div>   

        <label class="col-lg-2 col-form-label text-right">No. Pajak:</label>
                
        <div class="col-lg-4">            
            <select name="pajak_id" id="kt_select2_1" class="form-control nokpa" required>
                @foreach ($nopajak as $item)
                    @if ($fakturpenjualan->pajak_id == $item->id)
                        <option value="{{$item->id}}" selected>{{$item->no_pajak}}</option>
                    @else
                        <option value="{{$item->id}}">{{$item->no_pajak}}</option>
                    @endif
                @endforeach
            </select>

            @error('no_kpa')
               <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @error('no_pajak')
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
                        @foreach ($FJdetails as $item)
                        <tr>
                            <td>{{ $item->products->kode }}</td>
                            <td>{{ $item->products->nama }}</td>
                            <td>{{ $item->satuan }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ number_format($item->hargajual, 2, ',', '.') }}</td>
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
                <textarea class="form-control" name="keterangan" id="keterangan"></textarea>
            </div>

        </div>
        <div class="col-lg-6">
            <div id="div_summary">
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Subtotal :</label>
                    <div class="col-lg-5 mb-2">
                        <div id="div_subtotal">
                            <input type="text" id="subtotal" class="form-control text-right" name="subtotal"
                                readonly="readonly" value="{{ number_format($fakturpenjualan->subtotal, 2, ',', '.')  }}">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Diskon :</label>
                    <div class="col-lg-5 mb-2">
                        <div class="input-group">
                            <input type="text" class="form-control text-right" id="diskon" name="diskon"
                                value="{{ number_format($fakturpenjualan->total_diskon_header, 2, ',', '.')  }}" readonly="readonly">

                        </div>

                    </div>
                </div>

                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Ongkir :</label>
                    <div class="col-lg-5 mb-2">
                        <input type="text" id="ongkirheader" value="{{ number_format($fakturpenjualan->ongkir, 2, ',', '.')  }}"
                            readonly="readonly" name="ongkirheader" class="form-control text-right">
                    </div>
                </div>

                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Total :</label>
                    <div class="col-lg-5 mb-2">
                        <input type="text" id="total" readonly="readonly"
                            value="{{ number_format($fakturpenjualan->total, 2, ',', '.')  }}" name="total"
                            class="form-control text-right">
                    </div>
                </div>
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">PPN (%) :</label>
                    <div class="col-lg-5 mb-2">
                        <div class="input-group">

                            <input type="text" class="form-control text-right" id="ppn" name="ppn"
                                value="{{ number_format($fakturpenjualan->ppn, 2, ',', '.')  }}" readonly="readonly">
                        </div>
                    </div>
                </div>              
                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Biaya lain-lain (Rp) :</label>
                    <div class="col-lg-5 mb-2">
                        <div class="input-group">
                            {{-- <a href="javascript:editbiaya();" class="btn  btn-icon btn-primary mr-1">
                                <i class="flaticon-edit"></i>
                            </a> --}}
                            <input type="text" class="form-control text-right" id="biaya" name="biaya" value="0" value="{{$fakturpenjualan->biaya_lain}}"
                                readonly="readonly">
                        </div>
                    </div>
                </div>    

                <div class="row">
                    <label class="col-lg-7 col-form-label text-right">Grand Total :</label>
                    <div class="col-lg-5">
                        <input type="text" id="grandtotal" readonly="readonly" name="grandtotal"
                            class="form-control text-right"
                            value="{{ number_format($fakturpenjualan->grandtotal, 2, ',', '.')  }}">
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