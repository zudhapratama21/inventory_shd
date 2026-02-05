<div class="card-body">
    <div class="form-group">
        <label>Merk *:</label>
        <select class="form-control select2" id="merk" name="merk_id">
            <option value="">Pilih Merk</option>
            @foreach ($merks as $mrk)
                @if ($product->merk_id == $mrk->id)
                    <option selected="selected" value="{{ $mrk->id }}">{{ $mrk->nama }}</option>
                @else
                    <option value="{{ $mrk->id }}">{{ $mrk->nama }}</option>
                @endif
            @endforeach
        </select>
        @error('merk_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="separator separator-dashed my-5"></div>



    <div class="form-group">
        <label>Nama Barang *:</label>
        <input type="text" name="nama" value="{{ old('nama') ?? $product->nama }}"
            class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan Nama Product" />
        @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="separator separator-dashed my-5"></div>

    <div class="form-group">
        <label>Kategori *:</label>
        <select class="form-control select2" id="productcategory" name="productcategory_id">
            <option value="">Pilih Merk</option>
            @foreach ($productcategories as $mrk)
                @if ($product->productcategory_id == $mrk->id)
                    <option selected="selected" value="{{ $mrk->id }}">{{ $mrk->nama }}</option>
                @else
                    <option value="{{ $mrk->id }}">{{ $mrk->nama }}</option>
                @endif
            @endforeach
        </select>
        @error('productcategory_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="separator separator-dashed my-5"></div>

    <div class="form-group">
        <label>Sub Kategori *:</label>
        <select class="form-control select2" id="productsubcategory" name="productsubcategory_id">
            @foreach ($productsubcategories as $kotax)
                @if ($product->productsubcategory_id == $kotax->id)
                    <option selected="selected" value="{{ $kotax->id }}">{{ $kotax->nama }}</option>
                @else
                    <option value="{{ $kotax->id }}">{{ $kotax->nama }}</option>
                @endif
            @endforeach
        </select>
        @error('productsubcategory_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="separator separator-dashed my-5"></div>
    <div class="form-group row">
        <div class="col-lg-3">
            <label>Jenis :</label>
            <input type="text" name="jenis" value="{{ old('jenis') ?? $product->jenis }}"
                class="form-control @error('jenis') is-invalid @enderror" placeholder="Masukkan Jenis Product" />
            @error('jenis')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-lg-3">
            <label>Tipe :</label>
            <input type="text" name="tipe" value="{{ old('tipe') ?? $product->tipe }}"
                class="form-control @error('tipe') is-invalid @enderror" placeholder="Masukkan Tipe Product" />
            @error('tipe')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-lg-3">
            <label>Ukuran :</label>
            <input type="text" name="ukuran" value="{{ old('ukuran') ?? $product->ukuran }}"
                class="form-control @error('ukuran') is-invalid @enderror" placeholder="Masukkan Ukuran Product" />
            @error('ukuran')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-lg-3">
            <label>Kemasan :</label>
            <input type="text" name="kemasan" value="{{ old('kemasan') ?? $product->kemasan }}"
                class="form-control @error('kemasan') is-invalid @enderror" placeholder="Masukkan Kemasan Product" />
            @error('kemasan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="separator separator-dashed my-5"></div>



    <div class="form-group">
        <label>Satuan *:</label>
        <select class="form-control select2" id="satuan" name="satuan">
            <option value="">Pilih Merk</option>
            @foreach ($satuans as $mrk)
                @if ($product->satuan == $mrk->nama)
                    <option selected="selected" value="{{ $mrk->nama }}">{{ $mrk->nama }}</option>
                @else
                    <option value="{{ $mrk->nama }}">{{ $mrk->nama }}</option>
                @endif
            @endforeach
        </select>
        @error('Satuan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="separator separator-dashed my-5"></div>
    <div class="form-group">
        <label>Katalog :</label>
        <input type="text" name="katalog" value="{{ old('katalog') ?? $product->katalog }}"
            class="form-control @error('katalog') is-invalid @enderror" placeholder="Masukkan katalog Product" />
        @error('katalog')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="separator separator-dashed my-5"></div>
    <div class="form-group">
        <label>Asal Negara :</label>
        <input type="text" name="asal_negara" value="{{ old('asal_negara') ?? $product->asal_negara }}"
            class="form-control @error('asal_negara') is-invalid @enderror"
            placeholder="Masukkan Asal Negara Product" />
        @error('asal_negara')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="separator separator-dashed my-5"></div>
    <div class="form-group">
        <label>Pabrikan :</label>
        <input type="text" name="pabrikan" value="{{ old('pabrikan') ?? $product->pabrikan }}"
            class="form-control @error('pabrikan') is-invalid @enderror" placeholder="Masukkan pabrikan Product" />
        @error('pabrikan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="separator separator-dashed my-5"></div>
    <div class="form-group">
        <label>No Ijin Edar :</label>
        <input type="text" name="no_ijinedar" value="{{ old('no_ijinedar') ?? $product->no_ijinedar }}"
            class="form-control @error('no_ijinedar') is-invalid @enderror"
            placeholder="Masukkan No Ijin Edar Product" />
        @error('no_ijinedar')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="separator separator-dashed my-5"></div>
    <div class="form-group row">
        <div class="col-lg-4 col-md-9 col-sm-12">
            <label>Tgl. Exp. Ijin Edar :</label>
            <div class="input-group date">
                @if ($product->exp_ijinedar != null)
                    <input type="text" class="form-control" name="exp_ijinedar" readonly
                        value="{{ $product->exp_ijinedar->format('d-m-Y') }}" id="tgl1" />
                @else
                    <input type="text" class="form-control" name="exp_ijinedar" readonly value=""
                        id="tgl1" />
                @endif

                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-calendar"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="separator separator-dashed my-5"></div>

    <div class="form-group row">
        <div class="col-lg-3">
            <label>Harga Jual *:</label>
            <input type="number" id="hargajual" name="hargajual"
                value="{{ old('hargajual') ?? $product->hargajual }}"
                class="form-control @error('hargajual') is-invalid @enderror"
                placeholder="Masukkan Harga Jual Product" />
            @error('hargajual')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-lg-3">
            <label>Harga Beli *:</label>
            <input type="number" id="hargabeli" name="hargabeli"
                value="{{ old('hargabeli') ?? $product->hargabeli }}"
                class="form-control @error('hargabeli') is-invalid @enderror"
                placeholder="Masukkan Harga Beli Product" />
            @error('hargabeli')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-lg-3">
            <label>Diskon (%) :</label>
            <input type="number" name="diskon_persen"
                class="form-control @error('diskon_persen') is-invalid @enderror"
                value="{{ $product->diskon_persen ?? 0 }}" />
            @error('diskon_persen')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-lg-3">
            <label>Diskon (Rp.) :</label>
            <input type="number" class="form-control @error('diskon_rp') is-invalid @enderror" name="diskon_rp"
                value="{{ $product->diskon_rp ?? 0 }}" />
            @error('diskon_rp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

    </div>

    <div class="form-group row">
       <div class="col-lg-3">
            <label>Stok Awal</label>
            <input type="number" class="form-control" name="stok" value="{{ $product->stok ?? 0 }}" />           
        </div>
    </div>
    <div class="separator separator-dashed my-5"></div>
    <div class="form-group">
        <label>Status * :</label>
        <div class="radio-inline">
            <label class="radio">
                <input type="radio" @if ($product->status == 'Aktif') checked="checked" @endif value="Aktif"
                    name="status" />
                <span></span>
                Aktif
            </label>
            <label class="radio">
                <input type="radio" name="status" @if ($product->status == 'Non Aktif') checked="checked" @endif
                    value="Non Aktif" />
                <span></span>
                Non Aktif
            </label>

        </div>
        <span class="form-text text-muted">Produk yang non-aktif tidak akan dimunculkan dalam transaksi.</span>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="separator separator-dashed my-5"></div>    
        <div class="form-group">
            <label>Ada Expired? * :</label>


            <div class="radio-inline">
                <label class="radio">
                    <input type="radio" @if ($product->status_exp == '1') checked="checked" @endif value="1"
                        name="status_exp" />
                    <span></span>
                    Ya
                </label>
                <label class="radio">
                    <input type="radio" name="status_exp"
                        @if ($product->status_exp == '0') checked="checked" @endif value="0" />
                    <span></span>
                    Tidak
                </label>

            </div>
            <span class="form-text text-muted">Pilih Ya Jika Produk Memiliki Expired Date</span>
            @error('status_exp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>    

    <div class="separator separator-dashed my-5"></div>
    <div class="form-group">
        <label>Keterangan :</label>
        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3"
            placeholder="Masukkan keterangan product">{{ old('keterangan') ?? $product->keterangan }}</textarea>
        @error('keterangan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

</div>
<!--end::Form-->
<div class="card-footer text-right">
    <div class="row">
        <div class="col-lg-12 ">
            <button type="submit" class="btn btn-success font-weight-bold mr-2"><i class="flaticon2-paperplane"></i>
                {{ $submit }}</button>
            <a href="{{ route('product.index') }}" class="btn btn-secondary font-weight-bold mr-2">
                Cancel</a>
        </div>
    </div>
</div>
