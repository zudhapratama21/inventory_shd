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
                                    <h3 class="card-label">Surat Menyurat</h3>
                                </div>

                                <div class="card-toolbar">
                                    <a href="{{ route('suratmenyurat.index') }}"
                                        class="btn btn-light-danger font-weight-bold mr-2">
                                        <i class="flaticon2-left-arrow-1"></i> Back
                                    </a>
                                </div>
                            </div>

                            <div class="card-body">
                                <form class="form" method="post"
                                    action="{{ route('suratmenyurat.update', ['id' => $surat->id]) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label for="">Kode</label>
                                        <input type="text" name="name" value="{{ $surat->kode }}"
                                            class="form-control" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Tanggal</label>
                                        <input type="date" name="tanggal" value="{{ $surat->tanggal }}"
                                            class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Request ? </label>
                                        <select name="requests" class="form-control" id="">
                                            @foreach ($user as $item)
                                                @if ($item->id == $surat->request)
                                                    <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>                                                    
                                                @endif
                                                
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Tipe Surat</label>
                                        <input type="text" name="tipesurat"  value="{{ $surat->tipesurat->nama }}"
                                            class="form-control" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Kepada ?</label>
                                        <input type="text" name="kepada" value="{{ $surat->kepada }}"
                                            class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="">Tentang ?</label>
                                        <input type="text" name="isi" value="{{ $surat->isi }}"
                                            class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="">Status</label>
                                        <select name="status" id="" class="form-control" required>
                                            <option value="{{ $surat->status }}" selected>{{ $surat->status }}</option>
                                            <option value="dibuat">Dibuat</option>
                                            <option value="dikirim">Dikirim</option>
                                            <option value="diterima">Diterima</option>
                                            <option value="disetujui">Disetujui</option>
                                            <option value="ditolak">Ditolak</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="">File</label>
                                        <br>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                            data-target="#create">
                                            Cek File
                                        </button>

                                        <a href="{{ asset('/storage/suratmenyurat/' . $surat->file) }}"
                                            class="btn btn-primary btn-sm" target="_blank">
                                            <i class="flaticon-download"></i>
                                        </a>

                                        <br>
                                        <input type="file" name="file" class="form-control mt-2">
                                    </div>

                                    <div class="form-group">
                                        <label for="">Publish ? </label>
                                        <select name="publish" id="" class="form-control" required>
                                            <option value="{{ $surat->publish }}" selected>{{ $surat->publish }}</option>
                                            <option value="ya">Iya</option>
                                            <option value="tidak">Tidak</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="">keterangan ?</label>
                                        <input type="text" name="keterangan" value="{{ $surat->keterangan }}"
                                            class="form-control" required>
                                    </div>

                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary"> <i class="flaticon2-reload"></i>
                                            Dapatkan Kode</button>
                                    </div>




                                </form>
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
    </div>


    <!-- Modal -->
    <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Surat Menyurat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe src="{{ asset('/storage/suratmenyurat/' . $surat->file) }}"
                        title="W3Schools Free Online Web Tutorials" width="100%" height="500px"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
@endpush
