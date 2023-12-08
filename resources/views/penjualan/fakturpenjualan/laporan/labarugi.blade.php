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
                    <div class="card-body">
                        <table class="table table-separate table-head-custom table-checkable" id="kt_datatable1">
                            <thead >
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
                                    <th>PPN</th>
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
                                        <td>{{$item['tanggal']}}</td>
                                        <td>{{$item['no_kpa']}}</td>
                                        <td>{{$item['customer']}}</td>
                                        <td>{{$item['products']}}</td>
                                        <td>{{$item['qty']}}</td>
                                        <td>{{ number_format($item['hargajual'], 2, ',', '.') }}</td>
                                        <td>{{$item['diskon_persen']}}</td>
                                        <td>{{$item['diskon_rp']}}</td>
                                        <td>{{number_format($item['total_diskon'], 2, ',', '.') }}</td>
                                        <td>{{number_format($item['subtotal'], 2, ',', '.') }}</td>
                                        <td>{{number_format($item['ppn'], 2, ',', '.')}}</td>
                                        <td>{{number_format($item['total'], 2, ',', '.')}}</td>
                                        <td>{{number_format($item['cn_rupiah'], 2, ',', '.')}}</td>
                                        <td>{{number_format($item['nett'], 2, ',', '.')}}</td>
                                        <td>{{number_format($item['harga_beli'], 2, ',', '.') }}</td>
                                        <td>{{number_format($item['diskon_beli_persen'], 2, ',', '.')}}</td>
                                        <td>{{number_format($item['diskon_beli_rupiah'], 2, ',', '.') }}</td>
                                        <td>{{number_format($item['total_diskon_beli'], 2, ',', '.') }}</td>
                                        <td>{{number_format($item['hpp'], 2, ',', '.') }}</td>
                                        <td>{{number_format($item['laba_kotor'], 2, ',', '.') }}</td>
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
