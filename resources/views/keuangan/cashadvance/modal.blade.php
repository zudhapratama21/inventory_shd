<div class="modal fade" id="editcashadvance" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cash Advance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="{{$data->tanggal}}" id="tanggal"
                            aria-describedby="emailHelp" placeholder="Tanggal">
                    </div>
                    <div class="form-group">
                        <label for="">Karyawan</label> <br>
                        <select class="form-control" id="kt_select2_4" name="karyawan_id">
                            <option value="">Pilih Karyawan</option>
                            @foreach ($karyawan as $item)
                                @if ($item->id == $data->karyawan_id)
                                    <option value="{{ $item->id }}" selected>{{ $item->nama }}</option> 
                                @else
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>                                
                                @endif                                
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Nominal</label>
                        <input type="number" class="form-control" value="{{$data->nominal}}" name="nominal" id="nominal"
                            aria-describedby="emailHelp" placeholder="Nominal">
                    </div>
                    <div class="form-group">
                        <label for="">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control">{{$data->keterangan}}</textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold"
                        data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary font-weight-bold" onclick="updatecash({{$data->id}})">Save</button>
                </div>
            </div>
        </div>
    </div>