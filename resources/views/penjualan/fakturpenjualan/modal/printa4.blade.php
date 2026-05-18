<div class="modal fade" id="printa4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pilih Bank</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('fakturpenjualan.print_a4', $fakturpenjualan) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label for="">Pilih Bank</label>
                    <select name="bank" id="bank" class="form-control">
                        <option value="" selected disabled>Pilih Bank</option>
                        @foreach ($bank as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }} - {{ $item->nomor }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" target="_blank" >Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
