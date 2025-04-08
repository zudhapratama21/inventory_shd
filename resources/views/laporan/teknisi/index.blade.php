@extends('layouts.app', ['title' => $title])

@section('content')
    <!--begin::Content-->
    <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->

        <!--end::Subheader-->

        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid mt-10">
            <!--begin::Container-->
            <div class=" container ">
                @if (session('status'))
                    <div class="alert alert-custom alert-success fade show pb-2 pt-2" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                        <div class="alert-text">{{ session('status') }}</div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="ki ki-close"></i></span>
                            </button>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-custom alert-success fade show pb-2 pt-2" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                        <div class="alert-text">{{ session('error') }}</div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="ki ki-close"></i></span>
                            </button>
                        </div>
                    </div>
                @endif
                <div class="row">

                    <div class="col-lg-12">
                        <!--begin::Card-->
                        <div class="card card-custom">
                            <div class="card-header py-3">
                                <div class="card-title">
                                    <span class="card-icon">
                                        <span class="svg-icon svg-icon-md svg-icon-primary">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Chart-bar1.svg--><svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <rect fill="#000000" opacity="0.3" x="12" y="4" width="3"
                                                        height="13" rx="1.5" />
                                                    <rect fill="#000000" opacity="0.3" x="7" y="9" width="3"
                                                        height="8" rx="1.5" />
                                                    <path
                                                        d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z"
                                                        fill="#000000" fill-rule="nonzero" />
                                                    <rect fill="#000000" opacity="0.3" x="17" y="11" width="3"
                                                        height="6" rx="1.5" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon--></span> </span>
                                    <h3 class="card-label">Laporan Kunjungan Teknisi</h3>

                                </div>
                                <div class="card-toolbar">
                                    <!--begin::Button-->
                                    {{-- <form method="POST" action="{{ route('laporanteknisi.print') }}">
                                        @csrf

                                        <input type="hidden" name="sales" value="all" id="formsales">
                                        <input type="hidden" name="tanggal_mulai" id="formtanggal_mulai">
                                        <input type="hidden" name="tanggal_selesai" id="formtanggal_selesai">
                                        @can('laporansales-list')
                                            <button type="submit" class="btn btn-primary font-weight-bolder ">
                                                <i class="flaticon2-printer "></i>
                                                Print Laporan
                                                </a>
                                        @endcan
                                    </form> --}}

                                    <button class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#exampleModal">Download Laporan</button>

                                    <!-- Modal-->
                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Download Laporan</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <i aria-hidden="true" class="ki ki-close"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('laporanteknisi.print') }}" method="POST">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="">Tanggal Awal</label>
                                                            <input type="date" name="tanggal_mulai" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Tanggal Akhir</label>
                                                            <input type="date" name="tanggal_selesai" class="form-control">
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="">Teknisi</label>
                                                            <select name="sales" id="sales" class="form-control">                                                                >
                                                                <option value="All">Semua</option>
                                                                @foreach ($sales as $item)
                                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                @endforeach            
                                                            </select>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light-primary font-weight-bold"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submitxx" class="btn btn-primary font-weight-bold">Save
                                                                changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                               
                                            </div>
                                        </div>
                                    </div>

                                    <!--end::Button-->
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Teknisi</label>
                                            <select name="" id="saleschart" class="form-control"
                                                onchange="filtersaleschart()">
                                                <option value="All">Semua</option>
                                                @foreach ($sales as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Outlet</label>
                                            <select name="" id="kt_select2_2" class="form-control"
                                                onchange="filteroutlet()">
                                                <option value="All">Semua</option>
                                                @foreach ($outlet as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="example-preview" id="kt_blockui_content">
                                    <div id="calender"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-lg-12">
                        <div class="card card-custom">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="card-label">Analisis Outlet</h3>
                                </div>
                            </div>

                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Tahun</label>
                                            <select name="chart_year" class="form-control" id="tahun"
                                                onchange="filtertahun()">
                                                @php
                                                    $year = 2020;
                                                @endphp
                                                @foreach (range(date('Y'), $year) as $x)
                                                    <option value="{{ $x }}">{{ $x }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Bulan</label>
                                            <select name="" id="bulan" class="form-control"
                                                onchange="filterbulan()">
                                                <option value="All">Semua</option>
                                                @foreach ($bulan as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Sales</label>
                                            <select name="" id="sales" class="form-control"
                                                onchange="filtersales()">
                                                <option value="All">Semua</option>
                                                @foreach ($sales as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <table class="table yajra-datatablecustomer collapsed ">
                                    <thead class="datatable-head">
                                        <tr>
                                            <th>Outlet</th>
                                            <th>Jumlah di Kunjungi</th>
                                            <th style="width: 15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
    <!--end::Content-->
    <div id="modal-confirm-delete"></div>
    <div id="modal-show-detail"></div>

    @include('laporan.sales.partial.modalsales')
@endsection
@push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js?v=7.0.6') }} "></script>
    <script src="{{ asset('/assets/js/pages/features/calendar/basic.js?v=7.0.6') }}"></script>

    {{-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script> --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>

    <script type="text/javascript">
        let tahun = {{ now()->format('Y') }};
        let sales = 'All';
        let bulan = 'All';
        let outlet_id = null;
        let calendar;

        let saleschart = 'All';
        let outlet = 'All';

        $(function() {
            calender();
            datatable();
            datatableSales();
        });

        function datatable() {
            var table = $('.yajra-datatablecustomer').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    type: 'POST',
                    url: "{{ route('laporansales.datatable') }}",
                    data: function(params) {
                        params.tahun = tahun;
                        params.bulan = bulan;
                        params.sales = sales;
                        params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [{
                        data: 'outlet',
                        name: 'outlet'
                    },
                    {
                        data: 'users',
                        name: 'users'
                    },
                    {
                        data: 'action',
                        render: function(data) {
                            return htmlDecode(data);
                        },
                        className: "nowrap",
                    },
                ],
                columnDefs: [

                    {
                        responsivePriority: 3,
                        targets: 1,

                    },
                ],
            });
        }

        function datatableSales() {
            var table = $('.yajra-datatablesales').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    type: 'POST',
                    url: "{{ route('laporansales.datatablesales') }}",
                    data: function(params) {
                        params.tahun = tahun;
                        params.bulan = bulan;
                        params.sales = sales;
                        params.outlet = outlet_id;
                        params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [{
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'jam_buat',
                        name: 'jam_buat'
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'aktifitas',
                        name: 'aktifitas'
                    },
                ],
                columnDefs: [{
                    responsivePriority: 3,
                    targets: 1,
                }, ],
            });
        }

        function calender() {
            var todayDate = moment().startOf('day');
            var YM = todayDate.format('YYYY-MM');
            var YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
            var TODAY = todayDate.format('YYYY-MM-DD');
            var TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');

            var calendarEl = document.getElementById('calender');
            calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['bootstrap', 'interaction', 'dayGrid', 'timeGrid', 'list'],
                themeSystem: 'bootstrap',
                isRTL: KTUtil.isRTL(),
                height: 800,
                contentHeight: 780,
                aspectRatio: 3,
                nowIndicator: true,
                now: TODAY + 'T09:25:00',
                views: {
                    dayGridMonth: {
                        buttonText: 'month'
                    },
                },
                defaultView: 'dayGridMonth',
                defaultDate: TODAY,
                editable: true,
                eventLimit: true,
                navLinks: true,
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: `{{ route('laporanteknisi.list') }}`,
                        type: 'GET',
                        data: {
                            start: fetchInfo.startStr, // Mengambil tanggal awal dari FullCalendar
                            end: fetchInfo.endStr, // Mengambil tanggal akhir dari FullCalendar
                            sales: saleschart,
                            outlet: outlet
                        },
                        beforeSend: function() {
                            KTApp.block('#kt_blockui_content', {
                                overlayColor: '#000000',
                                state: 'primary',
                                message: 'Processing...'
                            });
                        },
                        success: function(response) {
                            successCallback(
                                response); // Pastikan callback dipanggil dengan response
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching events:', error);
                            failureCallback(error); // Pastikan callback error dipanggil
                        },
                        complete: function() {
                            KTApp.unblock('#kt_blockui_content');
                        }

                    });
                },
                eventClick: function({
                    event
                }) {
                    $.ajax({
                        type: 'GET',
                        url: `{{ url('laporan/laporankunjunganteknisi') }}/${event.id}/show`,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        beforeSend: function() {
                            KTApp.block('#kt_blockui_content', {
                                overlayColor: '#000000',
                                state: 'primary',
                                message: 'Processing...'
                            });
                        },
                        success: function(res) {
                            $('#modal-show-detail').html(res);
                            $('#modallaporan').modal('show');
                            let editor1;
                            ClassicEditor
                                .create(document.querySelector('#editor'))
                                .then(editor => {
                                    editor1 = editor;
                                })
                                .catch(error => {
                                    console.error(error);
                                });

                        },
                        complete: function() {
                            KTApp.unblock('#kt_blockui_content');
                        }
                    });



                },
            });

            calendar.render();

        }


        function htmlDecode(data) {
            var txt = document.createElement('textarea');
            txt.innerHTML = data;
            return txt.value;
        }

        function filtersaleschart() {
            let e = document.getElementById("saleschart");
            saleschart = e.options[e.selectedIndex].value;
            calendar.refetchEvents();
        }

        function filteroutlet() {
            let e = document.getElementById("kt_select2_2");
            outlet = e.options[e.selectedIndex].value;
            calendar.refetchEvents();
        }

        function filtersales() {
            let e = document.getElementById("sales");
            sales = e.options[e.selectedIndex].value;
            $('.yajra-datatablecustomer').DataTable().ajax.reload(null, false);
        }

        function filtertahun() {
            let e = document.getElementById("tahun");
            tahun = e.options[e.selectedIndex].value;
            $('.yajra-datatablecustomer').DataTable().ajax.reload(null, false);
        }

        function filterbulan() {
            let e = document.getElementById("bulan");
            bulan = e.options[e.selectedIndex].value;
            $('.yajra-datatablecustomer').DataTable().ajax.reload(null, false);
        }



        function showSales(id) {
            $('#listsales').modal('show');
            outlet_id = id;
            $('.yajra-datatablesales').DataTable().ajax.reload(null, false);
        }
    </script>
@endpush
