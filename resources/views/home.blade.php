@extends('layouts.app')

@section('content')
    {{-- <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-12  subheader-transparent " id="kt_subheader">
            <div class=" container  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">

                    <!--begin::Heading-->
                    <div class="d-flex flex-column">
                        <!--begin::Title-->
                        <h2 class="text-white font-weight-bold my-2 mr-5">
                            Dashboard </h2>
                        <!--end::Title-->

                        <!--begin::Breadcrumb-->
                        <div class="d-flex align-items-center font-weight-bold my-2">
                            <!--begin::Item-->
                            <a href="#" class="opacity-75 hover-opacity-100">
                                <i class="flaticon2-shelter text-white icon-1x"></i>
                            </a>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
                            <a href="" class="text-white text-hover-white opacity-75 hover-opacity-100">
                                Dashboard </a>
                            <!--end::Item-->
                            <!--begin::Item-->


                            <!--end::Item-->
                        </div>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Heading-->

                </div>
                <!--end::Info-->

                <!--begin::Toolbar-->

                <!--end::Toolbar-->
            </div>
        </div>
        <!--end::Subheader-->

        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class=" container ">
                <!--begin::Dashboard-->
                <div class="row">
                    <div class="col-lg-12">
                        <!--begin::Card-->

                        <div class="card card-custom gutter-b">
                            <!--begin::Header-->
                            <div class="card-header h-auto d-flex justify-content-between">
                                <!--begin::Title-->
                                <div class="card-title py-5">
                                    <h3 class="card-label">
                                        Grafik Penjualan
                                    </h3>
                                </div>


                                <!--end::Title-->
                            </div>
                            <!--end::Header-->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Tahun</label>
                                            <select name="chart_year" class="form-control" id="kt_select2_1"
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

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Kategori Pesanan</label>
                                            <select name="chart_kategori" class="form-control" id="kt_select2_2"
                                                onchange="filterKategori()">
                                                <option value="All" selected>Semua</option>
                                                @foreach ($kategori as $x)
                                                    <option value="{{ $x->id }}">{{ $x->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>



                                <!--begin::Chart-->
                                {{-- <div id="penjualanchart"></div> --}}
                                <div>
                                    <canvas id="myChart" height="100"></canvas>
                                </div>
                                <!--end::Chart-->
                            </div>
                        </div>


                        <!--end::Card-->
                    </div>
                </div>

                <!--begin::Row-->
                <div class="row">
                    <div class="col-xl-8">
                        <!--begin::Tiles Widget 1-->
                        <div class="card card-custom gutter-b card-stretch">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <div class="card-title">
                                    <div class="card-label">
                                        <div class="font-weight-bolder">Grafik Per Kategori</div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Header-->

                            {{-- Grafik --}}
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">Tahun</label>
                                    <select name="chart_year" class="form-control" id="kt_select2_7"
                                        onchange="filterYearKategori()">
                                        @php
                                            $year = 2020;
                                            https: //e-katalog.lkpp.go.id/katalog/produk/detail/83636355?type=regency&location_id=290
                                        @endphp
                                        @foreach (range(date('Y'), $year) as $x)
                                            <option value="{{ $x }}">{{ $x }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <canvas id="KategoriChart" height="100"></canvas>
                            </div>

                            {{-- end Of Grafik --}}

                        </div>
                        <!--end::Tiles Widget 1-->
                    </div>
                </div>
                <!--end::Row-->

                {{-- GRAFIK UNTUK PRODUK --}}
                <div class="row">
                    <div class="col-xl-12">
                        <!--begin::Tiles Widget 1-->
                        <div class="card card-custom gutter-b card-stretch">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <div class="card-title">
                                    <div class="card-label">
                                        <div class="font-weight-bolder">Grafik Penjualan Produk</div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Header-->

                            {{-- Grafik --}}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Tahun</label>
                                            <select name="chart_year" class="form-control" id="kt_select2_5"
                                                onchange="filteryearproduk()">
                                                @php
                                                    $year = 2020;
                                                @endphp
                                                @foreach (range(date('Y'), $year) as $x)
                                                    <option value="{{ $x }}">{{ $x }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Produk</label>
                                            <select name="chart_year" class="form-control" id="kt_select2_3"
                                                onchange="filterProduk()">
                                                @foreach ($produk as $item)
                                                    <option value="{{ $item->id }}">{{ $item->kode }} -
                                                        {{ $item->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <canvas id="produkChart" height="100"></canvas>
                            </div>

                            {{-- end Of Grafik --}}

                        </div>
                        <!--end::Tiles Widget 1-->
                    </div>
                </div>
                {{-- END OF GRAFIK PRODUK --}}


                {{--  BEST PRODUK --}}
                <div class="row">
                    <div class="col-xl-12">
                        <!--begin::Tiles Widget 1-->
                        <div class="card card-custom gutter-b card-stretch">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <div class="card-title">
                                    <div class="card-label">
                                        <div class="font-weight-bolder">Top Produk</div>
                                    </div>
                                </div>

                                <div class="card-toolbar">

                                    <form method="POST" action="{{ route('home.exporttopproduct') }}">
                                        @csrf
                                        <input type="hidden" name="tahun" value="2024" id="tahun">
                                        <input type="hidden" name="bulan_product" value="all" id="bulan_product">
                                        <input type="hidden" name="kategori_product" value="all" id="kategori_product">

                                        <button type="submit"
                                            class="btn btn-success font-weight-bolder mr-4">
                                            <i class="flaticon-download "></i>
                                            Download Excel
                                        </button>
                                    </form>


                                </div>
                            </div>
                            <!--end::Header-->

                            {{-- Grafik --}}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Tahun</label>
                                            <select name="chart_year" class="form-control" id="kt_select2_8"
                                                onchange="filteryearbestproduk()">
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
                                            <label for="">Tipe</label>
                                            <select name="chart_year" class="form-control" id="kt_select2_9"
                                                onchange="filtertypebestproduk()">
                                                <option value="harga" selected>Harga</option>
                                                <option value="stok">Stok</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Bulan</label>
                                            <select name="chart_year" class="form-control" id="kt_select2_4"
                                                onchange="filterbulanbestproduk()">
                                                <option value="All" selected>Semua</option>
                                                @foreach ($bulan as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Kategori Pesanan</label>
                                            <select name="chart_year" class="form-control" id="kt_select2_11"
                                                onchange="filterkategoribestproduk()">
                                                <option value="All" selected>Semua</option>
                                                @foreach ($kategori as $x)
                                                    <option value="{{ $x->id }}">{{ $x->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                {{-- <canvas id="chartbestproduk" height="100"></canvas> --}}

                                <table
                                    class="table table-separate table-head-custom table-checkable table  yajra-datatable collapsed ">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Produk</th>
                                            <th>Qty</th>
                                            <th>Total Penjualan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            {{-- end Of Grafik --}}

                        </div>
                        <!--end::Tiles Widget 1-->
                    </div>
                </div>
                {{-- END OF BEST PRODUK --}}


                {{-- TOP CUSTOMER --}}
                <div class="row">
                    <div class="col-xl-12">
                        <!--begin::Tiles Widget 1-->
                        <div class="card card-custom gutter-b card-stretch">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <div class="card-title">
                                    
                                    <div class="card-label">
                                        <div class="font-weight-bolder">Top Customer</div>
                                    </div>
                                </div>

                                <div class="card-toolbar">

                                    <form method="POST" action="{{ route('home.exporttopcustomer') }}">
                                        @csrf
                                        <input type="hidden" name="tahun_customer" value="2024" id="tahun_customer">
                                        <input type="hidden" name="bulan_customer" value="all" id="bulan_customer">
                                        <input type="hidden" name="kategori_customer" value="all" id="kategori_customer">

                                        <button type="submit"
                                            class="btn btn-success font-weight-bolder mr-4">
                                            <i class="flaticon-download "></i>
                                            Download Excel
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <!--end::Header-->

                            {{-- Grafik --}}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Tahun</label>
                                            <select name="chart_year" class="form-control" id="kt_select2_13"
                                                onchange="filteryeartopcustomer()">
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
                                            <select name="chart_year" class="form-control" id="kt_select2_15"
                                                onchange="filterbulantopcustomer()">
                                                <option value="All" selected>Semua</option>
                                                @foreach ($bulan as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Kategori Pesanan</label>
                                            <select name="chart_year" class="form-control" id="kt_select2_16"
                                                onchange="filterkategoritopcustomer()">
                                                <option value="All" selected>Semua</option>
                                                @foreach ($kategori as $x)
                                                    <option value="{{ $x->id }}">{{ $x->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                {{-- <canvas id="chartbestproduk" height="100"></canvas> --}}

                                <table
                                    class="table table-separate table-head-custom table-checkable table  yajra-datatabletopcustomer collapsed ">
                                    <thead>
                                        <tr>
                                            <th>Bulan Transaksi</th>
                                            <th>Nama Customer</th>
                                            <th>Qty</th>
                                            <th>Total Penjualan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            {{-- end Of Grafik --}}

                        </div>
                        <!--end::Tiles Widget 1-->
                    </div>
                </div>

                {{-- END TOP CUSTOMER --}}

                {{-- TOP PRINCIPLE --}}
                <div class="row">
                    <div class="col-xl-12">
                        <!--begin::Tiles Widget 1-->
                        <div class="card card-custom gutter-b card-stretch">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <div class="card-title">
                                    
                                    <div class="card-label">
                                        <div class="font-weight-bolder">Top Principle</div>
                                    </div>
                                </div>

                                <div class="card-toolbar">

                                    <form method="POST" action="{{ route('home.exporttopcustomer') }}">
                                        @csrf
                                        <input type="hidden" name="tahun_customer" value="2024" id="tahun_customer">
                                        <input type="hidden" name="bulan_customer" value="all" id="bulan_customer">
                                        <input type="hidden" name="kategori_customer" value="all" id="kategori_customer">

                                        <button type="submit"
                                            class="btn btn-success font-weight-bolder mr-4">
                                            <i class="flaticon-download "></i>
                                            Download Excel
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <!--end::Header-->

                            {{-- Grafik --}}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Tahun</label>
                                            <select name="chart_year" class="form-control" id="topprincipletahun"
                                                onchange="filteryeartopprinciple()">
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
                                            <select name="chart_year" class="form-control" id="topprinciplebulan"
                                                onchange="filterbulantopprinciple()">
                                                <option value="All" selected>Semua</option>
                                                @foreach ($bulan as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Kategori Pesanan</label>
                                            <select name="chart_year" class="form-control" id="topprinciplekategori"
                                                onchange="filterkategoritopprinciple()">
                                                <option value="All" selected>Semua</option>
                                                @foreach ($kategori as $x)
                                                    <option value="{{ $x->id }}">{{ $x->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                {{-- <canvas id="chartbestproduk" height="100"></canvas> --}}

                                <table
                                    class="table table-separate table-head-custom table-checkable table  yajra-datatabletopprinciple collapsed ">
                                    <thead>
                                        <tr>
                                            <th>Bulan Transaksi</th>
                                            <th>Nama Supplier</th>
                                            <th>Qty</th>
                                            <th>Total Penjualan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            {{-- end Of Grafik --}}

                        </div>
                        <!--end::Tiles Widget 1-->
                    </div>
                </div>

                {{-- END OF TOP PRINCIPLE --}}

            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div> --}}

    {{-- modal Customer --}}
    @include('partial.modal.produk')
    @include('partial.modal.customer')
    @include('partial.modal.principle')
@endsection

{{-- @push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.6') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');
        const chartKategori = document.getElementById('KategoriChart');
        const produk_chart = document.getElementById('produkChart');
        const best_produk = document.getElementById('chartbestproduk');


        let year = {{ now()->format('Y') }};
        let kategori = 'All';
        let dataRange = null;
        let bulan = 'All';
        let dataBulan = null;
        let chart = null;
        let produk = {{ $produk[0]->id }};
        let tipe = 'harga';
        let product_id = null;
        let customer_id = null;
        let supplier_id = null;

        // var bulan = @json($bulan);

        // variable top customer 
        let topcustomeryear = {{ now()->format('Y') }};
        let topcustomerbulan = 'All';
        let topcustomerkategori = 'All';


        // variable top principle
        let topprincipleyear = {{ now()->format('Y') }};
        let topprinciplebulan = 'All';
        let topprinciplekategori = 'All';



        $(document).ready(function() {
            chartyear();
            chart_kategori();
            chartProduk();
            // chartbestproduk();
            datatable();
            datatableCustomer();
            datatabletopcustomer();
            datatablelistproduct();
            datatabletopPrinciple();
            datatableProductByPrinciple();


        })

        // chart Bar Pejualan
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

        function chartyear() {
            $.ajax({
                type: 'POST',
                url: '{{ route('chart.year') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'year': year,
                    'kategori': kategori,
                    'tipe': tipe,
                    'bulan': bulan,
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

        function filterYear() {
            let e = document.getElementById("kt_select2_1");
            year = e.options[e.selectedIndex].value;
            $.ajax({
                type: 'POST',
                url: '{{ route('chart.year') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'year': year,
                    'kategori': kategori,
                    'tipe': tipe,
                    'bulan': bulan,
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

        function filterKategori() {
            let e = document.getElementById("kt_select2_2");
            kategori = e.options[e.selectedIndex].value;
            $.ajax({
                type: 'POST',
                url: '{{ route('chart.year') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'year': year,
                    'kategori': kategori,
                    'tipe': tipe,
                    'bulan': bulan,
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

        // end of Chart Penjualan Bar

        let dougnut = {
            type: 'bar',
            data: {
                labels: null,
                datasets: [{
                    label: 'Grafik Penjualan Per Kategori',
                    data: null,
                    borderWidth: 1,
                    backgroundColor: ['#FF6384', '#36A2EB'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: (ctx) => 'Data Dalam Persen Rupiah ',
                    },

                },
                scales: {
                    y: {
                        stacked: true
                    }
                }
            },
            interaction: {
                intersect: false,
            }
        }

        // Chart dougnut Kategori Penjualan
        function chart_kategori() {
            $.ajax({
                type: 'POST',
                url: '{{ route('chart.kategori') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'year': year,
                    "_token": "{{ csrf_token() }}"
                },

                success: function(data) {
                    res = JSON.parse("[" + data + "]");
                    datakategori = res[0].datakategori;
                    datapenjualan = res[0].datapenjualan;

                    dougnut.data.labels = datakategori;
                    dougnut.data.datasets[0].data = datapenjualan;
                    chartkategori = new Chart(chartKategori, dougnut);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function filterYearKategori() {
            let e = document.getElementById("kt_select2_7");
            year = e.options[e.selectedIndex].value;
            $.ajax({
                type: 'POST',
                url: '{{ route('chart.kategori') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'year': year,
                    "_token": "{{ csrf_token() }}"
                },

                success: function(data) {
                    res = JSON.parse("[" + data + "]");
                    datakategori = res[0].datakategori;
                    datapenjualan = res[0].datapenjualan;

                    dougnut.data.labels = datakategori;
                    dougnut.data.datasets[0].data = datapenjualan;

                    chartkategori.destroy();
                    chartkategori = new Chart(chartKategori, dougnut);
                    chartkategori.update();

                },
                error: function(data) {
                    console.log(data);
                }
            });
        }


        // untuk Forecast Produk
        let produkchart = {
            type: 'line',
            data: {
                labels: null,
                datasets: [{
                    label: 'Penjualan per Produk',
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

        function chartProduk() {
            $.ajax({
                type: 'POST',
                url: '{{ route('chart.produk') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'year': year,
                    'produk': produk,
                    "_token": "{{ csrf_token() }}"
                },

                success: function(data) {
                    res = JSON.parse("[" + data + "]");
                    dataStok = res[0].stok;
                    dataBulan = res[0].bulan;

                    produkchart.data.labels = dataBulan;
                    produkchart.data.datasets[0].data = dataStok;
                    grafikProduk = new Chart(produk_chart, produkchart);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function filteryearproduk() {
            let e = document.getElementById("kt_select2_5");
            year = e.options[e.selectedIndex].value;

            $.ajax({
                type: 'POST',
                url: '{{ route('chart.produk') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'year': year,
                    'produk': produk,
                    "_token": "{{ csrf_token() }}"
                },

                success: function(data) {
                    res = JSON.parse("[" + data + "]");
                    dataStok = res[0].stok;
                    dataBulan = res[0].bulan;

                    produkchart.data.labels = dataBulan;
                    produkchart.data.datasets[0].data = dataStok;

                    grafikProduk.destroy();
                    grafikProduk = new Chart(produk_chart, produkchart);
                    grafikProduk.update();

                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function filterProduk() {
            let e = document.getElementById("kt_select2_3");
            produk = e.options[e.selectedIndex].value;

            $.ajax({
                type: 'POST',
                url: '{{ route('chart.produk') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'year': year,
                    'produk': produk,
                    "_token": "{{ csrf_token() }}"
                },

                success: function(data) {
                    res = JSON.parse("[" + data + "]");
                    dataStok = res[0].stok;
                    dataBulan = res[0].bulan;

                    produkchart.data.labels = dataBulan;
                    produkchart.data.datasets[0].data = dataStok;

                    grafikProduk.destroy();
                    grafikProduk = new Chart(produk_chart, produkchart);
                    grafikProduk.update();

                },
                error: function(data) {
                    console.log(data);
                }
            });
        }


        // GRAFIK PRODUK DENGAN PENJUALAN TERBAIK 
        function datatable() {
            var table = $('.yajra-datatable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "{{ route('chart.bestproduk') }}",
                    // headers: { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                    type: "POST",
                    data: function(params) {
                        params.year = year,
                            params.bulan = bulan,
                            params.tipe = tipe,
                            params.kategori = kategori,
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
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'stok_produk',
                        name: 'stok_produk'
                    },
                    {
                        data: 'total',
                        name: 'total'
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


        let bestproduk = {
            type: 'bar',
            data: {
                labels: null,
                datasets: [{
                    label: 'Grafik Top Penjualan',
                    data: null,
                    borderWidth: 1,
                    borderColor: ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56'],
                    backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56']
                }, ],
            },
            options: {
                indexAxis: 'y',
                elements: {
                    bar: {
                        borderWidth: 2,
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                },
                scales: {
                    y: {
                        stacked: true,
                        ticks: {
                            font: {
                                size: 9,
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10,
                            }
                        }
                    }
                }
            },
        }

        function chartbestproduk() {
            $.ajax({
                type: 'POST',
                url: '{{ route('chart.bestproduk') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: {
                    'year': year,
                    'bulan': bulan,
                    'tipe': tipe,
                    "_token": "{{ csrf_token() }}"
                },

                success: function(data) {
                    res = JSON.parse("[" + data + "]");
                    dataNamaProduk = res[0].nama_produk;
                    dataStokProduk = res[0].stok;

                    bestproduk.data.labels = dataNamaProduk;
                    bestproduk.data.datasets[0].data = dataStokProduk;
                    grafikbestproduk = new Chart(best_produk, bestproduk);

                },
                error: function(data) {
                    console.log(data);
                }
            });
        }


        function filteryearbestproduk() {
            let e = document.getElementById("kt_select2_8");
            
            year = e.options[e.selectedIndex].value;
            $('#tahun').val(year);

            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filterbulanbestproduk() {
            let e = document.getElementById("kt_select2_4");
            bulan = e.options[e.selectedIndex].value;
            $('#bulan_product').val(bulan);

            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filtertypebestproduk() {
            let e = document.getElementById("kt_select2_9");
            tipe = e.options[e.selectedIndex].value;
            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filterkategoribestproduk() {
            let e = document.getElementById("kt_select2_11");
            kategori = e.options[e.selectedIndex].value;
            $('#kategori_product').val(kategori);
            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        // ======================================= MODAL CUSTOMER ==========================================
        function showCustomer(id) {
            $('#listcustomer').modal('show');
            product_id = id;
            $('.yajra-datatablecustomer').DataTable().ajax.reload(null, false);

        }

        function datatableCustomer() {
            var tablecustomer = $('.yajra-datatablecustomer').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "{{ route('datatable.listcustomer') }}",
                    type: "POST",
                    data: function(params) {
                        params.year = year,
                            params.bulan = bulan,
                            params.product_id = product_id,
                            params.kategori = kategori,
                            params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [
                    //   {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'stok_produk',
                        name: 'stok_produk'
                    },
                    {
                        data: 'total',
                        name: 'total'
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

        // ================================= TOP CUSTOMER =====================================
        function datatabletopcustomer(params) {
            var tabletopcustomer = $('.yajra-datatabletopcustomer').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "{{ route('datatable.topCustomer') }}",
                    type: "POST",
                    data: function(params) {
                        params.year = topcustomeryear,
                            params.bulan = topcustomerbulan,
                            params.kategori = topcustomerkategori,
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
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'stok_produk',
                        name: 'stok_produk'
                    },
                    {
                        data: 'total',
                        name: 'total'
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

        function showProduct(id) {
            $('#listproduk').modal('show');
            customer_id = id;
            $('.yajra-datatabletopproduct').DataTable().ajax.reload(null, false);
        }

        function datatablelistproduct() {
            var tablelistproduct = $('.yajra-datatabletopproduct').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "{{ route('datatable.productbyprinciple') }}",
                    type: "POST",
                    data: function(params) {
                            params.year = topcustomeryear,
                            params.bulan = topcustomerbulan,
                            params.customer = customer_id,
                            params.kategori = topcustomerkategori,
                            params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [
                    //   {data: 'DT_RowIndex', name: 'DT_RowIndex'},                
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'stok_produk',
                        name: 'stok_produk'
                    },
                    {
                        data: 'total',
                        name: 'total'
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

        function filteryeartopcustomer() {
            let e = document.getElementById("kt_select2_13");
            topcustomeryear = e.options[e.selectedIndex].value;
            $('#tahun_customer').val(topcustomeryear);
            $('.yajra-datatabletopcustomer').DataTable().ajax.reload(null, false);
        }

        function filterbulantopcustomer() {
            console.log('masuk');
            let e = document.getElementById("kt_select2_15");
            topcustomerbulan = e.options[e.selectedIndex].value;
            $('#bulan_customer').val(topcustomerbulan);
            $('.yajra-datatabletopcustomer').DataTable().ajax.reload(null, false);
        }

        function filterkategoritopcustomer() {
            let e = document.getElementById("kt_select2_16");
            topcustomerkategori = e.options[e.selectedIndex].value;
            $('#kategori_customer').val(topcustomerkategori);
            $('.yajra-datatabletopcustomer').DataTable().ajax.reload(null, false);
        }



        // ========================= DATATABLE TOP PRINCIPLE =============================================

        function datatabletopPrinciple(params) {
            var tabletopcustomer = $('.yajra-datatabletopprinciple').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "{{ route('datatable.topPrinciple') }}",
                    type: "POST",
                    data: function(params) {
                            params.year = topprincipleyear,
                            params.bulan = topprinciplebulan,
                            params.kategori = topprinciplekategori,
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
                        data: 'nama_supplier',
                        name: 'nama_supplier'
                    },
                    {
                        data: 'stok_produk',
                        name: 'stok_produk'
                    },
                    {
                        data: 'total',
                        name: 'total'
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

        function filteryeartopprinciple() {
            let e = document.getElementById("topprincipletahun");
            topprincipleyear = e.options[e.selectedIndex].value;
            $('#tahun_principle').val(topprincipleyear);
            $('.yajra-datatabletopprinciple').DataTable().ajax.reload(null, false);
        }

        function filterbulantopprinciple() {
            console.log('masuk');
            let e = document.getElementById("topprinciplebulan");
            topprinciplebulan = e.options[e.selectedIndex].value;
            $('#bulan_principle').val(topprinciplebulan);
            $('.yajra-datatabletopprinciple').DataTable().ajax.reload(null, false);
        }

        function filterkategoritopprinciple() {
            let e = document.getElementById("topprinciplekategori");
            topprinciplekategori = e.options[e.selectedIndex].value;
            $('#kategori_principle').val(topprinciplekategori);
            $('.yajra-datatabletopprinciple').DataTable().ajax.reload(null, false);
        }

        function datatableProductByPrinciple() {
            var tabletopcustomer = $('.yajra-datatableproductbyprinciple').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "{{ route('datatable.productbyprinciple') }}",
                    type: "POST",
                    data: function(params) {
                            params.year = topprincipleyear,
                            params.bulan = topprinciplebulan,
                            params.kategori = topprinciplekategori,
                            params.supplier = supplier_id,
                            params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [
                    //   {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {
                        data: 'nama_produk',
                        name: 'nama_produk'
                    },
                    {
                        data: 'nama_merekx',
                        name: 'nama_merekx'
                    },
                    {
                        data: 'stok_produk',
                        name: 'stok_produk'
                    },
                    {
                        data: 'total',
                        name: 'total'
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

        function showProductByPrinciple(id) {
            $('#productbyprinciple').modal('show');
            supplier_id = id;
            $('.yajra-datatableproductbyprinciple').DataTable().ajax.reload(null, false);
        }

        
    </script>
@endpush --}}
