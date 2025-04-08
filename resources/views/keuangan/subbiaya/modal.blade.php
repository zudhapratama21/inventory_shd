<div class="modal fade" id="editdata" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="form-group">
                        <label for="">Nama Sub Biaya</label>
                        <input type="text" value="{{ $subbiaya->nama }}" class="form-control" name="nama" id="nama"
                            >
                    </div>

                    <div class="form-group">
                        <label for="">No Akun</label>
                        <input type="text" class="form-control" value="{{ $subbiaya->no_akun }}" name="no_akun" id="no_akun"
                            >
                    </div>

                    <div class="form-group">
                        <label for="">Jenis Biaya</label> <br>
                        <select class="form-control" id="jenisbiaya">
                            @foreach ($jenisbiaya as $item)
                                @if ($item->id == $subbiaya->jenisbiaya_id)
                                    <option value="{{ $item->id }}" selected>{{ $item->nama }}</option>
                                @else
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Keterangan</label>
                        <input type="text" class="form-control" name="keterangan" id="keterangan" value="{{ $subbiaya->keterangan }}">
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary font-weight-bold" onclick="update({{$subbiaya->id}})">Save changes</button>
            </div>
        </div>
    </div>
</div>
