<div class="modal fade" id="setbiayalain" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Biaya Lain</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" style="height: 400px;">
                <form class="form">
                    <div class="card-body">                        
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Ongkir</label>
                            <div class="col-lg-10">
                                <input type="number" class="form-control" id="ongkir" name="ongkir"
                                    value="0" />
                            </div>
                        </div>                       
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Materai</label>
                            <div class="col-lg-10">
                                <input type="number" class="form-control" id="materai" name="materai"
                                    value="0" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">Close</button>
                <button type="button" onclick="submitbiaya();"
                    class="btn btn-success ">Submit</button>
            </div>
        </div>


    </div>

</div>
