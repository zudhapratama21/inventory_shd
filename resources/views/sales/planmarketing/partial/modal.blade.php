<div id="xcontohmodal">
    <div class="modal fade" id="modalplan" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Outlet</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-action" action="" method="POST">
                        <div class="form-group">
                            <label for="">Tanggal</label>
                            <input type="date" class="form-control" value="{{ $request->start_date }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="">Outlet</label> <br>
                            <select name="" id="kt_select2_3" class="form-control" required>
                                <option selected disabled>==== PILIH OUTLET =====</option>
                                @foreach ($outlet as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="input" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-light-primary font-weight-bold"
                                data-dismiss="modal">Close</button>
                        </div>
                    <form>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
