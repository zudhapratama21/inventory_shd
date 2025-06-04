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
                                    <h3 class="card-label">Analisis Beban Perusahaan</h3>
                                </div>
                                <div class="card-toolbar">
                                    <h6 class="badge badge-sm badge-info mr-2">Total Pengeluaran : </h6>
                                    <div class="d-flex">


                                        <input type="text" class="form-control text-right mr-2" id="grandtotal"
                                            name="grandtotal" value="0" readonly>


                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#download">
                                            Download
                                        </button>


                                    </div>


                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Tahun</label>
                                                    <select name="chart_year" class="form-control" id="grafik_tahun"
                                                        onchange="filtertahungrafik()">
                                                        @php
                                                            $year = 2020;
                                                        @endphp
                                                        @foreach (range(date('Y'), $year) as $x)
                                                            <option value="{{ $x }}">{{ $x }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <canvas id="myChart" height="100"></canvas>
                                        </div>


                                        <!--end::Card-->
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card mt-2 card-custom">
                            <div class="card-header py-3">
                                <div class="card-title">
                                    <h3 class="card-label">Analisis Beban Per Kategori</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Tahun</label>
                                                    <select name="chart_year" class="form-control" id="tahun"
                                                        onchange="filterkategori('tahun')">
                                                        @php
                                                            $year = 2020;
                                                        @endphp
                                                        @foreach (range(date('Y'), $year) as $x)
                                                            <option value="{{ $x }}">{{ $x }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Divisi</label>
                                                    <select name="chart_year" class="form-control" id="divisi_id"
                                                        onchange="filterkategori('divisi_id')">
                                                        <option value="" selected>Semua</option>
                                                        @foreach ($divisi as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Karyawan</label>
                                                    <select name="chart_year" class="form-control" id="kt_select2_1"
                                                        onchange="filterkategori('kt_select2_1')">
                                                        <option value="all" selected>Semua</option>
                                                        @foreach ($karyawan as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <table class="table yajra-datatable collapsed">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Jenis Biaya</th>
                                                    <th>Total Biaya</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>


                                        <!--end::Card-->
                                    </div>
                                </div>

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

    <!-- Modal-->
    <div class="modal fade" id="download" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Download Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('analisiskeuangan.download') }}" method="POST" id="form-download">
                        @csrf
                        <div class="form-group">
                            <label for="">Jenis Laporan</label>
                            <select name="jenis_laporan" class="form-control" id="">
                                <option value="biaya_operational">Biaya Operational</option>
                                <option value="cash_advance">Cash Advance</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai"
                                value="{{ now()->format('Y-m-d') }}">
                        </div>

                        <div class="form-group">
                            <label for="">Tanggal Akhir</label>
                            <input type="date" class="form-control" name="tanggal_akhir" id="tanggal_akhir"
                                value="{{ now()->format('Y-m-d') }}">
                        </div>

                        <div class="form-group">
                            <label>Jenis Biaya :</label> <br>
                            <select name="jenisbiaya_id" class="form-control select2" id="kt_select2_8">
                                @foreach ($jenisbiaya as $item)
                                    <option value="all" selected>Semua</option>
                                    <optgroup label="{{ $item->nama }}">
                                        @foreach ($item->subjenisbiaya as $data)
                                            <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach

                            </select>
                            <p style="font-size:70%" class="text-danger">*Jika jenis laporan cash advance , jenis biaya tidak perlu di pilih.</p>
                        </div>

                        <div class="form-group">
                            <label>Karyawan :</label> <br>
                            <select name="karyawan_id" class="form-control select2" id="kt_select2_4">
                                <option value="all">Semua</option>
                                @foreach ($karyawan as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold"
                        data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    @include('keuangan.analisis.modal')
@endsection
@push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
    {{-- <script src="{{ asset('/assets/js/pages/features/charts/apexcharts.js?v=7.0.6') }} "></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('myChart');

        // var untuk grafik 
        let tahungrafik = {{ now()->format('Y') }};
        let karyawan_id = 'all';
        let divisi_id = null;
        let jenisbiaya = null;
        let tahun_kategori = {{ now()->format('Y') }};
        let chart = null;

        $(document).ready(function() {
            datatable();
            grafikdivisi();
            datatablehistorycash();
        });

        let options = {
            type: 'bar',
            data: {
                labels: null,
                datasets: [{
                    label: 'Beban Perusahaan',
                    data: null,
                    pointStyle: 'circle',
                    pointRadius: 10,
                    pointHoverRadius: 15,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                    ],
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

        function grafikdivisi() {
            $.ajax({
                type: 'POST',
                url: '{{ route('analisiskeuangan.grafikdivisi') }}',
                dataType: 'html',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'tahun': tahungrafik,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    res = JSON.parse("[" + data + "]");
                    total_biaya = res[0].total_biaya;
                    divisi = res[0].divisi;
                    let grandtotal = res[0].grand_total;

                    hitungtotalgrafik(grandtotal);

                    options.data.labels = divisi;
                    options.data.datasets[0].data = total_biaya;
                    if (chart !== null) {
                        chart.destroy();
                    }
                    chart = new Chart(ctx, options);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }


        function hitungtotalgrafik(data) {
            $('#grandtotal').val(data);
        }

        function filtertahungrafik() {
            let e = document.getElementById("grafik_tahun");
            tahungrafik = e.options[e.selectedIndex].value;
            grafikdivisi();
        }

        function datatable() {
            var table = $('.yajra-datatable').DataTable({
                responsive: true,
                processing: true,
                order: [],
                ajax: {
                    url: "{{ route('analisiskeuangan.datatable') }}",
                    type: "POST",
                    data: function(params) {
                        params.tahun = tahun_kategori;
                        params.divisi_id = divisi_id;
                        params.karyawan_id = karyawan_id;
                        params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'sub_biaya',
                        name: 'sub_biaya'
                    },
                    {
                        data: 'total_biaya',
                        name: 'total_biaya'
                    },
                    {
                        data: 'action',
                        render: function(data) {
                            return `
                                <button type="button" class="btn btn-sm btn-outline-info mr-2" onclick="reportcash(${data})">
                                    Cek History
                                </button>                               
                            `;
                        },
                        className: "nowrap",
                    },
                ],
            });
        }

        function filterkategori(tahunkategori) {
            if (tahunkategori == 'divisi_id') {
                let e = document.getElementById(tahunkategori);
                divisi_id = e.options[e.selectedIndex].value;
            } else if (tahunkategori == 'kt_select2_1') {
                let e = document.getElementById(tahunkategori);
                karyawan_id = e.options[e.selectedIndex].value;
            } else {
                let e = document.getElementById(tahunkategori);
                tahun_kategori = e.options[e.selectedIndex].value;
            }
            $('.yajra-datatable').DataTable().ajax.reload(null, false);
        }

        function reportcash(id) {
            $('#analisis').modal('show');
            jenisbiaya = id;
            $('.yajra-datatable-cash').DataTable().ajax.reload(null, false);
        }

        function datatablehistorycash() {
            var table = $('.yajra-datatable-cash').DataTable({
                responsive: true,
                processing: true,
                order: [],
                ajax: {
                    url: "{{ route('analisiskeuangan.datatablehistorycash') }}",
                    type: "POST",
                    data: function(params) {
                        params.tahun = tahun_kategori;
                        params.divisi_id = divisi_id;
                        params.karyawan_id = karyawan_id;
                        params.jenisbiaya_id = jenisbiaya;
                        params._token = "{{ csrf_token() }}";
                        return params;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },

                    {
                        data: 'karyawan',
                        name: 'karyawan'

                    },
                    {
                        data: 'divisi',
                        name: 'divisi'
                    },
                    {
                        data: 'total_biaya',
                        name: 'total_biaya'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                ],
            });
        }
    </script>
@endpush
