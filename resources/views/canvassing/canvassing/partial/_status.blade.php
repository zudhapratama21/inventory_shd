@if ($status == 1)
    <span class="badge badge-danger">Belum Set Data Kirim</span>
@elseif($status == 2)
    <span class="badge badge-primary">Data Kirim Success</span>
@elseif($status == 3)
    <span class="badge badge-info">Produk sudah Dikembalikan Sebagian</span>
@elseif($status == 4)
<span class="badge badge-success">Produk sudah Dikembalikan Seluruhnya</span>
@endif