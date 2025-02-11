<!-- Modal-->
<div class="modal fade" id="tambahpengumuman" data-backdrop="static" tabindex="-1" role="dialog"
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
                        <select name="tujuan_id" class="form-control js-example-basic-multiple" id="tujuan_id" multiple="multiple" required>
                            <option value="All" selected>Semua</option>
                            @foreach ($pengumuman->bisalihat as $item)
                               <option value="{{ $item->id }}">{{ $item->nama }}</option>
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
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Subject</label>
                        <input type="text" class="form-control" name="subject" id="subject">
                    </div>

                    <div class="form-group">
                        <label for="">Informasi</label>
                        <textarea name="kt-ckeditor-2" id="editor2" class="form-control" cols="30" rows="5" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="">Upload File</label>
                        <input type="file" class="form-control" id="file" name="file">
                    </div>
                    <div class="row">
                        <div class="col-md-3 d-flex ">
                            
                                <button type="button" class="btn btn-light-primary font-weight-bold mr-2"
                                    data-dismiss="modal">Close</button>
                                <button type="button" onclick="javascript:store();"
                                    class="btn btn-success mr-2 btn-block">Submit</button>
                            
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
