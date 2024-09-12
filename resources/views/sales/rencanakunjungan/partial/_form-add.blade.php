<div class="card-body">    
    <div class="form-group">
        <label for="">Outlet</label>
        <select name="outlet_id" class="form-control" id="kt_select2_1" required>
            <option value="" selected disabled>======= Pilih Outlet =================
            </option>
            @foreach ($outlet as $item)
                <option value="{{ $item->id }}">{{ $item->nama }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="">Tanggal : </label>
        <input type="date" class="form-control" name="tanggal" id="tgl1">
    </div>

    <div class="form-group">
        <label>Aktivitas :</label>
        <textarea type="text" name="aktivitas" value=""
        class="form-control" placeholder="Masukkan aktifitas" id="kt-ckeditor-1" cols="30" rows="5" ></textarea>
       
        @error('aktifitas')
        <div class="invalid-feedback">{{ $message }}</div>  
        @enderror
    </div>
          
</div>


<div class="card-footer text-right">
    <div class="row">
        <div class="col-lg-12 ">
            <button type="submit" class="btn btn-success font-weight-bold mr-2 submit" id="save-jpeg"><i class="flaticon2-paperplane"></i>
               Save </button>
            <a href="{{ route('rencanakunjungan.index') }}" class="btn btn-secondary font-weight-bold mr-2">
                Cancel</a>
        </div>
    </div>
</div>