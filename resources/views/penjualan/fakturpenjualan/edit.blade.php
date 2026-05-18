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
                                    <span class="card-icon">
                                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                                            <!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo2\dist/../src/media/svg/icons\Communication\Shield-user.svg--><svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path
                                                        d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z"
                                                        fill="#000000" opacity="0.3" />
                                                    <path
                                                        d="M12,11 C10.8954305,11 10,10.1045695 10,9 C10,7.8954305 10.8954305,7 12,7 C13.1045695,7 14,7.8954305 14,9 C14,10.1045695 13.1045695,11 12,11 Z"
                                                        fill="#000000" opacity="0.3" />
                                                    <path
                                                        d="M7.00036205,16.4995035 C7.21569918,13.5165724 9.36772908,12 11.9907452,12 C14.6506758,12 16.8360465,13.4332455 16.9988413,16.5 C17.0053266,16.6221713 16.9988413,17 16.5815,17 C14.5228466,17 11.463736,17 7.4041679,17 C7.26484009,17 6.98863236,16.6619875 7.00036205,16.4995035 Z"
                                                        fill="#000000" opacity="0.3" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon--></span>
                                    </span>
                                    <h3 class="card-label">Faktur Penjualan</h3>
                                </div>

                                <div class="card-toolbar">
                                    {{-- <a href="{{ route('assignpermission.index') }}"
                                class="btn btn-light-danger font-weight-bold mr-2">
                                <i class="flaticon2-left-arrow-1"></i> Back
                                </a> --}}
                                </div>
                            </div>
                            <!--begin::Form-->
                            <div class="card-body">

                                <form class="form" method="post"
                                    action="{{ route('fakturpenjualan.update', ['fakturpenjualan' => $fakturpenjualan->id]) }}">
                                    @csrf
                                    @include('penjualan.fakturpenjualan._form-control-edit', [
                                        'submit' => 'Update',
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

        @include('penjualan.fakturpenjualan.modal.caribarang')
        @include('penjualan.fakturpenjualan.modal.biayalain')
        <div id="modal-setbarang"></div>
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
            let faktur_id = {{ $fakturpenjualan->id }};
            let status = {{ $status }}


            $(document).on('click', '#check-all', function() {
                $('.sj-checkbox').prop('checked', this.checked);
            });

            $(document).ready(function() {
                databarang();
                datatablebarang();
                hitungtotal();
            });

            function databarang() {
                var table = $('.yajra-datatable-databarang').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('fakturpenjualan.loadfj') }}",
                        type: "POST",
                        data: function(params) {
                            params.id = faktur_id,
                                params._token = "{{ csrf_token() }}";
                            return params;
                        }
                    },
                    columns: [{
                            data: 'nama',
                            name: 'products.nama'
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
                            data: 'ppn',
                            name: 'ppn'
                        },
                        {
                            data: 'hargajual',
                            name: 'hargajual'
                        },
                        {
                            data: 'diskon_persen',
                            name: 'diskon_persen'
                        },
                        {
                            data: 'diskon_rp',
                            name: 'diskon_rp'
                        },
                        {
                            data: 'subtotal',
                            name: 'subtotal'
                        },
                        {
                            data: 'total_diskon',
                            name: 'total_diskon'
                        },
                        {
                            data: 'total',
                            name: 'total'
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

            function editsj(id) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('fakturpenjualan.editdetail') }}',
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
                        $('#modal-setbarang').html(data);
                        $('#setbarang').modal('show');
                    },
                    complete: function() {
                        KTApp.unblockPage();
                    },
                    error: function(data) {}
                });
            }

            function updatesj(id) {
                var product_id = document.getElementById('product_id').value;
                var qty = document.getElementById('qty').value;
                var satuan = document.getElementById('satuan').value;
                var hargajual = document.getElementById('hargajual').value;
                var ppn = document.getElementById('ppn').value;
                var diskon_persen = document.getElementById('diskon_persen').value;
                var diskon_rp = document.getElementById('diskon_rp').value;
                var keterangan = document.getElementById('keterangan').value;

                $.ajax({
                    type: 'POST',
                    url: '{{ route('fakturpenjualan.updatedetail') }}',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        KTApp.blockPage();
                    },
                    data: {
                        "faktur_id": faktur_id,
                        "id": id,
                        "product_id": product_id,
                        "qty": qty,
                        "satuan": satuan,
                        "hargajual": hargajual,
                        "ppn": ppn,
                        "diskon_persen": diskon_persen,
                        "diskon_rp": diskon_rp,
                        "keterangan": keterangan,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#setbarang').modal('hide');
                        iziToast.success({
                            title: 'success',
                            message: 'Data Barang Berhasil Diupdate',
                            position: 'topRight',
                        });
                        // lakukan sesuatu setelah berhasil menyimpan
                        $('.yajra-datatable-databarang').DataTable().ajax.reload(null, false);
                        hitungtotal();
                    },
                    complete: function() {
                        KTApp.unblockPage();
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

            function hapussj(id) {
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('fakturpenjualan.hapusdetail') }}',
                            dataType: 'json',
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
                                    title: 'success',
                                    message: 'Data Barang Berhasil Dihapus',
                                    position: 'topRight',
                                });
                                // lakukan sesuatu setelah berhasil menghapus
                                $('.yajra-datatable-databarang').DataTable().ajax.reload(null, false);
                                hitungtotal();
                            },
                            complete: function() {
                                KTApp.unblockPage();
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
                });
            }

            function datatablebarang() {
                var table = $('.yajra-datatable-datatablebarang').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('fakturpenjualan.caribarang') }}",
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

            function tambahbarang() {
                $('#caribarang').modal('show');
                $('.yajra-datatable-datatablebarang').DataTable().ajax.reload(null, false);
            }

            function pilihbarang(id) {
                $('#caribarang').modal('hide');
                $.ajax({
                    type: 'POST',
                    url: '{{ route('fakturpenjualan.setbarang') }}',
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
                        $('#setbarang').modal('show');
                    },
                    complete: function() {
                        KTApp.unblock();
                    },
                    error: function(data) {}
                });
            }

            function submititem() {
                var product_id = document.getElementById('product_id').value;
                var nama = document.getElementById('nama').value;
                var qty = document.getElementById('qty').value;
                var satuan = document.getElementById('satuan').value;
                var hargajual = document.getElementById('hargajual').value;
                var ppn = document.getElementById('ppn').value;
                var diskon_persen = document.getElementById('diskon_persen').value;
                var diskon_rp = document.getElementById('diskon_rp').value;
                var keterangan = document.getElementById('keterangan').value;
                $.ajax({
                    type: 'POST',
                    url: '{{ route('fakturpenjualan.tambahdetail') }}',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        KTApp.blockPage();
                    },
                    data: {
                        'faktur_id': faktur_id,
                        "product_id": product_id,
                        "nama": nama,
                        "qty": qty,
                        "satuan": satuan,
                        "hargajual": hargajual,
                        "ppn": ppn,
                        "diskon_persen": diskon_persen,
                        "diskon_rp": diskon_rp,
                        "keterangan": keterangan,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#setbarang').modal('hide');
                        iziToast.success({
                            title: 'success',
                            message: 'Data Barang Berhasil Diupdate',
                            position: 'topRight',
                        });
                        // lakukan sesuatu setelah berhasil menyimpan
                        $('.yajra-datatable-databarang').DataTable().ajax.reload(null, false);
                        hitungtotal();
                    },
                    complete: function() {
                        KTApp.unblockPage();
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

            function hitungtotal() {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('fakturpenjualan.totaldetail') }}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'faktur_id': faktur_id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        let data = res.data;
                        // format ke rupiah
                        $('#subtotal').val(formatRupiah(data.subtotal));
                        $('#diskon').val(formatRupiah(data.total_diskon));
                        $('#totalppn').val(formatRupiah(data.ppn));
                        $('#grandtotal').val(formatRupiah(data.grandtotal));
                        $('#total').val(formatRupiah(data.total));
                        $('#ongkirtotal').val(formatRupiah(data.ongkir));
                        $('#materaitotal').val(formatRupiah(data.materai));

                        $('#ongkir').val(data.ongkir);
                        $('#materai').val(data.materai);
                    },
                    error: function() {
                        console.log('Gagal ambil total');
                    }
                });
            }

            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(angka);
            }

            function tambahbiaya() {     
                           
                if (status == 0) {
                    $('#setbiayalain').modal('show');
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak dapat menambahkan biaya karena sudah terbayar',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }

            }

            function submitbiaya() {
                var ongkir = document.getElementById('ongkir').value;
                var materai = document.getElementById('materai').value;

                $.ajax({
                    type: 'POST',
                    url: '{{ route('fakturpenjualan.updatebiayadetail') }}',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        KTApp.blockPage();
                    },
                    data: {
                        "faktur_id": faktur_id,
                        "ongkir": ongkir,
                        "materai": materai,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#setbiayalain').modal('hide');
                        iziToast.success({
                            title: 'success',
                            message: 'Data Biaya Lain Berhasil Disimpan',
                            position: 'topRight',
                        });
                        // lakukan sesuatu setelah berhasil menyimpan
                        hitungtotal();
                    },
                    complete: function() {
                        KTApp.unblockPage();
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

            function submitfj() {
                var tanggalfj = document.getElementById('tgl1').value;
                var tanggal_jatuh_tempo = document.getElementById('tgl2').value;
                var no_urut = document.getElementById('no_urut').value;
                var sales_id = document.getElementById('sales_id').value;
                var komoditas_id = document.getElementById('komoditas_id').value;
                var kategori_id = document.getElementById('kategori_id').value;
                var no_sp_customer = document.getElementById('no_sp_customer').value;
                var tanggal_sp_customer = document.getElementById('tanggal_sp_customer').value;
                var keteranganfj = document.getElementById('keteranganfj').value;

                $.ajax({
                    type: 'POST',
                    url: '{{ route('fakturpenjualan.updatefj') }}',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        KTApp.blockPage();
                    },
                    data: {
                        "id": faktur_id,
                        "tanggal": tanggalfj,
                        "tanggal_jatuh_tempo": tanggal_jatuh_tempo,
                        "no_urut": no_urut,
                        "sales_id": sales_id,
                        "komoditas_id": komoditas_id,
                        "kategori_id": kategori_id,
                        "no_sp_customer": no_sp_customer,
                        "tanggal_sp_customer": tanggal_sp_customer,
                        "keterangan": keteranganfj,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {

                        iziToast.success({
                            title: 'success',
                            message: 'Data Faktur Penjualan Berhasil Disimpan',
                            position: 'topRight',
                        });

                        // lakukan sesuatu setelah berhasil menyimpan
                        setTimeout(function() {
                            window.location.href = "{{ route('fakturpenjualan.index') }}";
                        }, 1000); // kasih delay biar toast sempat muncul

                    },
                    complete: function() {
                        KTApp.unblockPage();
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

            function cekNomor() {
                var tanggal = document.getElementById('tgl1').value;
                var no_urut = document.getElementById('no_urut').value;

                $.ajax({
                    type: 'POST',
                    url: '{{ route('fakturpenjualan.ceknomor') }}',
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
                        tanggal: tanggal,
                        no_urut: no_urut,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        iziToast.success({
                            title: 'Success',
                            message: 'No aman untuk dipakai',
                            position: 'topRight',
                        });
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
