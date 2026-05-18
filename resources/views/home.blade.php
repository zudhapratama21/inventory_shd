@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container">
            @include('partial.table.analisis')
        </div>
    </div>



    {{-- modal Customer --}}

    @php
        $userPermissions = $permission; // Mengambil daftar permission user
    @endphp
    
    <div id="modal-data"></div>
    @include('partial.modal.produk')
    @include('partial.modal.customer')
    @include('partial.modal.principle')
    @include('partial.modal.historyproduk')
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
        let customergrafik = 'All';
        let salesgrafik = 'All';        
        // ======================================= END ==============================================================

        // ================================== VARIABLE UNTUK BEST PRODUK ===========================================
        let salesProduk = 'All';        
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
        // let produk = {{ $produk }};
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
            chartyear();
            datatable();
            datatableCustomer();
            datatabletopcustomer();
            datatablelistproduct();

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
                    'customergrafik': customergrafik,                    
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
                    'customergrafik': customergrafik,                    
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
                            params.kategori = kategoriProduk,
                            params.sales = salesProduk,                            
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

        function showHistoryProduct(id) {
            $('#historyproduk').modal('show');
            customer_id = id;
            $('.yajra-datatablehistoryproduct').DataTable().ajax.reload(null, false);
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

    </script>
@endpush
