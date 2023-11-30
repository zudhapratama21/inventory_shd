@extends('layouts.app', ['title' => 'Role And Permissions'])

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
                                <h3 class="card-label">ASSIGN PERMISSIONS</h3>
                            </div>

                            <div class="card-toolbar">
                                <a href="{{ route('assignpermission.index') }}"
                                    class="btn btn-light-danger font-weight-bold mr-2">
                                    <i class="flaticon2-left-arrow-1"></i> Back
                                </a>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <div class="card-body">
                            <form class="form" name="form1" action="{{ route('assignpermission.sync', $role) }}"
                                method="POST">
                                @csrf
                                @method('put')
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-bold">Role Name:</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" id="role_name" value="{{ $role->name }}"
                                            disabled />
                                        <input type="hidden" id="role_id" name="role_id" value="{{ $role->id }}" />
                                    </div>
                                </div>
                                <table class="table">
                                    <tr>
                                        <td></td>
                                        <td>
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" name="selectAll" onchange="checkAllList()"
                                                    id="checkall_list" />
                                                <span></span>
                                                &nbsp; <b>Select All List</b>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" name="selectAll" onchange="checkAllCreate()"
                                                    id="" />
                                                <span></span>
                                                &nbsp; <b>Select All Create</b>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" name="selectAll" onchange="checkAllEdit()"
                                                    id="" />
                                                <span></span>
                                                &nbsp; <b>Select All Edit</b>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" name="selectAll" onchange="checkAllDelete()"
                                                    id="" />
                                                <span></span>
                                                &nbsp; <b>Select All Delete</b>
                                            </label>
                                        </td>
                                    </tr>
                                    @foreach ($datas as $key_index => $data)
                                    <tr>
                                        @foreach ($data as $key_item => $item)
                                            @if ($key_item == 0)
                                            <td><b>{{ $item }}</b></td>
                                            @else
                                            <td>
                                                <label class="checkbox checkbox-success">
                                                    <input
                                                        {{ $role->permissions()->find($item->id) ? "checked='checked'" : "" }}
                                                        type="checkbox" name="permission[]" value="{{ $item->id }}"
                                                        id="{{ explode('-', $item->name)[1].$item->id }}" />
                                                    <span></span>
                                                    &nbsp; {{ $item->name }}
                                                </label>
                                            </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>

                                        </td>

                                    </tr>
                                </table>

                        </div>
                        <!--end::Form-->
                        <div class="card-footer text-right">
                            <div class="row">
                                <div class="col-lg-12 ">
                                    <button type="submit" class="btn btn-success font-weight-bold mr-2"><i
                                            class="flaticon2-refresh"></i>
                                        Sync</button>
                                </div>
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

@endsection
@push('script')
<script type="text/javascript">
    function checkAllList() {
        var jmlChecked = $('input:checkbox[id^="list"]:checked').length;
        if(jmlChecked > 0){
            $('input:checkbox[id^="list"]').prop('checked', false);;
        }else{
            $('input:checkbox[id^="list"]').prop('checked', true);;
        }
     }

     function checkAllCreate() {
        var jmlChecked = $('input:checkbox[id^="create"]:checked').length;
        if(jmlChecked > 0){
            $('input:checkbox[id^="create"]').prop('checked', false);;
        }else{
            $('input:checkbox[id^="create"]').prop('checked', true);;
        }
     }

     function checkAllEdit() {
        var jmlChecked = $('input:checkbox[id^="edit"]:checked').length;
        if(jmlChecked > 0){
            $('input:checkbox[id^="edit"]').prop('checked', false);;
        }else{
            $('input:checkbox[id^="edit"]').prop('checked', true);;
        }
     }
     function checkAllDelete() {
        var jmlChecked = $('input:checkbox[id^="delete"]:checked').length;
        if(jmlChecked > 0){
            $('input:checkbox[id^="delete"]').prop('checked', false);;
        }else{
            $('input:checkbox[id^="delete"]').prop('checked', true);;
        }
     }

</script>
<script src="{{ asset('assets/js/pages/widgets.js?v=7.0.6"') }}"></script>
@endpush