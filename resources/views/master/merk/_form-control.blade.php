<div class="card-body">

    <div class="form-group">
        <label>Nama :</label>
        <input type="text" name="nama" value="{{ old('nama') ?? $merk->nama }}"
            class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan Nama Kategori Customer" />
        @error('nama')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Keterangan :</label>
        <input type="text" name="keterangan" value="{{ old('keterangan') ?? $merk->keterangan }}" class="form-control"
            placeholder="Keterangan" />
        @error('keterangan')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="S">Supplier</label>
        <select name="supplier_id" id="kt_select2_1" class="form-control" required>

            @foreach ($supplier as $item)
                @if ($merk)
                    @if ($merk->supplier_id == $item->id)
                    <option value="{{$item->id}}" selected>{{$item->nama}}</option>           
                    @else
                    <option value="{{$item->id}}">{{$item->nama}}</option>    
                    @endif
                @else
                <option value="{{$item->id}}">{{$item->nama}}</option>    
                @endif                    
            @endforeach            
        </select>
    </div>

</div>

</div>
<!--end::Form-->
<div class="card-footer text-right">
    <div class="row">
        <div class="col-lg-12 ">
            <button type="submit" class="btn btn-success font-weight-bold mr-2"><i class="flaticon2-paperplane"></i>
                {{ $submit }}</button>
            <a href="{{ route('merk.index') }}" class="btn btn-secondary font-weight-bold mr-2">
                Cancel</a>
        </div>
    </div>
</div>