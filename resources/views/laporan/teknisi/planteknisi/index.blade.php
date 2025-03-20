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
                <div class="row">

                    <div class="col-lg-12">
                        <!--begin::Card-->
                        <div class="card card-custom">
                            <div class="card-header py-3">
                                <div class="card-title">
                                    <span class="card-icon"> <span class="svg-icon svg-icon-md svg-icon-primary">
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
                                            </svg> <!--end::Svg Icon--></span> </span>
                                    <h3 class="card-label">Laporan Plan Teknisi</h3>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="kt_tab_pane_4_1" role="tabpanel"
                                        aria-labelledby="kt_tab_pane_4_1">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Outlet</label>
                                                    <select name="" id="kt_select2_3" class="form-control"
                                                        onchange="filterOutlet()">
                                                        <option value="All" selected>Semua</option>
                                                        @foreach ($outlet as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Sales</label>
                                                    <select name="" id="kt_select2_4" class="form-control"
                                                        onchange="filterSales()">
                                                        <option value="All" selected>Semua</option>
                                                        @foreach ($sales as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>


                                        </div>

                                        <!--begin: Datatable-->
                                        <div id="calender"></div>
                                    </div>
                                </div>



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
@endsection
@push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js?v=7.0.6') }} "></script>
    <script src="{{ asset('/assets/js/pages/features/calendar/basic.js?v=7.0.6') }}"></script>

    <script type="text/javascript">
        let sales = "All";
        let outlet = "All";

        $(function() {
            calender();
        });

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
                        url: `{{ route('laporanplanteknisi.list') }}`,
                        type: 'GET',
                        data: {
                            start: fetchInfo.startStr, // Mengambil tanggal awal dari FullCalendar
                            end: fetchInfo.endStr, // Mengambil tanggal akhir dari FullCalendar
                            sales: sales,
                            outlet: outlet
                        },
                        success: function(response) {
                            successCallback(
                                response); // Pastikan callback dipanggil dengan response
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching events:', error);
                            failureCallback(error); // Pastikan callback error dipanggil
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
                            element.find('.fc-title').append('<div class=fc-description;' +
                                info.event.extendedProps.description + '<div>;');
                        } else if (element.find('.fc-list-item-title').lenght !== 0) {
                            element.find('.fc-list-item-title').append(
                                '<div class=fc-description;' + info.event.extendedProps
                                .description + '<div>;');
                        }
                    }
                }
            });

            calendar.render();
        }

        function filterOutlet() {
            let e = document.getElementById("kt_select2_3");
            outlet = e.options[e.selectedIndex].value;
            calendar.refetchEvents();
        }

        function filterSales() {
            let e = document.getElementById("kt_select2_4");
            sales = e.options[e.selectedIndex].value;

            calendar.refetchEvents();
        }
    </script>
@endpush
