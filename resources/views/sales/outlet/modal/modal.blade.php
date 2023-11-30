<!-- Modal-->
<div class="modal fade" id="tambahdata" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
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
                        <label for="">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control">
                     </div>

                     <div class="form-group">
                        <label for="">Area</label>
                        <input type="text" name="area" id="area" class="form-control">
                     </div>

                     <div class="form-group">
                        <label for="">Sales</label>   
                        <select name="" id="id_sales" class="form-control">
                            @foreach ($sales as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>    
                            @endforeach                            
                        </select>                
                     </div>

                     <div class="form-group">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary font-weight-bold" onClick="javascript:store();">Save changes</button>
                    </div>
                </form>
            </div>
           
        </div>
    </div>
</div>