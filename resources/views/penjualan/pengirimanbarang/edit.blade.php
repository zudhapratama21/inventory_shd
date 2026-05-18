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
                <div class="row">

                    <div class="col-lg-12">
                        <!--begin::Card-->
                        <div class="card card-custom gutter-b example example-compact">
                            <div class="card-header ">
                                <div class="card-title">
                                    <h3 class="card-label">Pengiriman Barang</h3>
                                </div>
                            </div>
                            <!--begin::Form-->
                            <div class="card-body">

                                <form class="form" method="post">
                                    @include('penjualan.pengirimanbarang._form-control-edit')
                                </form>
                            </div>
                            <!--end::Card-->


                        </div>
                    </div>

                </div>
                <!--end::Container-->
            </div>
            <!--end::Entry-->
        </div>

        <div id="modal-setbarang"></div>

        @include('penjualan.pengirimanbarang.modal._caribarang')
    @endsection
    @push('script')
        <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
        <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
        <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
        <script src="{{ asset('/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.6') }}"></script>
        <script src="{{ asset('assets/js/pages/features/miscellaneous/blockui.js?v=7.0.6') }} "></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
            integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
            integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>


        <script type="text/javascript">        

        // let id = {{ $pengirimanbarang->id }}

            function store() {
                var customer = document.getElementById('customer_id').value;
                var tanggal = document.getElementById('tgl1').value;
                var keterangan = document.getElementById('keterangan').value;

                $.ajax({
                    type: 'POST',
                    url: '{{ route('pengirimanbarang.updatePB', ['pengirimanbarang' => $pengirimanbarang->id]) }}',
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        KTApp.blockPage();
                    },
                    complete: function() {
                        KTApp.unblock();
                    },
                    data: {
                        "customer": customer,
                        "tanggal": tanggal,
                        "keterangan": keterangan,
                        "_token": "{{ csrf_token() }}"
                    },

                    success: function(data) {
                        iziToast.success({
                            title: 'success',
                            message: 'Data berhasil Di Ubah',
                            position: 'topRight',
                        });                        

                        setTimeout(function() {
                            window.location.href = "{{ route('pengirimanbarang.index') }}";
                        }, 1000); // kasih delay biar toast sempat muncul

                    },
                    error: function(xhr) {
                        const response = JSON.parse(xhr.responseText);
                        if (xhr.status === 500 || xhr.status === 422) {
                            iziToast.error({
                                title: 'error',
                                message: response.message,
                                position: 'topRight',
                            });
                        }
                    }
                });

            }
        </script>
    @endpush
