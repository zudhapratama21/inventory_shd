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
                    <div class="col-lg-12 mt-10">
                        <div class="card">
                            <div class="card-header">
                               <h4>Plan Marketing</h4> 
                            </div>
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
    
   
    <div id="modal-setbarang"></div>
    {{-- @include('sales.planmarketing.partial.modal') --}}
@endsection
@push('script')
    {{-- <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script> --}}
    <script src="{{ asset('/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js?v=7.0.6') }} "></script>
    <script src="{{ asset('/assets/js/pages/features/calendar/basic.js?v=7.0.6') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
        integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
        integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script type="text/javascript">
        let tahun = {{ now()->format('Y') }};
        let bulan = {{ now()->format('m') }};
        let outlet = "all";

        $(function() {
            // datatable();
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
                events: `{{ route('planmarketing.list') }}`,
                dateClick: function(info) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('planmarketing.create') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            start_date: info.dateStr,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            $('#modal-setbarang').html(res);
                            $('#modalplan').modal('show');

                            $('#form-action').on('submit', function(event) {
                                event.preventDefault();

                                let e = document.getElementById("kt_select2_4");
                                let outlet = e.options[e.selectedIndex].value;
                                let tanggal = document.getElementById("tanggal").value;

                                $.ajax({
                                    type: 'POST',
                                    url: '{{ route('planmarketing.store') }}',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    data: {
                                        outlet_id: outlet,
                                        tanggal: tanggal,
                                        "_token": "{{ csrf_token() }}"
                                    },
                                    success: function() {
                                        $('#modalplan').modal('hide');
                                        iziToast.success({
                                            title: 'Success',
                                            message: 'Data Berhasil Ditambahkan',
                                            position: 'topRight',
                                        });
                                        calendar.refetchEvents();
                                    }
                                });

                            })                           

                            

                        }
                    });

                },
                eventClick: function({
                    event
                }) {
                    $.ajax({
                        type: 'GET',
                        url: `{{ url('sales/planmarketing') }}/${event.id}/edit`,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            $('#modal-setbarang').html(res);
                            $('#modaledit').modal('show');

                            $('#form-action').on('submit', function(event) {
                                event.preventDefault();

                                let data_id = document.getElementById("data_id").value;
                                let e = document.getElementById("kt_select2_4");
                                let outlet = e.options[e.selectedIndex].value;
                                let tanggal = document.getElementById("tanggal").value;

                                $.ajax({
                                    type: 'POST',
                                    url: '{{ route('planmarketing.update') }}',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    data: {
                                        data_id: data_id,
                                        outlet_id: outlet,
                                        tanggal: tanggal,
                                        "_token": "{{ csrf_token() }}"
                                    },
                                    success: function() {
                                        $('#modaledit').modal('hide');
                                        iziToast.success({
                                            title: 'Success',
                                            message: 'Data Berhasil Diubah',
                                            position: 'topRight',
                                        });
                                        calendar.refetchEvents();
                                    }
                                });

                            })

                            $('#delete-btn').on('click', function(event) {
                                event.preventDefault();
                                let data_id = document.getElementById("data_id").value;
                                $.ajax({
                                    type: 'POST',
                                    url: '{{ route('planmarketing.delete') }}',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    data: {
                                        data_id: data_id,                                       
                                        "_token": "{{ csrf_token() }}"
                                    },
                                    success: function() {
                                        $('#modaledit').modal('hide');
                                        iziToast.success({
                                            title: 'Success',
                                            message: 'Data Berhasil Dihapus',
                                            position: 'topRight',
                                        });
                                        calendar.refetchEvents();
                                    }
                                });
                                
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
                                '&lt;div class=&quot;fc-description&quot;&gt;' + info.event
                                .extendedProps.description + '&lt;/div&gt;');
                        } else if (element.find('.fc-list-item-title').lenght !== 0) {
                            element.find('.fc-list-item-title').append(
                                '&lt;div class=&quot;fc-description&quot;&gt;' + info.event
                                .extendedProps.description + '&lt;/div&gt;');
                        }
                    }
                }
            });

            calendar.render();

        }

        // function datatable() {
        //     var table = $('.yajra-datatable').DataTable({
        //         processing: true,
        //         serverSide: true,
        //         scrollX: true,
        //         bFilter: false,
        //         ajax: {
        //             url: "{{ route('planmarketing.datatable') }}",
        //             type: "POST",
        //             data: function(params) {
        //                 params.tahun = tahun,
        //                     params.bulan = bulan,
        //                     params.outlet = outlet,
        //                     params._token = "{{ csrf_token() }}";
        //                 return params;
        //             }
        //         },
        //         columns: [{
        //                 data: 'waktu',
        //                 name: 'waktu'
        //             },
        //             {
        //                 data: 'outlet',
        //                 name: 'outlet.nama'
        //             },
        //             {
        //                 data: 'week1',
        //                 name: 'week1'
        //             },
        //             {
        //                 data: 'week2',
        //                 name: 'week2'
        //             },
        //             {
        //                 data: 'week3',
        //                 name: 'week3'
        //             },
        //             {
        //                 data: 'week4',
        //                 name: 'week4'
        //             },
        //             {
        //                 data: 'week5',
        //                 name: 'week5'
        //             },
        //             {
        //                 data: 'action',
        //                 render: function(data) {
        //                     return htmlDecode(data);
        //                 },
        //                 className: "nowrap",
        //             },
        //         ],
        //     });
        // }

        // function htmlDecode(data) {
        //     var txt = document.createElement('textarea');
        //     txt.innerHTML = data;
        //     return txt.value;
        // }

        // function destroy(data_id) {
        //     swal({
        //             title: "Apakah anda yakin menghapus item ini ?",
        //             icon: "warning",
        //             buttons: true,
        //             dangerMode: true,
        //         })
        //         .then((willDelete) => {
        //             if (willDelete) {
        //                 $.ajax({
        //                     type: 'POST',
        //                     url: '{{ route('planmarketing.delete') }}',
        //                     dataType: 'html',
        //                     headers: {
        //                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                     },
        //                     data: {
        //                         id: data_id,
        //                         "_token": "{{ csrf_token() }}"
        //                     },

        //                     success: function(data) {
        //                         swal("Poof! Your imaginary file has been deleted!", {
        //                             icon: "success",
        //                         });

        //                         $('.yajra-datatable').DataTable().ajax.reload(null, false);
        //                     },
        //                     error: function(data) {
        //                         console.log(data);
        //                     }
        //                 });

        //             } else {

        //             }
        //         });
        // }

        // function filterYear() {
        //     let e = document.getElementById("kt_select2_1");
        //     tahun = e.options[e.selectedIndex].value;

        //     $('.yajra-datatable').DataTable().ajax.reload(null, false);
        // }

        // function filterBulan() {
        //     let e = document.getElementById("kt_select2_2");
        //     bulan = e.options[e.selectedIndex].value;

        //     $('.yajra-datatable').DataTable().ajax.reload(null, false);
        // }

        // function filterOutlet() {
        //     let e = document.getElementById("kt_select2_3");
        //     outlet = e.options[e.selectedIndex].value;

        //     $('.yajra-datatable').DataTable().ajax.reload(null, false);
        // }

        // function remind() {
        //     $('#remind').modal('show');
        // }

        // function chat() {

        //     let r = document.getElementById("kt_select2_7");
        //     minggu = r.options[r.selectedIndex].value;

        //     let s = document.getElementById("kt_select2_8");
        //     hari = s.options[s.selectedIndex].value;

        //     $.ajax({
        //         type: 'POST',
        //         url: '{{ route('planmarketing.remind') }}',
        //         dataType: 'html',
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         data: {
        //             id: minggu,
        //             hari: hari,
        //             tahun: tahun,
        //             bulan: bulan,
        //             minggu: minggu,
        //             "_token": "{{ csrf_token() }}"
        //         },

        //         success: function(data) {
        //             $('#modal-remind-detail').html(data);
        //             $('#remind').modal('hide');
        //             $('#option').modal('show');
        //         },
        //         error: function(data) {
        //             console.log(data);
        //         }
        //     });
        // }
    </script>
@endpush
