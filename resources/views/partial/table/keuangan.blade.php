  @can('rekaplabarugi-list')
      <div class="row mb-10">
          <div class="col-xl-6">
              <div class="card card-custom">
                  <div class="card-body">
                      <div class="row">
                          <div class="col-md-6">
                              <h3 class="fw-bold">Laba/Rugi Perusahaan</h3>
                          </div>
                          <div class="col-md-3">
                          </div>

                          <div class="col-md-3">
                              <select name="tahunrekaplabarugi" id="tahunrekaplabarugi" class="form-control" onchange="filtertahunlabarugi()">
                                  @php
                                      $year = 2020;
                                  @endphp
                                  @foreach (range(date('Y'), $year) as $x)
                                      <option value="{{ $x }}">{{ $x }}</option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                      <hr>
                      <div class="d-flex justify-content-between mb-2">
                          <h5>Laba Penjualan</h5>
                          <h5 id="laba_penjualan" class="text-success">0</h5>
                      </div>
                      <div class="d-flex justify-content-between mb-2">
                          <h5>Beban Operasional</h5>
                          <h5 id="beban_operasional" class="text-danger">0</h5>
                      </div>

                       {{-- <div class="d-flex justify-content-between mb-2">
                          <h5>Beban Persediaan / Stok</h5>
                          <h5 id="beban_persediaan" class="text-danger">0</h5>
                      </div> --}}
                      <hr>
                      <div class="d-flex justify-content-between">
                          <h5 class="fw-bold">Total Keuntungan</h5>
                          <h5 id="total_keuntungan" class="text-primary">0</h5>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  @endcan

  <div class="row">
      @can('rekaphutang-list')
          <div class="col-lg-6">
              <!--begin::Card-->

              <div class="card card-custom gutter-b ">
                  <!--begin::Header-->
                  <div class="card-header d-flex justify-content-between align-items-center">
                      <!--begin::Title-->
                      <div class="card-title">
                          <h3 class="card-label">
                              Total Hutang
                          </h3>
                      </div>
                      <div>
                          <button id="scroll-button" class="btn btn-primary btn-sm"
                              onclick="document.querySelector('#target-hutang').scrollIntoView({ behavior: 'smooth' })"><i
                                  class="flaticon-more-1"></i>
                              Preview</button>
                      </div>
                  </div>
                  <!--end::Header-->
                  <div class="card-body">
                      <div class="row">
                          <div class="col-lg-6 ">
                              <div class="row mb-5 d-flex justify-content-between">
                                  <div class="col-lg-6">
                                      <b>Hutang Pertahun</b>
                                  </div>
                                  <div class="col-lg-6 d-flex justify-content-end">
                                      <select name="" id="rekaphutang" onchange="filterhutang()">
                                          @php
                                              $year = 2020;
                                          @endphp
                                          @foreach (range(date('Y'), $year) as $x)
                                              <option value="{{ $x }}">{{ $x }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-lg-12">
                                      <h3 id="totalhutangtahunan" class="text-info">Rp.0</h3>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="progress">
                                          <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                              role="progressbar" id="progress-hutang-lunas" style="width: 0%"
                                              aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                          <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                                              role="progressbar" id="progress-hutang-belum-lunas" style="width: 0%"
                                              aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-lg-6">
                                      <p class="text-left text-success"><b id="hutang-lunas">Rp.0</b></p>
                                  </div>
                                  <div class="col-lg-6">
                                      <p class="text-right text-danger"><b id="hutang-belum-lunas">Rp.0</b></p>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-lg-6">
                                      <p class="text-left">Lunas</p>
                                  </div>
                                  <div class="col-lg-6">
                                      <p class="text-right">Belum Lunas</p>
                                  </div>
                              </div>
                          </div>

                          <div class="col-md-6">
                              <div class="row mb-3 d-flex justify-content-between">
                                  <div class="col-md-12">
                                      <p><b>Hutang Keseluruhan</b></p>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-lg-12">
                                      <h3 id="totalhutangseluruh" class="text-info">Rp.0</h3>
                                  </div>
                              </div>

                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="progress">
                                          <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                                              role="progressbar" id="progress-jatuh-tempo" style="width: 0%"
                                              aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                          <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                              role="progressbar" id="progress-belum-jatuh-tempo" style="width: 0%"
                                              aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-lg-6">
                                      <p class="text-left text-danger"><b id="hutang-jatuh-tempo">Rp.0</b></p>
                                  </div>
                                  <div class="col-lg-6">
                                      <p class="text-right text-primary"><b id="hutang-belum-jatuh-tempo">Rp.0</b></p>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-lg-6">
                                      <p class="text-left">Sudah Jatuh Tempo</p>
                                  </div>
                                  <div class="col-lg-6">
                                      <p class="text-right">Belum Jatuh Tempo</p>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <!--end::Card-->
      @endcan
      @can('rekappiutang-list')
          <div class="col-lg-6">
              <!--begin::Card-->

              <div class="card card-custom gutter-b ">
                  <!--begin::Header-->
                  <div class="card-header d-flex justify-content-between align-items-center">
                      <!--begin::Title-->
                      <div class="card-title">
                          <h3 class="card-label">
                              Total Piutang
                          </h3>
                      </div>
                      <div>
                          <button class="btn btn-primary btn-sm"
                              onclick="document.querySelector('#target-piutang').scrollIntoView({ behavior: 'smooth' })"><i
                                  class="flaticon-more-1"></i>
                              Preview</button>
                      </div>
                  </div>
                  <!--end::Header-->
                  <div class="card-body">
                      <div class="row">
                          <div class="col-lg-6 ">
                              <div class="row mb-5 d-flex justify-content-between">
                                  <div class="col-lg-6">
                                      <b>Piutang Pertahun</b>
                                  </div>
                                  <div class="col-lg-6 d-flex justify-content-end">
                                      <select name="" id="rekappiutang" onchange="filterpiutang()">
                                          @php
                                              $year = 2020;
                                          @endphp
                                          @foreach (range(date('Y'), $year) as $x)
                                              <option value="{{ $x }}">{{ $x }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-lg-12">
                                      <h3 id="totalpiutangtahunan" class="text-info">Rp.0</h3>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="progress">
                                          <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                              role="progressbar" id="progress-piutang-lunas" style="width: 0%"
                                              aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                          <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                                              role="progressbar" id="progress-piutang-belum-lunas" style="width: 0%"
                                              aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-lg-6">
                                      <p class="text-left text-success"><b id="piutang-lunas">Rp.0</b></p>
                                  </div>
                                  <div class="col-lg-6">
                                      <p class="text-right text-danger"><b id="piutang-belum-lunas">Rp.0</b></p>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-lg-6">
                                      <p class="text-left">Lunas</p>
                                  </div>
                                  <div class="col-lg-6">
                                      <p class="text-right">Belum Lunas</p>
                                  </div>
                              </div>
                          </div>

                          <div class="col-md-6">
                              <div class="row mb-3 d-flex justify-content-between">
                                  <div class="col-md-12">
                                      <p><b>Piutang Keseluruhan</b></p>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-lg-12">
                                      <h3 id="totalpiutangseluruh" class="text-info">Rp.0</h3>
                                  </div>
                              </div>

                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="progress">
                                          <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                                              role="progressbar" id="progress-piutang-jatuh-tempo" style="width: 0%"
                                              aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                          <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                              role="progressbar" id="progress-piutang-belum-jatuh-tempo"
                                              style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-lg-6">
                                      <p class="text-left text-danger"><b id="piutang-jatuh-tempo">Rp.0</b></p>
                                  </div>
                                  <div class="col-lg-6">
                                      <p class="text-right text-primary"><b id="piutang-belum-jatuh-tempo">Rp.0</b></p>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-lg-6">
                                      <p class="text-left">Sudah Jatuh Tempo</p>
                                  </div>
                                  <div class="col-lg-6">
                                      <p class="text-right">Belum Jatuh Tempo</p>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <!--end::Card-->
          </div>
      @endcan
  </div>


  @can('datapengiriman-list')
      <div class="row">
          <div class="col-xl-12">
              <div class="card card-custom gutter-b card-stretch">
                  <div class="card-header border-0 pt-5">
                      <div class="card-title">
                          <div class="card-label">
                              <div class="font-weight-bolder">Pesanan Belum Terkirim</div>
                          </div>
                      </div>
                  </div>
                  <div class="card-body">
                      <table
                          class="table table-separate table-head-custom table-checkable table  yajra-datatable-pengiriman collapsed ">
                          <thead>
                              <tr>
                                  <th>Kode</th>
                                  <th>Tanggal</th>
                                  <th>Customer</th>
                                  <th>Umur</th>
                                  <th>Status</th>
                                  <th>Ket Internal</th>
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
  @endcan


  {{-- end table --}}

  {{-- table pembelian belum diterima  --}}
  @can('datapenerimaan-list')
      <div class="row">
          <div class="col-xl-12">
              <div class="card card-custom gutter-b card-stretch">
                  <div class="card-header border-0 pt-5">
                      <div class="card-title">
                          <div class="card-label">
                              <div class="font-weight-bolder">Pembelian Belum Diterima</div>
                          </div>
                      </div>
                  </div>
                  <div class="card-body">
                      <table
                          class="table table-separate table-head-custom table-checkable table  yajra-datatable-pembelian collapsed ">
                          <thead>
                              <tr>
                                  <th>Kode</th>
                                  <th>Tanggal</th>
                                  <th>Customer</th>
                                  <th>Umur</th>
                                  <th>Status</th>
                                  <th>Ket Internal</th>
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
  @endcan


  @can('datahutang-list')
      <div class="row" id="target-hutang">
          <div class="col-xl-12">
              <div class="card card-custom gutter-b card-stretch">
                  <div class="card-header border-0 pt-5">
                      <div class="card-title">
                          <div class="card-label">
                              <div class="font-weight-bolder">Hutang Belum Lunas</div>
                          </div>
                      </div>
                  </div>
                  <div class="card-body">
                      <table
                          class="table table-separate table-head-custom table-checkable table  yajra-datatable-hutang collapsed ">
                          <thead>
                              <tr>
                                  <th>Tanggal TOP</th>
                                  <th>Supplier</th>
                                  <th>No Faktur</th>
                                  <th>No Faktur Supplier</th>
                                  <th>Total Hutang</th>
                                  <th>Terbayar</th>
                                  <th>Sisa Hutang</th>
                                  <th>Umur</th>
                                  <th>Status</th>
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
  @endcan

  @can('datapiutang-list')
      <div class="row" id="target-piutang">
          <div class="col-xl-12">
              <div class="card card-custom gutter-b card-stretch">
                  <div class="card-header border-0 pt-5">
                      <div class="card-title">
                          <div class="card-label">
                              <div class="font-weight-bolder">Piutang Belum Terbayar</div>
                          </div>
                      </div>
                  </div>
                  <div class="card-body">
                      <table
                          class="table table-separate table-head-custom table-checkable table  yajra-datatable-piutang collapsed ">
                          <thead>
                              <tr>
                                  <th>Tanggal Top</th>
                                  <th>No BSB</th>
                                  <th>Customer</th>
                                  <th>Total</th>
                                  <th>Terbayar</th>
                                  <th>Sisa</th>
                                  <th>Umur</th>
                                  <th>Status</th>
                                  <th>Sales</th>
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
  @endcan

  @can('piutangsales-list')
      <div class="row">
          <div class="col-xl-12">
              <div class="card card-custom gutter-b card-stretch">
                  <div class="card-header border-0 pt-5">
                      <div class="card-title">
                          <div class="card-label">
                              <div class="font-weight-bolder">Beban Piutang Sales</div>
                          </div>
                      </div>
                  </div>
                  <div class="card-body">
                      <table class="table table-separate table-head-custom table-checkable table  collapsed ">
                          <thead>
                              <tr>
                                  <th>Tanggal</th>
                                  <th>Nama Produk</th>
                                  <th>Qty</th>
                                  <th>Total Penjualan</th>
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
  @endcan
