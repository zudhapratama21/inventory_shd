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
                <!-- begin::Card-->
                <div class="card card-custom overflow-hidden">
                    <div class="card-body p-0">
                        <!-- begin: Invoice-->
                        <!-- begin: Invoice header-->
                        <div class="row justify-content-center py-8 px-8 py-md-27 px-md-0">
                            <div class="col-md-9">
                                <div class="d-flex justify-content-between pb-10 pb-md-20 flex-column flex-md-row">
                                    <h1 class="display-4 font-weight-boldest mb-10">CANVASSING</h1>
                                    <div class="d-flex flex-column align-items-md-end px-0">
                                        <!--begin::Logo-->
                                        <a href="#" class="mb-5">
                                            <img src="assets/media/logos/logo-dark.png" alt="" />
                                        </a>
                                        <!--end::Logo-->
                                        <span class=" d-flex flex-column align-items-md-end opacity-70">
                                            <span>{{ \Carbon\Carbon::parse($canvas->tanggal)->format('d F Y') }}</span>
                                            <span>{{ $canvas->creator->name }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="border-bottom w-100"></div>
                                <div class="d-flex justify-content-between pt-6">
                                    <div class="d-flex flex-column flex-root">
                                        <span class="font-weight-bolder mb-2">Kode</span>
                                        <span class="opacity-70">{{ $canvas->kode }}</span>
                                    </div>


                                    <div class="d-flex flex-column flex-root">
                                        <span class="font-weight-bolder mb-2">Kode Pesanan</span>
                                        <span class="opacity-70">{{ $canvas->kode_pesanan }}</span>
                                    </div>

                                    <div class="d-flex flex-column flex-root">
                                        <span class="font-weight-bolder mb-2">Customer</span>
                                        <span class="opacity-70">{{ $canvas->customer->nama }}</span>
                                    </div>
                                </div>


                            </div>
                        </div>


                        <!-- end: Invoice header-->

                        <!-- begin: Invoice body-->
                        <div class="row justify-content-center py-8 px-8 py-md-10 px-md-0">
                            <div class="col-md-9">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="pl-0 font-weight-bold text-muted  text-uppercase">Kode
                                                </th>
                                                <th class="text-left font-weight-bold text-muted text-uppercase">Produk
                                                </th>
                                                <th class="text-left font-weight-bold text-muted text-uppercase">Satuan
                                                </th>
                                                <th class="text-left font-weight-bold text-muted text-uppercase">Qty</th>
                                                <th class="text-left font-weight-bold text-muted text-uppercase">Qty Sisa
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($canvasdet as $a)
                                                <tr class="font-weight-boldest">
                                                    <td class="pl-0 pt-7">{{ $a->product->kode }}</td>
                                                    <td class="text-left pt-7">{{ $a->product->nama }}</td>
                                                    <td class="text-left pt-7">{{ $a->product->satuan }}</td>
                                                    <td class=" pt-7 text-left">{{ $a->qty }}</td>
                                                    <td class=" pt-7 text-left">{{ $a->qty_sisa }}
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- end: Invoice body-->

                        <!-- begin: Invoice footer-->
                        <div class="row justify-content-center bg-gray-100 py-8 px-8 py-md-10 px-md-0">
                            <div class="col-md-9">
                                <div class="table-responsive">
                                    <br />
                                    <h4>Keterangan :</h4>
                                    <p>{{ $canvas->keterangan }} </p>
                                </div>
                            </div>
                        </div>
                        <!-- end: Invoice footer-->
                        <!-- begin: Invoice footer-->
                        <div class="row justify-content-center  py-8 px-8 py-md-10 px-md-0">
                            <div class="col-md-9">
                                <div class="border-bottom w-100"></div>


                                <div class="card card-custom gutter-b">
                                    <div class="card-header">
                                        <div class="card-title justify-content-center">
                                            <h3 class="card-label  ">
                                                Info

                                            </h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="font-weight-bold text-muted  text-uppercase">Created at</th>
                                                    <th class="font-weight-bold text-muted  text-uppercase">Created by </th>
                                                    <th class="font-weight-bold text-muted  text-uppercase">Updated At</th>
                                                    <th class="font-weight-bold text-muted  text-uppercase">Updated By</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="font-weight-bolder">
                                                    <td>{{ $canvas->created_at }}</td>
                                                    <td>{{ $canvas->creator->name }}</td>
                                                    <td>{{ $canvas->updated_at }}</td>
                                                    <td>{{ $canvas->updater->name }}</td>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <!-- end: Invoice footer-->
                        <!-- begin: Invoice action-->
                        <div class="row justify-content-center py-8 px-8 py-md-10 px-md-0">

                            <div class="col-md-9">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('canvassing.print', ['id'=>$canvas->id]) }}"
                                        class="btn btn-primary " target="_blank">
                                        <i class="flaticon2-print font-weight-bold"></i> Download & Print
                                    </a>
                                    {{-- <button type="button" class="btn btn-primary font-weight-bold"
                                    onclick="window.print();">Print </button> --}}
                                    <a class="btn btn-danger font-weight-bold"
                                        href="{{ route('canvassingpengembalian.index') }}">Back </a>
                                </div>
                            </div>
                        </div>
                        <!-- end: Invoice action-->

                        <!-- end: Invoice-->
                    </div>
                </div>
                <!-- end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
    <!--end::Content-->
@endsection
@push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
    <script src="{{ asset(' /assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.6') }}"></script>


    <script type="text/javascript"></script>
@endpush
