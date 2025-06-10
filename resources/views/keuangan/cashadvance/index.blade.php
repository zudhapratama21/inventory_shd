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
                                    <h3 class="card-label">Data Cash Advance </h3>
                                </div>
                                <div class="card-toolbar">

                                    @can('cashadvance-create')
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#tambahcashadvance">
                                            <i class="fas fa-plus"></i> Tambah Cash Advance
                                        </button>
                                    @endcan

                                </div>
                            </div>
                            <div class="card-body">
                                <!--begin: Datatable-->
                                <table class="table  yajra-datatable collapsed ">
                                    <thead class="datatable-head">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Kode</th>
                                            <th>Karyawan</th>
                                            <th>Nominal</th>
                                            <th>Keterangan</th>
                                            <th>Umur</th>
                                            <th>Pengembalian</th>
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
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
    <!--end::Content-->
    <div id="modal-setdata"></div>

    <!-- Modal-->
    <div class="modal fade" id="tambahcashadvance" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cash Advance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" id="tanggal"
                            aria-describedby="emailHelp" placeholder="Tanggal">
                    </div>
                    <div class="form-group">
                        <label for="">Karyawan</label> <br>
                        <select class="form-control" id="kt_select2_4" name="karyawan_id">
                            <option value="">Pilih Karyawan</option>
                            @foreach ($karyawan as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Nominal</label>
                        <input type="number" class="form-control" name="nominal" id="nominal"
                            aria-describedby="emailHelp" placeholder="Nominal">
                    </div>
                    <div class="form-group">
                        <label for="">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold"
                        data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary font-weight-bold" onclick="inputcash()">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('assets/js/pages/features/miscellaneous/blockui.js?v=7.0.6') }} "></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
        integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
        integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />




    <script type="text/javascript">
        $(function() {
            var table = $('.yajra-datatable').DataTable({
                ajax: "{{ route('cashadvance.datatable') }}",
                columns: [{
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'karyawan.nama',
                        name: 'karyawan.nama'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'umur',
                        name: 'umur',
                    },
                    {
                        data: 'pengembalian',
                        name: 'pengembalian',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'action',
                        render: function(data) {
                            return htmlDecode(data);
                        },
                        className: "nowrap",
                    },
                ]
            });
        });


        function htmlDecode(data) {
            var txt = document.createElement('textarea');
            txt.innerHTML = data;
            return txt.value;
        }

        function deletecashadvance(data_id) {
            Swal.fire({
                icon: "question",
                title: "Mau menghapus data ini ?",
                showCancelButton: true,
                confirmButtonText: "Save",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('cashadvance.delete') }}',
                        dataType: 'html',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: data_id,
                            "_token": "{{ csrf_token() }}"
                        },

                        success: function(data) {
                            iziToast.success({
                                title: 'Success',
                                message: 'Data Berhasil Dihapus',
                                position: 'topRight',
                            });

                            $('.yajra-datatable').DataTable().ajax.reload(null, false);
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                }
            });
        }

        function inputcash() {

            var tanggal = document.getElementById('tanggal').value;
            let e = document.getElementById("kt_select2_4");
            var karyawan_id = e.options[e.selectedIndex].value;
            var nominal = document.getElementById('nominal').value;
            var keterangan = document.getElementById('keterangan').value;

            $.ajax({
                type: 'POST',
                url: '{{ route('cashadvance.store') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    tanggal: tanggal,
                    karyawan_id: karyawan_id,
                    nominal: nominal,
                    keterangan: keterangan,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    document.getElementById('nominal').value = '';
                    document.getElementById('keterangan').value = '';
                    var e = document.getElementById("kt_select2_4");
                    e.selectedIndex = 0;
                    $('#kt_select2_4').select2({
                        placeholder: "Pilih Karyawan",
                        allowClear: true
                    });

                    document.getElementById('tanggal').value = '';

                    iziToast.success({
                        title: 'Success',
                        message: 'Data Berhasil ditambahkan',
                        position: 'topRight',
                    });
                    $('.yajra-datatable').DataTable().ajax.reload(null, false);
                    $('#tambahcashadvance').modal('hide');
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function edit(id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('cashadvance.edit') }}',
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
                    $('#modal-setdata').html(data);
                    $('#editcashadvance').modal('show');

                },
                error: function(data) {
                    console.log(data);
                },
                complete: function() {
                    KTApp.unblock();
                },
            });
        }

        function updatecash(id) {

            var tanggal = document.getElementById('tanggal').value;
            let e = document.getElementById("kt_select2_4");
            var karyawan_id = e.options[e.selectedIndex].value;
            var nominal = document.getElementById('nominal').value;
            var keterangan = document.getElementById('keterangan').value;

            $.ajax({
                type: 'POST',
                url: '{{ route('cashadvance.update') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    tanggal: tanggal,
                    karyawan_id: karyawan_id,
                    nominal: nominal,
                    keterangan: keterangan,
                    id: id,
                    "_token": "{{ csrf_token() }}"
                },

                success: function(data) {
                    document.getElementById('nominal').value = '';
                    document.getElementById('keterangan').value = '';
                    var e = document.getElementById("kt_select2_4");
                    e.selectedIndex = 0;
                    $('#kt_select2_4').select2({
                        placeholder: "Pilih Karyawan",
                        allowClear: true
                    });

                    document.getElementById('tanggal').value = '';

                    Swal.fire({
                        icon: 'success',
                        title: 'Data Berhasil Ditambahkan',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    $('.yajra-datatable').DataTable().ajax.reload(null, false);
                    $('#editcashadvance').modal('hide');
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function reportcash(id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('cashadvance.reportcash') }}',
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
                    $('#modal-setdata').html(data);
                    $('#reportcash').modal('show');
                    datatablereport(id);

                },
                error: function(data) {
                    console.log(data);
                },
                complete: function() {
                    KTApp.unblock();
                },
            });
        }

        function datatablereport(id) {
            var table = $('.yajra-datatable-reportcash').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('cashadvance.datatablecash') }}",
                    type: "POST",
                    data: function(params) {
                        params.id = id;
                        params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [{
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'jenisbiaya.nama',
                        name: 'jenisbiaya.nama'
                    },
                    {
                        data: 'subbiaya.nama',
                        name: 'subbiaya.nama'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal'
                    },
                    {
                        data: 'bank.nama',
                        name: 'bank.nama'
                    },
                    {
                        data: 'verified',
                        name: 'verified'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'action',
                        render: function(data) {
                            return '<span class="btn btn-sm btn-outline-danger" onclick="deletedata(' +
                                data + ')"><i class="fas fa-trash"></i></span>';
                        },
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

        function inputreportcash(id) {
            var tanggal = document.getElementById('tanggal').value;
            let e = document.getElementById("kt_select2_8");
            var jenis_biaya_id = e.options[e.selectedIndex].value;
            var nominal = document.getElementById('nominal').value;
            var keterangan = document.getElementById('keterangan').value;
            var kode = document.getElementById('kode').value;

            let k = document.getElementById("kt_select2_7");
            var bank_id = k.options[k.selectedIndex].value;

            $.ajax({
                type: 'POST',
                url: '{{ route('cashadvance.inputcash') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    tanggal: tanggal,
                    jenis_biaya_id: jenis_biaya_id,
                    nominal: nominal,
                    keterangan: keterangan,
                    kode: kode,
                    id: id,
                    bank_id: bank_id,
                    "_token": "{{ csrf_token() }}"
                },

                success: function(data) {
                    // $('#modal-setdata').html(data);

                    document.getElementById('nominal').value = '';
                    document.getElementById('keterangan').value = '';
                    var e = document.getElementById("kt_select2_8");
                    e.selectedIndex = 0;
                    $('#kt_select2_8').select2({
                        placeholder: "Pilih Karyawan",
                        allowClear: true
                    });

                    document.getElementById('tanggal').value = '';
                    document.getElementById('kode').value = '';
                    document.getElementById('tanggal').value = '';

                    var e = document.getElementById("kt_select2_7");
                    e.selectedIndex = 0;
                    $('#kt_select2_7').select2({
                        placeholder: "Pilih Karyawan",
                        allowClear: true
                    });

                    iziToast.success({
                        title: 'Success',
                        message: 'Data Berhasil Ditambahkan',
                        position: 'topRight',
                    });

                    $('.yajra-datatable-reportcash').DataTable().ajax.reload(null, false);
                    // $('.yajra-datatable').DataTable().ajax.reload(null, false);
                },
                error: function(xhr) {
                    const response = JSON.parse(xhr.responseText);
                    if (xhr.status == 422 || xhr.status == 403 || xhr.status == 500) {
                        iziToast.error({
                            title: 'Error',
                            message: response.message,
                            position: 'topRight',
                        });
                    }
                }
            });

        }

        function deletedata(id) {
            Swal.fire({
                icon: "question",
                title: "Mau menghapus data ini ?",
                showCancelButton: true,
                confirmButtonText: "Save",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('cashadvance.deletereportcash') }}',
                        dataType: 'html',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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

                            $('.yajra-datatable-reportcash').DataTable().ajax.reload(null, false);
                            // $('.yajra-datatable').DataTable().ajax.reload(null, false);

                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                }
            });
        }

        function gantistatus(id) {
            Swal.fire({
                icon: "question",
                title: "Mau mengubah status Cash Advance ini ?",
                showCancelButton: true,
                confirmButtonText: "Save",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('cashadvance.setstatus') }}',
                        dataType: 'html',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: id,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            $('.yajra-datatable').DataTable().ajax.reload(null, false);
                        }
                    });
                }
            });
        }
    </script>
@endpush
