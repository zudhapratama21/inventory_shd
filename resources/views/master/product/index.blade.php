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
                                <h3 class="card-label">Daftar Produk</h3>
                            </div>    
                            <div class="d-flex align-items-center">
                                <a href="#" class="btn btn-primary mr-2" data-toggle="modal" data-target="#modalexport">
                                <i class="flaticon-technology"></i>                                  
                                Export to Excel</a>                                                                                          
                            </div>
                                                    
                            <div >
                                <div class="btn-group mr-2">
                                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">Atribut Produk</button>
                                    <div class="dropdown-menu">
                                        @can('productgroup-list')
                                        <a class="dropdown-item" href="{{ route('productgroup.index') }}">Group
                                            Barang</a>
                                        @endcan
                                        @can('satuan-list')
                                        <a class="dropdown-item" href="{{ route('satuan.index') }}">Satuan</a>
                                        @endcan
                                        @can('merk-list')
                                        <a class="dropdown-item" href="{{ route('merk.index') }}">Merk</a>
                                        @endcan
                                        @can('productcategory-list')
                                        <a class="dropdown-item"
                                            href="{{ route('productcategory.index') }}">Kategori</a>
                                        @endcan
                                        @can('productsubcategory-list')
                                        <a class="dropdown-item" href="{{ route('productsubcategory.index') }}">Sub
                                            Kategori</a>
                                        @endcan
                                    </div>
                                </div>

                                <!--begin::Button-->
                                @can('product-create')
                                <a href="{{ route('product.create') }}" class="btn btn-primary font-weight-bolder">
                                    <i class="flaticon2-add"></i>
                                    Produk
                                </a>
                                @endcan

                                <!--end::Button-->
                            </div>                            
                        </div>
                        <div class="card-body">
                            <!--begin: Datatable-->
                            <table class="table  yajra-datatable collapsed ">
                                <thead class="datatable-head">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Katalog</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Kategori</th>
                                        <th>Sub Kategori</th>
                                        <th>Status Exp</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
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

<!-- Button trigger modal -->

  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Import Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('product.import') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Download Template Excel</label> <br>
                    <a  href="{{ asset('producttemplate.xlsx') }}" download class="btn btn-primary btn-sm mr-2">
                        <span class="svg-icon svg-icon-default svg-icon-1x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo2\dist/../src/media/svg/icons\Files\Import.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 7.000000) rotate(-180.000000) translate(-12.000000, -7.000000) " x="11" y="1" width="2" height="12" rx="1"/>
                                <path d="M17,8 C16.4477153,8 16,7.55228475 16,7 C16,6.44771525 16.4477153,6 17,6 L18,6 C20.209139,6 22,7.790861 22,10 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,9.99305689 C2,7.7839179 3.790861,5.99305689 6,5.99305689 L7.00000482,5.99305689 C7.55228957,5.99305689 8.00000482,6.44077214 8.00000482,6.99305689 C8.00000482,7.54534164 7.55228957,7.99305689 7.00000482,7.99305689 L6,7.99305689 C4.8954305,7.99305689 4,8.88848739 4,9.99305689 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,10 C20,8.8954305 19.1045695,8 18,8 L17,8 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                <path d="M14.2928932,10.2928932 C14.6834175,9.90236893 15.3165825,9.90236893 15.7071068,10.2928932 C16.0976311,10.6834175 16.0976311,11.3165825 15.7071068,11.7071068 L12.7071068,14.7071068 C12.3165825,15.0976311 11.6834175,15.0976311 11.2928932,14.7071068 L8.29289322,11.7071068 C7.90236893,11.3165825 7.90236893,10.6834175 8.29289322,10.2928932 C8.68341751,9.90236893 9.31658249,9.90236893 9.70710678,10.2928932 L12,12.5857864 L14.2928932,10.2928932 Z" fill="#000000" fill-rule="nonzero"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>    
                    Download Excel</a> 
                </div>                

                <div class="form-group">
                    <label for="">Upload File Excel</label>
                    <input type="file" class="form-control" name="file">
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
       </form>
      </div>
    </div>
  </div>


  {{-- modal export --}}
 <!-- Modal -->
 <div class="modal fade" id="modalexport" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Export Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('product.export') }}" method="post">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Kategori : </label> <br>
                    <select name="kategori_id" class="form-control" id="kt_select2_1" required>
                        <option value="all" selected>Semua</option>
                        @foreach ($kategory as $item)
                            <option value="{{$item->id}}">{{$item->nama}}</option>
                        @endforeach
                    </select>
                </div>        
                <div class="form-group">
                    <label for="">Merk : </label> <br>
                    <select name="merk_id" class="form-control" id="kt_select2_2" required>
                        <option value="all" selected>Semua</option>
                        @foreach ($merk as $item)
                            <option value="{{$item->id}}">{{$item->nama}}</option>
                        @endforeach
                    </select>
                </div>  
                <div class="form-group">
                    <label for="">Stok : </label> <br>
                    <select name="stok_id" class="form-control" id="kt_select2_3" required>
                        <option value="all" selected>Semua</option>
                        <option value="0">0</option>
                        <option value="1">Stok diatas 0</option>
                    </select>
                </div>    
                
                <div class="form-group">
                    <label for="">Ada Ijin Edar : </label> <br>
                    <select name="ijinedar_id" class="form-control" id="kt_select2_4" required>
                        <option value="all" selected>Semua</option>
                        <option value="iya">Iya</option>
                        <option value="tidak">Tidak</option>
                    </select>
                </div>    
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Export to Excel</button>
            </div>
       </form>
      </div>
    </div>
</div>


  {{-- end modal export --}}

@endsection
@push('script')
<script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
<script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
<script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>



<script type="text/javascript">
    $(function () {
          
          var table = $('.yajra-datatable').DataTable({
              responsive: true,
              processing: true,
              serverSide: true,
              ajax: "{{ route('product.index') }}",
              columns: [
                //   {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'kode', name: 'kode'},
                  {data: 'nama', name: 'nama'},
                  {data: 'katalog', name: 'katalog'},
                  {data: 'hargajual', name: 'hargajual'},
                  {data: 'stok', name: 'stok'},
                  {data: 'kategori', name: 'categories.nama'},
                  {data: 'subkategori', name: 'subcategories.nama'},
                  {data: 'status_exp', name: 'status_exp'},

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
                    targets: 1
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

    function show_detail(data_id){
        $.ajax({
                type: 'POST',
                url: '{{ route('product.detail') }}',
                dataType: 'html',
                headers: { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                data: {id:data_id, "_token": "{{ csrf_token() }}"},
                
                success: function (data){
                    console.log(data);
                    $('#modal-show-detail').html(data);
                    $('#detailModal').modal('show');
                },
                error: function(data){
                    console.log(data);
                }
        });
    }

    function show_confirm(data_id){
            $.ajax({
                type: 'POST',
                url: '{{ route('product.delete') }}',
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

    function show_delete() {
        $('#notifDelete').modal('show');
    }






</script>
@endpush