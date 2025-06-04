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
                <!-- begin::Card-->
                <div class="card card-custom overflow-hidden">
                    <div class="card-body p-0">
                        <!-- begin: Invoice-->
                        <!-- begin: Invoice header-->
                        <div class="row justify-content-center py-8 px-8 py-md-27 px-md-0">
                            <div class="col-md-9">
                                <div class="d-flex justify-content-between pb-10 pb-md-20 flex-column flex-md-row">
                                    <h1 class="display-4 font-weight-boldest mb-10">FAKTUR PENJUALAN</h1>
                                    <div class="d-flex flex-column align-items-md-end px-0">
                                        <!--begin::Logo-->
                                        <a href="#" class="mb-5">
                                            <img src="assets/media/logos/logo-dark.png" alt="" />
                                        </a>
                                        <!--end::Logo-->
                                        <span class=" d-flex flex-column align-items-md-end opacity-70">
                                            <span>{{ $fakturpenjualan->tanggal->format('d F Y') }}</span>
                                            <span>{{ $fakturpenjualan->creator->name }}</span>
                                            <span
                                                class="font-weight-bold font-italic text-primary font-size-h3">{{ $fakturpenjualan->kode }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="border-bottom w-100"></div>
                                <div class="d-flex justify-content-between pt-6">
                                    <div class="d-flex flex-column flex-root">
                                        <span class="font-weight-bolder mb-2">CUSTOMER</span>
                                        <span class="opacity-70">{{ $fakturpenjualan->customers->nama }}</span>
                                    </div>

                                    <div class="d-flex flex-column flex-root">
                                        <span class="font-weight-bolder mb-2">NPWP</span>
                                        <span class="opacity-70">{{ $fakturpenjualan->customers->npwp }}</span>
                                    </div>

                                    <div class="d-flex flex-column flex-root">
                                        <span class="font-weight-bolder mb-2">ALAMAT</span>
                                        <span class="opacity-70">{{ $fakturpenjualan->customers->alamat }}</span>
                                    </div>

                                    <div class="d-flex flex-column flex-root">
                                        <span class="font-weight-bolder mb-2">PENGIRIMAN BARANG</span>
                                        <span class="opacity-70">{{ $fakturpenjualan->SJ->kode }}</span>
                                    </div>
                                    <div class="d-flex flex-column flex-root">
                                        <span class="font-weight-bolder mb-2">SURAT PESANAN</span>
                                        <span class="opacity-70">{{ $fakturpenjualan->SO->kode }}</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between pt-6">
                                    <div class="d-flex flex-column flex-root">
                                        <span class="font-weight-bolder mb-2">No. Invoice KPA</span>
                                        <span class="opacity-70">{{ $fakturpenjualan->no_kpa }}</span>
                                    </div>
                                    <div class="d-flex flex-column flex-root">
                                        <span class="font-weight-bolder mb-2">No. Faktur Pajak</span>
                                        <span
                                            class="opacity-70">{{ $fakturpenjualan->nopajak && $fakturpenjualan->no_seri_pajak ? $fakturpenjualan->no_seri_pajak . '-' . $fakturpenjualan->no_pajak : '-' }}</span>
                                    </div>
                                    <div class="d-flex flex-column flex-root">
                                        <span class="font-weight-bolder mb-2">SO Customer</span>
                                        <span class="opacity-70">{{ $fakturpenjualan->SO->no_so }}</span>
                                    </div>
                                    <div class="d-flex flex-column flex-root">
                                        <span class="font-weight-bolder mb-2">Tanggal SO Customer</span>
                                        <span
                                            class="opacity-70">{{ $fakturpenjualan->SO->tanggal_pesanan_customer ? \Carbon\Carbon::parse($fakturpenjualan->SO->tanggal_pesanan_customer)->format('d F Y') : '-' }}</span>
                                    </div>
                                    <div class="d-flex flex-column flex-root">
                                        <span class="font-weight-bolder mb-2"></span>
                                        <span class="opacity-70"></span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end: Invoice header-->


                        <!-- begin: Invoice body-->
                        <div class="row justify-content-center py-8 px-8 py-md-10 px-md-0">
                            <div class="col-md-9">
                                <a href="{{ route('fakturpenjualan.syncronisasi2', ['id' => $fakturpenjualan->id]) }}"
                                    class="btn btn-primary btn-sm"><i class="flaticon2-reload-1"></i> Atur Qty</a>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="pl-0 font-weight-bold text-muted  text-uppercase">Kode
                                                </th>
                                                <th class="text-left font-weight-bold text-muted text-uppercase">Produk
                                                </th>
                                                <th class="text-left font-weight-bold text-muted text-uppercase">Satuan
                                                </th>
                                                <th class="text-left font-weight-bold text-muted text-uppercase">Qty</th>
                                                <th class="text-left font-weight-bold text-muted text-uppercase">Harga</th>
                                                <th class="text-left font-weight-bold text-muted text-uppercase">Disk.(%)
                                                </th>
                                                <th class="text-left font-weight-bold text-muted text-uppercase">
                                                    Disk.(Rp.)</th>
                                                <th class="text-left font-weight-bold text-muted text-uppercase">Subtotal
                                                </th>
                                                <th class="text-left font-weight-bold text-muted text-uppercase">Total
                                                    Disc.</th>
                                                <th class="text-left font-weight-bold text-muted text-uppercase">CN (%)</th>
                                                <th class="text-left font-weight-bold text-muted text-uppercase">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($fakturpenjualandetails as $a)
                                                <tr class="font-weight-boldest">
                                                    <td class="pl-0 pt-7">{{ $a->products->kode }}</td>
                                                    <td class="text-left pt-7">{{ $a->products->nama }}</td>
                                                    <td class="text-left pt-7">{{ $a->satuan }}</td>
                                                    <td class=" pt-7 text-left">{{ $a->qty }}</td>
                                                    <td class=" pt-7 text-left">
                                                        {{ number_format($a->hargajual, 2, ',', '.') }}
                                                    </td>
                                                    <td class=" pt-7 text-left">{{ $a->diskon_persen }}</td>
                                                    <td class=" pt-7 text-left">{{ $a->diskon_rp }}</td>
                                                    <td class=" pt-7 text-left">
                                                        {{ number_format($a->subtotal, 2, ',', '.') }}
                                                    </td>
                                                    <td class=" pt-7 text-left">
                                                        {{ number_format($a->total_diskon, 2, ',', '.') }}</td>
                                                    <td class=" pr-0 pt-7 text-left">
                                                        {{ $a->cn_persen ? $a->cn_persen : 0 }}%</td>
                                                    <td class="text-danger pr-0 pt-7 text-left">
                                                        {{ number_format($a->total, 2, ',', '.') }}</td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- end: Invoice body-->

                        <!-- begin: Invoice footer-->
                        <div class="row justify-content-center bg-gray-100 py-8 px-8 py-md-10 px-md-0">
                            <div class="col-md-9">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="font-weight-bold text-muted  text-uppercase">SUBTOTAL</th>
                                                <th class="font-weight-bold text-muted  text-uppercase">DISKON TAMBAHAN</th>
                                                <th class="font-weight-bold text-muted  text-uppercase">TOTAL</th>
                                                <th class="font-weight-bold text-muted  text-uppercase">PPN</th>
                                                <th class="font-weight-bold text-muted  text-uppercase">ONGKIR</th>
                                                <th class="font-weight-bold text-muted  text-uppercase">BIAYA LAIN-LAIN</th>

                                                <th class="font-weight-bold text-muted  text-uppercase">GRANDTOTAL</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="font-weight-bolder">
                                                <td>{{ number_format($fakturpenjualan->subtotal, 2, ',', '.') }}</td>
                                                <td>{{ number_format($fakturpenjualan->total_diskon_header, 2, ',', '.') }}
                                                </td>
                                                <td>{{ number_format($fakturpenjualan->total, 2, ',', '.') }}</td>
                                                <td>{{ number_format($fakturpenjualan->ppn, 2, ',', '.') }}</td>
                                                <td>{{ number_format($fakturpenjualan->ongkir, 2, ',', '.') }}</td>
                                                <td>{{ number_format($fakturpenjualan->biaya_lain, 2, ',', '.') }}</td>
                                                <td class="text-danger font-size-h5 font-weight-boldest">
                                                    {{ number_format($fakturpenjualan->grandtotal, 2, ',', '.') }}</td>
                                            </tr>
                                        </tbody>

                                    </table>

                                    @can('fakturpenjulan-edit')
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="font-weight-bold text-muted  text-uppercase">JUMLAH CN</th>
                                                    <th class="font-weight-bold text-muted  text-uppercase">Saldo Harga Bersih
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>{{ $fakturpenjualan->total_cn ? number_format($fakturpenjualan->total_cn, 0, ',', '.') : '0' }}
                                                    </td>

                                                    <td>
                                                        {{ number_format($fakturpenjualan->grandtotal - $fakturpenjualan->total_cn, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                        </table>
                                    @endcan


                                    <br />
                                    <h4>Keterangan :</h4>
                                    <p>{{ $fakturpenjualan->keterangan }} </p>
                                </div>
                            </div>
                        </div>
                        <!-- end: Invoice footer-->
                        <!-- begin: Invoice footer-->
                        <div class="row justify-content-center  py-8 px-8 py-md-10 px-md-0">
                            <div class="col-md-9">
                                <div class="border-bottom w-100"></div>


                                <div class="card card-custom gutter-b">
                                    <div class="card-header">
                                        <div class="card-title justify-content-center">
                                            <h3 class="card-label  ">
                                                Info

                                            </h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="font-weight-bold text-muted  text-uppercase">Created at</th>
                                                    <th class="font-weight-bold text-muted  text-uppercase">Created by
                                                    </th>
                                                    <th class="font-weight-bold text-muted  text-uppercase">Updated At</th>
                                                    <th class="font-weight-bold text-muted  text-uppercase">Updated By</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="font-weight-bolder">
                                                    <td>{{ $fakturpenjualan->created_at }}</td>

                                                    <td>{{ $fakturpenjualan->creator->name }}</td>
                                                    <td>{{ $fakturpenjualan->updated_at }}</td>
                                                    <td>{{ $fakturpenjualan->updater->name }}</td>

                                                </tr>

                                            </tbody>
                                        </table>

                                    </div>
                                </div>


                            </div>
                        </div>
                        <!-- end: Invoice footer-->
                        <!-- begin: Invoice action-->
                        <div class="row justify-content-center py-8 px-8 py-md-10 px-md-0">

                            <div class="col-md-9">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex justify-content-between ">
                                        @can('fakturpenjualan-create')
                                            <button type="button" class="btn btn-danger dropdown-toggle mr-2"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Print &
                                                Download</button>

                                            <div class="dropdown-menu">
                                                <a href="{{ route('fakturpenjualan.print_a4_koma', $fakturpenjualan) }}"
                                                    class="btn btn-primary mr-2" target="_blank">
                                                    <i class="flaticon2-print font-weight-bold"></i> Download & Print Koma
                                                </a>

                                                <a href="{{ route('fakturpenjualan.print_a4', $fakturpenjualan) }}"
                                                    class="btn btn-primary mr-2" target="_blank">
                                                    <i class="flaticon2-print font-weight-bold"></i> Download & Print Tanpa
                                                    Koma
                                                </a>
                                            </div>
                                        @endcan
                                        @can('fakturpenjualan-delete')
                                            <a href="{{ route('fakturpenjualan.kwitansi', $fakturpenjualan) }}"
                                                class="btn btn-success " target="_blank">
                                                <i class="flaticon2-print font-weight-bold"></i> Kwitansi
                                            </a>
                                        @endcan

                                        @can('fakturpenjualan-delete')
                                            <a href="{{ route('fakturpenjualan.editCN', $fakturpenjualan) }}"
                                                class="btn btn-warning ml-4">
                                                <i class="flaticon2-print font-weight-bold"></i> CN
                                            </a>
                                        @endcan

                                        @can('fakturpenjualan-delete')
                                            <a href="{{ route('fakturpenjualan.biayalain.index', ['fakturpenjualan' => $fakturpenjualan->id]) }}"
                                                class="btn btn-info ml-4">
                                                <i class="fas fa-cash-register"></i> Biaya Lain - Lain
                                            </a>
                                        @endcan

                                        @can('fakturpenjualan-delete')
                                            <button type="button" class="btn btn-danger ml-4" data-toggle="modal"
                                                data-target="#exampleModalLong">
                                                <i class="flaticon-tool "></i> Tanda Terima
                                            </button>
                                        @endcan

                                        @can('fakturpenjualan-delete')
                                              <button type="button" class="btn btn-success ml-4" data-toggle="modal"
                                                data-target="#tandaterimaberkas">
                                                <i class="flaticon-email "></i> Tanda Terima Berkas
                                            </button>
                                        @endcan


                                        @can('labarugi-list')
                                            <a href="{{ route('fakturpenjualan.labarugi.show', ['fakturpenjualan' => $fakturpenjualan->id]) }}"
                                                class="btn btn-primary ml-4">
                                                <i class="fas fa-paper-plane"></i> Laba / Rugi
                                            </a>
                                        @endcan
                                    </div>

                                    <a class="btn btn-danger font-weight-bold"
                                        href="{{ url('penjualan/fakturpenjualan') }}">Back
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- end: Invoice action-->

                        <!-- end: Invoice-->
                    </div>
                </div>
                <!-- end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
    <!--end::Content-->


    {{-- MODAL TANDA TERIMA --}}
    <div class="modal fade" id="exampleModalLong" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tanda Terima</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                @if ($fakturpenjualan->status_diterima !== null)
                    <div class="modal-body">
                        <form action="{{ route('fakturpenjualan.edittandaterima', ['id' => $fakturpenjualan->id]) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="">Tanggal Diterima</label>
                                <input type="date" class="form-control"
                                    value="{{ $fakturpenjualan->tanggal_diterima }}" name="tanggal_diterima">
                            </div>
                            <div class="form-group">
                                <label for="">No Resi</label>
                                <input type="text" class="form-control" value="{{ $fakturpenjualan->no_resi }}"
                                    name="no_resi">
                            </div>
                            <div class="form-group">
                                <label for="">Foto Bukti</label> <br>
                                <img src="{{ asset('storage/bukti_tandaterima/' . $fakturpenjualan->foto_bukti) }}"
                                    alt="" width="30%" style="margin-bottom:10px">
                                <a href="{{ asset('storage/bukti_tandaterima/' . $fakturpenjualan->foto_bukti) }}"
                                    class="btn btn-primary btn-sm" download><i class="fas fa-download"></i></a>
                                <input type="file" class="form-control" name="foto_bukti">
                            </div>
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="status_diterima" id="" class="form-control">
                                    <option value="{{ $fakturpenjualan->status_diterima }}" selected>
                                        {{ ucfirst($fakturpenjualan->status_diterima) }}</option>
                                    <option value="terima">Terima</option>
                                    <option value="belum terima">Belum Diterima</option>
                                </select>
                            </div>
                           
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary font-weight-bold"
                                    data-dismiss="modal">Close</button>
                                <button type="input" class="btn btn-primary font-weight-bold">Save changes</button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="modal-body">
                        <form action="{{ route('fakturpenjualan.tandaterima', ['id' => $fakturpenjualan->id]) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">Tanggal Diterima</label>
                                <input type="date" class="form-control" name="tanggal_diterima">
                            </div>
                            <div class="form-group">
                                <label for="">No Resi</label>
                                <input type="text" class="form-control" name="no_resi">
                            </div>
                            <div class="form-group">
                                <label for="">Foto Bukti Terima Barang</label>
                                <input type="file" class="form-control" name="foto_bukti">
                            </div>
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="status_diterima" id="" class="form-control">
                                    <option selected disabled>Pilih Status</option>
                                    <option value="terima">Diterima</option>
                                    <option value="belum terima">Belum Diterima</option>
                                </select>
                            </div>                          
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary font-weight-bold"
                                    data-dismiss="modal">Close</button>
                                <button type="input" class="btn btn-primary font-weight-bold">Save changes</button>
                            </div>
                        </form>
                    </div>
                @endif


            </div>
        </div>
    </div>
    {{-- END MODAL  --}}

      {{-- MODAL TANDA TERIMA --}}
    <div class="modal fade" id="tandaterimaberkas" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tanda Terima Berkas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                @if ($fakturpenjualan->status_berkas !== null)
                    <div class="modal-body">
                        <form action="{{ route('fakturpenjualan.editterimaberkas', ['id' => $fakturpenjualan->id]) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="">Tanggal Diterima Berkas</label>
                                <input type="date" class="form-control"
                                    value="{{ $fakturpenjualan->tanggal_terima_berkas }}" name="tanggal_terima_berkas">
                            </div>
                            <div class="form-group">
                                <label for="">No Resi</label>
                                <input type="text" class="form-control" value="{{ $fakturpenjualan->no_resi_berkas }}"
                                    name="no_resi_berkas">
                            </div>
                            <div class="form-group">
                                <label for="">Foto Bukti</label> <br>
                                <img src="{{ asset('storage/bukti_tandaterima_berkas/' . $fakturpenjualan->foto_bukti_berkas) }}"
                                    alt="" width="30%" style="margin-bottom:10px">
                                <a href="{{ asset('storage/bukti_tandaterima_berkas/' . $fakturpenjualan->foto_bukti_berkas) }}"
                                    class="btn btn-primary btn-sm" download><i class="fas fa-download"></i></a>
                                <input type="file" class="form-control" name="foto_bukti_berkas">
                            </div>
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="status_berkas" id="" class="form-control">
                                    <option value="{{ $fakturpenjualan->status_berkas }}" selected>
                                        {{ ucfirst($fakturpenjualan->status_berkas) }}</option>
                                    <option value="terima">Terima</option>
                                    <option value="belum terima">Belum Diterima</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Apakah Jatuh Tempo terhitung dari tanggal tanda terima ? </label>
                                <select name="top_status" class="form-control" id="">
                                    <option value="{{ $fakturpenjualan->status_tanggaltop }}" selected>
                                        {{ ucfirst($fakturpenjualan->status_tanggaltop) }}</option>
                                    <option value="ya">Ya</option>
                                    <option value="tidak">Tidak</option>
                                </select>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary font-weight-bold"
                                    data-dismiss="modal">Close</button>
                                <button type="input" class="btn btn-primary font-weight-bold">Save changes</button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="modal-body">
                          <form action="{{ route('fakturpenjualan.inputterimaberkas', ['id' => $fakturpenjualan->id])}}"
                            method="POST" enctype="multipart/form-data">
                            @csrf                            
                            <div class="form-group">
                                <label for="">Tanggal Diterima Berkas</label>
                                <input type="date" class="form-control" name="tanggal_terima_berkas">
                            </div>
                            <div class="form-group">
                                <label for="">No Resi</label>
                                <input type="text" class="form-control" name="no_resi_berkas">
                            </div>
                            <div class="form-group">
                                <label for="">Foto Bukti</label> <br>                                
                                <input type="file" class="form-control" name="foto_bukti_berkas">
                            </div>
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="status_diterima" id="" class="form-control">                                                                          
                                    <option value="terima">Terima</option>
                                    <option value="belum terima">Belum Diterima</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Apakah Jatuh Tempo terhitung dari tanggal tanda terima ? </label>
                                <select name="top_status" class="form-control" id="">                                    
                                    <option value="ya">Ya</option>
                                    <option value="tidak">Tidak</option>
                                </select>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary font-weight-bold"
                                    data-dismiss="modal">Close</button>
                                <button type="input" class="btn btn-primary font-weight-bold">Save changes</button>
                            </div>
                        </form>
                    </div>
                @endif


            </div>
        </div>
    </div>
    {{-- END MODAL  --}}
@endsection
@push('script')
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
    <script src="{{ asset(' /assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.6') }}"></script>


    <script type="text/javascript"></script>
@endpush
