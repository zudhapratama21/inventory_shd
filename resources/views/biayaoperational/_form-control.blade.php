<div class="card-body">
    <div class="form-group">
        <label>Tanggal :</label>
        <input type="date" name="tanggal" value="{{ old('tanggal') ? $biayaoperational->tanggal : '' }}"
            class="form-control @error('tanggal') is-invalid @enderror" placeholder="Masukkan Tanggal" />
        @error('tanggal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Kode :</label>
        <input type="input" name="kode" class="form-control" placeholder="kode" required/>
        @error('keterangan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Jenis Biaya :</label>
        <select name="jenis_biaya_id" class="form-control select2" id="kt_select2_4">
            @foreach ($jenisbiaya as $item)
                <optgroup label="{{ $item->nama }}">
                    @foreach ($item->subjenisbiaya as $data)
                        <option value="{{ $data->id }}">{{ $data->nama }}</option>
                    @endforeach
                </optgroup>
            @endforeach

        </select>

        @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Nominal :</label>
        <input type="number" name="nominal" value="{{ old('nominal') ? $biayaoperational->nominal : '' }}"
            class="form-control @error('nominal') is-invalid @enderror" placeholder="Masukkan Nominal Biaya" />
        @error('nominal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Request :</label>
        <select type="text" name="karyawan_id" class="form-control" id="kt_select2_2">
            @foreach ($karyawan as $item)
                <option value="{{ $item->id }}">{{ $item->nama }}</option>
            @endforeach
        </select>
        @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Sumber Dana :</label>
        <select name="bank_id" id="kt_select2_1" class="form-control">

            @foreach ($bank as $item)
                <option value="{{ $item->id }}">{{ $item->nama }}</option>
            @endforeach

        </select>

        @error('sumberdana')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Keterangan :</label>
        <input type="text" name="keterangan" value="{{ old('keterangan') ? $biayaoperational->keterangan : '' }}"
            class="form-control" placeholder="Keterangan" />
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
            <a href="{{ route('biayaoperational.index') }}" class="btn btn-secondary font-weight-bold mr-2">
                Cancel</a>
        </div>
    </div>
</div>
