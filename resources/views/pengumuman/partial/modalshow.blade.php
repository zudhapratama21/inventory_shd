<!-- Modal-->
<div class="modal fade" id="pengumumanshow" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document" style="height: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <h5 class="modal-title" id="exampleModalLabel">Pengumuman</h5> --}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" style="height: 300px;">                
                    <div class="font-weight-bold text-success mb-5">
                        {{\Carbon\Carbon::parse($pengumuman->created_at)->format('d F Y')}} || {{ucfirst($pengumuman->pembuat->name)}}
                    </div>      
                    <p><span class="badge badge-primary">{{$pengumuman->topic->nama}}</span></p>  
                    <p class="text-dark-75 font-weight-bolder font-size-h5 m-0">
                        {{$pengumuman->subject}}                        
                    </p>
                    <p>
                        {!! $pengumuman->description !!}
                    </p>
                    <a href="{{ asset('storage/pengumuman/' . $pengumuman->file) }}" class="btn btn-primary btn-sm" download><i
                        class="fas fa-download"></i> Download File</a>
                
            </div>
            </div>

        </div>
    </div>
</div>
<script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
