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
                                    <h3 class="card-label">Pesanan Penjualan</h3>
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

                                <form class="form" method="post" action="{{ route('pesananpenjualan.create') }}">
                                    @csrf
                                    @include('penjualan.pesananpenjualan._form-control', [
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
        </div>
    @endsection
    @push('script')
        <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
        <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
        <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
        <script src="{{ asset('/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.6') }}"></script>


        <script type="text/javascript">
            $(function() {
                hitungAll()

                var table = $('.yajra-datatable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('pesananpenjualan.caribarang') }}",
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

            function pilihBarang(data_id) {
                $('#caribarang').modal('hide');
                //alert(data_id);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpenjualan.setbarang') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                    url: '{{ route('pesananpenjualan.editbarang') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                var hargajual = document.getElementById('hargajual').value;
                var diskon_persen = document.getElementById('diskon_persen').value;
                var diskon_rp = document.getElementById('diskon_rp').value;
                var ongkir = document.getElementById('ongkir').value;
                var ppn_ongkir = document.getElementById('ppn_ongkir').value;
                var keterangan = document.getElementById('keterangan').value;
                var ppn = document.getElementById('ppnprice').value;


                //alert(product_id);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpenjualan.inputtempso') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "product_id": product_id,
                        "qty": qty,
                        "satuan": satuan,
                        "hargajual": hargajual,
                        "diskon_persen": diskon_persen,
                        "diskon_rp": diskon_rp,
                        "ongkir": ongkir,
                        "ppn_ongkir": ppn_ongkir,
                        "keterangan": keterangan,
                        "ppn": ppn,
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
                var hargajual = document.getElementById('hargajual').value;
                var diskon_persen = document.getElementById('diskon_persen').value;
                var diskon_rp = document.getElementById('diskon_rp').value;
                var ongkir = document.getElementById('ongkir').value;
                var ppn_ongkir = document.getElementById('ppn_ongkir').value;
                var keterangan = document.getElementById('keterangan').value;
                var ppn = document.getElementById('ppnprice').value;

                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpenjualan.updatebarang') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "id": id,
                        "product_id": product_id,
                        "qty": qty,
                        "satuan": satuan,
                        "hargajual": hargajual,
                        "diskon_persen": diskon_persen,
                        "diskon_rp": diskon_rp,
                        "ongkir": ongkir,
                        "ppn_ongkir": ppn_ongkir,
                        "keterangan": keterangan,
                        'ppn': ppn,
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

            function loadTempSO() {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpenjualan.loadtempso') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "id": "",
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

            function editdiskon() {
                var data_id = '';
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpenjualan.editdiskon') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: data_id,
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        console.log(data);
                        $('#modal-setdiskon').html(data);
                        $('#setDiskonModal').modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

            function updateDiskon() {
                var diskon_persen = document.getElementById('diskon_persen_h').value;
                var diskon_rupiah = document.getElementById('diskon_rp_h').value;
                var id_diskon = document.getElementById('id_diskon').value;
                //alert(diskon_persen);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpenjualan.updatediskon') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "id_diskon": id_diskon,
                        "diskon_persen": diskon_persen,
                        "diskon_rupiah": diskon_rupiah,
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        console.log(data);
                        $('#setDiskonModal').modal('hide');
                        //$('#diskon').val(data);
                        hitungAll();

                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

            function editppn() {
                var data_id = '';
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpenjualan.editppn') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: data_id,
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        console.log(data);
                        $('#modal-setppn').html(data);
                        $('#setPpnModal').modal('show');

                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

            function updatePPN() {
                var persen = document.getElementById('ppn_persen').value;
                var id_ppn = document.getElementById('id_ppn').value;

                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpenjualan.updateppn') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "id_ppn": id_ppn,
                        "persen": persen,
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        console.log(data);
                        $('#setPpnModal').modal('hide');
                        //$('#ppn').val(data);
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
                // var a = $('#id_detail').val();
                //alert(a);
            }

            function destroy_detail() {
                var data_id = $('#id_detail').val();
                //alert(data_id);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpenjualan.destroy_detail') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
            /* Fungsi formatRupiah */
            function formatRupiah(angka, elemenid) {
                //alert(angka);
                var prefix = 'Rp. ';
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                //alert (rupiah);
                //return rupiah;
                document.getElementById(elemenid).value = rupiah;
                //return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
            }

            function hitungSubtotal() {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpenjualan.hitungsubtotal') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        console.log(data);
                        $('#subtotal').val(data);

                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

            function hitungDiskon() {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpenjualan.hitungdiskon') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        console.log(data);
                        $('#diskon').val(data);

                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

            function hitungTotal() {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpenjualan.hitungtotal') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        console.log(data);
                        $('#total').val(data);

                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

            function hitungPPN() {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpenjualan.hitungppn') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        console.log(data);
                        $('#ppn').val(data);

                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

            function hitungOngkir() {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpenjualan.hitungongkir') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        console.log(data);
                        $('#ongkirheader').val(data);

                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

            function hitungGrandTotal() {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pesananpenjualan.hitunggrandtotal') }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        console.log(data);
                        $('#grandtotal').val(data);

                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

            function hitungAll() {
                loadTempSO();
                hitungSubtotal();
                hitungDiskon();
                hitungTotal();
                hitungPPN();
                hitungOngkir();
                hitungGrandTotal();
            }
        </script>
    @endpush
