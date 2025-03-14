
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
                                <h3 class="card-label">Atur Product Kirim</h3>
                            </div>
                            <div class="card-toolbar">
                                <!--begin::Button-->

                                <a href="{{ route('canvassingpengembalian.index') }}"
                                    class="btn btn-danger font-weight-bolder ">
                                    <i class="flaticon2-fast-back"></i>
                                    Back
                                </a>
                                <!--end::Button-->
                            </div>
                        </div>
                        <div class="card-body">
                            <!--begin: Datatable-->
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Kode barang</th>
                                        <th>Nama Barang</th>            
                                        <th>Qty Kembali</th>     
                                        <th>Status</th>       
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($canvasdet as $item)
                                    <tr>
                                        <td>{{ $item->product->kode }}</td>
                                        <td>{{ $item->product->nama }}</td>                                    
                                        <td>{{ $item->qty_kirim }}</td>
                                        <td>
                                            @if ($item->status_data == 0)
                                                <span class="badge badge-warning">Pending</span>                                                                            
                                            @else
                                                <span class="badge badge-success">Done</span>                                               
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('canvassingpengembalian.setexp', ['canvassingpengembalian_id'=>$item->id , 'id_produk' => $item->product->id]) }}"
                                                class="btn btn-sm btn-primary">Atur Product Kembali</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!--end: Datatable-->

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