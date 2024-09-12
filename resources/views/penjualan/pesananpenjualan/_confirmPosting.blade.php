<!-- Modal-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            @if($can_posting == "YES")
            <form id="destroy-form" action="{{ route('pesananpenjualan.posted') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Posting Pesanan Penjualan?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2">
                            <span class="svg-icon svg-icon-primary svg-icon-4x">
                                <!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo2\dist/../src/media/svg/icons\Code\Warning-2.svg--><svg
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M11.1669899,4.49941818 L2.82535718,19.5143571 C2.557144,19.9971408 2.7310878,20.6059441 3.21387153,20.8741573 C3.36242953,20.9566895 3.52957021,21 3.69951446,21 L21.2169432,21 C21.7692279,21 22.2169432,20.5522847 22.2169432,20 C22.2169432,19.8159952 22.1661743,19.6355579 22.070225,19.47855 L12.894429,4.4636111 C12.6064401,3.99235656 11.9909517,3.84379039 11.5196972,4.13177928 C11.3723594,4.22181902 11.2508468,4.34847583 11.1669899,4.49941818 Z"
                                            fill="#000000" opacity="0.3" />
                                        <rect fill="#000000" x="11" y="9" width="2" height="7" rx="1" />
                                        <rect fill="#000000" x="11" y="17" width="2" height="2" rx="1" />
                                    </g>
                                </svg>
                            </span>
                        </div>
                        <div class="col-md-10 " style="display: inline;">
                            <div class="align-middle">
                                Proses Posting jika pesanan penjualan sudah final dan dapat dilakukan proses pengiriman
                                barang.
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold"
                        data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger font-weight-bold">Yes, Posting Now !</button>
                </div>
            </form>
            @else
            <form id="destroy-form" action="#">
                <input type="hidden" name="id" value="{{ $id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tidak Dapat Memposting Pesanan Pembelian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2">
                            <span class="svg-icon svg-icon-primary svg-icon-4x">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M11.1669899,4.49941818 L2.82535718,19.5143571 C2.557144,19.9971408 2.7310878,20.6059441 3.21387153,20.8741573 C3.36242953,20.9566895 3.52957021,21 3.69951446,21 L21.2169432,21 C21.7692279,21 22.2169432,20.5522847 22.2169432,20 C22.2169432,19.8159952 22.1661743,19.6355579 22.070225,19.47855 L12.894429,4.4636111 C12.6064401,3.99235656 11.9909517,3.84379039 11.5196972,4.13177928 C11.3723594,4.22181902 11.2508468,4.34847583 11.1669899,4.49941818 Z"
                                            fill="#000000" opacity="0.3" />
                                        <rect fill="#000000" x="11" y="9" width="2" height="7" rx="1" />
                                        <rect fill="#000000" x="11" y="17" width="2" height="2" rx="1" />
                                    </g>
                                </svg>
                            </span>
                        </div>
                        <div class="col-md-10 " style="display: inline;">
                            <div class="align-middle">
                                Tidak dapat melakukan Posting Pesanan Pembelian / Purchase Order (sudah diposting atau
                                sudah dibatalkan)
                                !
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold"
                        data-dismiss="modal">Cancel</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
<!-- Modal-->