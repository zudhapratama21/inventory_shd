@can('grafikpenjualan-list')
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

                <div class="card-toolbar">
                    <h6 class="badge badge-info">Total Penjualan : </h6>

                    <input type="text" class="form-control text-right" id="grandtotal" name="grandtotal"
                        value="0" readonly="readonly">
                </div>


                <!--end::Title-->
            </div>
            <!--end::Header-->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Tahun</label>
                            <select name="chart_year" class="form-control" id="grafik_tahun"
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
                            <select name="chart_kategori" class="form-control" id="grafik_kategori"
                                onchange="filterKategori()">
                                <option value="All" selected>Semua</option>
                                @foreach ($kategori as $x)
                                    <option value="{{ $x->id }}">{{ $x->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Customer</label> <br>
                            <select name="chart_kategori" class="form-control" id="kt_select2_1"
                                onchange="filtercustomergrafik()">
                                <option value="All" selected>Semua</option>
                                @foreach ($customer as $x)
                                    <option value="{{ $x->id }}">{{ $x->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Sales</label> <br>
                            <select name="chart_kategori" class="form-control" id="kt_select2_2"
                                onchange="filtersalesgrafik()">
                                <option value="All" selected>Semua</option>
                                @foreach ($sales as $x)
                                    <option value="{{ $x->id }}">{{ $x->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Principle</label> <br>
                            <select name="chart_kategori" class="form-control" id="kt_select2_4"
                                onchange="filterprinciplegrafik()">
                                <option value="All" selected>Semua</option>
                                @foreach ($supplier as $x)
                                    <option value="{{ $x->id }}">{{ $x->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Merk</label> <br>
                            <select name="chart_kategori" class="form-control" id="kt_select2_5"
                                onchange="filtermerkgrafik()">
                                <option value="All" selected>Semua</option>
                                @foreach ($merk as $x)
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
@endcan
@can('grafikkategori-list')
<div class="row">
    <div class="col-md-6">
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
                    <select name="chart_year" class="form-control" id="chart_kategori"
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
@endcan
<!--begin::Row-->

@can('grafikproduk-list')
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
                            <select name="chart_year" class="form-control" id="grafikproduk_tahun"
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
                            <label for="">Produk</label>  <br>
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
@endcan

<!--end::Row-->
@can('tabletopproduk-list')                      
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
                        <input type="hidden" name="kategori_product" value="all"
                            id="kategori_product">

                        {{-- <button type="submit" class="btn btn-success font-weight-bolder mr-4">
                            <i class="flaticon-download "></i>
                            Download Excel
                        </button> --}}
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
                            <select name="chart_year" class="form-control" id="produk_tahun"
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
                            <select name="chart_year" class="form-control" id="produk_tipe"
                                onchange="filtertypebestproduk()">
                                <option value="harga" selected>Harga</option>
                                <option value="stok">Stok</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Bulan</label>
                            <select name="chart_year" class="form-control" id="produk_bulan"
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
                            <select name="chart_year" class="form-control" id="produk_kategori"
                                onchange="filterkategoribestproduk()">
                                <option value="All" selected>Semua</option>
                                @foreach ($kategori as $x)
                                    <option value="{{ $x->id }}">{{ $x->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Sales</label> <br>
                            <select name="chart_year" class="form-control" id="kt_select2_7"
                                onchange="filtersalesbestproduk()">
                                <option value="All" selected>Semua</option>
                                @foreach ($sales as $x)
                                    <option value="{{ $x->id }}">{{ $x->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Merk</label> <br>
                            <select name="chart_year" class="form-control" id="kt_select2_8"
                                onchange="filtermerkbestproduk()">
                                <option value="All" selected>Semua</option>
                                @foreach ($merk as $x)
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
@endcan

@can('tabletopcustomer')
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
                        <input type="hidden" name="kategori_customer" value="all"
                            id="kategori_customer">

                        {{-- <button type="submit" class="btn btn-success font-weight-bolder mr-4">
                            <i class="flaticon-download "></i>
                            Download Excel
                        </button> --}}
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

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Sales</label>
                            <select name="chart_year" class="form-control" id="sales_customer"
                                onchange="filtersalestopcustomer()">
                                <option value="All" selected>Semua</option>
                                @foreach ($sales as $x)
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
@endcan                       
@can('tabletopprinciple')
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
                        <input type="hidden" name="kategori_customer" value="all"
                            id="kategori_customer">

                        {{-- <button type="submit" class="btn btn-success font-weight-bolder mr-4">
                            <i class="flaticon-download "></i>
                            Download Excel
                        </button> --}}
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

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Sales</label>
                            <select name="chart_year" class="form-control" id="sales_principle"
                                onchange="filtersalestopprinciple()">
                                <option value="All" selected>Semua</option>
                                @foreach ($sales as $x)
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
@endcan   