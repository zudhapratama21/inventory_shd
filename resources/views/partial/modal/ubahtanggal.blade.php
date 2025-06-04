    <div class="modal fade" id="ubahtanggal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ubah Jatuh Tempo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body" style="height: 400px;">                                
                        <div class="form-group">
                            <label for="">Tanggal Jatuh Tempo</label>
                            <input type="date" class="form-control" name="tanggaljatuhtempo" id="tanggaljatuhtempo"
                                value="{{ date('Y-m-d') }}">
                        </div>

                        <button class="btn btn-primary btn-sm" onclick="simpantanggal({{$id}})">simpan</button>                    
                </div>
                <div class="modal-footer">
                    <button type="#" class="btn btn-light-primary font-weight-bold"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>