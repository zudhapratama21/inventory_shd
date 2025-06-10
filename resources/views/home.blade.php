@extends('layouts.app')

@section('content')
    <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-12  subheader-transparent " id="kt_subheader">
            <div class=" container  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <!--begin::Heading-->
                    <div class="d-flex">


                        <div class="card card-custom gutter-b">
                            <div class="card-body">
                                <ul class="nav nav-tabs nav-bold nav-tabs-line">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#kt_tab_pane_1_4">
                                            <span class="nav-icon"><i class="flaticon2-chat-1"></i></span>
                                            <span class="nav-text">Pekerjaan</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_2_4">
                                            <span class="nav-icon"><i class="flaticon2-drop"></i></span>
                                            <span class="nav-text">Analisis</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Heading-->
                </div>
            </div>
        </div>
        <!--end::Subheader-->

        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <div class="container ">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="kt_tab_pane_1_4" role="tabpanel"
                        aria-labelledby="kt_tab_pane_1_4">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="card card-custom gutter-b">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h3 class="card-label">
                                                Laman Pengumuman
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <!--begin::Accordion-->
                                        <div class="accordion  accordion-toggle-arrow" id="accordionExample4">
                                            <div class="card">
                                                <div class="card-header" id="headingOne4">
                                                    <div class="card-title" data-toggle="collapse"
                                                        data-target="#collapseOne4">
                                                        <i class="flaticon2-layers-1"></i> Pengumuman Terbaru
                                                    </div>
                                                </div>
                                                <div id="collapseOne4" class="collapse show"
                                                    data-parent="#accordionExample4">
                                                    <div class="card-body">
                                                        <h3>{{ $pengumuman->subject }}</h3>
                                                        <p>Dibuat Oleh : {{ $pengumuman->pembuat->name }} || dibuat pada :
                                                            {{ \Carbon\Carbon::parse($pengumuman->updated_at)->format('d F Y') }}
                                                        </p>
                                                        <hr>
                                                        <p>
                                                            {!! $pengumuman->description !!}
                                                        </p>
                                                        <a href="{{ asset('storage/pengumuman/' . $pengumuman->file) }}"
                                                            class="btn btn-primary btn-sm" download><i
                                                                class="fas fa-download"></i>Download File</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="headingTwo4">
                                                    <div class="card-title collapsed" data-toggle="collapse"
                                                        data-target="#collapseTwo4">
                                                        <i class="flaticon2-copy"></i> Pengumuman yang lain
                                                    </div>
                                                </div>
                                                <div id="collapseTwo4" class="collapse" data-parent="#accordionExample4">
                                                    <div class="card-body">
                                                        <table
                                                            class="table table-separate table-head-custom table-checkable table  yajra-datatable-pengumuman collapsed ">
                                                            <thead>
                                                                <tr>
                                                                    <th>Tanggal</th>
                                                                    <th>Topic</th>
                                                                    <th>Subject</th>
                                                                    <th>Download File</th>
                                                                    <th>Action</th>
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
                        </div>
                        @include('partial.table.keuangan')
                    </div>

                    <div class="tab-pane fade " id="kt_tab_pane_2_4" role="tabpanel" aria-labelledby="kt_tab_pane_2_4">
                        @include('partial.table.analisis')
                    </div>
                </div>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>

    {{-- modal Customer --}}

    @php
        $userPermissions = $permission; // Mengambil daftar permission user
    @endphp
    <div id="modal-data"></div>
    @include('partial.modal.produk')
    @include('partial.modal.customer')
    @include('partial.modal.principle')
@endsection

@push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/features/charts/apexcharts.js?v=7.0.6') }} "></script>
    <script src="{{ asset('assets/js/pages/features/miscellaneous/blockui.js?v=7.0.6') }} "></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
        integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
        integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');
        const chartKategori = document.getElementById('KategoriChart');
        const produk_chart = document.getElementById('produkChart');
        const best_produk = document.getElementById('chartbestproduk');

        let userPermissions = @json($userPermissions);
        // =================================== VARIABLE UNTUK GRAFIK PENJUALAN =====================================
        let principlegrafik = 'All';
        let customergrafik = 'All';
        let salesgrafik = 'All';
        let merkgrafik = 'All';
        // ======================================= END ==============================================================

        // ================================== VARIABLE UNTUK BEST PRODUK ===========================================
        let salesProduk = 'All';
        let merkProduk = 'All';
        let tahunProduk = {{ now()->format('Y') }};
        let tipe = 'harga';
        let kategoriProduk = 'All';
        let bulanProduk = 'All';

        // ================================================== END ===================================================
        let year = {{ now()->format('Y') }};
        let kategori = 'All';
        let dataRange = null;
        let bulan = 'All';
        let dataBulan = null;
        let chart = null;
        let produk = {{ $produk[0]->id }};
        let product_id = null;
        let customer_id = null;
        let supplier_id = null;
        // var bulan = @json($bulan);

        // variable top customer 
        let topcustomeryear = {{ now()->format('Y') }};
        let topcustomerbulan = 'All';
        let topcustomerkategori = 'All';
        let salescustomer = 'All';

        // ================================== END ===============================
        // variable top principle
        let topprincipleyear = {{ now()->format('Y') }};
        let topprinciplebulan = 'All';
        let topprinciplekategori = 'All';
        let sales_principle = 'All';

        // ================================  TAHUN ==============================
        let tahunrekaphutang = {{ now()->format('Y') }};
        let tahunrekappiutang = {{ now()->format('Y') }};

        let tahunlabarugi = {{ now()->format('Y') }};

        // =========================================================================================================================
        $(document).ready(function() {            
            const permissionActions = {
                'grafikpenjualan-list': [chartyear],
                'grafikkategori-list': [chart_kategori],
                'grafikproduk-list': [chartProduk],
                'tabletopproduk-list': [datatable, datatableCustomer],
                'tabletopcustomer-list': [datatabletopcustomer, datatablelistproduct],
                'tabletopprinciple-list': [datatabletopPrinciple, datatableProductByPrinciple],
                'datapengiriman-list': [datatablepesanan],
                'datapenerimaan-list': [datatablepembelian],
                'datahutang-list': [datatablehutang],
                'datapiutang-list': [datatablepiutang],
                'rekaphutang-list': [datahutang],
                'rekappiutang-list': [datapiutang],
                'rekaplabarugi-list' : [rekaplabarugi]
            };

            Object.entries(permissionActions).forEach(([permission, actions]) => {
                if (hasPermission(permission)) {
                    actions.forEach(action => action());
                }
            });

            datatablepengumuman();
        })
        // ============================================================================================================================

        // ==================================================================== CHART UNTUK GRAFIK BAR PENJUALAN =======================================
        let options = {
            type: 'bar',
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

        // ==================================================================== CHART UNTUK GRAFIK BAR PENJUALAN =======================================

        function hasPermission(permission) {
            let dataPermission = userPermissions.map(p => p.name);
            return dataPermission.includes(permission);
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
                    'principlegrafik': principlegrafik,
                    'customergrafik': customergrafik,
                    'merkgrafik': merkgrafik,
                    'salesgrafik': salesgrafik,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    res = JSON.parse("[" + data + "]");
                    dataLaba = res[0].laba;
                    dataBulan = res[0].bulan;
                    let grandtotalpenjualan = res[0].total_penjualan;

                    hitungtotalgrafik(grandtotalpenjualan);

                    options.data.labels = dataBulan;
                    options.data.datasets[0].data = dataLaba;
                    chart = new Chart(ctx, options);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function chartGrafikUpdate() {
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
                    'principlegrafik': principlegrafik,
                    'customergrafik': customergrafik,
                    'merkgrafik': merkgrafik,
                    'salesgrafik': salesgrafik,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    res = JSON.parse("[" + data + "]");
                    dataLaba = res[0].laba;
                    dataBulan = res[0].bulan;

                    let grandtotalpenjualan = res[0].total_penjualan;

                    hitungtotalgrafik(grandtotalpenjualan);

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


        function hitungtotalgrafik(data) {
            $('#grandtotal').val(data);
        }

        function filterYear() {
            let e = document.getElementById("grafik_tahun");
            year = e.options[e.selectedIndex].value;
            chartGrafikUpdate();
        }

        function filterKategori() {
            let e = document.getElementById("grafik_kategori");
            kategori = e.options[e.selectedIndex].value;
            chartGrafikUpdate();
        }

        function filterprinciplegrafik() {
            let e = document.getElementById("kt_select2_4");
            principlegrafik = e.options[e.selectedIndex].value;
            chartGrafikUpdate();
        }

        function filtercustomergrafik() {
            let e = document.getElementById("kt_select2_1");
            customergrafik = e.options[e.selectedIndex].value;
            chartGrafikUpdate();
        }

        function filtermerkgrafik() {
            let e = document.getElementById("kt_select2_5");
            merkgrafik = e.options[e.selectedIndex].value;
            chartGrafikUpdate();
        }

        function filtersalesgrafik() {
            let e = document.getElementById("kt_select2_2");
            salesgrafik = e.options[e.selectedIndex].value;
            chartGrafikUpdate();
        }


        //========================================================= end of Chart Penjualan Bar =====================================================

        //=========================================================CHART UNTUK DOUGNOT  =====================================================
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
            let e = document.getElementById("chart_kategori");
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

        //========================================================= CHART UNTUK DOUGNOT  ===================================================== 

        //========================================================= CHART UNTUK FORECAST PRODUK  ===================================================== 
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

        function chartProdukUpdate() {
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

        function filteryearproduk() {
            let e = document.getElementById("grafikproduk_tahun");
            year = e.options[e.selectedIndex].value;
            chartProdukUpdate();
        }

        function filterProduk() {
            let e = document.getElementById("kt_select2_3");
            produk = e.options[e.selectedIndex].value;
            chartProdukUpdate();
        }

        //========================================================= END OF  CHART UNTUK FORECAST PRODUK  ===================================================== 


        // ============================================================== DATATABLE PRODUK TERBAIK ============================================================
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
                        params.year = tahunProduk,
                            params.bulan = bulanProduk,
                            params.tipe = tipe,
                            params.kategori = kategoriProduk,
                            params.sales = salesProduk,
                            params.merk = merkProduk,
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
                columnDefs: [{
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

        function filteryearbestproduk() {
            let e = document.getElementById("produk_tahun");

            tahunProduk = e.options[e.selectedIndex].value;
            $('#tahun').val(tahunProduk);
            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filterbulanbestproduk() {
            let e = document.getElementById("produk_bulan");
            bulanProduk = e.options[e.selectedIndex].value;
            $('#bulan_product').val(bulanProduk);
            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filtertypebestproduk() {
            let e = document.getElementById("produk_tipe");
            tipe = e.options[e.selectedIndex].value;
            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filterkategoribestproduk() {
            let e = document.getElementById("produk_kategori");
            kategoriProduk = e.options[e.selectedIndex].value;
            $('#kategori_product').val(kategoriProduk);
            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filtersalesbestproduk() {
            let e = document.getElementById("kt_select2_7");
            salesProduk = e.options[e.selectedIndex].value;
            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filtermerkbestproduk() {
            let e = document.getElementById("kt_select2_8");
            merkProduk = e.options[e.selectedIndex].value;
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
                        params.bulan = bulanProduk,
                            params.year = tahunProduk,
                            params.tipe = tipe,
                            params.kategori = kategoriProduk,
                            params.sales = salesProduk,
                            params.merk = merkProduk,
                            params.product_id = product_id,
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
                columnDefs: [{
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


        // ===================================================== END OF BEST PRODUK ===========================================================================




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
                            params.sales = salescustomer,
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
                columnDefs: [{
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
                    url: "{{ route('datatable.topCustomerProduct') }}",
                    type: "POST",
                    data: function(params) {
                        params.year = topcustomeryear,
                            params.bulan = topcustomerbulan,
                            params.customer = customer_id,
                            params.kategori = topcustomerkategori,
                            params.sales = salescustomer,
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
                        data: 'nama_merk',
                        name: 'nama_merk'
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
                columnDefs: [{
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

        function filtersalestopcustomer() {
            let e = document.getElementById("sales_customer");
            salescustomer = e.options[e.selectedIndex].value;
            $('#kategori_customer').val(salescustomer);
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
                            params.sales = sales_principle,
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
                columnDefs: [{
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


        function filtersalestopprinciple() {
            let e = document.getElementById("sales_principle");
            sales_principle = e.options[e.selectedIndex].value;
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
                            params.sales = sales_principle,
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
                        data: 'nama_merek',
                        name: 'nama_merek'
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
                columnDefs: [{
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

        function datatablepengumuman() {
            var table = $('.yajra-datatable-pengumuman').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "{{ route('pengumuman.homedatatable') }}",
                    type: "POST",
                    data: function(params) {
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
                        data: 'topic',
                        name: 'topic'
                    },
                    {
                        data: 'subject',
                        name: 'subject'
                    },
                    {
                        data: 'file',
                        name: 'file'
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
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    },
                ],
            });
        }

        function showpengumuman(id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('pengumuman.show') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id': id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    $('#modal-data').html(data);
                    $('#pengumumanshow').modal('show');
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function initializeDataTable(selector, url, columns) {
            return $(selector).DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: url,
                    type: "POST",
                    data: function(params) {
                        params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: columns,
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    }
                ]
            });
        }

        function renderActionButton(urlBase, data) {
            return `<a href="${urlBase}/${data}/create" class="btn btn-success btn-sm">
                        <i class="flaticon2-check-mark"></i> Pilih
                    </a>`;
        }

        function renderActionButton(urlBase, data) {
            return `<a href="${urlBase}/${data}/create" class="btn btn-success btn-sm">
                        <i class="flaticon2-check-mark"></i> Pilih
                    </a>`;
        }

        function renderAgeBadge(data) {
            var badgeClass = data > 0 ? 'badge-info' : 'badge-danger';
            return `<span class="badge ${badgeClass}">${data} Hari</span>`;
        }

        function datatablepesanan() {
            initializeDataTable('.yajra-datatable-pengiriman', "{{ route('home.pengiriman') }}", [{
                    data: 'kode',
                    name: 'kode'
                },
                {
                    data: 'tanggal',
                    name: 'tanggal'
                },
                {
                    data: 'customer',
                    name: 'customers.nama'
                },
                {
                    data: 'umur',
                    searchable: false,
                    render: renderAgeBadge
                },
                {
                    data: 'status',
                    name: 'StatusSo.nama',
                    searchable: false
                },
                {
                    data: 'keterangan_internal',
                    name: 'keterangan_internal'
                },
                {
                    data: 'action',
                    render: data => renderActionButton('penjualan/pengirimanbarang', data),
                    className: "nowrap"
                }
            ]);
        }

        function datatablepembelian() {
            initializeDataTable('.yajra-datatable-pembelian', "{{ route('home.penerimaan') }}", [{
                    data: 'kode',
                    name: 'kode'
                },
                {
                    data: 'tanggal',
                    name: 'tanggal'
                },
                {
                    data: 'supplier',
                    name: 'suppliers.nama'
                },
                {
                    data: 'umur',
                    searchable: false,
                    render: renderAgeBadge
                },
                {
                    data: 'status',
                    name: 'statusPO.nama'
                },
                {
                    data: 'keterangan_internal',
                    name: 'keterangan_internal'
                },
                {
                    data: 'action',
                    render: data => renderActionButton('pembelian/penerimaanbarang', data),
                    className: "nowrap"
                }
            ]);
        }

        function datatablehutang() {
            initializeDataTable('.yajra-datatable-hutang', "{{ route('home.hutang') }}", [{
                    data: 'tanggal_top',
                    searchable: false,
                    name: 'tanggal_top'
                },
                {
                    data: 'nama_supplier',
                    name: 'suppliers.nama'
                },
                {
                    data: 'kode_faktur',
                    name: 'FakturPO.kode'
                },
                {
                    data: 'no_faktur_supplier',
                    name: 'FakturPO.no_faktur_supplier'
                },
                {
                    data: 'total',
                    searchable: false,
                    name: 'total'
                },
                {
                    data: 'dibayar',
                    searchable: false,
                    name: 'dibayar'
                },
                {
                    data: 'sisa',
                    searchable: false,
                    name: 'sisa'
                },
                {
                    data: 'umur',
                    searchable: false,
                    render: renderAgeBadge
                },
                {
                    data: 'status',
                    render: function(data) {
                        if (data == 1) {
                            return '<span class="badge badge-info">Belum Jatuh Tempo</span>';
                        } else {
                            return '<span class="badge badge-danger">Sudah Jatuh Tempo</span>';
                        }
                    }
                },
                {
                    data: 'action',
                    render: data => renderActionButton('pembayaran/pembayaranhutang', data),
                    className: "nowrap"
                }
            ]);
        }

        function datatablepiutang() {
            initializeDataTable('.yajra-datatable-piutang', "{{ route('home.piutang') }}", [{
                    data: 'tanggal_top',
                    searchable: false,
                    render: function(data, type, row) {
                        // row.id berisi id, row.tanggal_top berisi tanggal
                        // Contoh: return tanggal dan id
                        return `${data} <span class="btn btn-icon btn-outline-success btn-circle btn-sm" onclick=ubahtanggal(${row.id})><i class="flaticon-browser"></i></span>`;
                    }
                },
                {
                    data: 'no_kpa',
                    name: 'fakturpenjualan.no_kpa'
                },
                {
                    data: 'customer',
                    name: 'customers.nama'
                },
                {
                    data: 'total',
                    searchable: false,
                    name: 'total'
                },
                {
                    data: 'dibayar',
                    searchable: false,
                    name: 'dibayar'
                },
                {
                    data: 'sisa',
                    searchable: false,
                    name: 'sisa'
                },
                {
                    data: 'umur',
                    searchable: false,
                    render: renderAgeBadge
                },
                {
                    data: 'status',
                    render: function(data) {
                        if (data == 1) {
                            return '<span class="badge badge-info">Belum Jatuh Tempo</span>';
                        } else {
                            return '<span class="badge badge-danger">Sudah Jatuh Tempo</span>';
                        }
                    }
                },
                {
                    data: 'sales',
                    name: 'sales'
                },
                {
                    data: 'action',
                    render: data => renderActionButton('pembayaran/pembayaranpiutang', data),
                    className: "nowrap"
                }
            ]);
        }

        function datahutang() {
            $.ajax({
                type: 'POST',
                url: '{{ route('home.rekaphutang') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'tahun': tahunrekaphutang,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    let response = JSON.parse(data);
                    $('#hutang-lunas').html(response.total_lunas);
                    $('#hutang-belum-lunas').html(response.total_belum_lunas);
                    $('#hutang-jatuh-tempo').html(response.total_jatuh_tempo);
                    $('#hutang-belum-jatuh-tempo').html(response.total_belum_jatuh_tempo);

                    $('#totalhutangtahunan').html(response.hutangtotaltahunan);
                    $('#totalhutangseluruh').html(response.hutangtotal);
                    // Update the width of the progress bar based on the percentage values
                    $('#progress-hutang-lunas').css('width', response.persenlunas + '%');
                    $('#progress-hutang-belum-lunas').css('width', response.persenbelumlunas + '%');
                    $('#progress-jatuh-tempo').css('width', response.persenjatuhtempo + '%');
                    $('#progress-belum-jatuh-tempo').css('width', response.persenbelumjatuhtempo + '%');
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }



        function datapiutang(params) {
            $.ajax({
                type: 'POST',
                url: '{{ route('home.rekappiutang') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'tahun': tahunrekappiutang,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    let response = JSON.parse(data);
                    $('#piutang-lunas').html(response.total_lunas);
                    $('#piutang-belum-lunas').html(response.total_belum_lunas);
                    $('#piutang-jatuh-tempo').html(response.total_jatuh_tempo);
                    $('#piutang-belum-jatuh-tempo').html(response.total_belum_jatuh_tempo);

                    $('#totalpiutangtahunan').html(response.piutangtotaltahunan);
                    $('#totalpiutangseluruh').html(response.piutangtotal);

                    // Update the width of the progress bar based on the percentage values
                    $('#progress-piutang-lunas').css('width', response.persenlunas + '%');
                    $('#progress-piutang-belum-lunas').css('width', response.persenbelumlunas + '%');
                    $('#progress-piutang-jatuh-tempo').css('width', response.persenjatuhtempo + '%');
                    $('#progress-piutang-belum-jatuh-tempo').css('width', response.persenbelumjatuhtempo + '%');
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function filterhutang() {
            let e = document.getElementById("rekaphutang");
            tahunrekaphutang = e.options[e.selectedIndex].value;
            datahutang();
        }

        function filterpiutang() {
            let e = document.getElementById("rekappiutang");
            tahunrekappiutang = e.options[e.selectedIndex].value;
            datapiutang();
        }


        function ubahtanggal(id) {

            $.ajax({
                type: 'POST',
                url: '{{ route('home.ubahtanggal') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    KTApp.blockPage();
                },
                data: {
                    'id': id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    $('#modal-data').html(data);
                    $('#ubahtanggal').modal('show');
                },
                complete: function() {
                    KTApp.unblock();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function simpantanggal(id) {
            let tanggaljatuhtempo = $('#tanggaljatuhtempo').val();
            $.ajax({
                type: 'POST',
                url: '{{ route('home.simpantanggal') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id': id,
                    'tanggaljatuhtempo': tanggaljatuhtempo,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    $('.yajra-datatable-piutang').DataTable().ajax.reload(null, false);
                    iziToast.success({
                        title: 'Success',
                        message: 'Data Berhasil Diubah',
                        position: 'topRight',
                    });
                    $('#ubahtanggal').modal('hide');

                },
                error: function(data) {
                    console.log(data);
                }
            });

        }

        function rekaplabarugi() {
            $.ajax({
                type: 'POST',
                url: '{{ route('home.rekaplabarugi') }}',
                dataType: 'html',
                beforeSend: function() {
                    KTApp.blockPage();
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'tahun': tahunlabarugi,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    let response = JSON.parse(data);
                    $('#laba_penjualan').html(response.grand_total_penjualan_bersih);
                    $('#beban_operasional').html(response.grand_total_pengeluaran);
                    $('#total_keuntungan').html(response.total_keuntungan);
                    $('#beban_persediaan').html(response.total_stok);
                },
                complete: function() {
                    KTApp.unblock();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function filtertahunlabarugi() {
            let e = document.getElementById("tahunrekaplabarugi");
            tahunlabarugi = e.options[e.selectedIndex].value;
            rekaplabarugi();
        }
    </script>
@endpush
