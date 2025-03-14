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
                                    <h3 class="card-label">Atur Expired Date</h3>
                                </div>
                                <div class="card-toolbar">
                                    <!--begin::Button-->

                                    <a href="{{ route('penerimaanbarang.index') }}"
                                        class="btn btn-danger font-weight-bolder ">
                                        <i class="flaticon2-fast-back"></i>
                                        Back
                                    </a>
                                    <!--end::Button-->
                                </div>
                            </div>
                            <div class="card-body">
                                <!--begin: Datatable-->
                                <table class="table yajra-datatable collapsed ">
                                    <thead class="datatable-head">
                                        <tr>
                                            <th style="width: 10%">Kode barang</th>
                                            <th style="width: 50%">Nama Barang</th>
                                            <th style="width: 5%">Satuan</th>
                                            <th style="width: 5%">Qty Diterima</th>
                                            <th style="width: 10%">Is Expired ?</th>
                                            <th style="width: 5%">Status</th>
                                            <th style="width: 10%">Action</th>
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
    <div id="modal-setbarang"></div>
@endsection
@push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.6') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
        integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
        integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>

    <script type="text/javascript">
        let id = {{ $penerimaanbarang->id }};
        let detail_id = 0;
        $(document).ready(function() {
            datapesanan();
        });

        function initializeDataTable(selector, url, extraParams = {}, columns) {
            return $(selector).DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                pageLength: 7,
                order: [],
                ajax: {
                    url: url,
                    type: "POST",
                    data: function(params) {
                        return Object.assign(params, extraParams, {
                            _token: "{{ csrf_token() }}"
                        });
                    }
                },
                columns: columns,
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    }
                ]
            });
        }

        function datapesanan() {
            initializeDataTable('.yajra-datatable', "{{ route('penerimaanbarang.listpesanan') }}", {
                id: id
            }, [{
                    data: 'kode',
                    searchable: false,
                    name: 'products.kode'
                },
                {
                    data: 'product',
                    name: 'products.nama'
                },
                {
                    data: 'satuan',
                    searchable: false,
                    name: 'products.satuan'
                },
                {
                    data: 'qty',
                    searchable: false,
                    name: 'qty'
                },
                {
                    data: 'status_exp',
                    searchable: false,
                    render: function(data) {
                        if (data == 1) {
                            return '<span class="badge badge-success badge-sm">Expired</span>'
                        } else {
                            return '<span class="badge badge-info badge-sm">Non Exp</span>'
                        }

                    }
                },
                {
                    data: 'status',
                    searchable: false,
                    render: function(data) {
                        if (data == 1) {
                            return '<span class="badge badge-success badge-sm">Done</span>'
                        } else {
                            return '<span class="badge badge-warning badge-sm">Pending</span>'
                        }

                    }
                },
                {
                    data: 'action',
                    render: function(data) {
                        return '<span class="btn btn-primary btn-sm" onclick="pilihBarang(' + data +
                            ')">Pilih</span>'
                    }
                }
            ]);
        }

        function pilihBarang(id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('penerimaanbarang.setexp') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    KTApp.blockPage();
                },
                data: {
                    id: id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    $('#modal-setbarang').html(data);
                    dataexp(id);
                    $('#formexp').modal('show');

                },
                error: function(data) {
                    console.log(data);
                },
                complete: function() {
                    KTApp.unblock();
                }
            });


        }

        function dataexp(data_id) {
            initializeDataTable('.yajra-datatable-exp', "{{ route('penerimaanbarang.listexp') }}", {
                id: data_id,
            }, [{
                    data: 'tanggal',
                    searchable: false,
                    name: 'tanggal'
                },
                {
                    data: 'lot',
                    searchable: false,
                    name: 'lot'
                },
                {
                    data: 'qty',
                    searchable: false,
                    name: 'qty'
                },
                {
                    data: 'action',
                    render: function(data) {
                        return '<span class="btn btn-danger btn-sm" onclick="hapusexp(' + data +
                            ')"><i class="fas fa-trash"></i></span>'
                    }
                }
            ]);
        }

        function submitexp() {
            var detail_id = document.getElementById('detail_id').value;
            var qty = document.getElementById('qty').value;
            var lot = document.getElementById('lot').value;
            var tanggal = document.getElementById('tgl1').value;

            $.ajax({
                type: 'POST',
                url: '{{ route('penerimaanbarang.saveexp') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "detail_id": detail_id,
                    "qty": qty,
                    "lot": lot,
                    "tanggal": tanggal,
                    "_token": "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    KTApp.blockPage();
                },
                success: function(data) {
                    $('.yajra-datatable').DataTable().ajax.reload(null, false);
                    $('.yajra-datatable-exp').DataTable().ajax.reload(null, false);
                    iziToast.success({
                        title: 'success',
                        message: 'Data berhasil ditambahkan',
                        position: 'topLeft',
                    });

                    document.getElementById('qty').value = 0;
                    document.getElementById('lot').value = 0;
                    document.getElementById('tgl1').value = '';
                },
                complete: function() {
                    KTApp.unblock();
                },
                error: function(xhr) {
                    const response = JSON.parse(xhr.responseText);
                    if (xhr.status === 500 || xhr.status === 422) {
                        iziToast.error({
                            title: 'error',
                            message: response.message,
                            position: 'topRight',
                        });
                    }
                }
            });
        }

        function hapusexp(id) {
            Swal.fire({
                icon: "question",
                title: "Mau menghapus data ini ?",
                showCancelButton: true,
                confirmButtonText: "Save",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('penerimaanbarang.hapusexp') }}',
                        dataType: 'html',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            KTApp.blockPage();
                        },
                        data: {
                            id: id,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            iziToast.success({
                                title: 'Success',
                                message: 'Data Berhasil Dihapus',
                                position: 'topRight',
                            });
                            $('.yajra-datatable').DataTable().ajax.reload(null, false);
                            $('.yajra-datatable-exp').DataTable().ajax.reload(null, false);
                        },
                        error: function(data) {
                            console.log(data);
                        },
                        complete: function() {
                            KTApp.unblock();
                        }
                    });
                }
            });
        }
    </script>
@endpush
