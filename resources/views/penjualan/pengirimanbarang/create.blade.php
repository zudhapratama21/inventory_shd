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
                        <div class="card card-custom gutter-b example example-compact">
                            <div class="card-header ">
                                <div class="card-title">
                                    <h3 class="card-label">Pengiriman Barang</h3>
                                </div>
                            </div>
                            <!--begin::Form-->
                            <div class="card-body">

                                <form class="form" method="post"
                                    action="{{ route('pengirimanbarang.create', $pesananpenjualan) }}">
                                    @csrf
                                    @include('penjualan.pengirimanbarang._form-control', [
                                        'submit' => 'Save',
                                    ])
                                </form>
                            </div>
                            <!--end::Card-->


                        </div>
                    </div>

                </div>
                <!--end::Container-->
            </div>
            <!--end::Entry-->
        </div>

        <div id="modal-setbarang"></div>
    @endsection
    @push('script')
        <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
        <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
        <script src="{{ asset('/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.6') }}"></script>
        <script src="{{ asset('assets/js/pages/features/miscellaneous/blockui.js?v=7.0.6') }} "></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
            integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
            integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>


        <script type="text/javascript">
            let id = {{ $pesananpenjualan->id }};
            $(document).ready(function() {
                datapesanan();
                loadTempSJ();
            });

            function initializeDataTable(selector, url, columns) {
                return $(selector).DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: url,
                        type: "POST",
                        data: function(params) {
                            params.id = id;
                            params._token = "{{ csrf_token() }}";
                            return params;
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
                initializeDataTable('.yajra-datatable-pesanan', "{{ route('pengirimanbarang.datatablebarang') }}", [{
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
                        data: 'stok',
                        searchable: false,
                        name: 'products.stok'
                    },
                    {
                        data: 'qty',
                        searchable: false,
                        name: 'qty'
                    },
                    {
                        data: 'qty_sisa',
                        searchable: false,
                        name: 'qty_sisa'
                    },
                    {
                        data: 'status',
                        searchable: false,
                        render: function(data) {
                            if (data == 1) {
                                return '<span class="badge badge-success badge-sm">Sudah Dipilih</span>'
                            } else {
                                return '<span class="badge badge-info badge-sm">Belum Dipilih</span>'
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

            function pilihBarang(data_id) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pengirimanbarang.setbarang') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        KTApp.blockPage();
                    },
                    data: {
                        id: data_id,
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        $('#modal-setbarang').html(data);
                        $('#setBarangModal').modal('show');
                    },
                    complete: function() {
                        KTApp.unblock();
                    }
                });
            }

            function submitItem() {
                var detail_id = document.getElementById('detail_id').value;
                var qty = document.getElementById('qty').value;
                var keterangan = document.getElementById('keterangan').value;

                //alert(product_id);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pengirimanbarang.inputtempsj') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        KTApp.blockPage();
                    },
                    data: {
                        "detail_id": detail_id,
                        "qty": qty,
                        "keterangan": keterangan,
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        $('#setBarangModal').modal('hide');
                        iziToast.success({
                            title: 'Success',
                            message: 'Data Berhasil Ditambahkan',
                            position: 'topRight',
                        });

                        $('.yajra-datatable-pesanan').DataTable().ajax.reload(null, false);
                        $('.yajra-datatable-daftar').DataTable().ajax.reload(null, false);

                    },
                    error: function(xhr) {
                        const response = JSON.parse(xhr.responseText);
                        if (xhr.status === 422) {
                            // Error qty melebihi stok
                            iziToast.error({
                                title: 'error',
                                message: response.message,
                                position: 'topRight',
                            });
                        }

                        if (xhr.status === 500) {
                            // Error qty melebihi stok
                            iziToast.error({
                                title: 'error',
                                message: response.message,
                                position: 'topRight',
                            });
                        }
                    },
                    complete: function() {
                        KTApp.unblock();
                    }
                });
            }

            function loadTempSJ() {
                initializeDataTable('.yajra-datatable-daftar', "{{ route('pengirimanbarang.daftarbarang') }}", [{
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
                        data: 'action',
                        render: function(data) {
                            return '<span class="btn btn-danger btn-sm" onclick="hapusbarang(' + data +
                                ')"><i class="fas fa-trash"></i></span>'
                        }
                    }
                ]);
            }

            function hapusbarang(id) {
                Swal.fire({
                    icon: "question",
                    title: "Mau menghapus data ini ?",
                    showCancelButton: true,
                    confirmButtonText: "Save",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('pengirimanbarang.deletetemp') }}',
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
                                $('.yajra-datatable-pesanan').DataTable().ajax.reload(null, false);
                                $('.yajra-datatable-daftar').DataTable().ajax.reload(null, false);
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
