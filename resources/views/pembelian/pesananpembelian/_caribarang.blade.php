<!-- Modal-->

<div class="modal fade" id="caribarangModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cari Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" style="height: 400px;">

                <table class="table  yajra-datatable collapsed ">
                    <thead class="datatable-head">
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Katalog</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>
<script>
    $(function () {
          
          var table = $('.yajra-datatable').DataTable({
              responsive: true,
              processing: true,
              serverSide: true,
              ajax: "{{ route('pesananpembelian.caribarang') }}",
              columns: [
                //   {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'kode', name: 'kode'},
                  {data: 'nama', name: 'nama'},
                  {data: 'katalog', name: 'katalog'},

                  
              ],
              columnDefs: [

                {
                    responsivePriority: 1,
                    targets: 1
                },
                {
                    responsivePriority: 2,
                    targets: -1
                },
            ],
        });
          
    });

    function htmlDecode(data){
        var txt = document.createElement('textarea');
        txt.innerHTML=data;
        return txt.value;
    }
</script>
<!-- Modal-->