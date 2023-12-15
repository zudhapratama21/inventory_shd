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
                                    <h3 class="card-label">Tambah Plan Marketing</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('planmarketing.update', ['id' => $planmarketing->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="">Outlet</label>
                                        <select name="outlet_id" class="form-control" id="kt_select2_1">
                                            @foreach ($outlet as $item)
                                                @if ($planmarketing->outlet_id == $item->id)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Bulan</label>
                                                <input type="text" name="bulan" class="form-control"
                                                    value="{{ \Carbon\Carbon::parse(now())->format('m') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Tahun</label>
                                                <select name="tahun" id="kt_select2_9" class="form-control"
                                                    onchange="filterYear()">
                                                    @php
                                                        $year = 2020;
                                                        $now = date('Y') + 1;
                                                    @endphp
                                                    @foreach (range($now, $year) as $x)
                                                        @if ($planmarketing->tahun == date('Y'))
                                                            <option value="{{ $x }}" selected>
                                                                {{ $x }}</option>
                                                        @else
                                                            <option value="{{ $x }}">{{ $x }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Minggu ke - 1</label>
                                        <select name="day_minggu1[]" class="form-control" id="kt_select2_2"
                                            multiple="multiple">

                                            @foreach ($day as $item)
                                                <?php
                                                $count = 0;
                                                ?>
                                                @foreach ($planmarketing->planmarketingdetailminggu1 as $week1)
                                                    @if ($week1->day_id == $item->id)
                                                        <option value="{{ $item->id }}" selected>{{ $item->nama }}
                                                        </option>
                                                        <?php
                                                        $count++;
                                                        ?>
                                                    @endif
                                                @endforeach

                                                @if ($count == 0)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Minggu ke - 2</label>
                                        <select name="day_minggu2[]" class="form-control" id="kt_select2_3"
                                            multiple="multiple">
                                            @foreach ($day as $item)
                                                <?php
                                                $count = 0;
                                                ?>
                                                @foreach ($planmarketing->planmarketingdetailminggu2 as $week1)
                                                    @if ($week1->day_id == $item->id)
                                                        <option value="{{ $item->id }}" selected>{{ $item->nama }}
                                                        </option>
                                                        <?php
                                                        $count++;
                                                        ?>
                                                    @endif
                                                @endforeach

                                                @if ($count == 0)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Minggu ke - 3</label>
                                        <select name="day_minggu3[]" class="form-control" id="kt_select2_4"
                                            multiple="multiple">
                                            @foreach ($day as $item)
                                                <?php
                                                $count = 0;
                                                ?>
                                                @foreach ($planmarketing->planmarketingdetailminggu3 as $week1)
                                                    @if ($week1->day_id == $item->id)
                                                        <option value="{{ $item->id }}" selected>{{ $item->nama }}
                                                        </option>
                                                        <?php
                                                        $count++;
                                                        ?>
                                                    @endif
                                                @endforeach

                                                @if ($count == 0)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Minggu ke - 4</label>
                                        <select name="day_minggu4[]" class="form-control" id="kt_select2_5"
                                            multiple="multiple">
                                            @foreach ($day as $item)
                                                <?php
                                                $count = 0;
                                                ?>
                                                @foreach ($planmarketing->planmarketingdetailminggu4 as $week1)
                                                    @if ($week1->day_id == $item->id)
                                                        <option value="{{ $item->id }}" selected>{{ $item->nama }}
                                                        </option>
                                                        <?php
                                                        $count++;
                                                        ?>
                                                    @endif
                                                @endforeach

                                                @if ($count == 0)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Minggu ke - 5</label>
                                        <select name="day_minggu5[]" class="form-control" id="kt_select2_7"
                                            multiple="multiple">
                                            @foreach ($day as $item)
                                                <?php
                                                $count = 0;
                                                ?>
                                                @foreach ($planmarketing->planmarketingdetailminggu5 as $week1)
                                                    @if ($week1->day_id == $item->id)
                                                        <option value="{{ $item->id }}" selected>{{ $item->nama }}
                                                        </option>
                                                        <?php
                                                        $count++;
                                                        ?>
                                                    @endif
                                                @endforeach

                                                @if ($count == 0)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-primary" type="submit">Save</button>
                                        <a href="{{ route('planmarketing.index') }}" class="btn btn-secondary">Close</a>
                                    </div>

                                </form>
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
    <div id="modal-show-detail"></div>
@endsection
@push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>

    <script>
        $('#kt_select2_3_modal').select2({
            placeholder: & quot;Select a state & quot;,
        });
    </script>
@endpush
