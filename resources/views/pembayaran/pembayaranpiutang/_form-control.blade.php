<div class="card-body">

    <div class="form-group">
        <label>No. Faktur :</label>
        <input type="text" name="no_faktur" readonly="readonly" value="{{ $piutang->fakturpenjualan->kode }}"
            class="form-control form-control-solid" />

    </div>

    <div class="form-group">
        <label>Kode KPA :</label>
        <input type="text" name="no_faktur" readonly="readonly" value="{{ $piutang->fakturpenjualan->no_kpa }}"
            class="form-control form-control-solid" />

    </div>
    <div class="form-group">
        <label>Customer :</label>
        <input type="text" readonly="readonly" name="supplier" value="{{ $piutang->customers->nama }}"
            class="form-control form-control-solid" />

    </div>
    <div class="form-group">
        <label>Total :</label>
        <input readonly="readonly" type="text" name="total"
            value="{{ number_format($piutang->total, 0, ',', '.') }}" class="form-control form-control-solid" />
    </div>
    <div class="form-group">
        <label>Terbayar :</label>
        <input readonly="readonly" type="text" name="dibayar"
            value="{{ number_format($piutang->dibayar, 0, ',', '.') }}" class="form-control form-control-solid" />
    </div>
    <div class="form-group">
        <label>Sisa Piutang :</label>
        <input readonly="readonly" type="text" name="sisa"
            value="{{ number_format($piutang->total - $piutang->dibayar, 0, ',', '.') }}"
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
        <input type="text" name="nominal" id="nominal" onkeyup="javascript:formatRupiah(this.value, 'nominal')"
            required value="" class="form-control" />
    </div>
    @error('nominal')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-group">
        <label>Media Pembayaran :</label>
        <select class="form-control select2" required id="select2" name="bank_id">
            <option value="">Pilih Media Pembayaran</option>
            @foreach ($banks as $cg)
                @if ($pembayaranpiutang->bank_id == $cg->id)
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

<hr>

<table class="table yajra-datatable collapsed ">
    <thead class="datatable-head">
        <tr>
            <th>Tanggal</th>
            <th>Customer</th>
            <th>Faktur</th>
            <th>No KPA</th>
            <th>Akun Bank</th>
            <th>Nominal</th>
            <th>Keterangan</th>
            <th style="width: 15%">Action</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

</div>
<!--end::Form-->
<div class="card-footer text-right">
    <div class="row">
        <div class="col-lg-12 ">
            <button type="submit" class="btn btn-success font-weight-bold mr-2"><i class="flaticon2-paperplane"></i>
                {{ $submit }}</button>
            <a href="{{ route('pembayaranpiutang.index') }}" class="btn btn-secondary font-weight-bold mr-2">
                Cancel</a>
        </div>
    </div>
</div>
