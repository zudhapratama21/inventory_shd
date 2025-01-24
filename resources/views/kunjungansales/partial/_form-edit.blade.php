<div class="card-body">
    <div class="card-body">
        <div class="form-group">
            <label>Outlet :</label>
            <select name="outlet_id" class="form-control select2" id="kt_select2_2" required>
                <option disabled selected>========= Pilih Outlet =============</option>
                @foreach ($outlet as $item)
                    @if ($kunjungan->outlet_id == $item->id)
                        <option value="{{ $item->id }}" selected>{{ $item->nama }}</option>
                    @else
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endif
                @endforeach
            </select>

        </div>

        <div class="form-group">
            <label>Aktivitas :</label>
            <textarea type="text" name="aktifitas" value="" class="form-control" placeholder="Masukkan aktifitas"
                id="kt-ckeditor-1" cols="30" rows="5">{{ $kunjungan->aktifitas }}</textarea>


        </div>

        <div class="form-group">
            <div class="col-md-2 mb-4">
                <label>Foto Kunjungan :</label>
                <a href="#" data-toggle="modal" data-target="#fotokunjungan">
                    <img src="{{ asset('storage/kunjungan/' . $kunjungan->image) }}" class="img-fluid" alt="">
                </a>
            </div>

            <input type="file" name="image" value="{{ old('image') }}" class="form-control"
                placeholder="Masukkan image" />
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <div class="form-group">
            <div class="col-md-3">
                <label>Foto TTD :</label>
                <a href="#" data-toggle="modal" data-target="#fotottd">
                    <img src="{{ asset('ttd/' . $kunjungan->ttd) }}" class="img-fluid" alt="">
                </a>
            </div>


            <div class="wrapper">
                <div class="text-right">
                    <button type="button" class="btn btn-default btn-sm" id="undo"><i class="fa fa-undo"></i>
                        Undo</button>
                    <button type="button" class="btn btn-danger btn-sm" id="clear"><i class="fa fa-eraser"></i>
                        Clear</button>
                    <canvas id="signature-pad" class="signature-pad"></canvas>
                </div>
                <br>
                <div id="ttd"></div>
            </div>

        </div>
    </div>


    <div class="card-footer text-right">
        <div class="row">
            <div class="col-lg-12 ">
                <button type="submit" class="btn btn-success font-weight-bold mr-2 submit" id="save-jpeg"><i
                        class="flaticon2-paperplane"></i>
                    Update </button>
                <a href="{{ route('kunjungansales.index') }}" class="btn btn-secondary font-weight-bold mr-2">
                    Cancel</a>
            </div>
        </div>
    </div>
</div>

{{-- MODAL FOTO KUNJUNGAN --}}
<!-- Modal -->
<div class="modal fade" id="fotokunjungan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Foto Kunjungan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="{{ asset('storage/kunjungan/' . $kunjungan->image) }}" class="img-fluid" alt="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
{{-- END MODAL FOTO KUNJUNGAN --}}

{{--  MODAL FOTO TTD --}}
<div class="modal fade" id="fotottd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Foto TTD</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="{{ asset('ttd/' . $kunjungan->ttd) }}" class="img-fluid" alt="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
{{-- END MODAL FOTO TTD --}}
