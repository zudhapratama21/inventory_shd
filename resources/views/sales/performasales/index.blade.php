@extends('layouts.app', ['title' => $title])

@section('content')
<!--begin::Content-->
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->

    {{-- GRAFIK PERFORMA SALES --}}

    <div class="container ">
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
                            <h3 class="card-label">Grafik Performa Sales</h3>                           
                        </div>                            
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">    
                                    <label for="">Tahun</label>                                                    
                                    <select name="chart_year" class="form-control" id="kt_select2_3" onchange="filteryeargrafik()">                               
                                        @php
                                        $year = 2020;
                                        @endphp
                                        @foreach (range(date('Y'), $year) as $x)
                                            <option value="{{$x}}">{{$x}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">    
                                    <label for="">Kategori</label>                                                    
                                    <select name="chart_year" class="form-control" id="kt_select2_4" onchange="filterkategorigrafik()">    
                                        <option value="All">Semua</option>
                                        @foreach ($kategori as $item)
                                        
                                          <option value="{{$item->id}}">{{$item->nama}}</option>  
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        
                            <div class="col-md-4">
                                <div class="form-group">    
                                    <label for="">Bulan</label>                                                    
                                    <select name="chart_year" class="form-control" id="kt_select2_5" onchange="filterbulangrafik()">    
                                        <option value="All">Semua</option>
                                        @foreach ($bulan as $data)
                                          <option value="{{$data['id']}}">{{$data['nama']}}</option>  
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                            <canvas class="row" id="chartperformasales">
                                                                    
                            </canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END GRAFIK PERFORMA SALES --}}

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid mt-10">

        
        <!--begin::Container-->
        <div class="container ">
            <div class="row">

                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <div class="card-title ">                                
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
                                    <h3 class="card-label">Data Performa Sales</h3>
                                                                                                            
                            </div>    
                            @can('targetsales-list')
                            <div>
                                <a href="{{ route('targetsales.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-bullseye"></i>Data Target Sales</a>
                            </div>
                            @endcan                        
                            
                        </div>
                        
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">    
                                        <label for="">Tahun</label>                                                    
                                        <select name="chart_year" class="form-control" id="kt_select2_1" onchange="filterYear()">                               
                                            @php
                                            $year = 2020;
                                            @endphp
                                            @foreach (range(date('Y'), $year) as $x)
                                                <option value="{{$x}}">{{$x}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">    
                                        <label for="">Bulan</label>                                                    
                                        <select name="chart_year" class="form-control" id="kt_select2_2" onchange="filterMonth()">    
                                            @foreach ($bulan as $item)
                                              @if ($item['id'] ==  now()->format('m') )
                                                    <option value="{{$item['id']}}" selected >{{$item['nama']}}</option>
                                              @else 
                                                    <option value="{{$item['id']}}">{{$item['nama']}}</option>  
                                              @endif
                                              
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">    
                                        <label for="">Kategori Pesanan</label>                                                    
                                        <select name="chart_year" class="form-control" id="kt_select2_7" onchange="filterCategory()">    
                                            <option value="All">Semua</option>
                                            @foreach ($kategori as $item)
                                              <option value="{{$item->id}}">{{$item->nama}}</option>  
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @php
                                    $access = 0;
                                @endphp
                                
                                @can('chat-list')
                                    @php
                                        $access = 1 ;
                                    @endphp    
                                @endcan
                             
                            </div>
                                <div class="row" id="performasales">
                                        
                                </div>
                      
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>

@endsection
@push('script')
<script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6') }}"></script>
<script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
<script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const idperformasales = document.getElementById('chartperformasales');
    const performasales = document.getElementById('performasales');

    let year = {{now()->format('Y')}};
    let month = {{now()->format('m')}};
    let kategori = 'All';
    let bulan = 'All';
    let category = 'All';
    let access = {{$access}};
    

    $(document).ready(function() {
        DataPerformaSales();
        grafikperformasales();

        console.log(access);
    })

    let barPerformaSales= {
            type: 'bar',
            data: {
                labels: null ,
                datasets: [{
                    label: 'Grafik Performa Sales',
                    data: null,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                title: {
                    display: true,
                    text: (ctx) => 'Data Dalam Rupiah ',
                }
                },
                scales: {
                    y: {
                        stacked: true
                    }
                }
            },
            interaction: {
                    intersect: false,
            }
        }

    function DataPerformaSales() {
        $.ajax({
                    type: 'POST',
                    url: '{{ route('performasales.dataperformasales') }}',
                    dataType: 'html',
                    headers: { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                    data: {                       
                        "_token": "{{ csrf_token() }}",
                        'year' : year,
                        'month' : month,
                        'kategori' : category
                    },                    
                    success: function (data){
                        res = JSON.parse("[" + data + "]");
                        let dataperformasales = res[0].sales;                        
                        performasales.innerHTML = cardSales(dataperformasales);                                                                                                                                  
                    },
                    error: function(data){
                        console.log(data.stok_obat);
                    }
                });	   
    }

    function cardSales(data) {        

        let length = data.length;
        
        if (length > 0) {
            return data.map(item =>`
            <div class="col-md-4 py-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <span><strong>${item.nama} </strong></span>
                            <span class="text-success"><strong> Rp.${item.laba}</strong></span>
                        </div>
                        <hr>                                           
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: ${item.persen}%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div> </br>                           
                        </div>
                        <span class="text-primary">${
                            item.persen
                        }%</span>
                        <hr> 
                        
                        <div class="d-flex justify-content-between align-items-center"> 
                            <span class="text-danger"><strong>${item.bulan}</strong></span>  
                            
                            <div>
                                ${WAblast(item.user , item.persen , item.laba)}
                                <a href="/sales/performasales/${item.id}/${month}/${category}/detail" class="btn btn-primary btn-outline btn-sm" > Detail </a>
                            </div>
                        </div>    
                        
                    </div>
                </div>
            </div>
            `).join('');
        }else{
            return `
                    <div class="col-md-12"> 
                        <div class="text-center alert alert-custom alert-warning fade show pb-2 pt-2" role="alert">
                            <div class="alert-icon text-center"><i class="flaticon-warning"></i></div>
                            <div class="alert-text">BELUM ADA PENJUALAN PADA BULAN INI !!</div>
                            <div class="alert-close">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true"><i class="ki ki-close"></i></span>
                                </button>
                            </div>
                        </div>
                    </div>
                `
        }
       
    }

    function WAblast(user,persen,laba) {
        let count = user.length;

        if (count > 0 && access == 1) {
            return user.map(item =>`
                      <a href="whatsapp://send?text=Assalamualaikum wr wb %0AMohon Perhatian !!%0ATarget Penjualan pada bulan ini masih ${persen}% dengan nominal Rp.${laba} %0A Segera Tingkatkan strategi untuk mencapai target penjualan !! %0ASemangatt !!&app_absent=0&phone=${item.phone}" 
                      target="_blank" class="btn btn-outline-success btn-sm" >${item.name}</a>
            `).join('');
        }else{
            return '-';
        }
    }

    function filterYear() {
            let e = document.getElementById("kt_select2_1");
            year = e.options[e.selectedIndex].value; 
            idperformasales.innerHTML = "";
            
            DataPerformaSales();

    }

    function filterMonth() {
            let e = document.getElementById("kt_select2_2");
            month = e.options[e.selectedIndex].value; 
            idperformasales.innerHTML = "";
            
            DataPerformaSales();

    }
    function filterCategory() {
            let e = document.getElementById("kt_select2_7");
            category = e.options[e.selectedIndex].value; 
            idperformasales.innerHTML = "";
            
            DataPerformaSales();

    }

    function grafikperformasales() {
        $.ajax({
                    type: 'POST',
                    url: '{{ route('performasales.grafikperformasales') }}',
                    dataType: 'html',
                    headers: { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                    data: {                       
                        "_token": "{{ csrf_token() }}",
                        'year' : year,
                        'kategori' : kategori,
                        'bulan' : bulan                       
                    },                    
                    success: function (data){
                        
                        res = JSON.parse("[" + data + "]");
                        let label = res[0].sales;
                        let dataPenjualan = res[0].penjualan;

                        barPerformaSales.data.labels =  label;
                        barPerformaSales.data.datasets[0].data = dataPenjualan;

                        chartkategori = new Chart(idperformasales,barPerformaSales);   
                                                                                      
                    },
                    error: function(data){
                        console.log(data);
                    }
                });	   
    }


    function filteryeargrafik() {
            let e = document.getElementById("kt_select2_3");
            year = e.options[e.selectedIndex].value; 
            
            $.ajax({
                    type: 'POST',
                    url: '{{ route('performasales.grafikperformasales') }}',
                    dataType: 'html',
                    headers: { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                    data: {                       
                        "_token": "{{ csrf_token() }}",
                        'year' : year,
                        'kategori' : kategori,
                        'bulan' : bulan
                       
                    },                    
                    success: function (data){
                        
                        res = JSON.parse("[" + data + "]");
                        let label = res[0].sales;
                        let dataPenjualan = res[0].penjualan;

                        barPerformaSales.data.labels =  label;
                        barPerformaSales.data.datasets[0].data = dataPenjualan;

                        chartkategori.destroy();
                        chartkategori = new Chart(idperformasales,barPerformaSales);   
                        chartkategori.update();
                                                                                      
                    },
                    error: function(data){
                        console.log(data);
                    }
                });	   
    }

    function filterkategorigrafik() {
           let e = document.getElementById("kt_select2_4");
            kategori = e.options[e.selectedIndex].value; 
            
            $.ajax({
                    type: 'POST',
                    url: '{{ route('performasales.grafikperformasales') }}',
                    dataType: 'html',
                    headers: { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                    data: {                       
                        "_token": "{{ csrf_token() }}",
                        'year' : year,
                        'kategori' : kategori,
                        'bulan' : bulan
                       
                    },                    
                    success: function (data){
                        
                        res = JSON.parse("[" + data + "]");
                        let label = res[0].sales;
                        let dataPenjualan = res[0].penjualan;

                        barPerformaSales.data.labels =  label;
                        barPerformaSales.data.datasets[0].data = dataPenjualan;

                        chartkategori.destroy();
                        chartkategori = new Chart(idperformasales,barPerformaSales);   
                        chartkategori.update();
                                                                                      
                    },
                    error: function(data){
                        console.log(data);
                    }
                });	   
    }

    function filterbulangrafik() {
            let e = document.getElementById("kt_select2_5");
            bulan = e.options[e.selectedIndex].value; 
            
            $.ajax({
                    type: 'POST',
                    url: '{{ route('performasales.grafikperformasales') }}',
                    dataType: 'html',
                    headers: { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                    data: {                       
                        "_token": "{{ csrf_token() }}",
                        'year' : year,
                        'kategori' : kategori,
                        'bulan' : bulan
                       
                    },                    
                    success: function (data){
                        
                        res = JSON.parse("[" + data + "]");
                        let label = res[0].sales;
                        let dataPenjualan = res[0].penjualan;

                        barPerformaSales.data.labels =  label;
                        barPerformaSales.data.datasets[0].data = dataPenjualan;

                        chartkategori.destroy();
                        chartkategori = new Chart(idperformasales,barPerformaSales);   
                        chartkategori.update();
                                                                                      
                    },
                    error: function(data){
                        console.log(data);
                    }
                });	
    }   
</script>

@endpush