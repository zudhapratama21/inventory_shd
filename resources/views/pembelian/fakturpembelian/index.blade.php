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

                @if (session('gagal'))
                    <div class="alert alert-custom alert-danger fade show pb-2 pt-2" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                        <div class="alert-text">{{ session('gagal') }}</div>
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
                                    <h3 class="card-label">Data Faktur Pembelian</h3>
                                </div>
                                <div class="card-toolbar">


                                    <a href="{{ route('fakturpembelian.syncronisasi') }}"
                                        class="btn btn-danger font-weight-bolder mr-2 ">                                        
                                        Syncronisasi
                                    </a>
                                    <!--begin::Button-->

                                    @can('fakturpembelian-create')
                                        <a href="{{ route('fakturpembelian.listpb') }}"
                                            class="btn btn-primary font-weight-bolder ">
                                            <i class="flaticon2-add"></i>
                                            Faktur Pembelian
                                        </a>
                                    @endcan

                                    <!--end::Button-->
                                </div>
                            </div>
                            <div class="card-body">
                                <!--begin: Datatable-->
                                <table class="table yajra-datatable collapsed ">
                                    <thead class="datatable-head">
                                        <tr>
                                            <th>Kode</th>
                                            <th>No. Penerimaan</th>
                                            <th>Tanggal</th>
                                            <th>No. Surat Pesanan</th>
                                            <th>No KPA</th>
                                            <th>No Faktur Supplier</th>
                                            <th>Supplier</th>
                                            <th style="width: 15%">Action</th>
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
    <div id="modal-confirm-delete"></div>
    <div id="modal-show-detail"></div>
@endsection
@push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>




    <script type="text/javascript">
        $(function() {

            var table = $('.yajra-datatable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('fakturpembelian.index') }}",
                columns: [
                    //   {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'kode_pb',
                        name: 'pb.kode'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'kode_po',
                        name: 'po.kode'
                    },
                    {
                        data: 'no_so',
                        name: 'po.no_so'
                    },
                    {
                        data: 'no_faktur_supplier',
                        name: 'no_faktur_supplier'
                    },
                    {
                        data: 'supplier',
                        name: 'suppliers.nama'
                    },
                    {
                        data: 'action',
                        render: function(data) {
                            return htmlDecode(data);
                        },
                        className: "nowrap",
                    },
                ],
                columnDefs: [

                    {
                        responsivePriority: 3,
                        targets: 2,

                    },
                    {
                        responsivePriority: 10001,
                        targets: 4
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    },


                ],
            });

        });


        function htmlDecode(data) {
            var txt = document.createElement('textarea');
            txt.innerHTML = data;
            return txt.value;
        }

        function show_confirm(data_id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('fakturpembelian.delete') }}',
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
                    $('#modal-confirm-delete').html(data);
                    $('#exampleModal').modal('show');
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
    </script>
@endpush
