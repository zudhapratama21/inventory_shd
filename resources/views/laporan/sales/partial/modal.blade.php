<div id="xcontohmodal">
    <div class="modal fade" id="modallaporan" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="row">
                <div class="col-md-4">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Plan Marketing</h5>
                        </div>
                        <div class="modal-body">
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
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                            <td>{{ ucfirst($item->outlet ? $item->outlet->nama : '-') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Rencana Kunjungan</h5>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Outlet</th>
                                        <th>Aktivitas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rencanakunjungan as $item)
                                        <tr>
                                            <td>{{ ucfirst($item->outlet ? $item->outlet->nama : '-') }}</td>
                                            <td>{!! $item->aktivitas !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Kunjungan Sales
                                <a href="whatsapp://send?text=Assalamualaikum wr wb %0ABagaimana untuk hasil aktifitas {{$text}} pada {{ \Carbon\Carbon::parse($kunjungansales->tanggal)->format('d/F/Y') }} di {{ $kunjungansales->outlet ? $kunjungansales->outlet->nama : $kunjungansales->customer }} ?&app_absent=0&phone=+6285784260416"
                                    target="_blank" class="btn btn-light btn-hover-success btn-sm mr-3">
                                    <span class="badge badge-success badge-lg">Click to Wa !!</span>
                                                                    
                                </a>
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i aria-hidden="true" class="ki ki-close"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="">
                                <div class="form-group">
                                    <label for="">Jam Buat</label>
                                    <input type="text" class="form-control"
                                        value="{{ \Carbon\Carbon::parse($kunjungansales->jam_buat)->format('H:i') }}"
                                        readonly>
                                </div>

                                <div class="form-group">
                                    <label for="">Sales</label>
                                    <input type="text" class="form-control" value="{{ $kunjungansales->user->name }}"
                                        readonly>
                                </div>

                                <div class="form-group">
                                    <label for="">Outlet</label>
                                    <input type="text" class="form-control"
                                        value="{{ $kunjungansales->outlet ? $kunjungansales->outlet->nama : $kunjungansales->customer }}"
                                        readonly>
                                </div>

                                <div class="form-group">
                                    <label for="">Aktivitas</label>
                                    <textarea name="aktivitas" class="form-control" id="editor" cols="30" rows="5" readonly>{{ $kunjungansales->aktifitas }}</textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
