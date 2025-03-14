<div class="card-body">
   <div class="form-group row">
        <label class="col-lg-1 col-form-label text-right">Customer:</label>
        <div class="col-lg-4">
           <input type="text" class="form-control" value="{{$canvas->customer->nama}}" readonly disabled>           
        </div>
        <label class="col-lg-2 col-form-label text-right">Tanggal:</label>
        <div class="col-lg-4">
            <div class="input-group date">              
                  <input type="text" class="form-control" name="tanggal" value="{{ $tglNow}}" id="tgl1" />                    
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-calendar"></i>
                    </span>
                </div>
            </div>            
        </div>
        <input type="hidden" name="canvassing_id" value="{{$canvas->id}}">
    </div>

    
    <h6><i class="flaticon-add-label-button text-danger"></i> Daftar Produk Canvasing</h6>
    

    <div class="form-group row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table yajra-datatablecanvassing collapsed ">
                    <thead class="datatable-head">
                        <tr>                            
                            <th>Nama Barang</th>                            
                            <th>Qty</th>
                            <th>Qty Sisa</th>                            
                            <th>Status</th>                            
                            <th>Action</th>                                                        
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div> 

    
    <hr>
    <h6><i class="flaticon-add-label-button text-danger"></i> Produk Terpilih</h6>
    
    <div class="form-group row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table yajra-datatableinput collapsed ">
                    <thead class="datatable-head">
                        <tr>                            
                            <th style="width: 50%">Nama Barang</th>                            
                            <th>Qty</th>
                            <th>Qty Sisa</th>
                            <th>Qty Kembali</th>                                                        
                            <th>Action</th>                                                        
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="separator separator-dashed my-5"></div>
    <div class="form-group row">
        <div class="col-lg-6">
            <label class="">Keterangan:</label>
            <div class="kt-input-icon kt-input-icon--right">
                <textarea class="form-control" name="keterangan" id="keterangan"></textarea>
            </div>

        </div>
        <div class="col-lg-6">


        </div>

    </div>


</div>

</div>
<!--end::Form-->
<div class="card-footer text-right">
    <div class="row">
        <div class="col-lg-12 ">
            <button type="submit" class="btn btn-success font-weight-bold mr-2"><i class="flaticon2-paperplane"></i>
                {{ $submit }}</button>
            <a href="{{ route('pengirimanbarang.listso') }}" class="btn btn-secondary font-weight-bold mr-2">
                Cancel</a>
        </div>
    </div>
</div>