 <!-- Modal -->
 <div class="modal fade" id="export" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalCenterTitle">Export File Karyawan</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form action="{{ route('absensi.export') }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="form-group">
                         <label for="">Tipe Export</label>
                         <select name="tipe_export" id="" class="form-control" required>
                             <option value="" disabled selected>=====PILIH TIPE =====</option>
                             <option value="rekap_mingguan">Rekap Mingguan</option>
                             <option value="rekap_bulanan">Rekap Bulanan</option>
                         </select>
                         <span style="font-size" class="text-danger">*Ketika pilih rekap bulanan tidak perlu pilih
                             tanggal , pilih bulan saja</span>
                     </div>

                     <div class="form-group">
                         <label for="">Tanggal Awal</label>
                         <input type="date" name="tanggal_awal" class="form-control">
                     </div>

                     <div class="form-group">
                         <label for="">Tanggal Akhir</label>
                         <input type="date" name="tanggal_akhir" class="form-control">
                     </div>

                     <div class="form-group">
                         <label for="">Bulan</label>
                         <select name="bulan" id="" class="form-control">
                             <option value="" disabled selected>====== PILIH BULAN ======</option>
                             @foreach ($bulan as $item)
                                 <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                             @endforeach
                         </select>
                         <span style="font-size" class="text-danger">*Ketika pilih rekap mingguan tidak perlu pilih
                             bulan , pilih tanggal saja</span>
                     </div>

                     <div class="form-group">
                         <label for="">Tahun</label>
                         <select name="tahun" id="" class="form-control">                            
                             @php
                                 $year = 2020;
                             @endphp
                             @foreach (range(date('Y'), $year) as $x)
                                 <option value="{{ $x }}">{{ $x }}</option>
                             @endforeach
                         </select>
                         <span style="font-size" class="text-danger">*Ketika pilih rekap mingguan tidak perlu pilih
                             bulan , pilih tanggal saja</span>
                     </div>
             </div>

             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                 <button type="submit" class="btn btn-primary">Save changes</button>
             </div>
             </form>
         </div>
     </div>
 </div>
