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
                                    <h3 class="card-label">Data Evaluasi Sales</h3>
                                </div>
                                <div class="card-toolbar">
                                    <!--begin::Button-->
                                    @can('evaluasi-create')
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#tambahevaluasi">
                                            <i class="flaticon2-add"></i>
                                            Evaluasi
                                        </button>
                                    @endcan

                                    <!--end::Button-->
                                </div>
                            </div>
                            <div class="card-body">
                                <!--begin: Datatable-->
                                <table class="table yajra-datatable collapsed">
                                    <thead class="datatable-head">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Sales</th>
                                            <th>Evaluasi</th>
                                            <th>Saran</th>
                                            <th>Pembuat Data</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <!--end: Datatable-->

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
    @include('sales.evaluasi.partial.modal')
@endsection
@push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
        integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
        integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src=" https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.all.min.js "></script>
    <link href=" https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.min.css " rel="stylesheet">

    <script type="text/javascript">
        let editor1;
        let editor2;
        $(function() {
            datatable();
            ckeditor();
        });

        function datatable() {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ route('evaluasi.datatable') }}",
                    type: "POST",
                    data: function(params) {
                        params._token = "{{ csrf_token() }}";
                        return params;
                    }

                },
                columns: [
                    //   {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'sales',
                        name: 'sales.name'
                    },
                    {
                        data: 'evaluasi',
                        name: 'evaluasi'
                    },
                    {
                        data: 'saran',
                        name: 'saran'
                    },
                    {
                        data: 'pembuat',
                        name: 'pembuat.name'
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
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    },
                ],
            });
        }

        function htmlDecode(data) {
            var txt = document.createElement('textarea');
            txt.innerHTML = data;
            return txt.value;
        }

        function store() {
            let e = document.getElementById("sales_id");
            let sales = e.options[e.selectedIndex].value;
            let evaluasi = editor1.getData();
            let saran = editor2.getData();


            $.ajax({
                type: 'POST',
                url: '{{ route('evaluasi.store') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                        .attr('content')
                },
                data: {
                    sales: sales,
                    saran: saran,
                    evaluasi: evaluasi,
                    "_token": "{{ csrf_token() }}"
                },
                success: function() {
                    $('#tambahevaluasi').modal('hide');

                    iziToast.success({
                        title: 'Success',
                        message: 'Data Berhasil Ditambahkan',
                        position: 'topRight',
                    });

                    // Reset nilai sales dan evaluasi menjadi null setelah berhasil
                    document.getElementById("sales_id").selectedIndex = 0; // Reset pilihan sales ke default
                    editor1.setData(''); // Menghapus data dari editor1
                    editor2.setData(''); // Menghapus data dari editor2

                    $('.yajra-datatable').DataTable().ajax.reload(null, false);
                }
            });
        }

        function destroy(id) {
            Swal.fire({
                title: "Apakah kamu yakin ?",
                text: "Kamu tidak akan bisa mengembalikan data ini !",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('evaluasi.destroy') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                .attr('content')
                        },
                        data: {
                            id: id,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function() {
                            iziToast.success({
                                title: 'Success',
                                message: 'Data Berhasil Ditambahkan',
                                position: 'topRight',
                            });
                            $('.yajra-datatable').DataTable().ajax.reload(null, false);
                        }
                    });
                }
            });
        }

        function edit(id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('evaluasi.edit') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                        .attr('content')
                },
                data: {
                    id: id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(res) {

                    $('#modal-confirm-delete').html(res);
                    $('#editevaluasi').modal('show');
                    ckeditor();

                }
            });
        }

        function update(id) {
            let e = document.getElementById("sales_id");
            let sales = e.options[e.selectedIndex].value;
            let evaluasi = editor1.getData();
            let saran = editor2.getData();
            $.ajax({
                type: 'POST',
                url: '{{ route('evaluasi.update') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                        .attr('content')
                },
                data: {
                    id: id,
                    sales: sales,
                    saran: saran,
                    evaluasi: evaluasi,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(res) {
                    $('#editevaluasi').modal('hide');
                    iziToast.success({
                        title: 'Success',
                        message: 'Data Berhasil Diubah',
                        position: 'topRight',
                    });
                    $('.yajra-datatable').DataTable().ajax.reload(null, false);

                }
            });
        }

        function ckeditor() {
            ClassicEditor
                .create(document.querySelector('#editor'))
                .then(editor => {
                    editor1 = editor;
                })
                .catch(error => {
                    console.error(error);
                });


            ClassicEditor
                .create(document.querySelector('#editor2'))
                .then(editor => {
                    editor2 = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        }
    </script>
@endpush
