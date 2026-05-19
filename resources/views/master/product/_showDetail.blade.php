<!-- Modal-->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" style="height:700px;">


                <table class="table">

                    <tbody>
                        <tr>
                            <th scope="row">Kode</th>
                            <td>:</td>
                            <td>{{ $product->kode }}</td>
                        </tr>

                        <tr>
                            <th scope="row">Kode Item Gudang</th>
                            <td>:</td>
                            <td>{{ $product->kode_item }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Nama</th>
                            <td>:</td>
                            <td>{{ $product->nama }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Kategori</th>
                            <td>:</td>
                            <td>{{ $product->categories->nama }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Sub Kategori</th>
                            <td>:</td>
                            <td>{{ $product->subcategories->nama }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Merk</th>
                            <td>:</td>
                            <td>{{ $product->merks ? $product->merks->nama : '-' }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Harga Jual</th>
                            <td>:</td>
                            <td>{{ 'Rp. ' . number_format($product->hargajual, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Diskon (%) Jual</th>
                            <td>:</td>
                            <td>{{ $product->diskon_persen }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Diskon (Rp) Jual</th>
                            <td>:</td>
                            <td>{{ $product->diskon_rp }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Harga Beli</th>
                            <td>:</td>
                            <td>{{ 'Rp. ' . number_format($product->hargabeli, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Diskon (%) Beli</th>
                            <td>:</td>
                            <td>{{ $product->diskon_persen_beli }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Diskon (Rp) Beli</th>
                            <td>:</td>
                            <td>{{ $product->diskon_rp_beli }}</td>
                        </tr>
                        <tr>
                            <th scope="row">HPP</th>
                            <td>:</td>
                            <td>{{ 'Rp. ' . number_format($product->hpp, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Stok</th>
                            <td>:</td>
                            <td>{{ $product->stok }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Tipe</th>
                            <td>:</td>
                            <td>{{ $product->tipe }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Jenis</th>
                            <td>:</td>
                            <td>{{ $product->jenis }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Ukuran</th>
                            <td>:</td>
                            <td>{{ $product->ukuran }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Kemasan</th>
                            <td>:</td>
                            <td>{{ $product->kemasan }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Satuan</th>
                            <td>:</td>
                            <td>{{ $product->satuan }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Katalog</th>
                            <td>:</td>
                            <td>{{ $product->katalog }}</td>
                        </tr>

                        <tr>
                            <th scope="row">Asal Negara</th>
                            <td>:</td>
                            <td>{{ $product->asal_negara }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Pabrikan</th>
                            <td>:</td>
                            <td>{{ $product->pabrikan }}</td>
                        </tr>
                        <tr>
                            <th scope="row">No. Ijin Edar</th>
                            <td>:</td>
                            <td>
                                {{ $product->no_ijinedar }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Tgl. Exp. Ijin Edar</th>
                            <td>:</td>
                            <td>
                                @if ($product->exp_ijinedar != null)
                                    {{ $product->exp_ijinedar->format('d-m-Y') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Status</th>
                            <td>:</td>
                            <td>{{ $product->status }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Keterangan</th>
                            <td>:</td>
                            <td>{{ $product->keterangan }}</td>
                        </tr>
                    </tbody>
                </table>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>
<!-- Modal-->
