<!-- Modal-->
@if($mode == "new")
<div class="modal fade" id="setBarangModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Opsi Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" style="height: 400px;">

                <form class="form">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Nama Barang</label>
                            <div class="col-lg-10">
                                <input type="text" readonly="readonly" disabled="disabled"
                                    class="form-control form-control-solid" name="nama" id="nama"
                                    value="{{ $product->nama }}" />
                                <input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">
                            </div>
                        </div>                    
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Satuan </label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control form-control-solid" readonly="readonly"
                                    id="satuan" name="satuan" value="{{ $product->satuan }}" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Stok Konversi</label>
                            <div class="col-lg-10">
                                <input type="number" class="form-control" id="stok_konversi" name="stok_konversi" />
                            </div>
                        </div>  
                        
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Harga Beli</label>
                            <div class="col-lg-10">
                                <input type="number" class="form-control" id="harga_beli" name="harga_beli" />
                            </div>
                        </div>  

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Diskon(%)</label>
                            <div class="col-lg-4">
                                <input type="number" class="form-control" id="diskon_persen"  name="diskon_persen" />                                
                            </div> 
                            
                            <label class="col-lg-2 col-form-label">Disc(Rp)</label>
                            <div class="col-lg-4">
                                <input type="number" class="form-control" id="diskon_rupiah" name="diskon_rupiah" />                                
                            </div>                                               
                        </div>  

                     
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Keterangan</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="keterangan" name="keterangan" value="" />
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" onclick="javascript:submitItem();" class="btn btn-success mr-2">Submit</button>

            </div>
        </div>
    </div>

</div>
@else
<div class="modal fade" id="setBarangModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" style="height: 400px;">

                <form class="form">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Nama Barang</label>
                            <div class="col-lg-10">
                                <input type="text" readonly="readonly" disabled="disabled"
                                    class="form-control form-control-solid" name="nama" id="nama"
                                    value="{{ $product_name }}" />
                                <input type="hidden" id="product_id" name="product_id" value="{{ $item->product_id }}">                                
                            </div>
                        </div>                       
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Satuan </label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control form-control-solid" readonly="readonly"
                                    id="satuan" name="satuan" value="{{ $item->satuan }}" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Stok Konversi</label>
                            <div class="col-lg-10">
                                <input type="number" class="form-control" id="stok_konversi" name="stok_konversi" value="{{ $item->qty }}"/>
                            </div>
                        </div> 

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Harga Beli</label>
                            <div class="col-lg-10">
                                <input type="number" class="form-control" id="harga_beli" value="{{$item->harga_beli}}" name="harga_beli" />
                            </div>
                        </div>  

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Diskon(%)</label>
                            <div class="col-lg-4">
                                <input type="number" class="form-control" id="diskon_persen" value="{{$item->diskon_persen}}"  name="diskon_persen" />                                
                            </div> 
                            
                            <label class="col-lg-2 col-form-label">Disc(Rp)</label>
                            <div class="col-lg-4">
                                <input type="number" class="form-control" id="diskon_rupiah" value="{{$item->diskon_rupiah}}" name="diskon_rupiah" />                                
                            </div>                                               
                        </div>  
                       
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Keterangan</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="keterangan" name="keterangan"
                                    value="{{ $item->keterangan }}" />
                            </div>
                        </div>

                    </div>
                </form>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" onclick="javascript:updateItem({{$item->id}});" class="btn btn-success mr-2">Update</button>

            </div>
        </div>
    </div>

</div>
@endif
<!-- Modal-->