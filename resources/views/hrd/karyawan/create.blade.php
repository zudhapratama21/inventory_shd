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
                                    <h3 class="card-label">Karyawan</h3>
                                </div>

                                <div class="card-toolbar">
                                    <a href="{{ route('karyawan.index') }}"
                                        class="btn btn-light-danger font-weight-bold mr-2">
                                        <i class="flaticon2-left-arrow-1"></i> Back
                                    </a>
                                </div>
                            </div>
                            <!--begin::Form-->
                            <div class="card-body">

                                <form class="form" method="post" action="{{ route('karyawan.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">NIP</label>
                                                <input type="text" name="nip" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="">No Employee</label>
                                                <input type="number" name="no_emp" class="form-control" required>
                                                <span><i class="text-danger" style="font-size: 80%">* No ini di ambil dari no_emp di mesin absensi</i></span>
                                            </div>



                                            <div class="form-group">
                                                <label for="">Nama Lengkap</label>
                                                <input type="text" name="nama" class="form-control" required>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Tempat Lahir</label>
                                                        <input type="text" name="tempat_lahir" class="form-control"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Tanggal Lahir</label>
                                                        <input type="date" name="tanggal_lahir" class="form-control"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="">Alamat Lengkap</label>
                                                <textarea name="alamat" id="" cols="30" rows="5" class="form-control"></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="">Jabatan</label>
                                                <select name="jabatan_id" class="form-control" id="">
                                                    @foreach ($jabatan as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="">Posisi</label>
                                                <select name="posisi_id" class="form-control" id="">
                                                    @foreach ($posisi as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="">Email</label>
                                                <input type="text" name="email" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label for="">Hp</label>
                                                <input type="text" name="hp" class="form-control">
                                            </div>


                                            <div class="form-group">
                                                <label for="">Status Karyawan</label>
                                                <select name="statuskaryawan_id" class="form-control" id="">
                                                    @foreach ($statuskaryawan as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>




                                        </div>
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="">Tanggal Masuk</label>
                                                <input type="date" name="tanggal_masuk" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label for="">No KTP</label>
                                                <input type="text" name="no_ktp" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label for="">Rekening</label>
                                                <input type="text" name="rekening" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label for="">Gaji Pokok</label>
                                                <input type="number" name="gaji_pokok" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label for="">Insentif</label>
                                                <input type="number" name="insentif" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label for="">Rekening</label>
                                                <input type="text" name="rekening" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label for="">Bank</label>
                                                <input type="text" name="bank" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label for="">Atas Nama</label>
                                                <input type="text" name="atas_nama" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label for="">Foto Profil</label>
                                                <input type="file" name="foto_profile" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label for="">Foto KTP</label>
                                                <input type="file" name="foto_ktp" class="form-control">
                                            </div>

                                        </div>

                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>

                                    </div>

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
        <!--end::Content-->
        <div id="modal-confirm-delete"></div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
@endpush
