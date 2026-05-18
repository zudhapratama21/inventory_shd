@extends('layouts.app', ['title' => $title])

@section('content')
    <!--begin::Content-->
    <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->

        <!--end::Subheader-->

        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid mt-10">
            <!--begin::Container-->
            <div class="container">
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

                                <form class="form" method="post">
                                    @include('penjualan.pengirimanbarang._form-control')
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

        @include('penjualan.pengirimanbarang.modal._caribarang')
    @endsection
    @push('script')
        <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
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
            $(document).ready(function() {
                listbarang();
                dataBarang();
            });

            function caribarang() {
                $('#caribarang').modal('show');
            }

            function listbarang() {
                var table = $('.yajra-datatable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('pengirimanbarang.listbarang') }}",
                    columns: [{
                            data: 'kode',
                            name: 'kode'
                        },
                        {
                            data: 'nama',
                            name: 'nama'
                        },
                        {
                            data: 'merk',
                            name: 'merk'
                        },
                        {
                            data: 'satuan',
                            name: 'satuan'
                        },
                        {
                            data: 'stok',
                            name: 'stok'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                    columnDefs: [{
                            responsivePriority: 1,
                            targets: 1
                        },

                    ],
                });
            }

            function pilihbarang(id) {
                $('#caribarang').modal('hide');
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
                        id: id,
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        $('#modal-setbarang').html(data);
                        $('#setBarangModal').modal('show');
                    },
                    complete: function() {
                        KTApp.unblock();
                    },
                    error: function(data) {}
                });

            }

            function satuanFunction() {
                $('#beda_satuan').on('change', function() {
                    if ($(this).is(':checked')) {
                        $(this).val('on');

                        // aktifkan input
                        $('#satuan_konversi').prop('disabled', false);
                        $('#qty_konversi').prop('disabled', false);

                    } else {
                        $(this).val('off');

                        // disable lagi
                        $('#satuan_konversi').prop('disabled', true);
                        $('#qty_konversi').prop('disabled', true);

                        // reset nilai (optional tapi disarankan)
                        $('#qty_konversi').val(0);
                        $('#satuan_konversi').prop('selectedIndex', 0);

                    }
                });

            }


            function submitItem() {
                var product_id = document.getElementById('product_id').value;
                var qty = document.getElementById('qty').value;
                var satuan = document.getElementById('satuan').value;
                var beda_satuan = document.getElementById('beda_satuan').value;
                var satuan_konversi = document.getElementById('satuan_konversi').value;
                var qty_konversi = document.getElementById('qty_konversi').value;
                var keterangan = document.getElementById('keterangan').value;

                $.ajax({
                    type: 'POST',
                    url: '{{ route('pengirimanbarang.inputbarang') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        KTApp.blockPage();
                    },
                    complete: function() {
                        KTApp.unblock();
                    },
                    data: {
                        "product_id": product_id,
                        "qty": qty,
                        "satuan": satuan,
                        "beda_satuan": beda_satuan,
                        "satuan_konversi": satuan_konversi,
                        'qty_konversi': qty_konversi,
                        "keterangan": keterangan,
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        iziToast.success({
                            title: 'success',
                            message: 'Data berhasil ditambahkan',
                            position: 'topRight',
                        });
                        $('#setBarangModal').modal('hide');

                        $('.yajra-datatable-databarang').DataTable().ajax.reload(null, false);

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

            function dataBarang() {
                var table = $('.yajra-datatable-databarang').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('pengirimanbarang.databarang') }}",
                    columns: [{
                            data: 'nama',
                            name: 'nama'
                        },
                        {
                            data: 'satuan',
                            name: 'satuan'
                        },
                        {
                            data: 'qty',
                            name: 'qty'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                    columnDefs: [{
                            responsivePriority: 1,
                            targets: 1
                        },

                    ],
                });
            }

            function hapusbarang(id) {
                Swal.fire({
                    icon: "question",
                    title: "Mau menghapus data ini ?",
                    showCancelButton: true,
                    confirmButtonText: "Hapus",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('pengirimanbarang.hapusItem') }}',
                            dataType: 'html',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            beforeSend: function() {
                                KTApp.blockPage();
                            },
                            data: {
                                "id": id,
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(data) {
                                iziToast.success({
                                    title: 'Success',
                                    message: 'Data Berhasil Dihapus',
                                    position: 'topRight',
                                });
                                $('.yajra-datatable-databarang').DataTable().ajax.reload(null, false);
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

            function store() {
                var customer = document.getElementById('customer_id').value;
                var tanggal = document.getElementById('tgl1').value;
                var keterangan = document.getElementById('keterangan').value;

                $.ajax({
                    type: 'POST',
                    url: '{{ route('pengirimanbarang.storePB') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        KTApp.blockPage();
                    },
                    complete: function() {
                        KTApp.unblock();
                    },
                    data: {
                        "customer": customer,
                        "tanggal": tanggal,
                        "keterangan": keterangan,
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        iziToast.success({
                            title: 'success',
                            message: 'Data berhasil ditambahkan',
                            position: 'topRight',
                        });                        

                        setTimeout(function() {
                            window.location.href = "{{ route('pengirimanbarang.index') }}";
                        }, 500); // kasih delay biar toast sempat muncul

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
        </script>
    @endpush
