<div class="modal fade" id="reportcash" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cash Advance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal :</label>
                            <input type="date" name="tanggal" id ="tanggal" class="form-control "
                                placeholder="Masukkan Tanggal" />
                        </div>
                        <div class="form-group">
                            <label>Kode :</label>
                            <input type="input" name="kode" id="kode" class="form-control" placeholder="kode"
                                required />

                        </div>

                        <div class="form-group">
                            <label>Jenis Biaya :</label> <br>
                            <select name="jenis_biaya_id" class="form-control select2" id="kt_select2_8">
                                @foreach ($jenisbiaya as $item)
                                    <optgroup label="{{ $item->nama }}">
                                        @foreach ($item->subjenisbiaya as $data)
                                            <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Nominal :</label>
                            <input type="number" name="nominal" id="nominal" class="form-control"
                                placeholder="Masukkan Nominal Biaya" />

                        </div>

                        <div class="form-group">
                            <label>Sumber Dana :</label> <br>
                            <select name="bank_id" id="kt_select2_7" class="form-control">
                                @foreach ($bank as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="form-group">
                            <label>Keterangan :</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control"
                                placeholder="Keterangan" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-primary font-weight-bold"
                                data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary font-weight-bold"
                                onclick="inputreportcash({{ $id }})">Save</button>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <table>
                                <tr>
                                    <th>Karyawan</th>
                                    <td>:</td>
                                    <td> {{ $cashadvance->karyawan->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Nominal</th>
                                    <td>:</td>
                                    <td> Rp. {{ number_format($cashadvance->nominal, 2, ',', '.') }}</td>
                                </tr>
                            </table>
                        <table class="table yajra-datatable-reportcash collapsed ">
                            <thead class="datatable-head">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kode</th>
                                    <th>Jenis Biaya</th>
                                    <th>Sub Jenis Biaya</th>
                                    <th>Nominal</th>
                                    <th>Sumber Dana</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>

<script src="{{ asset('/assets/js/pages/crud/forms/widgets/select2.js?v=7.0.6"') }}"></script>
<script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.6') }}"></script>
<script src="{{ asset('/assets/js/pages/crud/datatables/extensions/responsive.js?v=7.0.6') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
    integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
    integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
