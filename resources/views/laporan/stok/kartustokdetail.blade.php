@extends('layouts.app', ['title' => $title])

@section('content')
<!--begin::Content-->
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-12  subheader-transparent " id="kt_subheader">
        <div class=" container  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-1">

                <!--begin::Heading-->
                <div class="d-flex flex-column">
                    <!--begin::Title-->
                    <h2 class="text-white font-weight-bold my-2 mr-5">
                        Stok Produk </h2>
                    <!--end::Title-->


                </div>
                <!--end::Heading-->

            </div>
            <!--end::Info-->


        </div>
    </div>
    <!--end::Subheader-->

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
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
                        <div class="card-header">
                            <h3 class="card-title">
                                Kartu Stok : &nbsp; <i>{{ " " . $product->kode . " - ".$product->nama }}</i>
                            </h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <a href="{{ route('laporanstok.refresh') }}" class="btn btn-success btn-sm mr-2">Refresh</a>
                                    <form action="{{ route('laporanstok.exportkartustok') }}" method="POST">
                                        @csrf                                        

                                        <input type="hidden" name="product_id" value="{{$product->id}}">                                        
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <span class="svg-icon svg-icon-default svg-icon-1x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo2\dist/../src/media/svg/icons\Files\Import.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 7.000000) rotate(-180.000000) translate(-12.000000, -7.000000) " x="11" y="1" width="2" height="12" rx="1"/>
                                                    <path d="M17,8 C16.4477153,8 16,7.55228475 16,7 C16,6.44771525 16.4477153,6 17,6 L18,6 C20.209139,6 22,7.790861 22,10 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,9.99305689 C2,7.7839179 3.790861,5.99305689 6,5.99305689 L7.00000482,5.99305689 C7.55228957,5.99305689 8.00000482,6.44077214 8.00000482,6.99305689 C8.00000482,7.54534164 7.55228957,7.99305689 7.00000482,7.99305689 L6,7.99305689 C4.8954305,7.99305689 4,8.88848739 4,9.99305689 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,10 C20,8.8954305 19.1045695,8 18,8 L17,8 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                    <path d="M14.2928932,10.2928932 C14.6834175,9.90236893 15.3165825,9.90236893 15.7071068,10.2928932 C16.0976311,10.6834175 16.0976311,11.3165825 15.7071068,11.7071068 L12.7071068,14.7071068 C12.3165825,15.0976311 11.6834175,15.0976311 11.2928932,14.7071068 L8.29289322,11.7071068 C7.90236893,11.3165825 7.90236893,10.6834175 8.29289322,10.2928932 C8.68341751,9.90236893 9.31658249,9.90236893 9.70710678,10.2928932 L12,12.5857864 L14.2928932,10.2928932 Z" fill="#000000" fill-rule="nonzero"/>
                                                </g>
                                            </svg><!--end::Svg Icon--></span>    
                                        Export to Excel</button>
                                    
                                    </form>

                                    
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <div class="card-body">
                            <table class="table yajra-datatable collapsed ">
                                <thead class="datatable-head">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tanggal</th>
                                        <th>Qty</th>
                                        <th>Stok</th>
                                        <th>Transaksi</th>
                                        <th>Kode Transaksi</th>
                                        <th>Customer</th>
                                        <th>Supplier</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!--end::Form-->
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
<script src="{{ asset('assets/js/pages/widgets.js?v=7.0.6"') }}"></script>
<script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6') }}"></script>
<script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
<script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
<script type="text/javascript">
    $(function () {
        $('#provinsi').on('change', function () {
        // Kode untuk ajax request disini 

        });
   
          var table = $('.yajra-datatable').DataTable({
              responsive: true,
              processing: true,
              serverSide: true,
              autoWidth: false,
              ajax: "{{ route('laporanstok.kartustokdetail', $product) }}",
              columns: [
                //   {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'id', name: 'id'},
                  {data: 'tanggal', name: 'tanggal'},
                  {data: 'qty', name: 'qty'},
                  {data: 'stok', name: 'stok'},
                  {data: 'jenis', name: 'jenis'},
                  {data: 'jenis_id', name: 'jenis_id'},
                  {data: 'customer', name: 'customer'},
                  {data: 'supplier', name: 'supplier'},
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
                    responsivePriority: 3,
                    targets: 2,
                    
                },
                {responsivePriority: 10001, targets: 4},
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

    
    
</script>
@endpush