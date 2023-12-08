@extends('layouts.app', ['title' => $title])

@section('content')
    <!--begin::Content-->
    <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->

        <!--end::Subheader-->

        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid mt-10">
            <!--begin::Container-->
            <div class="container">
                <!-- begin::Card-->
                <div class="card card-custom">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <div class="card-title">

                            <span class="card-icon">
                                <span class="svg-icon svg-icon-md svg-icon-primary">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Chart-bar1.svg--><svg
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <rect fill="#000000" opacity="0.3" x="12" y="4" width="3" height="13"
                                                rx="1.5" />
                                            <rect fill="#000000" opacity="0.3" x="7" y="9" width="3" height="8"
                                                rx="1.5" />
                                            <path
                                                d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z"
                                                fill="#000000" fill-rule="nonzero" />
                                            <rect fill="#000000" opacity="0.3" x="17" y="11" width="3" height="6"
                                                rx="1.5" />
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon--></span> </span>
                            <h3 class="card-label">Laba / Rugi</h3>


                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-separate table-head-custom table-checkable" id="kt_datatable1">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>No KPA</th>
                                    <th>Customer</th>
                                    <th>Products</th>
                                    <th>QTY</th>
                                    <th>Harga Jual</th>
                                    <th>Diskon Jual (%)</th>
                                    <th>Diskon Jual (Rp.)</th>
                                    <th>Total Diskon</th>
                                    <th>Sub Total</th>                                    
                                    <th>Total Jual</th>
                                    <th>CN</th>
                                    <th>Harga Jual Nett</th>
                                    <th>Harga Beli</th>
                                    <th>Diskon Beli (%)</th>
                                    <th>Diskon Beli (Rp.)</th>
                                    <th>Total Diskon Beli</th>
                                    <th>HPP</th>
                                    <th>Laba Kotor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($labarugi as $item)
                                    <tr>
                                        <td>{{ $item['tanggal'] }}</td>
                                        <td>{{ $item['no_kpa'] }}</td>
                                        <td>{{ $item['customer'] }}</td>
                                        <td>{{ $item['products'] }}</td>
                                        <td>{{ $item['qty'] }}</td>
                                        <td>{{ number_format($item['hargajual'], 2, ',', '.') }}</td>
                                        <td>{{ $item['diskon_persen'] }}</td>
                                        <td>{{ $item['diskon_rp'] }}</td>
                                        <td>{{ number_format($item['total_diskon'], 2, ',', '.') }}</td>
                                        <td>{{ number_format($item['subtotal'], 2, ',', '.') }}</td>                                        
                                        <td>{{ number_format($item['total'], 2, ',', '.') }}</td>
                                        <td>{{ number_format($item['cn_rupiah'], 2, ',', '.') }}</td>
                                        <td>{{ number_format($item['nett'], 2, ',', '.') }}</td>
                                        <td>{{ number_format($item['harga_beli'], 2, ',', '.') }}</td>
                                        <td>{{ number_format($item['diskon_beli_persen'], 2, ',', '.') }}</td>
                                        <td>{{ number_format($item['diskon_beli_rupiah'], 2, ',', '.') }}</td>
                                        <td>{{ number_format($item['total_diskon_beli'], 2, ',', '.') }}</td>
                                        <td>{{ number_format($item['hpp'], 2, ',', '.') }}</td>
                                        <td>{{ number_format($item['laba_kotor'], 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
@endpush
