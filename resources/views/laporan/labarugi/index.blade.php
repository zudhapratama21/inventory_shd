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
                                    <h3 class="card-label">Grafik Laba Rugi</h3>
                                </div>
                                <div class="card-toolbar">
                                    <label for="">Total Profit</label>
                                    <input type="text" value="" id="grandtotal" class="form-control" readonly>
                                </div>
                                <div class="card-toolbar">
                                    <a href="{{ route('laporanlabarugi.filter') }}" class="btn btn-primary"><i
                                            class="fas fa-download"></i> Download Laporan</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Tahun</label>
                                            <select name="chart_year" class="form-control" id="tahunLabaRugi"
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
                                            <select id="kt_select2_2" name="customer_id" class="form-control"
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
                                            <select id="kt_select2_5" name="customer_id" class="form-control"
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
                                            <select id="salesLabaRugi" name="customer_id" class="form-control"
                                                onchange="filterSales()">
                                                <option value="All">Semua</option>
                                                @foreach ($sales as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Customer</label>
                                            <select id="kt_select2_3" name="customer_id" class="form-control"
                                                onchange="filterCustomer()">
                                                <option value="All">Semua</option>
                                                @foreach ($customer as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }} -
                                                        {{ $item->namakota->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                </div>

                                <!--begin::Chart-->
                                {{-- <div id="penjualanchart"></div> --}}
                                <div>
                                    <div class="example-preview" id="kt_blockui_content">
                                        <canvas id="chartprinciple" height="100"></canvas>
                                    </div>

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
                                    <h3 class="card-label">Top Profit Customer</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Tahun</label>
                                            <select name="chart_year" class="form-control"
                                                onchange="filterYearCustomer()" id="yearcustomer">
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
                                            <select name="customer_id" class="form-control" id="bulancustomer"
                                                onchange="filterBulanCustomer()">
                                                <option value="All">Semua</option>
                                                @foreach ($months as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Kategori Pesanan</label>
                                            <select name="customer_id" class="form-control" id="kategoricustomer"
                                                onchange="filterKategoriCustomer()">
                                                <option value="All">Semua</option>
                                                @foreach ($kategori as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Sales</label>
                                            <select name="customer_id" class="form-control" id="salescustomer"
                                                onchange="filterSalesCustomer()">
                                                <option value="All">Semua</option>
                                                @foreach ($sales as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <!--begin: Datatable-->
                                <table class="table yajra-datatable collapsed ">
                                    <thead class="datatable-head">
                                        <tr>
                                            <th>Nama</th>
                                            <th>Laba Kotor</th>
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
                                    <h3 class="card-label">Top Profit Principle</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Tahun</label>
                                            <select name="chart_year" class="form-control"
                                                onchange="filteryearprinciple()" id="yearprinciple">
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
                                            <select name="principle_id" class="form-control" id="bulanprinciple"
                                                onchange="filterbulanprinciple()">
                                                <option value="All">Semua</option>
                                                @foreach ($months as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Kategori Pesanan</label>
                                            <select name="principle_id" class="form-control" id="kategoriprinciple"
                                                onchange="filterkategoriprinciple()">
                                                <option value="All">Semua</option>
                                                @foreach ($kategori as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Sales</label>
                                            <select name="customer_id" class="form-control" id="salesprinciple"
                                                onchange="filtersalesprinciple()">
                                                <option value="All">Semua</option>
                                                @foreach ($sales as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <!--begin: Datatable-->
                                <table class="table yajra-datatableprinciple collapsed ">
                                    <thead class="datatable-head">
                                        <tr>
                                            <th>Nama</th>
                                            <th>Laba Kotor</th>
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
                                    <h3 class="card-label">Top Profit Product</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Tahun</label>
                                            <select name="chart_year" class="form-control" onchange="filteryearproduct()"
                                                id="yearproduct">
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
                                            <select name="product_id" class="form-control" id="bulanproduct"
                                                onchange="filterbulanproduct()">
                                                <option value="All">Semua</option>
                                                @foreach ($months as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Kategori Pesanan</label>
                                            <select name="product_id" class="form-control" id="kategoriproduct"
                                                onchange="filterkategoriproduct()">
                                                <option value="All">Semua</option>
                                                @foreach ($kategori as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Sales</label>
                                            <select name="customer_id" class="form-control" id="salesproduct"
                                                onchange="filtersalesproduct()">
                                                <option value="All">Semua</option>
                                                @foreach ($sales as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Merk</label>
                                            <select name="customer_id" class="form-control" id="kt_select2_4"
                                                onchange="filtermerkproduct()">
                                                <option value="All">Semua</option>
                                                @foreach ($merk as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Principle</label>
                                            <select name="customer_id" class="form-control" id="kt_select2_7"
                                                onchange="filterprincipleproduct()">
                                                <option value="All">Semua</option>
                                                @foreach ($supplier as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <!--begin: Datatable-->
                                <div class="example-preview" id="block_produk">
                                    <table class="table yajra-datatableproduct collapsed ">
                                        <thead class="datatable-head">
                                            <tr>
                                                <th>Nama</th>
                                                <th>Merk</th>
                                                <th>Qty</th>
                                                <th>Laba Kotor</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <!--end: Datatable-->

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
                                    <h3 class="card-label">Total CN</h3>
                                </div>
                                <div class="card-toolbar">
                                    <label for="">Total Omset</label>
                                    <input type="text" value="" id="totalomset" class="form-control" readonly>
                                </div>

                                <div class="card-toolbar">
                                    <label for="">Total Laba Kotor</label>
                                    <input type="text" value="" id="totallabakotor" class="form-control"
                                        readonly>
                                </div>

                                <div class="card-toolbar">
                                    <label for="">Total CN</label>
                                    <input type="text" value="" id="totalcn" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Tahun</label>
                                            <select name="chart_year" class="form-control" onchange="filteryearcn()"
                                                id="yearcn">
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
                                            <select name="cn_id" class="form-control" id="bulancn"
                                                onchange="filterbulancn()">
                                                <option value="All">Semua</option>
                                                @foreach ($months as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Kategori Pesanan</label>
                                            <select name="cn_id" class="form-control" id="kategoricn"
                                                onchange="filterkategoricn()">
                                                <option value="All">Semua</option>
                                                @foreach ($kategori as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Sales</label>
                                            <select name="customer_id" class="form-control" id="salescn"
                                                onchange="filtersalescn()">
                                                <option value="All">Semua</option>
                                                @foreach ($sales as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!--begin: Datatable-->
                                <table class="table yajra-datatablecn collapsed ">
                                    <thead class="datatable-head">
                                        <tr>
                                            <th>Nama</th>
                                            <th>Omset</th>
                                            <th>Laba Kotor</th>
                                            <th>Total CN</th>
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
    @include('laporan.labarugi.partial.modalcustomer')
    @include('laporan.labarugi.partial.modalprinciple')
    @include('laporan.labarugi.partial.modalproduct')
    @include('laporan.labarugi.partial.modalcustomerreview')
@endsection
@push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
    <!--begin::Page Scripts(used by this page)-->
    <script src="{{ asset('assets/js/pages/features/miscellaneous/blockui.js?v=7.0.6') }} "></script>
    <!--end::Page Scripts-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script type="text/javascript">
        const ctx = document.getElementById('chartprinciple');


        // =================================== TOP CUSTOMER =======================================
        let yearCustomer = {{ now()->format('Y') }};
        let bulanCustomer = 'All';
        let salesCustomer = 'All';
        let kategoriCustomer = 'All';
        let customer_id = null;

        // =======================================================================================  

        // ========================================== TOP PRINCIPLE =============================
        let yearPrinciple = {{ now()->format('Y') }};
        let bulanPrinciple = 'All';
        let salesPrinciple = 'All';
        let kategoriPrinciple = 'All';
        let principle_id = null;

        // ============================================== END OF PRINCIPLE =====================================

        // =================================================== VARIABLE CHART =========================================
        let yearLabaRugi = {{ now()->format('Y') }};
        let principleLabaRugi = 'All';
        let merkLabaRugi = 'All';
        let salesLabaRugi = 'All';
        let customerLabaRugi = 'All';


        // ================================================= TOP PRODUCT ========================================
        let yearproduct = {{ now()->format('Y') }};
        let bulanproduct = 'All';
        let salesproduct = 'All';
        let kategoriproduct = 'All';
        let merkproduct = 'All';
        let principleproduct = 'All';
        let product_id = null;

        // =================================================== END OF PRODUCT ===================================

        // =============================================== CN ===================================================
        let yearcn = {{ now()->format('Y') }};
        let bulancn = 'All';
        let salescn = 'All';
        let kategoricn = 'All';
        // ======================================================================================================

        // ============================ TEMPLATE GRAFIK =====================================
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


        $(function() {
            datatable();
            labarugichart();
            datatableCustomerProduct();
            datatableprinciple();
            datatableprincipleperproduct();
            datatableproduct();
            datatableproductpercustomer();
            datatableCustomerProductReview();
            datatablecn();
            totalcn();
        });

        function labarugichart() {
            $.ajax({
                type: 'POST',
                url: '{{ route('laporanlabarugi.chartprinciple') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'year': yearLabaRugi,
                    'principle': principleLabaRugi,
                    'merk': merkLabaRugi,
                    'sales': salesLabaRugi,
                    'customer': customerLabaRugi,
                    "_token": "{{ csrf_token() }}"
                },

                success: function(data) {
                    res = JSON.parse("[" + data + "]");
                    dataLaba = res[0].laba;
                    dataBulan = res[0].bulan;
                    let grandtotalpenjualan = res[0].grandtotal;

                    options.data.labels = dataBulan;
                    options.data.datasets[0].data = dataLaba;
                    chart = new Chart(ctx, options);

                    hitungtotalgrafik(grandtotalpenjualan)
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function chartLabaRugiUpdate() {
            $.ajax({
                type: 'POST',
                url: '{{ route('laporanlabarugi.chartprinciple') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'year': yearLabaRugi,
                    'principle': principleLabaRugi,
                    'merk': merkLabaRugi,
                    'sales': salesLabaRugi,
                    'customer': customerLabaRugi,
                    "_token": "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    KTApp.block('#kt_blockui_content', {
                        overlayColor: '#000000',
                        state: 'primary',
                        message: 'Processing...'
                    });
                },
                success: function(data) {
                    res = JSON.parse("[" + data + "]");
                    dataLaba = res[0].laba;
                    dataBulan = res[0].bulan;
                    let grandtotalpenjualan = res[0].grandtotal;
                    chart.destroy();
                    options.data.labels = dataBulan;
                    options.data.datasets[0].data = dataLaba;
                    chart = new Chart(ctx, options);
                    chart.update();

                    hitungtotalgrafik(grandtotalpenjualan)
                },
                error: function(data) {
                    console.log(data);
                },
                // Menyembunyikan blok UI menggunakan KTApp.unblock setelah request selesai
                complete: function() {
                    KTApp.unblock('#kt_blockui_content');
                }
            });
        }



        function htmlDecode(data) {
            var txt = document.createElement('textarea');
            txt.innerHTML = data;
            return txt.value;
        }

        function hitungtotalgrafik(data) {
            $('#grandtotal').val(data);
        }

        function filterYear() {
            let e = document.getElementById("tahunLabaRugi");
            yearLabaRugi = e.options[e.selectedIndex].value;
            chartLabaRugiUpdate();
        }

        function filterprinciple() {
            let e = document.getElementById("kt_select2_2");
            principleLabaRugi = e.options[e.selectedIndex].value;
            chartLabaRugiUpdate();

        }

        function filtermerk() {
            let e = document.getElementById("kt_select2_5");
            merkLabaRugi = e.options[e.selectedIndex].value;
            chartLabaRugiUpdate();

        }

        function filterSales() {
            let e = document.getElementById("salesLabaRugi");
            salesLabaRugi = e.options[e.selectedIndex].value;
            chartLabaRugiUpdate();

        }

        function filterCustomer() {
            let e = document.getElementById("kt_select2_3");
            customerLabaRugi = e.options[e.selectedIndex].value;
            chartLabaRugiUpdate();

        }


        // ==========================================  END OF CHART ========================================================

        function datatable() {
            var table = $('.yajra-datatable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    type: 'POST',
                    url: "{{ route('laporanlabarugi.datatable') }}",
                    data: function(params) {
                        params.year = yearCustomer,
                            params.bulan = bulanCustomer,
                            params.sales = salesCustomer,
                            params.kategori = kategoriCustomer,
                            params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [{
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'laba_kotor',
                        name: 'laba_kotor'
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
                        responsivePriority: 3,
                        targets: 1,

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

        function htmlDecode(data) {
            var txt = document.createElement('textarea');
            txt.innerHTML = data;
            return txt.value;
        }

        function filterYearCustomer() {
            let e = document.getElementById("yearcustomer");
            yearCustomer = e.options[e.selectedIndex].value;
            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filterBulanCustomer() {
            let e = document.getElementById("bulancustomer");
            bulanCustomer = e.options[e.selectedIndex].value;
            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filterKategoriCustomer() {
            let e = document.getElementById("kategoricustomer");
            kategoriCustomer = e.options[e.selectedIndex].value;
            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function filterSalesCustomer() {
            let e = document.getElementById("salescustomer");
            salesCustomer = e.options[e.selectedIndex].value;
            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function showCustomer(id) {
            $('#listproduk').modal('show');
            customer_id = id;
            $('.yajra-datatabletopproduct').DataTable().ajax.reload(null, false);
        }

        function showCustomerReview(id) {
            $('#listprodukreview').modal('show');
            customer_id = id;
            $('.yajra-datatabletopproductreview').DataTable().ajax.reload(null, false);
        }

        function datatableCustomerProduct() {
            var tablecustomer = $('.yajra-datatabletopproduct').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                order: [],
                ajax: {
                    url: "{{ route('laporanlabarugi.datatablecustomerproduct') }}",
                    type: "POST",
                    data: function(params) {
                        params.year = yearCustomer,
                            params.bulan = bulanCustomer,
                            params.sales = salesCustomer,
                            params.kategori = kategoriCustomer,
                            params.customer_id = customer_id,
                            params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [
                    //   {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {
                        data: 'no_kpa',
                        name: 'no_kpa'
                    },
                    {
                        data: 'products',
                        name: 'products'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'hargajual',
                        name: 'hargajual'
                    },
                    {
                        data: 'diskon_persen',
                        name: 'diskon_persen'
                    },
                    {
                        data: 'diskon_rp',
                        name: 'diskon_rp'
                    },
                    {
                        data: 'total_diskon',
                        name: 'total_diskon'
                    },
                    {
                        data: 'subtotal',
                        name: 'subtotal'
                    },
                    {
                        data: 'pph',
                        name: 'pph'
                    },
                    {
                        data: 'cn_persen',
                        name: 'cn_persen'
                    },
                    {
                        data: 'cn_rupiah',
                        name: 'cn_rupiah'
                    },
                    {
                        data: 'nett',
                        name: 'nett'
                    },
                    {
                        data: 'harga_beli',
                        name: 'harga_beli'
                    },
                    {
                        data: 'diskon_beli_persen',
                        name: 'diskon_beli_persen'
                    },
                    {
                        data: 'diskon_beli_rupiah',
                        name: 'diskon_beli_rupiah'
                    },
                    {
                        data: 'total_diskon_beli',
                        name: 'total_diskon_beli'
                    },
                    {
                        data: 'ppn_beli',
                        name: 'ppn_beli'
                    },
                    {
                        data: 'hpp',
                        name: 'hpp'
                    },
                    {
                        data: 'laba_kotor',
                        name: 'laba_kotor'
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

        function datatableCustomerProductReview() {
            var tablecustomer = $('.yajra-datatabletopproductreview').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                order: [],
                ajax: {
                    url: "{{ route('laporanlabarugi.datatablecustomerreview') }}",
                    type: "POST",
                    data: function(params) {
                        params.year = yearCustomer,
                            params.bulan = bulanCustomer,
                            params.sales = salesCustomer,
                            params.kategori = kategoriCustomer,
                            params.customer_id = customer_id,
                            params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [{
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'laba_kotor',
                        name: 'laba_kotor'
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


        // ================================================================ DATATABLE PRINCIPLE =============================================================== 
        function datatableprinciple() {
            var table = $('.yajra-datatableprinciple').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    type: 'POST',
                    url: "{{ route('laporanlabarugi.principle') }}",
                    data: function(params) {
                        params.year = yearPrinciple,
                            params.bulan = bulanPrinciple,
                            params.sales = salesPrinciple,
                            params.kategori = kategoriPrinciple,
                            params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [{
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'laba_kotor',
                        name: 'laba_kotor'
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
                        responsivePriority: 3,
                        targets: 1,

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

        function filteryearprinciple() {
            let e = document.getElementById("yearprinciple");
            yearPrinciple = e.options[e.selectedIndex].value;
            $('.yajra-datatableprinciple').DataTable().ajax.reload(null, false);
        }

        function filterbulanprinciple() {
            let e = document.getElementById("bulanprinciple");
            bulanPrinciple = e.options[e.selectedIndex].value;
            $('.yajra-datatableprinciple').DataTable().ajax.reload(null, false);
        }

        function filterkategoriprinciple() {
            let e = document.getElementById("kategoriprinciple");
            kategoriPrinciple = e.options[e.selectedIndex].value;
            $('.yajra-datatableprinciple').DataTable().ajax.reload(null, false);
        }

        function filtersalesprinciple() {
            let e = document.getElementById("salesprinciple");
            salesPrinciple = e.options[e.selectedIndex].value;
            $('.yajra-datatableprinciple').DataTable().ajax.reload(null, false);
        }



        function showPrinciple(id) {
            $('#listprodukprinciple').modal('show');
            principle_id = id;
            $('.yajra-datatableprincipleperproduct').DataTable().ajax.reload(null, false);
        }

        function datatableprincipleperproduct() {
            var table = $('.yajra-datatableprincipleperproduct').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    type: 'POST',
                    url: "{{ route('laporanlabarugi.principleperproduct') }}",
                    data: function(params) {
                        params.year = yearPrinciple,
                            params.bulan = bulanPrinciple,
                            params.sales = salesPrinciple,
                            params.kategori = kategoriPrinciple,
                            params.supplier_id = principle_id,
                            params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [{
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'laba_kotor',
                        name: 'laba_kotor'
                    }
                ],
                columnDefs: [{
                        responsivePriority: 3,
                        targets: 1,

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

        // ============================================================== END OF DATATABLE ==================================================

        // ================================================== DATATABLE PRODUCT ============================================================
        function datatableproduct() {
            var table = $('.yajra-datatableproduct').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    type: 'POST',
                    url: "{{ route('laporanlabarugi.product') }}",
                    data: function(params) {
                        params.year = yearproduct,
                            params.bulan = bulanproduct,
                            params.sales = salesproduct,
                            params.kategori = kategoriproduct,
                            params.merk = merkproduct,
                            params.supplier = principleproduct,
                            params._token = "{{ csrf_token() }}";
                        return params;
                    },
                    beforeSend: function() {
                        // Menampilkan loading overlay menggunakan KTApp.block sebelum request AJAX dimulai
                        KTApp.block('#block_produk', {
                            overlayColor: '#000000', // Warna overlay
                            state: 'primary', // Status (warna)
                            message: 'Processing...' // Pesan loading
                        });
                    },
                    complete: function() {
                        // Menyembunyikan loading overlay setelah request selesai
                        KTApp.unblock('#block_produk');
                    }
                },
                columns: [{
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'merk',
                        name: 'merk'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'laba_kotor',
                        name: 'laba_kotor'
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
                        responsivePriority: 3,
                        targets: 1,

                    },
                    {
                        responsivePriority: 10001,
                        targets: 1
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    },
                ]
            });
        }

        function filteryearproduct() {
            let e = document.getElementById("yearproduct");
            yearproduct = e.options[e.selectedIndex].value;
            $('.yajra-datatableproduct').DataTable().ajax.reload(null, false);
        }

        function filterbulanproduct() {
            let e = document.getElementById("bulanproduct");
            bulanproduct = e.options[e.selectedIndex].value;
            $('.yajra-datatableproduct').DataTable().ajax.reload(null, false);
        }

        function filterkategoriproduct() {
            let e = document.getElementById("kategoriproduct");
            kategoriproduct = e.options[e.selectedIndex].value;
            $('.yajra-datatableproduct').DataTable().ajax.reload(null, false);
        }

        function filtersalesproduct() {
            let e = document.getElementById("salesproduct");
            salesproduct = e.options[e.selectedIndex].value;
            $('.yajra-datatableproduct').DataTable().ajax.reload(null, false);
        }

        function filterprincipleproduct() {
            let e = document.getElementById("kt_select2_7");
            principleproduct = e.options[e.selectedIndex].value;
            $('.yajra-datatableproduct').DataTable().ajax.reload(null, false);
        }

        function filtermerkproduct() {
            let e = document.getElementById("kt_select2_4");
            merkproduct = e.options[e.selectedIndex].value;
            $('.yajra-datatableproduct').DataTable().ajax.reload(null, false);
        }

        function showProduct(id) {
            $('#listprodukcustomer').modal('show');
            product_id = id;
            $('.yajra-datatableproductpercustomer').DataTable().ajax.reload(null, false);
        }

        function datatableproductpercustomer() {
            var table = $('.yajra-datatableproductpercustomer').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    type: 'POST',
                    url: "{{ route('laporanlabarugi.productpercustomer') }}",
                    data: function(params) {
                        params.year = yearproduct,
                            params.bulan = bulanproduct,
                            params.sales = salesproduct,
                            params.kategori = kategoriproduct,
                            params.merk = merkproduct,
                            params.supplier = principleproduct,
                            params.product_id = product_id,
                            params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [{
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'laba_kotor',
                        name: 'laba_kotor'
                    },
                ],
                columnDefs: [{
                        responsivePriority: 3,
                        targets: 1,

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

        // ====================================================================================================================================

        // ========================================================= DATATABLE CN ============================================================

        function datatablecn() {
            var table = $('.yajra-datatablecn').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    type: 'POST',
                    url: "{{ route('laporanlabarugi.cn') }}",
                    data: function(params) {
                        params.year = yearcn,
                            params.bulan = bulancn,
                            params.sales = salescn,
                            params.kategori = kategoricn,
                            params._token = "{{ csrf_token() }}";
                        return params;
                    }
                    
                },
                columns: [{
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'omset',
                        name: 'omset'
                    },
                    {
                        data: 'laba_kotor',
                        name: 'laba_kotor'
                    },
                    {
                        data: 'cn_rupiah',
                        name: 'cn_rupiah'
                    }
                ],
                columnDefs: [{
                        responsivePriority: 3,
                        targets: 1,

                    },
                    {
                        responsivePriority: 10001,
                        targets: 1
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    },
                ]               
            });
        }

        function filteryearcn() {
            let e = document.getElementById("yearcn");
            yearcn = e.options[e.selectedIndex].value;
            $('.yajra-datatablecn').DataTable().ajax.reload(null, false);
            totalcn();
        }

        function filterbulancn() {
            let e = document.getElementById("bulancn");
            bulancn = e.options[e.selectedIndex].value;
            $('.yajra-datatablecn').DataTable().ajax.reload(null, false);
            totalcn();
        }

        function filterkategoricn() {
            let e = document.getElementById("kategoricn");
            kategoricn = e.options[e.selectedIndex].value;
            $('.yajra-datatablecn').DataTable().ajax.reload(null, false);
            totalcn();
        }

        function filtersalescn() {
            let e = document.getElementById("salescn");
            salescn = e.options[e.selectedIndex].value;
            $('.yajra-datatablecn').DataTable().ajax.reload(null, false);
            totalcn();
        }

        function totalcn() {
            $.ajax({
                type: 'POST',
                url: '{{ route('laporanlabarugi.totalcn') }}',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    year: yearcn,
                    bulan: bulancn,
                    sales: salescn,
                    kategori: kategoricn,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {                    

                    $('#totalcn').val(data.total_cn_rupiah);
                    $('#totallabakotor').val(data
                        .total_laba_kotor); // Menampilkan 'total_laba_kotor' di elemen HTML
                    $('#totalomset').val(data.total_nett);
                },
                error: function(data) {
                    // console.log(data);
                }
            });
        }
    </script>
@endpush
