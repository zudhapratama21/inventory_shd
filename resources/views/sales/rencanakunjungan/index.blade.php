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
                <div class="row">

                    <div class="col-lg-12">
                        <div class="card mt-10">
                            <div class="card-header">
                                <h3>Rencana Aktivitas</h3>
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
    <div id="modal-setbarang"></div>
@endsection
@push('script')
    <script src="{{ asset('/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js?v=7.0.6') }} "></script>
    <script src="{{ asset('/assets/js/pages/features/calendar/basic.js?v=7.0.6') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
        integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
        integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>






    <script type="text/javascript">
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
            var calendar = new FullCalendar.Calendar(calendarEl, {
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
                events: `{{ route('rencanakunjungan.datatable') }}`,
                dateClick: function(info) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('rencanakunjungan.create') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            start_date: info.dateStr,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(res) {


                            $('#modal-setbarang').html(res);
                            $('#modalrencana').modal('show');
                            let editor1;
                            ClassicEditor
                                .create(document.querySelector('#editor'))
                                .then(editor => {
                                    editor1 = editor;
                                })
                                .catch(error => {
                                    console.error(error);
                                });

                            $('#form-action').on('submit', function(event) {
                                event.preventDefault();
                                let e = document.getElementById("kt_select2_4");
                                let outlet = e.options[e.selectedIndex].value;
                                let tanggal = document.getElementById("tanggal").value;
                                const aktivitasValue = editor1.getData();
                                
                                


                                $.ajax({
                                    type: 'POST',
                                    url: '{{ route('rencanakunjungan.store') }}',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    data: {
                                        outlet_id: outlet,
                                        tanggal: tanggal,
                                        aktivitas: aktivitasValue,
                                        "_token": "{{ csrf_token() }}"
                                    },
                                    success: function() {
                                        $('#modalrencana').modal('hide');
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
                eventClick: function({event}) {
                    $.ajax({
                        type: 'GET',
                        url: `{{ url('sales/rencanakunjungan') }}/${event.id}/edit`,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            $('#modal-setbarang').html(res);
                            $('#modalrencanaedit').modal('show');
                            let editor1;
                            ClassicEditor
                                .create(document.querySelector('#editor'))
                                .then(editor => {
                                    editor1 = editor;
                                })
                                .catch(error => {
                                    console.error(error);
                                });

                            $('#form-action').on('submit', function(event) {
                                event.preventDefault();

                                let data_id = document.getElementById("data_id").value;
                                let e = document.getElementById("kt_select2_4");
                                let outlet = e.options[e.selectedIndex].value;
                                let tanggal = document.getElementById("tanggal").value;
                                const aktivitasValue = editor1.getData();

                                $.ajax({
                                    type: 'POST',
                                    url: '{{ route('rencanakunjungan.update') }}',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    data: {
                                        data_id: data_id,
                                        outlet_id: outlet,                                        
                                        aktivitas: aktivitasValue,
                                        "_token": "{{ csrf_token() }}"
                                    },
                                    success: function() {
                                        $('#modalrencanaedit').modal('hide');
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
                                    url: '{{ route('rencanakunjungan.delete') }}',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    data: {
                                        data_id: data_id,
                                        "_token": "{{ csrf_token() }}"
                                    },
                                    success: function() {
                                        $('#modalrencanaedit').modal('hide');
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
            });

            calendar.render();

        }
    </script>
@endpush
