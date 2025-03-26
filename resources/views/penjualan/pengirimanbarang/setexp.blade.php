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
                    <div class="alert alert-custom alert-danger fade show pb-2 pt-2" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                        <div class="alert-text">{{ session('status') }}</div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="ki ki-close"></i></span>
                            </button>
                        </div>
                    </div>
                @endif
                @if (session('sukses'))
                    <div class="alert alert-custom alert-success fade show pb-2 pt-2" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                        <div class="alert-text">{{ session('sukses') }}</div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="ki ki-close"></i></span>
                            </button>
                        </div>
                    </div>
                @endif
                <div class="row ">

                    <div class="col-lg-12">
                        <!--begin::Card-->


                        <div class="card card-custom gutter-b">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="card-label">
                                        Pengiriman Barang
                                        <small>{{ $pengirimandet->PengirimanBarangs->kode }}</small>
                                    </h3>
                                </div>
                                <div class="card-toolbar">
                                    <!--begin::Button-->
                                    <a href="{{ route('pengirimanbarang.inputexp', $pengirimandet->PengirimanBarangs) }}"
                                        class="btn btn-danger font-weight-bolder ">
                                        <i class="flaticon2-fast-back"></i>
                                        Back
                                    </a>
                                    <!--end::Button-->
                                </div>
                            </div>
                            <div class="card-body">
                                <table>
                                    <tr>
                                        <th>Produk</th>
                                        <td>:</td>
                                        <td>{{ $pengirimandet->products->nama }}</td>
                                    </tr>
                                    <tr>
                                        <th>Qty Dikirim</th>
                                        <td>:</td>
                                        <td>{{ $pengirimandet->qty }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="row">

                    <div class="col-lg-12">
                        <!--begin::Card-->
                        <div class="card card-custom">
                            <div class="card-header py-3">
                                <div class="card-title">
                                    <h3 class="card-label">Daftar Produk</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--begin: Datatable-->
                                <table class="table yajra-datatable-dataproduk collapsed ">
                                    <thead class="datatable-head">
                                        <tr>
                                            <th>Tanggal Exp</th>
                                            <th>Supplier</th>
                                            <th>Harga Beli</th>
                                            <th>Diskon Beli (Rp.)</th>
                                            <th>Diskon Beli (%)</th>
                                            <th>Lot</th>
                                            <th>Qty</th>
                                            <th>Status</th>
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

                <div class="row mt-4">
                    <div class="col-lg-12">
                        <!--begin::Card-->
                        <div class="card card-custom">
                            <div class="card-header py-3">
                                <div class="card-title">
                                    <h3 class="card-label">Daftar Produk Yang Dikirim</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--begin: Datatable-->
                                <table class="table yajra-datatable-dataprodukirim collapsed ">
                                    <thead class="datatable-head">
                                        <tr>
                                            <th>Tanggal Exp</th>
                                            <th>Supplier</th>
                                            <th>Harga Beli</th>
                                            <th>Diskon Beli (Rp.)</th>
                                            <th>Diskon Beli (%)</th>
                                            <th>Lot</th>
                                            <th>Qty</th>
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
    <div id="modal-setbarang"></div>
@endsection
@push('script')
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
    <script src="{{ asset('assets/js/pages/features/miscellaneous/blockui.js?v=7.0.6') }} "></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
        integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
        integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>




    <script type="text/javascript">
        let product_id = {{ $pengirimandet->product_id }};
        let status = {{ $pengirimandet->products->status_exp }}
        let pengirimandet = {{ $pengirimandet->id }}
        $(function() {
            dataexp();
            daftarbarang();
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
                        params.product_id = product_id;
                        params.status = status;
                        params.pengirimandet = pengirimandet;
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

        function dataexp() {
            initializeDataTable('.yajra-datatable-dataproduk', "{{ route('pengirimanbarang.daftarproduk') }}",
                [{
                        data: 'tanggal',
                        searchable: false,
                        name: 'tanggal'
                    },
                    {
                        data: 'supplier',
                        searchable: false,
                        name: 'supplier'
                    },
                    {
                        data: 'harga_beli',
                        searchable: false,
                        name: 'harga_beli'
                    },
                    {
                        data: 'diskon_persen',
                        searchable: false,
                        name: 'diskon_persen'
                    },
                    {
                        data: 'diskon_rupiah',
                        searchable: false,
                        name: 'diskon_rupiah'
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
                            return '<span class="btn btn-primary btn-sm" onclick="pilihbarang(' + data +
                                ')">Pilih</span>'
                        }
                    }
                ]);
        }

        function pilihbarang(id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('pengirimanbarang.formbarang') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    KTApp.blockPage();
                },
                data: {
                    id: id,
                    status: status,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    $('#modal-setbarang').html(data);
                    $('#formbarang').modal('show');
                },
                error: function(data) {
                    console.log(data);
                },
                complete: function() {
                    KTApp.unblock();
                }
            });
        }

        function submitbarang() {
            var stok_id = document.getElementById('stok_id').value;
            var qty = document.getElementById('qty_kirim').value;

            //alert(product_id);
            $.ajax({
                type: 'POST',
                url: '{{ route('pengirimanbarang.submitbarang') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    KTApp.blockPage();
                },
                data: {
                    "stok_id": stok_id,
                    "qty": qty,
                    "status": status,
                    "pengirimandet": pengirimandet,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    $('#formbarang').modal('hide');
                    iziToast.success({
                        title: 'Success',
                        message: 'Data Berhasil Ditambahkan',
                        position: 'topRight',
                    });

                    $('.yajra-datatable-dataproduk').DataTable().ajax.reload(null, false);
                    $('.yajra-datatable-dataprodukirim').DataTable().ajax.reload(null, false);

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

        function daftarbarang() {
            initializeDataTable('.yajra-datatable-dataprodukirim', "{{ route('pengirimanbarang.daftarprodukkirim') }}",
                [{
                        data: 'tanggal',
                        searchable: false,
                        name: 'tanggal'
                    },
                    {
                        data: 'supplier',
                        searchable: false,
                        name: 'supplier'
                    },
                    {
                        data: 'harga_beli',
                        searchable: false,
                        name: 'harga_beli'
                    },
                    {
                        data: 'diskon_persen_beli',
                        searchable: false,
                        name: 'diskon_persen_beli'
                    },
                    {
                        data: 'diskon_rupiah_beli',
                        searchable: false,
                        name: 'diskon_rupiah_beli'
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
                      render: function(data){
                          return htmlDecode(data);
                      },
                      className:"nowrap",
                  },
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
                        url: '{{ route('pengirimanbarang.hapusbarang') }}',
                        dataType: 'html',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            KTApp.blockPage();
                        },
                        data: {
                            "id": id,
                            "status": status,
                            "pengirimandet": pengirimandet,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            iziToast.success({
                                title: 'Success',
                                message: 'Data Berhasil Dihapus',
                                position: 'topRight',
                            });
                            $('.yajra-datatable-dataproduk').DataTable().ajax.reload(null, false);
                            $('.yajra-datatable-dataprodukirim').DataTable().ajax.reload(null, false);
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

        function htmlDecode(data){
            var txt = document.createElement('textarea');
            txt.innerHTML=data;
            return txt.value;
        }

        function editExp(id){
            $.ajax({
                type: 'POST',
                url: '{{ route('pengirimanbarang.editexp') }}',
                dataType: 'html',
                headers: { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                data: {
                    id:id,
                    status:status, 
                    "_token": "{{ csrf_token() }}"},
                beforeSend: function() {
                    KTApp.blockPage();
                },
                success: function(data){
                    $('#modal-setbarang').html(data);
                    $('#formexp').modal('show');
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

        function submitexp(data_id) {
            var harga_beli = document.getElementById('harga_beli').value;
            var diskon_persen = document.getElementById('diskon_persen').value;
            var diskon_rupiah = document.getElementById('diskon_rupiah').value;

            //alert(product_id);
            $.ajax({
                type: 'POST',
                url: '{{ route('pengirimanbarang.submitexp') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    KTApp.blockPage();
                },
                data: {
                    "harga_beli": harga_beli,
                    "diskon_persen": diskon_persen,
                    "status": status,
                    "diskon_rupiah": diskon_rupiah,
                    "id": data_id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    $('#formexp').modal('hide');
                    iziToast.success({
                        title: 'Success',
                        message: 'Data Berhasil Ditambahkan',
                        position: 'topRight',
                    });

                    $('.yajra-datatable-dataproduk').DataTable().ajax.reload(null, false);
                    $('.yajra-datatable-dataprodukirim').DataTable().ajax.reload(null, false);

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
    </script>
@endpush
