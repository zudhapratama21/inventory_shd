<!-- Modal-->
<div class="modal fade" id="editpengumuman" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document" style="height: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pengumuman</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" style="height: 300px;">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="">Tujuan Informasi</label> <br>
                        <select name="tujuan_id" class="form-control" id="kt_select2_3" multiple="multiple" required>                                                        
                            @foreach ($pengumuman->bisalihat as $item)
                               <option value="{{ $item->divisi_id }}" selected>{{ $item->divisi->nama }}</option>
                            @endforeach
                            @foreach ($divisi as $item)                            
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Topic</label> <br>
                        <select name="" id="topic_id" class="form-control" required>
                            @foreach ($topic as $item)
                                @if ($pengumuman->topic_id)
                                    <option value="{{ $item->id }}" selected>{{ $item->nama }}</option>    
                                @else
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>                                   
                                @endif
                                
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Subject</label>
                        <input type="text" class="form-control" name="subject" id="subject" value="{{$pengumuman->subject}}">
                    </div>

                    <div class="form-group">
                        <label for="">Informasi</label>
                        <textarea name="kt-ckeditor-2" id="editor2" class="form-control" cols="30" rows="5" required>{{$pengumuman->description}}</textarea>
                    </div>


                    <div class="form-group">
                        <label for="">Upload File</label> <br>
                        <a href="{{ asset('storage/pengumuman/' . $pengumuman->file) }}" class="btn btn-primary btn-sm" download><i class="fas fa-download"></i>Download File</a>                                            
                        <input type="file" class="form-control" id="file_update" name="file" onchange="updateimage(event)">
                    </div>
                    <div class="row">
                        <div class="col-md-3 d-flex ">
                            
                                <button type="button" class="btn btn-light-primary font-weight-bold mr-2"
                                    data-dismiss="modal">Close</button>
                                <button type="button" onclick="javascript:update({{$pengumuman->id}});"
                                    class="btn btn-success mr-2 btn-block">Submit</button>
                            
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
<script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
