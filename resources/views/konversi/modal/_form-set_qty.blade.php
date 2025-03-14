<div class="modal fade" id="setQtyModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Set Jumlah Konversi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" style="height: 400px;">
                <form action="{{ route('konversisatuan.inputqty') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Nama Barang</label>
                            <div class="col-lg-10">
                                <input type="text" readonly="readonly" disabled="disabled"
                                    class="form-control form-control-solid" name="nama" id="nama"
                                    @if ($status == '1') 
                                        value="{{ $exp->products->nama }}" 
                                    @else
                                        value="{{ $exp->product->nama }}"
                                    @endif  />
                                <input type="hidden" id="exp_id" name="exp_id" value="{{ $exp->id }}">
                                <input type="hidden" id="status" name="status" value="{{ $status }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Stok</label>
                            <div class="col-lg-2">
                                <input type="text" readonly="readonly" class="form-control" id="qty_stok"
                                    name="qty_stok" value="{{ $exp->qty }}" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Jumlah Konversi <i class="text-danger">*</i> <i
                                    style="font-size: 70%">jumlah konversi harus dibawah stok</i></label>
                            <div class="col-lg-2">
                                <input type="number" class="form-control" id="qty_konversi" name="qty_konversi"
                                    max="{{ $exp->qty }}" min="0" required />
                            </div>
                        </div>

                       
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success mr-2">Submit</button>
                        <button type="button" class="btn btn-light-primary font-weight-bold"
                            data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
