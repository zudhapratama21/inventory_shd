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
                                    <h3 class="card-label">Data Sub Jenis Biaya</h3>
                                </div>
                                <div class="card-toolbar">
                                    <!-- Button trigger modal-->
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#tambahdata">
                                        Tambah Sub Biaya
                                    </button>
                                    <!--end::Button-->
                                </div>
                            </div>
                            <div class="card-body">
                                <!--begin: Datatable-->
                                <table class="table  yajra-datatable collapsed ">
                                    <thead class="datatable-head">
                                        <tr>
                                            <th>Nama</th>
                                            <th>No Akun</th>
                                            <th>Jenis Biaya</th>
                                            <th>Keterangan</th>
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
    <!-- Modal-->
    <div class="modal fade" id="tambahdata" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="form-group">
                            <label for="">Nama Sub Biaya</label>
                            <input type="text" class="form-control" name="nama" id="nama">
                        </div>

                        <div class="form-group">
                            <label for="">No Akun</label>
                            <input type="text" class="form-control" name="no_akun" id="no_akun">
                        </div>

                        <div class="form-group">
                            <label for="">Jenis Biaya</label> <br>
                            <select class="form-control" id="kt_select2_3">
                                @foreach ($biaya as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" id="keterangan">
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold"
                        data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary font-weight-bold" onclick="store()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--end::Content-->
    <div id="modal-setdata"></div>
@endsection
@push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
        integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
        integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>

    <script type="text/javascript">
        $(function() {
            datatable();
        });

        function datatable() {
            var table = $('.yajra-datatable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('subjenisbiaya.datatable') }}",
                    type: "POST",
                    data: function(params) {
                        params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [{
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_akun',
                        name: 'no_akun'
                    },
                    {
                        data: 'jenisbiaya',
                        name: 'jenisbiaya.nama'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'action',
                        render: function(data) {

                            return '<span class="btn btn-danger btn-sm ml-2" onclick="hapus(' +
                                data + ')"><i class="fas fa-trash"></i></span>';
                        }
                    }
                ]
            });
        }

        function store() {
            var nama = document.getElementById('nama').value;
            var no_akun = document.getElementById('no_akun').value;
            var keterangan = document.getElementById('keterangan').value;
            var e = document.getElementById("kt_select2_3");
            var jenisbiaya = e.options[e.selectedIndex].value;


            $.ajax({
                type: 'POST',
                url: '{{ route('subjenisbiaya.create') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    KTApp.blockPage();
                },
                data: {
                    "nama": nama,
                    "no_akun": no_akun,                    
                    "keterangan": keterangan,
                    "jenisbiaya": jenisbiaya,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    $('#tambahdata').modal('hide');
                    iziToast.success({
                        title: 'Success',
                        message: 'Data Berhasil Ditambahkan',
                        position: 'topRight',
                    });

                    $('.yajra-datatable').DataTable().ajax.reload(null, false);

                    // $('#nama').val('');
                    // $('#no_akun').val('');
                    // $('#keterangan').val('');
                    // $('#kt_select2_3').val('').trigger('change');

                },
                error: function(xhr) {
                    const response = JSON.parse(xhr.responseText);
                    if (xhr.status === 422 || xhr.status === 500) {
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

        function hapus(id) {
            Swal.fire({
                icon: "question",
                title: "Mau menghapus data ini ?",
                showCancelButton: true,
                confirmButtonText: "Save",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('subjenisbiaya.delete') }}',
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

                            $('.yajra-datatable').DataTable().ajax.reload(null, false);
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
            });
        }

    </script>
@endpush
