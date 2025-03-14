<div class="modal fade" id="formexp" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="modal-header">
                            <h5>Input Expired</h5>
                        </div>
                        <div class="modal-body">                           
                                <form action="">
                                    <div class="form-group">
                                        <label for="">Tanggal Exp</label>
                                        <input type="text" class="form-control" id="tgl1">                                        
                                        <input type="hidden" id="detail_id" value="{{ $penerimaanbarangdet->id }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Lot</label>
                                        <input type="text" id="lot" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Qty</label>
                                        <input type="number" value="0" id="qty" class="form-control">
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" onclick="javascript:submitexp();"
                                            class="btn btn-success mr-2">Submit</button>
                                        <button type="button" class="btn btn-light-primary font-weight-bold"
                                            data-dismiss="modal">Close</button>
                                    </div>
                                </form>                            
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="modal-header">
                            <h5>Data Expired</h5> 
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i aria-hidden="true" class="ki ki-close"></i>
                            </button>
                           
                        </div>
                        <div class="modal-body">
                            <table>
                                <tr>
                                    <th>Produk</th>
                                    <td>:</td>
                                    <td> {{ $penerimaanbarangdet->products->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Qty Diterima</th>
                                    <td>:</td>
                                    <td> {{ $penerimaanbarangdet->qty }}</td>
                                </tr>
                            </table>
                            <table class="table yajra-datatable-exp collapsed">
                                <thead class="datatable-head">
                                    <tr>                                                                                
                                        <th>Tanggal Exp</th>
                                        <th>Lot</th>
                                        <th>Qty</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div>

</div>

<script src="{{ asset('/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.6') }}"></script>