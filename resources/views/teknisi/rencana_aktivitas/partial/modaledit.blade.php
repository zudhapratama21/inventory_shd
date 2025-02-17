<div id="xcontohmodal">
    <div class="modal fade" id="modalrencanaedit" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">

        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="card mr-10">
                <div class="card-header">
                    <h5>Plan Teknisi</h5>
                </div>
                <div class="card-body" style="margin-left:-20px">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Outlet</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($planteknisi as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}</td>
                                    <td>{{ ucfirst($item->outlet->nama) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Outlet</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($terlambat == 1)
                        <div class="text-center">
                            <span class="badge badge-danger">Hari Sudah terlewat , mohon maaf tidak bisa menambahkan
                                data Rencana Aktivitas</span>
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal</label>
                            <input type="date" id="tanggal" class="form-control"
                                value="{{ $rencanaaktivitasteknisi->tanggal }}" readonly>
                        </div>

                        <input type="hidden" id="data_id" value="{{ $rencanaaktivitasteknisi->id }}" readonly> 

                        <div class="form-group">
                            <label for="">Outlet</label> <br>
                            <select name="" id="kt_select2_4" class="form-control" required readonly>
                                @foreach ($outlet as $item)
                                    @if ($rencanaaktivitasteknisi->outlet_id == $item->id)
                                        <option value="{{ $item->id }}" selected>{{ $item->nama }}</option>
                                    @else
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Aktivitas</label>
                            <textarea name="aktivitas" class="form-control" id="editor" cols="30" rows="5" readonly>
                            {{ $rencanaaktivitasteknisi->aktivitas }}
                        </textarea>
                        @else
                            <form id="form-action">
                                <div class="form-group">
                                    <label for="">Tanggal</label>
                                    <input type="date" id="tanggal" class="form-control"
                                        value="{{ $rencanaaktivitasteknisi->tanggal }}" readonly>
                                </div>

                                <input type="hidden" id="data_id" value="{{ $rencanaaktivitasteknisi->id }}">

                                <div class="form-group">
                                    <label for="">Outlet</label> <br>
                                    <select name="" id="kt_select2_4" class="form-control" required>
                                        @foreach ($outlet as $item)
                                            @if ($rencanaaktivitasteknisi->outlet_id == $item->id)
                                                <option value="{{ $item->id }}" selected>{{ $item->nama }}
                                                </option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="">Aktivitas</label>
                                    <textarea name="aktivitas" class="form-control" id="editor" cols="30" rows="5">
                                {{ $rencanaaktivitasteknisi->aktivitas }}
                            </textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                    <button type="button" class="btn btn-light-primary font-weight-bold"
                                        data-dismiss="modal">Close</button>
                                    <button type="button" id="delete-btn"
                                        class="btn btn-light-danger font-weight-bold"><i
                                            class="flaticon2-trash"></i>Hapus</button>
                                </div>
                                <form>

                    @endif


                </div>
            </div>
        </div>

    </div>
</div>

<script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
