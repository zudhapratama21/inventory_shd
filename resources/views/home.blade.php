@extends('layouts.app')

@section('content')
    <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
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
                                        <div class="card-title" data-toggle="collapse" data-target="#collapseOne4">
                                            <i class="flaticon2-layers-1"></i> Pengumuman Terbaru
                                        </div>
                                    </div>
                                    <div id="collapseOne4" class="collapse show" data-parent="#accordionExample4">
                                        <div class="card-body">
                                            <h3>{{ $pengumuman->subject }}</h3>
                                            <p>Dibuat Oleh : {{ $pengumuman->pembuat->name }} || dibuat pada :
                                                {{ \Carbon\Carbon::parse($pengumuman->updated_at)->format('d F Y') }} </p>
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
                                            <label for="">Customer</label>
                                            <select name="chart_kategori" class="form-control" id="kt_select2_1"
                                                onchange="filtercustomergrafik()">
                                                <option value="All" selected>Semua</option>
                                                @foreach ($customer as $x)
                                                    <option value="{{ $x->id }}">{{ $x->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Sales</label>
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
                                            <label for="">Principle</label>
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
                                            <label for="">Merk</label>
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
                @endcan
              
                <!--end::Row-->
                @can('tabletopproduk-list')
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
                                            <label for="">Sales</label>
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
                                            <label for="">Merk</label>
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
                {{-- END OF TOP PRINCIPLE --}}
                @endcan    
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

        // =========================================================================================================================
        $(document).ready(function() {  
            // if (hasPermission('grafikpenjualan-list')) {
            //     chartyear();
            // }
           
            
            // if (hasPermission('grafikkategori-list')) {
            //     chart_kategori();
            // }
            // if (hasPermission('grafikproduk-list')) {
            //     chartProduk();
            // }
            // if (hasPermission('tabletopproduk-list')) {
            //     datatable();
            //     datatableCustomer();
            // }
            // if (hasPermission('tabletopcustomer-list')) {
            //     datatabletopcustomer();
            //     datatablelistproduct();
            // }           
            // if (hasPermission('tabletopprinciple-list')) {
            //     datatabletopPrinciple();
            //     datatableProductByPrinciple();
            // }                       

            const permissionActions = {
                'grafikpenjualan-list': [chartyear],
                'grafikkategori-list': [chart_kategori],
                'grafikproduk-list': [chartProduk],
                'tabletopproduk-list': [datatable, datatableCustomer],
                'tabletopcustomer-list': [datatabletopcustomer, datatablelistproduct],
                'tabletopprinciple-list': [datatabletopPrinciple, datatableProductByPrinciple],
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
            type: 'bar', data: { labels: null, datasets: [{ label: 'Penjualan', data: null, pointStyle: 'circle', pointRadius: 10, pointHoverRadius: 15, }] }, options: { responsive: true, plugins: { title: { display: true, text: (ctx) => 'Data Dalam Persen Rupiah ', }, legend: { labels: { font: { size: 11 } } } }, scales: { y: { stacked: true, ticks: { font: { size: 12, } } }, x: { ticks: { font: { size: 12, } } } } }, interaction: { intersect: false, }
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
            type: 'line', data: { labels: null, datasets: [{ label: 'Penjualan per Produk', data: null, pointStyle: 'circle', pointRadius: 10, pointHoverRadius: 15, }] }, options: { responsive: true, plugins: { title: { display: true, text: (ctx) => 'Data Dalam Persen Rupiah ', }, legend: { labels: { font: { size: 11 } } } }, scales: { y: { stacked: true, ticks: { font: { size: 12, } } }, x: { ticks: { font: { size: 12, } } } } }, interaction: { intersect: false, }
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
                    // headers: { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                    type: "POST",
                    data: function(params) {
                        // params.year = tahunProduk,
                        //     params.bulan = bulanProduk,
                        //     params.tipe = tipe,
                        //     params.kategori = kategoriProduk,
                        //     params.sales = salesProduk,
                        //     params.merk = merkProduk,
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
    </script>
@endpush
