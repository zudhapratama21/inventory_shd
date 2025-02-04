<div id="xcontohmodal">
    <div class="modal fade" id="modalrencana" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">

        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="card mr-10">
                <div class="card-header">
                    <h5>Plan Marketing</h5>
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
                            @foreach ($planmarketing as $item)
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
                    @else
                        <form id="form-action">
                            <div class="form-group">
                                <label for="">Tanggal</label>
                                <input type="date" id="tanggal" class="form-control"
                                    value="{{ $request->start_date }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="">Outlet</label> <br>
                                <select name="" id="kt_select2_4" class="form-control" required>
                                    @foreach ($outlet as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Aktivitas</label>
                                <textarea name="aktivitas" class="form-control" id="editor" cols="30" rows="5"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                <button type="button" class="btn btn-light-primary font-weight-bold"
                                    data-dismiss="modal">Close</button>
                            </div>
                            <form>

                    @endif

                </div>
            </div>
        </div>

    </div>
</div>

<script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
