<div class="card-body">

    <div class="form-group">
        <label>No. Faktur :</label>
        <input type="text" name="no_faktur" readonly="readonly" value="{{ $hutang->FakturPO->kode }}"
            class="form-control form-control-solid" />

    </div>
    <div class="form-group">
        <label>No. Surat :</label>
        <input type="text" name="no_faktur" readonly="readonly" value="{{ $hutang->PO->no_so }}"
            class="form-control form-control-solid" />

    </div>
    <div class="form-group">
        <label>No. Faktur Supplier :</label>
        <input type="text" name="no_faktur_supplier" readonly="readonly" value="{{ $hutang->FakturPO->no_faktur_supplier }}"
            class="form-control form-control-solid" />

    </div>
    <div class="form-group">
        <label>No. SO Customer :</label>
        <input type="text" name="no_faktur" readonly="readonly" value="{{ $hutang->PO->no_so_customer }}"
            class="form-control form-control-solid" />

    </div>
    <div class="form-group">
        <label>Supplier :</label>
        <input type="text" readonly="readonly" name="supplier" value="{{ $hutang->suppliers->nama }}"
            class="form-control form-control-solid" />

    </div>
    <div class="form-group">
        <label>Total :</label>
        <input readonly="readonly" type="text" name="total" value="{{ number_format($hutang->total , 0, ',', '.') }}"
            class="form-control form-control-solid" />
    </div>
    <div class="form-group">
        <label>Terbayar :</label>
        <input readonly="readonly" type="text" name="dibayar"
            value="{{ number_format($hutang->dibayar , 0, ',', '.') }}" class="form-control form-control-solid" />
    </div>
    <div class="form-group">
        <label>Sisa Hutang :</label>
        <input readonly="readonly" type="text" name="sisa"
            value="{{ number_format(($hutang->total - $hutang->dibayar) , 0, ',', '.') }}"
            class="form-control form-control-solid" />
    </div>
    <div class="form-group">
        <label>Tgl. Pembayaran :</label>
        <input type="text" class="form-control" required name="tanggal" readonly value="" id="tgl1" />
        @error('tanggal')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Jumlah Pembayaran :</label>
        <input type="text" name="nominal" id="nominal" onkeyup="javascript:formatRupiah(this.value, 'nominal')" required
            value="" class="form-control" />
    </div>
    @error('nominal')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-group">
        <label>Media Pembayaran :</label>
        <select class="form-control select2" required id="select2" name="bank_id">
            <option value="">Pilih Media Pembayaran</option>
            @foreach ($banks as $cg)
            @if ($pembayaranhutang->bank_id == $cg->id)
            <option selected="selected" value="{{ $cg->id }}">{{ $cg->nama }}</option>
            @else
            <option value="{{ $cg->id }}">{{ $cg->nama }}</option>
            @endif

            @endforeach
        </select>
        @error('bank_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label>Keterangan :</label>
        <input type="text" name="keterangan" value="" class="form-control" placeholder="Keterangan" />
        @error('keterangan')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

</div>
<!--end::Form-->
<div class="card-footer text-right">
    <div class="row">
        <div class="col-lg-12 ">
            <button type="submit" class="btn btn-success font-weight-bold mr-2"><i class="flaticon2-paperplane"></i>
                {{ $submit }}</button>
            <a href="{{ route('pembayaranhutang.index') }}" class="btn btn-secondary font-weight-bold mr-2">
                Cancel</a>
        </div>
    </div>
</div>