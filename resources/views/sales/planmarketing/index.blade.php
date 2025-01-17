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
                                    <h3 class="card-label">Data Plan Marketing</h3>
                                </div>
                                <div class="card-toolbar">
                                    <!--begin::Button-->

                                    @can('planmarketing-create')
                                        <a href="#" class="btn btn-danger font-weight-bolder mr-2" onclick="remind()">
                                            <i class="flaticon2-crisp-icons-1"></i>
                                            Remind Me
                                        </a>

                                        <a href="{{ route('planmarketing.create') }}"
                                            class="btn btn-primary font-weight-bolder ">
                                            <i class="flaticon2-add"></i>
                                            Plan Marketing
                                        </a>
                                    @endcan

                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Tahun</label>
                                            <select name="" id="kt_select2_1" class="form-control"
                                                onchange="filterYear()">
                                                @php
                                                    $year = 2020;
                                                    $now = date('Y') + 1;
                                                @endphp
                                                @foreach (range($now, $year) as $x)
                                                    @if ($x == date('Y'))
                                                        <option value="{{ $x }}" selected>{{ $x }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $x }}">{{ $x }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Bulan</label>
                                            <select name="" id="kt_select2_2" class="form-control"
                                                onchange="filterBulan()">
                                                @foreach ($bulan as $item)
                                                    @if (now()->format('m') == $item['id'])
                                                        <option value="{{ $item['id'] }}" selected>{{ $item['nama'] }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Outlet</label>
                                            <select name="" id="kt_select2_3" class="form-control"
                                                onchange="filterOutlet()">
                                                <option value="all" selected>Semua</option>
                                                @foreach ($outlet as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                </div>
                                <!--begin: Datatable-->
                                <table class="table yajra-datatable collapsed ">
                                    <thead class="datatable-head">
                                        <tr>
                                            <th>Waktu</th>
                                            <th>Outlet</th>
                                            <th>Minggu ke 1 </th>
                                            <th>Minggu ke 2</th>
                                            <th>Minggu ke 3</th>
                                            <th>Minggu ke 4</th>
                                            <th>Minggu ke 5</th>
                                            <th style="width: 15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <!--end: Datatable-->

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 mt-10">
                        <div class="card">
                            <div class="card-body">                                
                                <div id="calender"></div>
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
    <div id="modal-remind-detail"></div>

    <!-- Modal -->
    <div class="modal fade" id="remind" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">REMIND ME !!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Minggu ke ?</label> <br>
                        <select name="" class="form-control" id="kt_select2_7">
                            <option value="1">Minggu ke 1 </option>
                            <option value="2">Minggu ke 2 </option>
                            <option value="3">Minggu ke 3 </option>
                            <option value="4">Minggu ke 4 </option>
                            <option value="5">Minggu ke 5 </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Hari apa ?</label> <br>
                        <select name="" class="form-control" id="kt_select2_8">
                            @foreach ($day as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="chat()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-setbarang"></div>
{{-- @include('sales.planmarketing.partial.modal') --}}
@endsection
@push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js?v=7.0.6') }} "></script>
    <script src="{{ asset('/assets/js/pages/features/calendar/basic.js?v=7.0.6') }}"></script>

    <script type="text/javascript">
        let tahun = {{ now()->format('Y') }};
        let bulan = {{ now()->format('m') }};
        let outlet = "all";

        $(function() {
            datatable();
            calender();
        });

        function calender() {
                    var todayDate = moment().startOf('day');
                    var YM = todayDate.format('YYYY-MM');
                    var YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
                    var TODAY = todayDate.format('YYYY-MM-DD');
                    var TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');

                    var calendarEl = document.getElementById('calender');
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        plugins: ['bootstrap', 'interaction', 'dayGrid', 'timeGrid', 'list'],
                        themeSystem: 'bootstrap',
                        isRTL: KTUtil.isRTL(),
                        // header: {
                        //     left: 'prev,next today',
                        //     center: 'title',
                        //     right: 'dayGridMonth,timeGridWeek,timeGridDay'
                        // },
                        height: 800,
                        contentHeight: 780,
                        aspectRatio: 3, 
                        nowIndicator: true,
                        now: TODAY + 'T09:25:00', // just for demo
                        views: {
                            dayGridMonth: {
                                buttonText: 'month'
                            },
                            // timeGridWeek: {
                            //     buttonText: 'week'
                            // },
                            // timeGridDay: {
                            //     buttonText: 'day'
                            // }
                        },
                        defaultView: 'dayGridMonth',
                        defaultDate: TODAY,
                        editable: true,
                        eventLimit: true, 
                        navLinks: true,
                        events: [
                            {
                                title: 'fikri',
                                start: '2025-01-01',                                
                                description: 'Toto lorem ipsum dolor sit incid idunt ut',  
                                className: 'fc-event-danger fc-event-solid-warning'                           
                            },
                            {
                                title: 'fikri',
                                start: '2025-01-01',                                
                                description: 'Toto lorem ipsum dolor sit incid idunt ut',  
                                className: 'fc-event-danger fc-event-solid-warning'                           
                            },
                            {
                                title: 'fikri',
                                start: '2025-01-01',                                
                                description: 'Toto lorem ipsum dolor sit incid idunt ut',  
                                className: 'fc-event-danger fc-event-solid-warning'                           
                            },
                            {
                                title: 'fikri',
                                start: '2025-01-01',                                
                                description: 'Toto lorem ipsum dolor sit incid idunt ut',  
                                className: 'fc-event-danger fc-event-solid-warning'                          
                            },
                            {
                                title: 'fikri',
                                start: '2025-01-01',                                
                                description: 'Toto lorem ipsum dolor sit incid idunt ut',  
                                className: 'fc-event-danger fc-event-solid-warning'                           
                            },
                            {
                                title: 'fikri',
                                start: '2025-01-01',                                
                                description: 'Toto lorem ipsum dolor sit incid idunt ut',  
                                className: 'fc-event-danger fc-event-solid-warning'                           
                            }

                        ],
                        dateClick:function(info){                              
                            
                            $.ajax({
                                type: 'POST',
                                url : '{{route('planmarketing.create')}}',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data : {
                                    start_date : info.dateStr,        
                                    "_token": "{{ csrf_token() }}"                            
                                },
                                success : function (res) {                                                                    
                                    $('#modal-setbarang').html(res);
                                    $('#modalplan').modal('show');

                                    $('#form-action').on('input', function (e){
                                        e.preventDefault();

                                        // let 

                                        
                                    });

                                }
                            });


                        },
                        eventRender: function(info) {
                            var element = $(info.el);
                            if (info.event.extendedProps && info.event.extendedProps.description) {
                                if (element.hasClass('fc-day-grid-event')) {
                                    element.data('content', info.event.extendedProps.description);
                                    element.data('placement', 'top');
                                    KTApp.initPopover(element);
                                } else if (element.hasClass('fc-time-grid-event')) {
                                    element.find('.fc-title').append(
                                        '&lt;div class=&quot;fc-description&quot;&gt;' + info.event.extendedProps.description + '&lt;/div&gt;');
                                } else if (element.find('.fc-list-item-title').lenght !== 0) {
                                    element.find('.fc-list-item-title').append(
                                        '&lt;div class=&quot;fc-description&quot;&gt;' + info.event.extendedProps.description + '&lt;/div&gt;');
                                }
                            }
                        }
                    });

                    calendar.render();
                
        }

        function datatable() {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                bFilter: false,
                ajax: {
                    url: "{{ route('planmarketing.datatable') }}",
                    type: "POST",
                    data: function(params) {
                        params.tahun = tahun,
                            params.bulan = bulan,
                            params.outlet = outlet,
                            params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [{
                        data: 'waktu',
                        name: 'waktu'
                    },
                    {
                        data: 'outlet',
                        name: 'outlet.nama'
                    },
                    {
                        data: 'week1',
                        name: 'week1'
                    },
                    {
                        data: 'week2',
                        name: 'week2'
                    },
                    {
                        data: 'week3',
                        name: 'week3'
                    },
                    {
                        data: 'week4',
                        name: 'week4'
                    },
                    {
                        data: 'week5',
                        name: 'week5'
                    },
                    {
                        data: 'action',
                        render: function(data) {
                            return htmlDecode(data);
                        },
                        className: "nowrap",
                    },
                ],
            });
        }

        function htmlDecode(data) {
            var txt = document.createElement('textarea');
            txt.innerHTML = data;
            return txt.value;
        }

        function destroy(data_id) {
            swal({
                    title: "Apakah anda yakin menghapus item ini ?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('planmarketing.delete') }}',
                            dataType: 'html',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                id: data_id,
                                "_token": "{{ csrf_token() }}"
                            },

                            success: function(data) {
                                swal("Poof! Your imaginary file has been deleted!", {
                                    icon: "success",
                                });

                                $('.yajra-datatable').DataTable().ajax.reload(null, false);
                            },
                            error: function(data) {
                                console.log(data);
                            }
                        });

                    } else {

                    }
                });
        }

        function filterYear() {
            let e = document.getElementById("kt_select2_1");
            tahun = e.options[e.selectedIndex].value;

            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filterBulan() {
            let e = document.getElementById("kt_select2_2");
            bulan = e.options[e.selectedIndex].value;

            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filterOutlet() {
            let e = document.getElementById("kt_select2_3");
            outlet = e.options[e.selectedIndex].value;

            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function remind() {
            $('#remind').modal('show');
        }

        function chat() {

            let r = document.getElementById("kt_select2_7");
            minggu = r.options[r.selectedIndex].value;

            let s = document.getElementById("kt_select2_8");
            hari = s.options[s.selectedIndex].value;

            $.ajax({
                type: 'POST',
                url: '{{ route('planmarketing.remind') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: minggu,
                    hari: hari,
                    tahun: tahun,
                    bulan: bulan,
                    minggu: minggu,
                    "_token": "{{ csrf_token() }}"
                },

                success: function(data) {
                    $('#modal-remind-detail').html(data);
                    $('#remind').modal('hide');
                    $('#option').modal('show');
                },
                error: function(data) {
                    console.log(data);
                }
            });




        }
    </script>
@endpush
