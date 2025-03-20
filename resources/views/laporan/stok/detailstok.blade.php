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
                    </div>
                </div>
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
                                    Expired Date : &nbsp; <i>{{ ' ' . $product->kode . ' - ' . $product->nama }}</i>
                                </h3> <br>
                                <div class="card-toolbar">
                                    <div class="example-tools justify-content-center">
                                        <h5>
                                            Total Stok : {{ $product->stok }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            @can('dataexpired-list')
                            <div class="card-body">
                                <div class="row justify-content-end mb-3">
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambahexp">
                                        tambah barang</button>
                                </div>
                                <table class="table yajra-datatable-dataexp collapsed ">
                                    <thead class="datatable-head">
                                        <tr>
                                            <th>Tanggal Exp</th>
                                            <th>Supplier</th>
                                            <th>Harga Beli</th>
                                            <th>Diskon Beli (Rp.)</th>
                                            <th>Diskon Beli (%)</th>
                                            <th>Lot</th>
                                            <th>Qty</th>
                                            <th>Updated At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>                 
                            @endcan
                            
                                          
                          </div>                                   
                        </div>
                    </div>
                </div>                                    
                </div>                                    
                </div>                                    
            <div id="modal-setbarang"></div>                                            
                            <div class="modal fade" id="tambahexp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <i aria-hidden="true" class="ki ki-close"></i>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="">
                                                <div class="form-group">
                                                    <label for="tanggal">Tanggal Exp</label>
                                                    <input type="date" class="form-control" id="tanggal_exp" name="tanggal">
                                                    <span class="text-danger" style="font-size: 80%">*(Jika produk non expired tanggal pilih hari ini !)</span>
                                                </div>

                                                <div class="form-group">
                                                    <label for="supplier">Supplier</label> <br>
                                                    <select name="" id="kt_select2_2" class="form-control">
                                                           @foreach ($supplier as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                            @endforeach
                                                            </select>
                                                </div>
                            <div class="form-group">
                                <label for="">Qty</label>
                                <input type="text" value="0" id="qty_exp" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Lot</label>
                                <input type="text" value="" id="lot_exp" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Harga Beli</label>
                                <input type="number" id="harga_beli_exp" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Diskon (%)</label>
                                <input type="number" id="diskon_persen_exp" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Diskon (Rp.)</label>
                                <input type="number" id="diskon_rupiah_exp" class="form-control">
                            </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-primary font-weight-bold"
                                data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary font-weight-bold" onclick="createexp()">Save
                                changes</button>
                        </div>
                    </div>
                </div>
            </div>
        @endsection
        @push('script')
            <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
            <script src="{{ asset('assets/js/pages/widgets.js?v=7.0.6"') }}""></script>
            <script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
            <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
                integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
                integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
                crossorigin="anonymous" referrerpolicy="no-referrer" />

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
            <script>
                let id = {{ $product->id }}
                let status = {{ $product->status_exp }}
                $(document).ready(function() {
                    datatableExp();
                });

                function initializeDataTable(selector, url, columns) {
                    return $(selector).DataTable({
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: url,
                            type: "POST",
                            data: function(params) {
                                params.id = id;
                                params.status = status;
                                params._token = "{{ csrf_token() }}";
                                return params;
                            }
                        },
                        columns: columns,
                        columnDefs: [{
                                responsivePriority: 1,
                                targets: 0
                            },
                            {
                                responsivePriority: 2,
                                targets: -1
                            }
                        ]
                    });
                }

                function datatableExp() {
                    initializeDataTable('.yajra-datatable-dataexp', "{{ route('laporanstok.listexp') }}",
                        [{
                                data: 'tanggal',
                                searchable: false,
                                name: 'tanggal'
                            },
                            {
                                data: 'supplier',
                                searchable: false,
                                name: 'supplier'
                            },
                            {
                                data: 'harga_beli',
                                searchable: false,
                                name: 'harga_beli'
                            },
                            {
                                data: 'diskon_persen',
                                searchable: false,
                                name: 'diskon_persen'
                            },
                            {
                                data: 'diskon_rupiah',
                                searchable: false,
                                name: 'diskon_rupiah'
                            },
                            {
                                data: 'lot',
                                searchable: false,
                                name: 'lot'
                            },
                            {
                                data: 'qty',
                                searchable: false,
                                name: 'qty'
                            },
                            {
                                data: 'updated_at',
                                searchable: false,
                                name: 'updated_at'
                            },
                            {
                                data: 'action',
                                render: function(data) {

                                    let url = "{{ route('laporanstok.detailexp', [':id', ':status']) }}";
                                    url = url.replace(':id', data).replace(':status', status);

                                    return '<a href="' + url +
                                        '" class="btn btn-outline-primary btn-sm mr-2">Detail</a><span class="btn btn-primary btn-sm" onclick="pilihbarang(' +
                                        data +
                                        ')">edit</span><span class="btn btn-danger btn-sm ml-2" onclick="hapus(' +
                                        data + ')"><i class="fas fa-trash"></i></span>';
                                }
                            }
                        ]);
                }

                function pilihbarang(id) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('laporanstok.formexp') }}',
                        dataType: 'html',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            KTApp.blockPage();
                        },
                        data: {
                            id: id,
                            status: status,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            $('#modal-setbarang').html(data);
                            $('#formexp').modal('show');
                        },
                        error: function(data) {
                            console.log(data);
                        },
                        complete: function() {
                            KTApp.unblock();
                        }
                    })
                };

                function submitbarang() {
                    let stok_id = document.getElementById('stok_id').value;
                    let qty = document.getElementById('qty').value;
                    let lot = document.getElementById('lot').value;
                    let harga_beli = document.getElementById('harga_beli').value;
                    let diskon_persen = document.getElementById('diskon_persen').value;
                    let diskon_rupiah = document.getElementById('diskon_rupiah').value;
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('laporanstok.simpanexp') }}',
                        dataType: 'html',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            KTApp.blockPage();
                        },
                        data: {
                            "stok_id": stok_id,
                            "qty": qty,
                            "status": status,
                            "lot": lot,
                            "harga_beli": harga_beli,
                            "diskon_persen": diskon_persen,
                            "diskon_rupiah": diskon_rupiah,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            $('#formexp').modal('hide');
                            iziToast.success({
                                title: 'Success',
                                message: 'Data Berhasil Ditambahkan',
                                position: 'topRight',
                            });

                            $('.yajra-datatable-dataexp').DataTable().ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            const response = JSON.parse(xhr.responseText);
                            if (xhr.status === 422) {
                                // Error qty melebihi stok
                                iziToast.error({
                                    title: 'error',
                                    message: response.message,
                                    position: 'topRight',
                                });
                            }

                            if (xhr.status === 500) {
                                // Error qty melebihi stok
                                iziToast.error({
                                    title: 'error',
                                    message: response.message,
                                    position: 'topRight',
                                });
                            }
                        },
                        complete: function() {
                            KTApp.unblock();
                        }
                    });
                }

                function createexp() {
                    let e = document.getElementById("kt_select2_2");
                    let supplier = e.options[e.selectedIndex].value;
                    let qty = document.getElementById('qty_exp').value;
                    let lot = document.getElementById('lot_exp').value;
                    let harga_beli = document.getElementById('harga_beli_exp').value;
                    let diskon_persen = document.getElementById('diskon_persen_exp').value;
                    let diskon_rupiah = document.getElementById('diskon_rupiah_exp').value;
                    let tanggal = document.getElementById('tanggal_exp').value;
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('laporanstok.createexp') }}',
                        dataType: 'html',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            KTApp.blockPage();
                        },
                        data: {
                            "id": id,
                            "qty": qty,
                            "status": status,
                            "lot": lot,
                            "harga_beli": harga_beli,
                            "diskon_persen": diskon_persen,
                            "diskon_rupiah": diskon_rupiah,
                            "supplier": supplier,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            $('#tambahexp').modal('hide');
                            iziToast.success({
                                title: 'Success',
                                message: 'Data Berhasil Ditambahkan',
                                position: 'topRight',
                            });

                            // hilangkan value pada form tersebut
                            document.getElementById('qty_exp').value = 0;
                            document.getElementById('lot_exp').value = '';
                            document.getElementById('harga_beli_exp').value = '';
                            document.getElementById('diskon_persen_exp').value = '';
                            document.getElementById('diskon_rupiah_exp').value = '';
                            document.getElementById('tanggal_exp').value = '';


                            $('.yajra-datatable-dataexp').DataTable().ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            const response = JSON.parse(xhr.responseText);
                            if (xhr.status === 422) {
                                // Error qty melebihi stok
                                iziToast.error({
                                    title: 'error',
                                    message: response.message,
                                    position: 'topRight',
                                });
                            }

                            if (xhr.status === 500) {
                                // Error qty melebihi stok
                                iziToast.error({
                                    title: 'error',
                                    message: response.message,
                                    position: 'topRight',
                                });
                            }
                        },
                        complete: function() {
                            KTApp.unblock();
                        }
                    });
                }

                function hapus(id) {
                    Swal.fire({
                        icon: "question",
                        title: "Mau menghapus data ini ?",
                        showCancelButton: true,
                        confirmButtonText: "Save",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST',
                                url: '{{ route('laporanstok.hapusexp') }}',
                                dataType: 'html',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                beforeSend: function() {
                                    KTApp.blockPage();
                                },
                                data: {
                                    "id": id,
                                    "status": status,
                                    "_token": "{{ csrf_token() }}"
                                },
                                success: function(data) {
                                    iziToast.success({
                                        title: 'Success',
                                        message: 'Data Berhasil Dihapus',
                                        position: 'topRight',
                                    });

                                    $('.yajra-datatable-dataexp').DataTable().ajax.reload(null, false);
                                },
                                error: function(xhr) {
                                    const response = JSON.parse(xhr.responseText);
                                    if (xhr.status === 422) {
                                        // Error qty melebihi stok
                                        iziToast.error({
                                            title: 'error',
                                            message: response.message,
                                            position: 'topRight',
                                        });
                                    }

                                    if (xhr.status === 500) {
                                        // Error qty melebihi stok
                                        iziToast.error({
                                            title: 'error',
                                            message: response.message,
                                            position: 'topRight',
                                        });
                                    }
                                },
                                complete: function() {
                                    KTApp.unblock();
                                }
                            });
                        }
                    });

                }
            </script>
        @endpush
