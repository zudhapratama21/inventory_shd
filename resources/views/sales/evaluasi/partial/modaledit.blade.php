<!-- Modal-->
<div class="modal fade" id="editevaluasi" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document" style="height: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Evaluasi Sales</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" style="height: 300px;">
                <form action="">
                    <div class="form-group">
                        <label for="">Sales</label> <br>
                        <select name="sales_id" class="form-control" id="sales_id" required>
                            @foreach ($sales as $item)
                                @if ($evaluasi->sales_id == $item->id)
                                    <option value="{{ $item->id }}" selected>{{ $item->nama }}</option>
                                @else
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Evaluasi</label>
                        <textarea name="kt-ckeditor-1" id="editor" class="form-control" cols="30" rows="5" required>
                            {!! $evaluasi->evaluasi !!}
                        </textarea>
                    </div>

                    <div class="form-group">
                        <label for="">Saran</label>
                        <textarea name="kt-ckeditor-2" id="editor2" class="form-control" cols="30" rows="5" required>
                            {!! $evaluasi->saran !!}
                        </textarea>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-light-primary font-weight-bold"
                            data-dismiss="modal">Close</button>
                        <button type="button" onclick="javascript:update({{$evaluasi->id}});"
                            class="btn btn-success mr-2 btn-block">Submit</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>