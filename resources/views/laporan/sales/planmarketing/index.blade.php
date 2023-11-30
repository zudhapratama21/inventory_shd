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
                                    <span class="card-icon"> <span class="svg-icon svg-icon-md svg-icon-primary">
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
                                            </svg> <!--end::Svg Icon--></span> </span>
                                    <h3 class="card-label">Laporan Plan Marketing</h3>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="kt_tab_pane_4_1" role="tabpanel"
                                        aria-labelledby="kt_tab_pane_4_1">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Tahun</label>
                                                    <select name="" id="kt_select2_1" class="form-control"
                                                        onchange="filterYear()">
                                                        @php
                                                            $year = 2020;
                                                        @endphp
                                                        @foreach (range(date('Y'), $year) as $x)
                                                            <option value="{{ $x }}">{{ $x }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Bulan</label>
                                                    <select name="" id="kt_select2_2" class="form-control"
                                                        onchange="filterBulan()">
                                                        @foreach ($bulan as $item)
                                                            @if (now()->format('m') == $item['id'])
                                                                <option value="{{ $item['id'] }}" selected>
                                                                    {{ $item['nama'] }}</option>
                                                            @else
                                                                <option value="{{ $item['id'] }}">{{ $item['nama'] }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Outlet</label>
                                                    <select name="" id="kt_select2_3" class="form-control"
                                                        onchange="filterOutlet()">
                                                        <option value="all" selected>Semua</option>
                                                        @foreach ($outlet as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Sales</label>
                                                    <select name="" id="kt_select2_4" class="form-control"
                                                        onchange="filterSales()">
                                                        <option value="all" selected>Semua</option>
                                                        @foreach ($sales as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>


                                        </div>

                                        <!--begin: Datatable-->
                                        <table class="table yajra-datatable collapsed ">
                                            <thead class="datatable-head">
                                                <tr>
                                                    <th>Waktu</th>
                                                    <th>Sales</th>
                                                    <th>Outlet</th>
                                                    <th>Minggu ke 1 </th>
                                                    <th>Minggu ke 2</th>
                                                    <th>Minggu ke 3</th>
                                                    <th>Minggu ke 4</th>
                                                    <th>Minggu ke 5</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>



                            </div>
                        </div>


                        <div class="card card-custom" style="margin-top: 30px">
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
                                    <h3 class="card-label">Laporan Kunjungan Sales</h3>
                                </div>
                                <div class="card-toolbar">
                                    <!--begin::Button-->
                                    <form method="POST" action="{{ route('laporansales.print') }}">
                                        @csrf

                                        <input type="hidden" name="sales" value="all" id="formsales">
                                        <input type="hidden" name="tanggal_mulai" id="formtanggal_mulai">
                                        <input type="hidden" name="tanggal_selesai" id="formtanggal_selesai">
                                        @can('laporansales-list')
                                            <button type="submit" class="btn btn-primary font-weight-bolder ">
                                                <i class="flaticon2-printer "></i>
                                                Print Laporan
                                                </a>
                                            @endcan                                            
                                    </form>

                                    <!--end::Button-->
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Tanggal Mulai</label>
                                            <input type="date" class="form-control" id="tanggal_mulai"
                                                onchange="filterTanggalMulai()">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Tanggal Selesei</label>
                                            <input type="date" class="form-control" id="tanggal_selesai"
                                                onchange="filterTanggalSelesai()">
                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Sales</label>
                                            <select id="kt_select2_5" id="" name="sales_id"
                                                class="form-control" onchange="filterCustomer()">
                                                <option value="all">Semua</option>
                                                @foreach ($sales as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <!--begin: Datatable-->
                                <table class="table yajra-datatable2 collapsed ">
                                    <thead class="datatable-head">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jam</th>
                                            <th>Sales</th>
                                            <th>Customer</th>
                                            <th>Aktivitas</th>
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
        let tanggalMulai = '';
        let sales = 'all';
        let tanggalSelesai = '';

        let tahun = {{ now()->format('Y') }};
        let bulan = {{ now()->format('m') }};
        let outlet = "all";
        let salesMarketing = 'all';

        $(function() {
            datatable();
            datatable2();
        });

        function datatable() {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                bFilter: false,
                ajax: {
                    url: "{{ route('laporanplanmarketing.datatable') }}",
                    type: "POST",
                    data: function(params) {
                        params.tahun = tahun,
                            params.bulan = bulan,
                            params.outlet = outlet,
                            params.sales = salesMarketing,
                            params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [{
                        data: 'waktu',
                        name: 'waktu'
                    },
                    {
                        data: 'userName',
                        name: 'user.name'
                    },
                    {
                        data: 'outlet',
                        name: 'outlet.nama'
                    },
                    {
                        data: 'week1',
                        name: 'week1'
                    },
                    {
                        data: 'week2',
                        name: 'week2'
                    },
                    {
                        data: 'week3',
                        name: 'week3'
                    },
                    {
                        data: 'week4',
                        name: 'week4'
                    },
                    {
                        data: 'week5',
                        name: 'week5'
                    },

                ],
            });
        }

        function htmlDecode(data) {
            var txt = document.createElement('textarea');
            txt.innerHTML = data;
            return txt.value;
        }


        function filterYear() {
            let e = document.getElementById("kt_select2_1");
            tahun = e.options[e.selectedIndex].value;

            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filterBulan() {
            let e = document.getElementById("kt_select2_2");
            bulan = e.options[e.selectedIndex].value;

            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filterOutlet() {
            let e = document.getElementById("kt_select2_3");
            outlet = e.options[e.selectedIndex].value;

            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filterSales() {
            let e = document.getElementById("kt_select2_4");
            salesMarketing = e.options[e.selectedIndex].value;

            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }


        function datatable2() {
            var table = $('.yajra-datatable2').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    type: 'POST',
                    url: "{{ route('laporansales.datatable') }}",
                    data: function(params) {
                        params.tanggalMulai = tanggalMulai;
                        params.tanggalSelesai = tanggalSelesai;
                        params.sales = sales;
                        params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [
                    //   {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'user',
                        name: 'user.name'
                    },
                    {
                        data: 'customer',
                        name: 'customer'
                    },
                    {
                        data: 'aktifitas',
                        name: 'aktifitas'
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
                        targets: 3
                    },


                ],
            });
        }

        function filterTanggalMulai() {
            tanggalMulai = document.getElementById('tanggal_mulai').value;
            $('#formtanggal_mulai').val(tanggalMulai);
            $('.yajra-datatable2').DataTable().ajax.reload(null, false);
        }

        function filterTanggalSelesai() {
            tanggalSelesai = document.getElementById('tanggal_selesai').value;
            $('#formtanggal_selesai').val(tanggalSelesai);
            $('.yajra-datatable2').DataTable().ajax.reload(null, false);
        }

        function filterCustomer() {
            let e = document.getElementById("kt_select2_5");
            sales = e.options[e.selectedIndex].value;
            $('#formsales').val(sales);
            $('.yajra-datatable2').DataTable().ajax.reload(null, false);

        }
    </script>
@endpush
