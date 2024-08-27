<div class="card-body">    
    <div class="form-group">
        <label>Customer :</label>
        <input type="text" name="customer" value="{{ old('customer') }}"
            class="form-control" placeholder="Masukkan customer" required/>
        @error('customer')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Aktivitas :</label>
        <textarea type="text" name="aktifitas" value=""
        class="form-control" placeholder="Masukkan aktifitas" id="kt-ckeditor-1" cols="30" rows="5" >-</textarea>
       
        @error('aktifitas')
        <div class="invalid-feedback">{{ $message }}</div>  
        @enderror
    </div>

    <div class="form-group">
        <label>Foto Kunjungan :</label>
        <input type="file" name="image" value="{{ old('image') }}"
            class="form-control" placeholder="Masukkan image"/>
        @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="wrapper">
        <div class="text-right">
            <button type="button" class="btn btn-default btn-sm" id="undo"><i class="fa fa-undo"></i> Undo</button>
            <button type="button" class="btn btn-danger btn-sm" id="clear"><i class="fa fa-eraser"></i> Clear</button>
             <canvas id="signature-pad" class="signature-pad"></canvas>            
      </div>
      {{-- <button type="button" class="btn btn-primary btn-sm" id="save-jpeg"><i class="fa fa-save"></i> save ttd</button> --}}
      <br>
      <div id="ttd"></div>
    </div>
      
</div>


<div class="card-footer text-right">
    <div class="row">
        <div class="col-lg-12 ">
            <button type="submit" class="btn btn-success font-weight-bold mr-2 submit" id="save-jpeg"><i class="flaticon2-paperplane"></i>
               Save </button>
            <a href="{{ route('kunjungansales.index') }}" class="btn btn-secondary font-weight-bold mr-2">
                Cancel</a>
        </div>
    </div>
</div>