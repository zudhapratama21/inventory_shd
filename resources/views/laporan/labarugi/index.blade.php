@extends('layouts.app', ['title' => $title])

@section('content')
    <!--begin::Content-->
    <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
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

                @if (session('error'))
                    <div class="alert alert-custom alert-success fade show pb-2 pt-2" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                        <div class="alert-text">{{ session('error') }}</div>
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
                                    <h3 class="card-label">Grafik Penjualan Principal</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Tahun</label>
                                            <select name="chart_year" class="form-control" id="kt_select2_3"
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
                                            <label for="">Principle</label>
                                            <select id="kt_select2_2" id="" name="customer_id" class="form-control"
                                                onchange="filterprinciple()">
                                                <option value="All">Semua</option>
                                                @foreach ($supplier as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Merk</label>
                                            <select id="kt_select2_5" id="" name="customer_id" class="form-control"
                                                onchange="filtermerk()">
                                                <option value="All">Semua</option>
                                                @foreach ($merk as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Sales</label>
                                            <select id="kt_select2_4" id="" name="customer_id"
                                                class="form-control" onchange="filterSales()">
                                                <option value="All">Semua</option>
                                                @foreach ($sales as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                </div>

                                <!--begin::Chart-->
                                {{-- <div id="penjualanchart"></div> --}}
                                <div>
                                    <canvas id="chartprinciple" height="100"></canvas>
                                </div>
                                <!--end::Chart-->



                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-10">

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
                                    <h3 class="card-label">Laporan Laba Rugi</h3>
                                </div>
                                <div class="card-toolbar">
                                    <!--begin::Button-->

                                    @can('laporanlabarugi-print')
                                        <a href="{{ route('laporanlabarugi.print') }}"
                                            class="btn btn-primary font-weight-bolder ">
                                            <i class="flaticon2-printer "></i>
                                            Print Laporan
                                        </a>
                                    @endcan

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
                                            <label for="">Customer</label>
                                            <select id="kt_select2_1" id="" name="customer_id"
                                                class="form-control" onchange="filterCustomer()">
                                                <option value="all">Semua</option>
                                                {{-- @foreach ($customer as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }} -
                                                        {{ $item->namakota->name }}</option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <!--begin: Datatable-->
                                <table class="table yajra-datatable collapsed ">
                                    <thead class="datatable-head">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Customer</th>
                                            <th>Product</th>
                                            <th>Total Penjualan</th>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script type="text/javascript">
        const ctx = document.getElementById('chartprinciple');

        let tanggalMulai = '';
        let customer = 'all';
        let tanggalSelesai = '';

        // =============== VARIABLE CHART ===================
        let year = 2023;
        let principle = 'All';
        let merk = 'All';
        let sales = 'All';

        // ============================ TEMPLATE GRAFIK =====================================
        let options = {
            type: 'line',
            data: {
                labels: null,
                datasets: [{
                    label: 'Penjualan',
                    data: null,
                    pointStyle: 'circle',
                    pointRadius: 10,
                    pointHoverRadius: 15,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: (ctx) => 'Data Dalam Persen Rupiah ',
                    },
                    legend: {
                        labels: {
                            // This more specific font property overrides the global property
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        stacked: true,
                        ticks: {
                            font: {
                                size: 12,
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12,
                            }
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
            }
        }


        $(function() {
            datatable();
            chartyear();
        });

        function chartyear() {
            $.ajax({
                type: 'POST',
                url: '{{ route('laporanlabarugi.chartprinciple') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'year': year,
                    'principle': principle,
                    'merk': merk,
                    'sales': sales,
                    "_token": "{{ csrf_token() }}"
                },

                success: function(data) {
                    res = JSON.parse("[" + data + "]");
                    dataLaba = res[0].laba;
                    dataBulan = res[0].bulan;

                    options.data.labels = dataBulan;
                    options.data.datasets[0].data = dataLaba;
                    chart = new Chart(ctx, options);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }


        function htmlDecode(data) {
            var txt = document.createElement('textarea');
            txt.innerHTML = data;
            return txt.value;
        }

        function filterTanggalMulai() {
            tanggalMulai = document.getElementById('tanggal_mulai').value;
            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filterTanggalSelesai() {
            tanggalSelesai = document.getElementById('tanggal_selesai').value;
            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filterYear() {
            let e = document.getElementById("kt_select2_3");
            year = e.options[e.selectedIndex].value;

            $.ajax({
                type: 'POST',
                url: '{{ route('laporanlabarugi.chartprinciple') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'year': year,
                    'principle': principle,
                    'merk': merk,
                    'sales': sales,
                    "_token": "{{ csrf_token() }}"
                },

                success: function(data) {
                    res = JSON.parse("[" + data + "]");
                    dataLaba = res[0].laba;
                    dataBulan = res[0].bulan;

                    options.data.labels = dataBulan;
                    options.data.datasets[0].data = dataLaba;

                    chart.destroy();
                    chart = new Chart(ctx, options);
                    chart.update();

                },
                error: function(data) {
                    console.log(data);
                }
            });


        }

        function filterprinciple() {
            let e = document.getElementById("kt_select2_2");
            principle = e.options[e.selectedIndex].value;

            $.ajax({
                type: 'POST',
                url: '{{ route('laporanlabarugi.chartprinciple') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'year': year,
                    'principle': principle,
                    'merk': merk,
                    'sales': sales,
                    "_token": "{{ csrf_token() }}"
                },

                success: function(data) {
                    res = JSON.parse("[" + data + "]");
                    dataLaba = res[0].laba;
                    dataBulan = res[0].bulan;

                    options.data.labels = dataBulan;
                    options.data.datasets[0].data = dataLaba;

                    chart.destroy();
                    chart = new Chart(ctx, options);
                    chart.update();

                },
                error: function(data) {
                    console.log(data);
                }
            });

        }

        function filtermerk() {
            let e = document.getElementById("kt_select2_5");
            merk = e.options[e.selectedIndex].value;

            $.ajax({
                type: 'POST',
                url: '{{ route('laporanlabarugi.chartprinciple') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'year': year,
                    'principle': principle,
                    'merk': merk,
                    'sales': sales,
                    "_token": "{{ csrf_token() }}"
                },

                success: function(data) {
                    res = JSON.parse("[" + data + "]");
                    dataLaba = res[0].laba;
                    dataBulan = res[0].bulan;

                    options.data.labels = dataBulan;
                    options.data.datasets[0].data = dataLaba;

                    chart.destroy();
                    chart = new Chart(ctx, options);
                    chart.update();

                },
                error: function(data) {
                    console.log(data);
                }
            });

        }

        function filterSales() {
            let e = document.getElementById("kt_select2_4");
            sales = e.options[e.selectedIndex].value;

            $.ajax({
                type: 'POST',
                url: '{{ route('laporanlabarugi.chartprinciple') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'year': year,
                    'principle': principle,
                    'merk': merk,
                    'sales': sales,
                    "_token": "{{ csrf_token() }}"
                },

                success: function(data) {
                    res = JSON.parse("[" + data + "]");
                    dataLaba = res[0].laba;
                    dataBulan = res[0].bulan;

                    options.data.labels = dataBulan;
                    options.data.datasets[0].data = dataLaba;

                    chart.destroy();
                    chart = new Chart(ctx, options);
                    chart.update();

                },
                error: function(data) {
                    console.log(data);
                }
            });

        }

        function datatable() {
            var table = $('.yajra-datatable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    type: 'POST',
                    url: "{{ route('laporanlabarugi.datatable') }}",
                    data: function(params) {
                        params.year = year,
                        params.principle = principle,
                        params.merk = merk,
                        params.sales = sales,
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
                        data: 'customer',
                        name: 'nama_customer'
                    },
                    {
                        data: 'product',
                        name: 'nama_product'
                    },
                    {
                        data: 'total',
                        name: 'total_penjualan'
                    },
                ],
                columnDefs: [

                    {
                        responsivePriority: 3,
                        targets: 2,

                    },
                    {
                        responsivePriority: 10001,
                        targets: 1
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    },


                ],
            });
        }
    </script>
@endpush
