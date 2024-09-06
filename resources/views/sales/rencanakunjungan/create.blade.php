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
            <div class="row">

                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b example example-compact">
                        <div class="card-header ">
                            <div class="card-title">
                                <span class="card-icon">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                                        <!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo2\dist/../src/media/svg/icons\Communication\Shield-user.svg--><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path
                                                    d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z"
                                                    fill="#000000" opacity="0.3" />
                                                <path
                                                    d="M12,11 C10.8954305,11 10,10.1045695 10,9 C10,7.8954305 10.8954305,7 12,7 C13.1045695,7 14,7.8954305 14,9 C14,10.1045695 13.1045695,11 12,11 Z"
                                                    fill="#000000" opacity="0.3" />
                                                <path
                                                    d="M7.00036205,16.4995035 C7.21569918,13.5165724 9.36772908,12 11.9907452,12 C14.6506758,12 16.8360465,13.4332455 16.9988413,16.5 C17.0053266,16.6221713 16.9988413,17 16.5815,17 C14.5228466,17 11.463736,17 7.4041679,17 C7.26484009,17 6.98863236,16.6619875 7.00036205,16.4995035 Z"
                                                    fill="#000000" opacity="0.3" />
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon--></span>
                                </span>
                                <h3 class="card-label">Tambah Kunjungan Sales</h3>
                            </div>

                            <div class="card-toolbar">
                               
                            </div>
                        </div>
                        <!--begin::Form-->
                        <div class="card-body">

                            <form class="form" method="POST" action="{{ route('kunjungansales.store') }}" enctype="multipart/form-data">
                                @csrf
                                @include('kunjungansales.partial._form-add')
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

    @endsection
    @push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>

    <script src="{{ asset('/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js?v=7.0.6') }} "></script>
    <!--end::Page Vendors-->

    <!--begin::Page Scripts(used by this page)-->
    <script src="{{ asset('/assets/js/pages/crud/forms/editors/ckeditor-classic.js?v=7.0.6') }} "></script>
    {{-- code  js untuk signatur --}}
    <script type="text/javascript">
            var canvas = document.getElementById('signature-pad');

            // Adjust canvas coordinate space taking into account pixel ratio,
            // to make it look crisp on mobile devices.
            // This also causes canvas to be cleared.
            function resizeCanvas() {
                // When zoomed out to less than 100%, for some very strange reason,
                // some browsers report devicePixelRatio as less than 1
                // and only part of the canvas is cleared then.
                var ratio =  Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
            }

            window.onresize = resizeCanvas;
            resizeCanvas();

            var signaturePad = new SignaturePad(canvas, {
               backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
            });

            // document.getElementById('save-png').addEventListener('click', function () {
            // if (signaturePad.isEmpty()) {
            //     alert("Tanda Tangan Anda Kosong! Silahkan tanda tangan terlebih dahulu.");
            // }else{
            //     var data = signaturePad.toDataURL('image/png');
            //     console.log(data);
            //     $('#myModal').modal('show').find('.modal-body').html('<h4>Format .PNG</h4><img src="'+data+'"><textarea id="signature64" name="signed" style="display:none">'+data+'</textarea>');
            // }
            // });

            document.getElementById('save-jpeg').addEventListener('click', function () {
            if (signaturePad.isEmpty()) {
                
            }else{
                var data = signaturePad.toDataURL();                
                $('#ttd').html('<textarea id="signature64" type="hidden" name="signed" style="display:none">'+data+'</textarea>');
            }
            });

            // document.getElementById('save-svg').addEventListener('click', function () {
            // if (signaturePad.isEmpty()) {
            //     alert("Tanda Tangan Anda Kosong! Silahkan tanda tangan terlebih dahulu.");
            // }else{
            //     var data = signaturePad.toDataURL('image/svg+xml');
            //     console.log(atob(data.split(',')[1]));
            //     $('#myModal').modal('show').find('.modal-body').text(atob(data.split(',')[1])).append('<h4><i>"Hanya copy kode di atas ke HTML Anda"</i></h4>');
            // }
            // });

            document.getElementById('clear').addEventListener('click', function () {
            signaturePad.clear();
            });

            document.getElementById('undo').addEventListener('click', function () {
                var data = signaturePad.toData();
            if (data) {
                data.pop(); // remove the last dot or line
                signaturePad.fromData(data);
            }
            });
    </script>
    @endpush