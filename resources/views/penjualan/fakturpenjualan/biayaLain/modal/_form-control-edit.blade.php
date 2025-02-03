<div class="modal fade" id="editData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form class="form" method="post">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Biaya Lain - Lain</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">            
                 
                    <div class="card-body">    
                        <div class="form-group">
                            <label>Jenis Biaya :</label> <br>                            
                            <select class="form-control " name="jenisbiaya_id" id="jenisbiaya_id" >
                    
                                @foreach ($jenisbiaya as $item)
                                    @if ($item->id == $biayalain->jenisbiaya_id)
                                      <option value="{{$item->id}}" selected>{{$item->nama}}</option>  
                                     @else 
                                      <option value="{{$item->id}}">{{$item->nama}}</option> 
                                     @endif
                                             
                                @endforeach
                    
                            </select>                            
                            
                            @error('jenisbiaya_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <input type="hidden" id="fakturpenjualan_id" name="fakturpenjualan_id" value="{{$biayalain->fakturpenjualan_id}}">
                        <input type="hidden" name="id" id="id" value="{{$biayalain->id}}">
                        
                        <div class="form-group">
                            <label>Nominal :</label>
                            <input type="number" name="nominal" id="nominal" value="{{$biayalain->nominal}}"
                                class="form-control @error('nominal') is-invalid @enderror" placeholder="Masukkan Nominal Biaya" />
                            @error('nominal')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="form-group">
                            <label for="">Pengurangan CN ?</label>
                            <select name="pengurangan_cn" class="form-control" id="pengurangan_cn" selected>
                                @if ($biayalain->pengurangan_cn == 1)
                                <option value="1" selected>Ya</option> 
                                <option value="0">No</option>
                                @else
                                <option value="1" >Ya</option> 
                                <option value="0" selected>No</option>
                                @endif
                                
                                
                            </select>
                
                        </div>
                    
                        <div class="form-group">
                            <label>Keterangan :</label>
                            <input type="text" name="keterangan" id="keterangan" value="{{$biayalain->keterangan}}"
                                class="form-control" placeholder="Keterangan" />
                            @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="javascript:update()" class="btn btn-success font-weight-bold mr-2"><i class="flaticon2-paperplane"></i>
                           Submit</button>
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                    </div>
            </form>
      </div>
    </div>
  </div>