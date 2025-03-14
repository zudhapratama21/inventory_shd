@extends('layouts.app', ['title' => $title])

@section('content')
    <div class="example-preview" id="kt_blockui_content">
        <!--begin::Content-->
        <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
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
                                            Daftar Stok Produk
                                            <small>{{ $canvassingdetail->product->kode }}</small>
                                        </h3>
                                    </div>
                                    <div class="card-toolbar">
                                        <!--begin::Button-->
                                        <a href="{{ route('canvassing.listexp', ['id' => $canvassingdetail->canvassing_pesanan_id]) }}"
                                            class="btn btn-danger font-weight-bolder ">
                                            <i class="flaticon2-fast-back"></i>
                                            Back
                                        </a>
                                        <!--end::Button-->
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="mb-2">
                                        <tr>
                                            <th>Produk</th>
                                            <td>:</td>
                                            <td>{{ $canvassingdetail->product->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>Qty Dikirim</th>
                                            <td>:</td>
                                            <td>{{ $canvassingdetail->qty }}</td>
                                        </tr>
                                    </table>

                                    @if ($canvassingdetail->product->status_exp == 1)
                                        <table class="table yajra-datatable collapsed ">
                                            <thead class="datatable-head">
                                                <tr>
                                                    <th>Tanggal Expired</th>
                                                    <th>Lot</th>
                                                    <th>Qty</th>
                                                    <th>Harga Beli</th>
                                                    <th>Diskon Beli (Rp.)</th>
                                                    <th>Diskon Beli (%)</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    @else
                                        <table class="table yajra-datatablenonexpired collapsed ">
                                            <thead class="datatable-head">
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Qty</th>    
                                                    <th>Harga Beli</th>
                                                    <th>Diskon Beli (Rp.)</th>
                                                    <th>Diskon Beli (%)</th>                                                    
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    @endif
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
                                        <h3 class="card-label">Daftar Produk Kirim</h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table yajra-datatabledaftarkirim collapsed ">
                                        <thead class="datatable-head">
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Produk</th>
                                                <th>Lot</th>
                                                <th>Harga Beli</th>
                                                <th>Qty</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
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
    </div>
    <!--end::Content-->
    <div id="modal-confirm-delete"></div>
    <div id="modal-show-detail"></div>
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
        let product_id = {{ $canvassingdetail->product_id }};
        let canvassingdetail_id = {{ $canvassingdetail->id }};

        $(function() {
            datatableExpired();
            datatableNonExpired();
            daftarprodukkirim();

            // geolocation();

        });

        // function geolocation() {
        //     if (navigator.geolocation) {
        //         navigator.geolocation.getCurrentPosition(showPosition);
        //     } else {
        //         console.log("Geolocation is not supported by this browser.");
        //     }
        // }

        // function showPosition(params) {
        //     let lokasi = params.coords.latitude + "," + params.coords.longitude;
        //     console.log(lokasi);    
        // }

        function datatableExpired() {
            var table = $('.yajra-datatable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                searching: false,
                order: [],
                ajax: {
                    url: "{{ route('canvassing.productexp') }}",
                    type: "POST",
                    data: function(params) {
                        params.product = product_id,
                            params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [{
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'lot',
                        name: 'lot'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'harga_beli',
                        name: 'harga_beli'
                    },
                    {
                        data: 'diskon_persen',
                        name: 'diskon_persen'
                    },
                    {
                        data: 'diskon_rupiah',
                        name: 'diskon_rupiah'
                    },
                    {
                        data: 'action',
                        render: function(data) {
                            return htmlDecode(data);
                        },
                        className: "nowrap",
                    },
                ],
                columnDefs: [{
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

        function datatableNonExpired() {
            var table = $('.yajra-datatablenonexpired').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                searching: false,                
                order: [],
                ajax: {
                    url: "{{ route('canvassing.productnonexp') }}",
                    type: "POST",
                    data: function(params) {
                          params.product = product_id,
                            params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [
                    {
                        data: 'product',
                        name: 'product'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'harga_beli',
                        name: 'harga_beli'
                    },
                    {
                        data: 'diskon_persen',
                        name: 'diskon_persen'
                    },
                    {
                        data: 'diskon_rupiah',
                        name: 'diskon_rupiah'
                    },
                    {
                        data: 'action',
                        render: function(data) {
                            return htmlDecode(data);
                        },
                        className: "nowrap",
                    },
                ],
                columnDefs: [{
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

        function formsetexp(id, status) {
            $.ajax({
                type: 'POST',
                url: '{{ route('canvassing.formsetexp') }}',
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
                    $('#modal-confirm-delete').html(data);
                    $('#formsetexp').modal('show');
                },
                error: function(data) {
                    console.log(data);
                },
                complete: function() {
                    KTApp.unblock();
                }
            });
        }

        function inputexp(id) {
            var qty = document.getElementById('qty').value;
            var status = document.getElementById('status_exp').value;

            $.ajax({
                type: 'POST',
                url: '{{ route('canvassing.inputexp') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    KTApp.blockPage();
                },
                data: {
                    id: id,
                    qty: qty,
                    status: status,
                    canvassingdetail_id: canvassingdetail_id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    $('#formsetexp').modal('hide');
                    iziToast.success({
                        title: 'Success',
                        message: 'Data Berhasil Ditambahkan',
                        position: 'topRight',
                    });
                    $('.yajra-datatable').DataTable().ajax.reload(null, false);

                    $('.yajra-datatablenonexpired').DataTable().ajax.reload(null, false);
                    $('.yajra-datatabledaftarkirim').DataTable().ajax.reload(null, false);

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

        function daftarprodukkirim() {
            var table = $('.yajra-datatabledaftarkirim').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                searching: false,
                order: [],
                ajax: {
                    url: "{{ route('canvassing.daftarprodukkirim') }}",
                    type: "POST",
                    data: function(params) {
                        params.canvassingdetail_id = canvassingdetail_id,
                            params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [{
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'product',
                        name: 'product'
                    },
                    {
                        data: 'lot',
                        name: 'lot'
                    },
                    {
                        data: 'harga_beli',
                        name: 'harga_beli'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'action',
                        render: function(data) {
                            return htmlDecode(data);
                        },
                        className: "nowrap",
                    },
                ],
                columnDefs: [{
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

        function hapusexp(id, status) {
            Swal.fire({
                icon: "question",
                title: "Mau menghapus data ini ?",                
                showCancelButton: true,
                confirmButtonText: "Save",                
            }).then((result) => {                
                if (result.isConfirmed) {
                  
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('canvassing.hapusexp') }}',
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
                            iziToast.success({ title: 'Success', message: 'Data Berhasil Dihapus', position: 'topRight', });
                            $('.yajra-datatable').DataTable().ajax.reload(null, false);
                            $('.yajra-datatablenonexpired').DataTable().ajax.reload(null, false);
                            $('.yajra-datatabledaftarkirim').DataTable().ajax.reload(null, false);
                            
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
