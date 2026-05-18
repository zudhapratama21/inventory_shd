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
                                    <h3 class="card-label">Pesanan Pembelian</h3>
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

                                <form class="form" method="post" action="{{ route('pesananpembelian.create') }}">
                                    @csrf
                                    @include('pembelian.pesananpembelian._form-control', [
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
        <!--end::Content-->
        <div id="modal-confirm-delete">
            <!-- Modal-->
            <div class="modal fade" id="detailDeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form id="destroy-form" action="#">
                            <input type="hidden" id="id_detail" name="id_detail" value="">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Are You Sure?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span class="svg-icon svg-icon-primary svg-icon-4x">
                                            <!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo2\dist/../src/media/svg/icons\Code\Warning-2.svg--><svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path
                                                        d="M11.1669899,4.49941818 L2.82535718,19.5143571 C2.557144,19.9971408 2.7310878,20.6059441 3.21387153,20.8741573 C3.36242953,20.9566895 3.52957021,21 3.69951446,21 L21.2169432,21 C21.7692279,21 22.2169432,20.5522847 22.2169432,20 C22.2169432,19.8159952 22.1661743,19.6355579 22.070225,19.47855 L12.894429,4.4636111 C12.6064401,3.99235656 11.9909517,3.84379039 11.5196972,4.13177928 C11.3723594,4.22181902 11.2508468,4.34847583 11.1669899,4.49941818 Z"
                                                        fill="#000000" opacity="0.3" />
                                                    <rect fill="#000000" x="11" y="9" width="2" height="7"
                                                        rx="1" />
                                                    <rect fill="#000000" x="11" y="17" width="2" height="2"
                                                        rx="1" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon--></span>
                                    </div>
                                    <div class="col-md-10 " style="display: inline;">
                                        <div class="align-middle">
                                            Deleting Detail Data, will be permanently removed from
                                            system.
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary font-weight-bold"
                                    data-dismiss="modal">Cancel</button>
                                <button type="button" onClick="javascript:destroy_detail();"
                                    class="btn btn-danger font-weight-bold">Yes, Delete Now !</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal-->
        </div>
        <div id="modal-caribarang"></div>
        <div id="modal-setbarang"></div>
        <div id="modal-setdiskon"></div>
        <div id="modal-setppn"></div>

        <div id="xcontohmodal">
            <!-- Modal-->

            <div class="modal fade" id="caribarang" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Cari Barang</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i aria-hidden="true" class="ki ki-close"></i>
                            </button>
                        </div>
                        <div class="modal-body" style="height: 400px;">

                            <table class="table  yajra-datatable collapsed ">
                                <thead class="datatable-head">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Katalog</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-primary font-weight-bold"
                                data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>

            </div>
            <script></script>
            <!-- Modal-->
        </div>

        @include('pembelian.pesananpembelian.partial.ongkir')
    @endsection
    @push('script')
        <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
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
            $(function() {
                hitungAll()

                var table = $('.yajra-datatable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('pesananpembelian.caribarang') }}",
                    columns: [
                        //   {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {
                            data: 'kode',
                            name: 'kode'
                        },
                        {
                            data: 'nama',
                            name: 'nama'
                        },
                        {
                            data: 'katalog',
                            name: 'katalog'
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
                            targets: 1
                        },

                    ],
                });
            });

            function htmlDecode(data) {
                var txt = document.createElement('textarea');
                txt.innerHTML = data;
                return txt.value;
            }

            function caribarang() {
                $('#caribarang').modal('show');

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

            function pilihBarang(data_id) {
                $('#caribarang').modal('hide');
                //alert(data_id);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpembelian.setbarang') }}',
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
                        id: data_id,
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        console.log(data);
                        $('#modal-setbarang').html(data);
                        $('#setBarangModal').modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

            function editBarang(data_id) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpembelian.editbarang') }}',
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
                        id: data_id,
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        console.log(data);
                        $('#modal-setbarang').html(data);
                        $('#setBarangModal').modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

            function submitItem() {
                var product_id = document.getElementById('product_id').value;
                var qty = document.getElementById('qty').value;
                var satuan = document.getElementById('satuan').value;
                var hargabeli = document.getElementById('hargabeli').value;
                var diskon_persen = document.getElementById('diskon_persen').value;
                var diskon_rp = document.getElementById('diskon_rp').value;
                var ongkir = document.getElementById('ongkir').value;
                var keterangan = document.getElementById('keterangan').value;
                var ppn = document.getElementById('ppnprice').value;

                var beda_satuan = document.getElementById('beda_satuan').value;
                var satuan_konversi = document.getElementById('satuan_konversi').value;
                var qty_konversi = document.getElementById('qty_konversi').value;




                //alert(product_id);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpembelian.inputtemppo') }}',
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
                        "hargabeli": hargabeli,
                        "diskon_persen": diskon_persen,
                        "diskon_rp": diskon_rp,
                        "ongkir": ongkir,
                        "keterangan": keterangan,
                        'ppn': ppn,
                        "beda_satuan": beda_satuan,
                        "satuan_konversi": satuan_konversi,
                        'qty_konversi': qty_konversi,
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        console.log(data);

                        $('#setBarangModal').modal('hide');
                        hitungAll();
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

            function updateItem() {
                var id = document.getElementById('id').value;
                var product_id = document.getElementById('product_id').value;
                var qty = document.getElementById('qty').value;
                var satuan = document.getElementById('satuan').value;
                var hargabeli = document.getElementById('hargabeli').value;
                var diskon_persen = document.getElementById('diskon_persen').value;
                var diskon_rp = document.getElementById('diskon_rp').value;
                var ongkir = document.getElementById('ongkir').value;
                var keterangan = document.getElementById('keterangan').value;
                var ppn = document.getElementById('ppnprice').value;

                var beda_satuan = document.getElementById('beda_satuan').value;
                var satuan_konversi = document.getElementById('satuan_konversi').value;
                var qty_konversi = document.getElementById('qty_konversi').value;

                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpembelian.updatebarang') }}',
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
                        "id": id,
                        "product_id": product_id,
                        "qty": qty,
                        "satuan": satuan,
                        "hargabeli": hargabeli,
                        "diskon_persen": diskon_persen,
                        "diskon_rp": diskon_rp,
                        "ongkir": ongkir,
                        "keterangan": keterangan,
                        "ppn": ppn,
                        "beda_satuan": beda_satuan,
                        "satuan_konversi": satuan_konversi,
                        'qty_konversi': qty_konversi,
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        console.log(data);

                        $('#setBarangModal').modal('hide');
                        hitungAll()
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

            function loadTempPO() {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpembelian.loadtemppo') }}',
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
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        console.log(data);
                        $('#tabel_detil').html(data);

                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

            function editOngkir() {
                $('#setOngkir').modal('show');
            }

            function updateOngkir() {
                var ongkir = document.getElementById('ongkir').value;
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpembelian.updateongkir') }}',
                    dataType: 'json',
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
                        "ongkir": ongkir,
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(res) {
                        let data = res.data;
                        $('#setOngkir').modal('hide');
                        $('#ongkir').val(data.ongkir);
                        hitungAll();
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }


            function delete_confirm(data_id) {
                $('#detailDeleteModal').modal('show');
                $('#id_detail').val(data_id);
            }

            function destroy_detail() {
                var data_id = $('#id_detail').val();
                //alert(data_id);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpembelian.destroy_detail') }}',
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
                        id: data_id,
                        "_method": "delete",
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        console.log(data);
                        $('#detailDeleteModal').modal('hide');
                        hitungAll();
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }          

            function hitungGrandTotal() {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpembelian.hitunggrandtotal') }}',
                    dataType: 'json',
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
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        let data = res.data;                        
                        $('#subtotal').val(formatRupiah(data.subtotal));
                        $('#diskon').val(formatRupiah(data.total_diskon));
                        $('#ppnheader').val(formatRupiah(data.ppn));
                        $('#grandtotal').val(formatRupiah(data.grandtotal));
                        $('#total').val(formatRupiah(data.total));                        
                        $('#ongkirheader').val(formatRupiah(data.ongkir));                        

                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

             function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(angka);
            }

            function hitungAll() {               
                hitungGrandTotal();
                loadTempPO();
            }

            function cekNomor() {
                var tanggal = document.getElementById('tgl1').value;
                var no_urut = document.getElementById('no_urut').value;

                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpembelian.ceksurat') }}',
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
