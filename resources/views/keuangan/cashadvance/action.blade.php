  <div>
      @if ($mode == 1)
          @if ($umur < 30)
              <span class="label label-inline label-light-success font-weight-bold">{{ $umur }} Hari</span>
          @else
              <span class="label label-inline label-light-danger font-weight-bold">{{ $umur }} Hari</span>
          @endif
      @elseif ($mode == 2)
          @if ($status == 0)
              <span class="label label-inline label-light-danger font-weight-bold">Belum Lunas</span>
          @else
              <span class="label label-inline label-light-success font-weight-bold">Lunas</span>
          @endif

          <a class="ml-2" onclick="gantistatus({{$id}})"><i class="text-success flaticon2-check-mark"></i></a>
      @else
          <div class="btn-group">
              <button type="button" class="btn btn-sm btn-outline-info mr-2" onclick="reportcash({{ $id }})"><i
                      class="flaticon2-box"></i></button>

              <button type="button" class="btn btn-sm btn-primary mr-2"
                  onclick="edit({{ $id }})">Edit</button>
              <button type="button" class="btn btn-sm btn-danger"
                  onclick="deletecashadvance({{ $id }})">Delete</button>
          </div>
      @endif    
</div>
