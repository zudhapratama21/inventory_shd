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
            <div class="alert alert-custom alert-danger fade show pb-2 pt-2" role="alert">
                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                <div class="alert-text">{{ session('status') }}</div>
                <div class="alert-close">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="ki ki-close"></i></span>
                    </button>
                </div>
            </div>

            @endif
            @if (session('sukses'))
            <div class="alert alert-custom alert-success fade show pb-2 pt-2" role="alert">
                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                <div class="alert-text">{{ session('sukses') }}</div>
                <div class="alert-close">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="ki ki-close"></i></span>
                    </button>
                </div>
            </div>

            @endif
            <div class="row ">

                <div class="col-lg-12">
                    <!--begin::Card-->


                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="card-label">
                                    Pengiriman Barang
                                    <small>{{ $pengirimanbarangdetail->pengirimanbarangs->kode }}</small>
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <!--begin::Button-->
                                <a href="{{ route('pengirimanbarang.inputexp', $pengirimanbarang) }}"
                                    class="btn btn-danger font-weight-bolder ">
                                    <i class="flaticon2-fast-back"></i>
                                    Back
                                </a>
                                <!--end::Button-->
                            </div>
                        </div>
                        <div class="card-body">
                            <table>
                                <tr>
                                    <th>Produk</th>
                                    <td>:</td>
                                    <td>{{ $pengirimanbarangdetail->products->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Qty Dikirim</th>
                                    <td>:</td>
                                    <td>{{ $pengirimanbarangdetail->qty }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>


                </div>
            </div>
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
                                <h3 class="card-label">Daftar Produk Yang Akan Dikirim</h3>
                            </div>
                            <div class="card-toolbar">
                                <!--begin::Button-->
                                @can('pengirimanbarang-create')
                                <a href="{{ route('pengirimanbarang.listproduk', $pengirimanbarangdetail) }}"
                                    class="btn btn-primary font-weight-bolder "> 
                                    <i class="flaticon2-add"></i>
                                    Tambah Produk
                                </a>
                                @endcan

                                <!--end::Button-->
                            </div>
                        </div>
                        <div class="card-body">
                            <!--begin: Datatable-->
                            <table class="table yajra-datatable collapsed ">
                                <thead class="datatable-head">
                                    <tr>
                                        <th>Supplier</th>
                                        <th>Harga Beli</th>
                                        <th>Diskon Beli (%)</th>
                                        <th>Diskon Beli (Rp.)</th>
                                        <th>Qty yang dikirim</th>                                        
                                        <th style="width: 15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($listProduk as $item)
                                    <tr>
                                        <td>{{ $item->harganonexpired->supplier->nama}}</td>
                                        <td>{{ $item->harga_beli }}</td>
                                        <td>{{ $item->diskon_persen_beli }}</td>
                                        <td>{{ $item->diskon_rupiah_beli }}</td>
                                        <td>{{ $item->qty * -1 }}</td>
                                        <td>
                                            <div style="text-align:center;">
                                                <div class="d-flex flex-nowrap">
                                                    <a href="#" onclick="deleteData({{$item->id}})"
                                                        class="btn btn-icon btn-light btn-hover-primary btn-sm mr-3">
                                                        <span class="svg-icon svg-icon-md svg-icon-primary">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/General/Trash.svg--><svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                height="24px" viewBox="0 0 24 24" version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none"
                                                                    fill-rule="evenodd">
                                                                    <rect x="0" y="0" width="24" height="24" />
                                                                    <path
                                                                        d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z"
                                                                        fill="#000000" fill-rule="nonzero" />
                                                                    <path
                                                                        d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                                                        fill="#000000" opacity="0.3" />
                                                                </g>
                                                            </svg>
                                                            <!--end::Svg Icon--></span> </a>

                                                </div>
                                            </div>
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
@push('script')
<script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6') }}"></script>
<script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
<script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>




<script type="text/javascript">
    $(function () {
   
         
    });
   
    function show_confirm(data_id){
        $.ajax({
            type: 'POST',
            url: '{{ route('pengirimanbarang.hapusexp') }}',
            dataType: 'html',
            headers: { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
            data: {id:data_id, "_token": "{{ csrf_token() }}"},
            
            success: function (data){
                console.log(data);
                $('#modal-confirm-delete').html(data);
                $('#exampleModal').modal('show');
            },
            error: function(data){
                console.log(data);
            }
        });
    }

    function htmlDecode(data){
        var txt = document.createElement('textarea');
        txt.innerHTML=data;
        return txt.value;
    }

    function deleteData(id) {
        Swal.fire({
        title: "Apakah Anda Yakin ?",
        text: "Kamu Tidak Akan Bisa Mengembalikan Data Ini !",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Hapus!"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pengirimanbarang.destroyproduk') }}',
                    dataType: 'html',
                    headers: { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        'id':id,
                        "_token": "{{ csrf_token() }}"},
                    
                    success: function (data){
                        Swal.fire(
                                "Terhapus!",
                                "Anda Berhasil menghapus Data",
                                "success"
                                 )
                        location.reload();
                        
                    },
                    error: function(data){
                        console.log(data);
                    }
                });               
            }
        });
    }

    
    
</script>
@endpush