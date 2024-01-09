@extends('layouts.app', ['title' => $title])

@section('content')
    <!--begin::Content-->
    <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">


        @if ($bulan_id == 'All')
            @php
                $bulan_id = 99;
            @endphp
        @endif
        <!--begin::Subheader-->

        {{-- GRAFIK PERFORMA SALES --}}

        <div class="container ">
            <div class="row">

                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom">
                        <div class="card-header py-3">
                            <div class="card-title">
                                <span class="card-icon">
                                    <span class="svg-icon svg-icon-md svg-icon-primary">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Chart-bar1.svg--><svg
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
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
                                <h3 class="card-label">Grafik Performa Sales : <i>{{ $sales->nama }}</i></h3>


                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Tahun</label>
                                        <select name="chart_year" class="form-control" id="kt_select2_3"
                                            onchange="filteryeargrafik()">
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
                                        <label for="">Kategori</label>
                                        <select name="chart_year" class="form-control" id="kt_select2_4"
                                            onchange="filteryearkategori()">
                                            <option value="All" selected>Semua</option>

                                            @foreach ($kategori as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                            </div>
                            <canvas class="row" height="100" id="chartperformasales">

                            </canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- END GRAFIK PERFORMA SALES --}}

        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid mt-10">


            <!--begin::Container-->
            <div class="container ">
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
                                    <h3 class="card-label">Data Penjualan Customer : <i>{{ $sales->nama }}</i></h3>



                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Tahun</label>
                                            <select name="chart_year" class="form-control" id="kt_select2_1"
                                                onchange="filtercustomeryear()">
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
                                            <select name="chart_year" class="form-control" id="kt_select2_11"
                                                onchange="filtercustomermonth()">
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
                                            <select name="chart_year" class="form-control" id="kt_select2_2"
                                                onchange="filtercustomerkategori()">
                                                <option value="All" selected>Semua</option>
                                                @foreach ($kategori as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Kategori Customer</label>
                                            <select name="chart_year" class="form-control" id="kt_select2_9"
                                                onchange="filtercustomercategorydata()">
                                                <option value="All" selected>Semua</option>
                                                @foreach ($categorycustomer as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                </div>
                                <table
                                    class="table table-separate table-head-custom table-checkable table  yajra-datatable collapsed ">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Customer</th>
                                            <th>Total Penjualan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- ======================================== PENJUALAN CUSTOMER =========================================        --}}

                    <div class="col-lg-12 mt-10">
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
                                    <h3 class="card-label">Data Penjualan Product : <i>{{ $sales->nama }}</i></h3>



                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Tahun</label>
                                            <select name="chart_year" class="form-control" id="kt_select2_5"
                                                onchange="filterproductyear()">
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
                                            <select name="chart_year" class="form-control" id="kt_select2_8"
                                                onchange="filterproductmonth()">
                                                <option value="All" selected>Semua</option>
                                                @foreach ($bulan as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Kategori</label>
                                            <select name="chart_year" class="form-control" id="kt_select2_7"
                                                onchange="filterproductcategori()">
                                                <option value="All" selected>Semua</option>
                                                @foreach ($kategori as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Merk</label>
                                            <select name="chart_year" class="form-control" id="kt_select2_12"
                                                onchange="filterproductmerk()">
                                                <option value="All" selected>Semua</option>
                                                @foreach ($merk as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }} - {{$item->id}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <table
                                    class="table table-separate table-head-custom table-checkable table  yajra-datatable2 collapsed ">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Product</th>
                                            <th>Total Penjualan</th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>        
    </div>

    @include('sales.performasales.detailperforma.modal.produk')
    
@endsection
@push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const idperformasales = document.getElementById('chartperformasales');
        const grafikcustomer = document.getElementById('performasalesCustomer');

        let year = {{ now()->format('Y') }};
        let month = {{ now()->format('m') }};
        let kategori = 'All';
        let kategoricustomer = 'All';


        // untuk customer
        let yearCustomer = {{ now()->format('Y') }};
        let kategoriCustomer = 'All';
        // end of customer

        let sales_id = {{ $sales_id }};
        let bulan = 'All';
        let customer_id = 'all';
        let merk = 'All';


        // untuk product 
        let bulanproduct = 'All';
        let yearProduct = {{ now()->format('Y') }};
        let kategoriProduct = 'All';
        // end of product

        $(document).ready(function() {
            grafikperformasalesdetail();
            datatableCustomer();
            datatableProduk();
            dataProduct();
        })

        let barPerformaSales = {
            type: 'bar',
            data: {
                labels: null,
                datasets: [{
                        label: 'Grafik Performa Sales',
                        data: null,
                        borderWidth: 1,
                        stack: 'combined',
                    },
                    {
                        label: 'Target Tertinggi Perbulan Sales',
                        data: null,
                        borderWidth: 4,
                        type: 'line',
                        color: '#36A2EB',
                        borderDash: [5, 5],
                    },

                ],

            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: (ctx) => 'Data Dalam Rupiah ',
                    }
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


        function grafikperformasalesdetail() {
            $.ajax({
                type: 'POST',
                url: '{{ route('performasales.dataperformasales.detailgrafik') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "_token": "{{ csrf_token() }}",
                    'year': year,
                    'kategori': kategori,
                    'id': sales_id
                },
                success: function(data) {

                    res = JSON.parse("[" + data + "]");
                    let bulan = res[0].bulan;
                    let dataPenjualan = res[0].laba;
                    let targetSales = res[0].targetsales;


                    barPerformaSales.data.labels = bulan;
                    barPerformaSales.data.datasets[0].data = dataPenjualan;
                    barPerformaSales.data.datasets[1].data = targetSales;

                    chartkategori = new Chart(idperformasales, barPerformaSales);

                },
                error: function(data) {
                    console.log(data);
                }
            });
        }


        function filteryeargrafik() {
            let e = document.getElementById("kt_select2_3");
            year = e.options[e.selectedIndex].value;

            $.ajax({
                type: 'POST',
                url: '{{ route('performasales.dataperformasales.detailgrafik') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "_token": "{{ csrf_token() }}",
                    'year': year,
                    'kategori': kategori,
                    'id': sales_id

                },
                success: function(data) {

                    res = JSON.parse("[" + data + "]");
                    let bulan = res[0].bulan;
                    let dataPenjualan = res[0].laba;
                    let targetSales = res[0].targetsales;

                    barPerformaSales.data.labels = bulan;
                    barPerformaSales.data.datasets[0].data = dataPenjualan;
                    barPerformaSales.data.datasets[1].data = targetSales;

                    chartkategori.destroy();
                    chartkategori = new Chart(idperformasales, barPerformaSales);
                    chartkategori.update();

                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function filteryearkategori() {
            let e = document.getElementById("kt_select2_4");
            kategori = e.options[e.selectedIndex].value;

            $.ajax({
                type: 'POST',
                url: '{{ route('performasales.dataperformasales.detailgrafik') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "_token": "{{ csrf_token() }}",
                    'year': year,
                    'kategori': kategori,
                    'id': sales_id

                },
                success: function(data) {

                    res = JSON.parse("[" + data + "]");
                    let bulan = res[0].bulan;
                    let dataPenjualan = res[0].laba;
                    let targetSales = res[0].targetsales;

                    barPerformaSales.data.labels = bulan;
                    barPerformaSales.data.datasets[0].data = dataPenjualan;
                    barPerformaSales.data.datasets[1].data = targetSales;
                    console.log(barPerformaSales.data);

                    chartkategori.destroy();
                    chartkategori = new Chart(idperformasales, barPerformaSales);
                    chartkategori.update();

                },
                error: function(data) {
                    console.log(data);
                }
            });
        }


        // ================================ GRAFIK TOP PENCAPAIAN CUSTOMER =====================

        function datatableCustomer() {
            var table = $('.yajra-datatable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "{{ route('performasales.dataperformasales.datatableCustomer') }}",
                    type: "POST",
                    data: function(params) {
                        params.year = yearCustomer,
                            params.kategori = kategoriCustomer,
                            params.sales_id = sales_id,
                            params.bulan = bulan,
                            params.kategoricustomer = kategoricustomer,
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

        function filtercustomeryear() {

            let e = document.getElementById("kt_select2_1");
            yearCustomer = e.options[e.selectedIndex].value;
            $('.yajra-datatable').DataTable().ajax.reload(null, false);


        }

        function filtercustomerkategori() {
            let e = document.getElementById("kt_select2_2");
            kategoriCustomer = e.options[e.selectedIndex].value;
            $('.yajra-datatable').DataTable().ajax.reload(null, false);


        }

        function filtercustomermonth() {

            let e = document.getElementById("kt_select2_11");
            bulan = e.options[e.selectedIndex].value;
            $('.yajra-datatable').DataTable().ajax.reload(null, false);


        }

        function filtercustomercategorydata() {

            let e = document.getElementById("kt_select2_9");
            kategoricustomer = e.options[e.selectedIndex].value;
            $('.yajra-datatable').DataTable().ajax.reload(null, false);

        }

        // ====================== MODAL PRODUK ==============================
        function showProduct(id) {
            $('#listproduk').modal('show');
            customer_id = id;
            $('.yajra-datatableproduct').DataTable().ajax.reload(null, false);
        }

        function datatableProduk() {
            var tablecustomer = $('.yajra-datatableproduct').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "{{ route('performasales.dataperformasales.datatableProduk') }}",
                    type: "POST",
                    data: function(params) {
                        params.year = yearCustomer,
                            params.bulan = bulan,
                            params.customer_id = customer_id,
                            params.kategori = kategoriCustomer,
                            params.sales_id = sales_id,
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


        // ======================================== DATATABLE PRODUCT ================================================

        function dataProduct() {
            var table = $('.yajra-datatable2').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "{{ route('performasales.dataperformasales.dataproduct') }}",
                    type: "POST",
                    data: function(params) {
                            params.year = yearProduct,
                            params.kategori = kategoriProduct,
                            params.sales_id = sales_id,
                            params.bulan = bulanproduct,
                            params.merk = merk,
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

        function filterproductyear() {

            let e = document.getElementById("kt_select2_5");
            yearProduct = e.options[e.selectedIndex].value;
            $('.yajra-datatable2').DataTable().ajax.reload(null, false);

        }

        function filterproductcategori() {
            let e = document.getElementById("kt_select2_7");
            kategoriProduct = e.options[e.selectedIndex].value;
            $('.yajra-datatable2').DataTable().ajax.reload(null, false);
        }

        function filterproductmonth() {

            let e = document.getElementById("kt_select2_8");
            bulanproduct = e.options[e.selectedIndex].value;
            $('.yajra-datatable2').DataTable().ajax.reload(null, false);

        }

        function filterproductmerk() {

            let e = document.getElementById("kt_select2_12");
            merk = e.options[e.selectedIndex].value;
            $('.yajra-datatable2').DataTable().ajax.reload(null, false);

        }

    </script>
@endpush
