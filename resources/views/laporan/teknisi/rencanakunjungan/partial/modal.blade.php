<div id="xcontohmodal">
    <div class="modal fade" id="modalrencana" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">     
            <div class="modal-content mr-10">
                <div class="card">
                    <div class="card-header">
                        <h5>Plan Marketing</h5>
                    </div>
                    <div class="card-body" style="margin-left:-20px">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Outlet</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($planmarketing as $item)
                                    <tr>
                                        <td>{{\Carbon\Carbon::parse($item->tanggal)->format('Y-m-d')}}</td>
                                        <td>{{ucfirst($item->user->name)}}</td>
                                        <td>{{ucfirst($item->outlet->nama)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>       
           
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Rencana Kunjungan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>User</th>
                            <th>Outlet</th>
                            <th>Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kunjungan as $item)
                            <tr>
                                <td>{{\Carbon\Carbon::parse($item->tanggal)->format('Y-m-d')}}</td>
                                <td>{{ucfirst($item->user->name)}}</td>
                                <td>{{ucfirst($item->outlet->nama)}}</td>
                                <td>{!! $item->aktivitas !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>

