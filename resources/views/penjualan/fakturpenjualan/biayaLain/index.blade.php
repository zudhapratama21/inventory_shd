@extends('layouts.app', ['title' => $title])

@section('content')
<!--begin::Content-->
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
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
                                <h3 class="card-label">Data Biaya Lain -Lain</h3>
                            </div>
                            <div class="card-toolbar">
                                <!--begin::Button-->
                                @can('biayalain-list')
                                <a href="{{ route('jenisbiaya.index') }}" class="btn btn-info font-weight-bolder mr-3">
                                    <i class="flaticon2-list-2"></i>
                                    Jenis Biaya Lain-Lain 
                                </a>
                                @endcan

                                @can('biayalain-create')
                                <button type="button" class="btn btn-primary font-weight-bolder" data-bs-toggle="modal" data-bs-target="#tambahdata">
                                    <i class="flaticon2-add"></i>
                                    Biaya Lain
                                  </button>                               
                                @endcan


                                <!--end::Button-->
                            </div>
                        </div>
                        <div class="card-body">
                            <!--begin: Datatable-->
                            <table class="table">
                                <thead class="datatable-head">
                                    <tr>                                                                                
                                        <th>Jenis Biaya</th>                                        
                                        <th>Nominal</th>       
                                        <th>Pengurangan CN ?</th>                                                                                                              
                                        <th>Keterangan</th>    
                                        <th>Aksi</th>                                    
                                    </tr>
                                </thead>
                                <tbody>
                                   @foreach ($biayalain as $item)
                                       <tr>
                                            <td>{{$item->jenisbiaya->nama}}</td>
                                            <td>{{number_format($item->nominal , 0, ',', '.')}}</td>
                                            @if ($item->pengurangan_cn == 1)
                                                <td><span class="badge badge-info badge-sm">Ya</span></td>
                                            @else
                                                <td><span class="badge badge-primary badge-sm">Tidak</span></td>
                                            @endif
                                            
                                            <td>{{$item->keterangan}}</td>
                                            <td>
                                                <div style="text-align:center;">
                                                    <div class="d-flex flex-nowrap">               
                                                        @can('biayalain-edit')
                                                            <a href="javascript:edit({{ $item->id }}) " class="btn btn-icon btn-light btn-hover-primary btn-sm mr-3">
                                                                <span class="svg-icon svg-icon-md svg-icon-primary">
                                                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Write.svg--><svg
                                                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                                        viewBox="0 0 24 24" version="1.1">
                                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                            <rect x="0" y="0" width="24" height="24" />
                                                                            <path
                                                                                d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z"
                                                                                fill="#000000" fill-rule="nonzero"
                                                                                transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953) " />
                                                                            <path
                                                                                d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z"
                                                                                fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                                        </g>
                                                                    </svg>
                                                                </span>
                                                            </a>
                                                        @endcan
                                                        @can('biayalain-delete')
                                                        <a href=" javascript:destroy({{ $item->id }}) " class="btn btn-icon btn-light btn-hover-primary btn-sm mr-3">
                                                            <span class="svg-icon svg-icon-md svg-icon-primary">
                                                                <!--begin::Svg Icon | path:assets/media/svg/icons/General/Trash.svg--><svg
                                                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
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
                                                        @endcan
                                                
                                                
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
<div id="modal-update"></div>

{{-- modal tambah data --}}
@include('penjualan.fakturpenjualan.biayaLain.modal._form-control')


@endsection
@push('script')
<script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
<script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
<script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>



<script type="text/javascript">
    $(function () {          
          var table = $('.yajra-datatable').DataTable({
              responsive: true,
              processing: true,
              serverSide: true,
              ajax: "{{ route('biayaoperational.index') }}",
              columns: [               
                  {data: 'jenis_biaya', name: 'jenisbiaya.nama'},
                  {data: 'nominal', name: 'nominal'},
                  {data: 'keterangan', name: 'keterangan'},
                  {
                      data: 'action', 
                      render: function(data){
                          return htmlDecode(data);
                      },
                      className:"nowrap",
                  },
              ],
              columnDefs: [

                {
                    responsivePriority: 1,
                    targets: 0
                },
                {
                    responsivePriority: 2,
                    targets: -1
                },
            ],
        });
          
    });

    function htmlDecode(data){
        var txt = document.createElement('textarea');
        txt.innerHTML=data;
        return txt.value;
    }

     function edit(data_id){
            $.ajax({
                type: 'POST',
                url: '{{ route('fakturpenjualan.biayalain.edit') }}',
                dataType: 'html',
                headers: { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                data: {id:data_id, "_token": "{{ csrf_token() }}"},
                
                success: function (data){
                    $('#modal-update').html(data);
                    $('#editData').modal('show');
                },
                error: function(data){
                    console.log(data);
                }
            });
        }

    function store() {
        var biaya = document.getElementById('jenisbiaya_id');
        var jenisbiaya_id = biaya.options[biaya.selectedIndex].value;
        var fakturpenjualan_id = document.getElementById('fakturpenjualan_id').value;
        var nominal = document.getElementById('nominal').value;
        var keterangan = document.getElementById('keterangan').value;
        var cn = document.getElementById('pengurangan_cn');
        var pengurangan_cn = cn.options[cn.selectedIndex].value;

        $.ajax({
           type: 'POST',
           url: '{{ route('fakturpenjualan.biayalain.store') }}',
           dataType: 'html',
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           data: {

               "keterangan": keterangan,
               "jenisbiaya_id" : jenisbiaya_id,
               "nominal" : nominal,
               "fakturpenjualan_id" : fakturpenjualan_id,
               "pengurangan_cn" : pengurangan_cn,
               "_token": "{{ csrf_token() }}"
           },
           success: function(data) {
            
               $('#tambahdata').modal('hide');
               location.reload();
           },
           error: function(data) {
               console.log(data);
           }
       });
    }

    function update(){
     
        let biayaId = document.getElementById('id').value;;
        var biaya = document.getElementById('jenisbiaya_id');
        var jenisbiaya_id = biaya.options[biaya.selectedIndex].value;
        var fakturpenjualan_id = document.getElementById('fakturpenjualan_id').value;
        var nominal = document.getElementById('nominal').value;
        var keterangan = document.getElementById('keterangan').value;
        var cn = document.getElementById('pengurangan_cn');
        var pengurangan_cn = cn.options[cn.selectedIndex].value;
        

        $.ajax({
           type: 'POST',
           url: '{{ route('fakturpenjualan.biayalain.ubah') }}',
           dataType: 'json',
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           data: {
               "keterangan": keterangan,
               "jenisbiaya_id" : jenisbiaya_id,
               "nominal" : nominal,
               "fakturpenjualan_id" : fakturpenjualan_id,
               "id" : biayaId,
               "pengurangan_cn" : pengurangan_cn,
               "_token": "{{ csrf_token() }}"
           },
           success: function(data) {
            
                 $('#editData').modal('hide');   
                //  location.reload();
              
           },
           error: function(data) {
            
               console.log(data);
           }
       });
    }

    function destroy(id) {
        let biayaId = id;
        let status = confirm('apakah anda yakin ?');
        if (status == true) {
            $.ajax({
                    type: 'POST',
                    url: "{{ route('fakturpenjualan.biayalain.destroy') }}",
                    dataType: 'html',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {             
                        "id" : biayaId,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        location.reload();              
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
        }

        // Swal.fire({
        //         title: "Are you sure?",
        //         text: "You won"t be able to revert this!",
        //         icon: "warning",
        //         showCancelButton: true,
        //         confirmButtonText: "Yes, delete it!"
        //     }).then(function(result) {
        //         if (result.value) {
        //             Swal.fire(
        //                 "Deleted!",
        //                 "Your file has been deleted.",
        //                 "success"
        //             )
        //         }
        //     });
    }
</script>
@endpush